<?php

/**
 * Mint #193555 - SQL injection guard for the KREST layer.
 *
 * This file is MintHCM's own code added on top of the vendored KREST
 * (aac services k.s.). It is not part of the upstream KREST distribution.
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Mint start #193555

/**
 * Raised when a KREST request parameter fails validation.
 *
 * Mapped to HTTP 400 by the error handler registered in KREST/index.php.
 */
class KRESTInvalidInputException extends InvalidArgumentException
{
}

/**
 * Input guard for KREST request parameters used in SQL.
 *
 * KREST handlers read query parameters from the raw QUERY_STRING through the
 * PSR-7 request object (Slim\Http\Uri::createFromEnvironment reads
 * $_SERVER['QUERY_STRING']). clean_incoming_data() in include/utils.php only
 * rewrites $_REQUEST, $_POST and $_GET, so securexss() never touches the values
 * a KREST handler sees. Route arguments and JSON request bodies are unfiltered
 * for the same reason.
 *
 * Every request value that reaches SQL from a KREST handler must therefore pass
 * through this class first. Two distinct cases, and they are not
 * interchangeable:
 *
 *  - Values (a string compared in a WHERE clause) -> escapeValue() / toInt().
 *  - Identifiers (column names, table names, operators, sort direction) ->
 *    the assert*() methods. Escaping does NOT protect an identifier position;
 *    only an allowlist does.
 */
class KRESTSqlGuard
{
    /**
     * Comparison operators accepted in the `default` branch of
     * KRESTModuleHandler::buildConditionWhereClause(). Operators with a
     * dedicated branch there (doy>=, doy<=, in, not in, currentuser) are
     * validated by this list as well so that a single call covers the whole
     * switch.
     */
    const ALLOWED_OPERATORS = array(
        '=', '!=', '<>', '<', '>', '<=', '>=',
        'like', 'not like',
        'in', 'not in',
        'doy>=', 'doy<=',
        'currentuser',
    );

    const ALLOWED_SORT_DIRECTIONS = array('ASC', 'DESC');

    const ALLOWED_JOIN_TYPES = array('INNER', 'LEFT', 'RIGHT');

    /** Boolean connectives allowed between search conditions. */
    const ALLOWED_LOGICAL_JOINS = array('AND', 'OR');

    /**
     * A bare SQL identifier: letter or underscore, then letters, digits,
     * underscores. Deliberately stricter than MySQL allows - every identifier
     * KREST legitimately handles is a SugarBean field or table name.
     */
    const IDENTIFIER_PATTERN = '/^[a-zA-Z_][a-zA-Z0-9_]*$/';

    /**
     * Per-request cache of schema lookups. A saved filter can carry many
     * conditions with the same `addjoin`, and the schema does not change within
     * a request, so each table is checked against the database only once.
     *
     * @var array<string, bool> table name => exists
     */
    private static $tableExistsCache = array();

    /** @var array<string, array> table name => columns as returned by get_columns() */
    private static $tableColumnsCache = array();

    /**
     * Escape a value destined for a quoted string literal in SQL.
     *
     * Wraps DBManager::quote(), which runs from_html() before escaping - that
     * matters here because a value may arrive either raw (KREST/PSR-7) or
     * already securexss-encoded (&#039;) if it came through $_REQUEST. Both
     * end up correctly escaped.
     *
     * Returns the escaped value WITHOUT surrounding apostrophes - the call
     * sites keep their apostrophes in the SQL literal.
     *
     * @param mixed $value
     * @return string
     */
    public static function escapeValue($value)
    {
        global $db;

        if (is_array($value) || is_object($value)) {
            throw new KRESTInvalidInputException('Expected a scalar value, got ' . gettype($value) . '.');
        }

        return $db->quote((string)$value);
    }

    /**
     * Cast a value used in a numeric SQL position (LIMIT, OFFSET, row counts).
     *
     * @param mixed $value
     * @param int $min Lower bound applied after the cast.
     * @return int
     */
    public static function toInt($value, $min = 0)
    {
        $intValue = (int)$value;

        return $intValue < $min ? $min : $intValue;
    }

    /**
     * Cast a value used in a numeric SQL expression that is not an integer
     * (geo coordinates in the distance sort function).
     *
     * @param mixed $value
     * @return float
     */
    public static function toFloat($value)
    {
        return (float)$value;
    }

    /**
     * Validate a column name against a bean's field definitions.
     *
     * field_defs is the only source of truth for what a module may be filtered
     * or sorted by. A name absent from it is rejected rather than escaped -
     * there is no escaping that makes an attacker-chosen identifier safe.
     *
     * @param SugarBean $bean
     * @param mixed $fieldName
     * @return string The validated column name.
     * @throws KRESTInvalidInputException
     */
    public static function assertColumn($bean, $fieldName)
    {
        if (!is_string($fieldName) || '' === $fieldName) {
            throw new KRESTInvalidInputException('Column name must be a non-empty string.');
        }

        if (!preg_match(self::IDENTIFIER_PATTERN, $fieldName)) {
            throw new KRESTInvalidInputException('Invalid column name: ' . $fieldName);
        }

        if (!is_object($bean) || !isset($bean->field_defs) || !is_array($bean->field_defs)) {
            throw new KRESTInvalidInputException('Cannot validate column "' . $fieldName . '" without field definitions.');
        }

        if (!isset($bean->field_defs[$fieldName])) {
            throw new KRESTInvalidInputException(
                'Unknown field "' . $fieldName . '" for module ' . (isset($bean->module_dir) ? $bean->module_dir : 'unknown') . '.'
            );
        }

        return $fieldName;
    }

    /**
     * Validate a column that may arrive qualified as "table.column".
     *
     * Both halves are validated: the table against the bean's own table names,
     * the column against field_defs.
     *
     * @param SugarBean $bean
     * @param mixed $fieldName
     * @return string The validated identifier, qualification preserved.
     * @throws KRESTInvalidInputException
     */
    public static function assertQualifiedColumn($bean, $fieldName)
    {
        if (!is_string($fieldName) || '' === $fieldName) {
            throw new KRESTInvalidInputException('Column name must be a non-empty string.');
        }

        if (false === strpos($fieldName, '.')) {
            return self::assertColumn($bean, $fieldName);
        }

        $parts = explode('.', $fieldName);
        if (2 !== count($parts)) {
            throw new KRESTInvalidInputException('Invalid qualified column name: ' . $fieldName);
        }

        list($tablePart, $columnPart) = $parts;

        $allowedTables = array();
        if (isset($bean->table_name) && '' !== $bean->table_name) {
            $allowedTables[] = $bean->table_name;
        }
        if (isset($bean->rel_table_name) && '' !== $bean->rel_table_name) {
            $allowedTables[] = $bean->rel_table_name;
        }

        if (!in_array($tablePart, $allowedTables, true)) {
            throw new KRESTInvalidInputException('Table "' . $tablePart . '" is not related to this module.');
        }

        return $tablePart . '.' . self::assertColumn($bean, $columnPart);
    }

    /**
     * Validate a table name used in a JOIN clause.
     *
     * Checked against the database schema rather than a hardcoded list: the set
     * of legitimate tables depends on the deployment (custom modules, custom
     * relationships), so the schema is the accurate allowlist.
     *
     * @param mixed $tableName
     * @return string
     * @throws KRESTInvalidInputException
     */
    public static function assertTable($tableName)
    {
        global $db;

        if (!is_string($tableName) || '' === $tableName) {
            throw new KRESTInvalidInputException('Table name must be a non-empty string.');
        }

        if (!preg_match(self::IDENTIFIER_PATTERN, $tableName)) {
            throw new KRESTInvalidInputException('Invalid table name: ' . $tableName);
        }

        if (!isset(self::$tableExistsCache[$tableName])) {
            self::$tableExistsCache[$tableName] = (bool)$db->tableExists($tableName);
        }

        if (!self::$tableExistsCache[$tableName]) {
            throw new KRESTInvalidInputException('Unknown table: ' . $tableName);
        }

        return $tableName;
    }

    /**
     * Validate a column of a table that is not the request's own bean - the far
     * side of an ad-hoc JOIN, where field_defs offers no coverage.
     *
     * Checked against the table's real columns, so an attacker cannot smuggle an
     * expression through in place of a column name.
     *
     * @param mixed $columnName
     * @param string $tableName Table the column must belong to; already validated.
     * @return string
     * @throws KRESTInvalidInputException
     */
    public static function assertTableColumn($columnName, $tableName)
    {
        global $db;

        if (!is_string($columnName) || '' === $columnName) {
            throw new KRESTInvalidInputException('Column name must be a non-empty string.');
        }

        if (!preg_match(self::IDENTIFIER_PATTERN, $columnName)) {
            throw new KRESTInvalidInputException('Invalid column name: ' . $columnName);
        }

        if (!isset(self::$tableColumnsCache[$tableName])) {
            $columns = $db->get_columns($tableName);
            self::$tableColumnsCache[$tableName] = is_array($columns) ? $columns : array();
        }

        $columns = self::$tableColumnsCache[$tableName];
        if (!is_array($columns) || !isset($columns[strtolower($columnName)])) {
            throw new KRESTInvalidInputException('Unknown column "' . $columnName . '" in table ' . $tableName . '.');
        }

        return $columnName;
    }

    /**
     * Validate a comparison operator.
     *
     * @param mixed $operator
     * @return string The operator, normalised to lower case.
     * @throws KRESTInvalidInputException
     */
    public static function assertOperator($operator)
    {
        if (!is_string($operator)) {
            throw new KRESTInvalidInputException('Operator must be a string.');
        }

        $normalised = strtolower(trim($operator));

        if (!in_array($normalised, self::ALLOWED_OPERATORS, true)) {
            throw new KRESTInvalidInputException('Unsupported operator: ' . $operator);
        }

        return $normalised;
    }

    /**
     * Validate a sort direction.
     *
     * @param mixed $direction
     * @param string $default Returned when no direction was supplied.
     * @return string ASC or DESC.
     * @throws KRESTInvalidInputException
     */
    public static function assertSortDirection($direction, $default = 'ASC')
    {
        if (null === $direction || '' === $direction) {
            return $default;
        }

        if (!is_string($direction)) {
            throw new KRESTInvalidInputException('Sort direction must be a string.');
        }

        $normalised = strtoupper(trim($direction));

        if (!in_array($normalised, self::ALLOWED_SORT_DIRECTIONS, true)) {
            throw new KRESTInvalidInputException('Unsupported sort direction: ' . $direction);
        }

        return $normalised;
    }

    /**
     * Validate the boolean connective used between search conditions.
     *
     * @param mixed $join
     * @return string AND or OR.
     * @throws KRESTInvalidInputException
     */
    public static function assertLogicalJoin($join)
    {
        if (!is_string($join)) {
            throw new KRESTInvalidInputException('Logical join must be a string.');
        }

        $normalised = strtoupper(trim($join));

        if (!in_array($normalised, self::ALLOWED_LOGICAL_JOINS, true)) {
            throw new KRESTInvalidInputException('Unsupported logical join: ' . $join);
        }

        return $normalised;
    }

    /**
     * Validate a JOIN type.
     *
     * @param mixed $joinType
     * @return string
     * @throws KRESTInvalidInputException
     */
    public static function assertJoinType($joinType)
    {
        if (!is_string($joinType)) {
            throw new KRESTInvalidInputException('Join type must be a string.');
        }

        $normalised = strtoupper(trim($joinType));

        if (!in_array($normalised, self::ALLOWED_JOIN_TYPES, true)) {
            throw new KRESTInvalidInputException('Unsupported join type: ' . $joinType);
        }

        return $normalised;
    }

    /**
     * Decode a JSON parameter that must contain a flat list of strings
     * (searchtermfields and friends).
     *
     * Several call sites used to foreach straight over json_decode() output,
     * which warns and skips on malformed input instead of rejecting it. An
     * explicit failure is the safer default.
     *
     * @param mixed $json
     * @return string[] Empty array when nothing was supplied.
     * @throws KRESTInvalidInputException
     */
    public static function decodeStringList($json)
    {
        if (null === $json || '' === $json) {
            return array();
        }

        if (is_array($json)) {
            $decoded = $json;
        } else {
            if (!is_string($json)) {
                throw new KRESTInvalidInputException('Expected a JSON list, got ' . gettype($json) . '.');
            }
            $decoded = json_decode(html_entity_decode($json, ENT_QUOTES), true);
        }

        if (!is_array($decoded)) {
            throw new KRESTInvalidInputException('Malformed JSON list parameter.');
        }

        foreach ($decoded as $entry) {
            if (!is_string($entry)) {
                throw new KRESTInvalidInputException('JSON list must contain strings only.');
            }
        }

        return array_values($decoded);
    }

    /**
     * Escape every element of a comma separated list used in an IN (...)
     * clause, returning a ready-to-embed, quoted list.
     *
     * @param mixed $value Either an array or a comma separated string.
     * @return string e.g. "'a','b'" - empty string when the list is empty.
     */
    public static function escapeValueList($value)
    {
        if (!is_array($value)) {
            if (null === $value || '' === $value) {
                return '';
            }
            if (!is_scalar($value)) {
                throw new KRESTInvalidInputException('Expected a list of values.');
            }
            $value = explode(',', (string)$value);
        }

        $escaped = array();
        foreach ($value as $entry) {
            if (is_array($entry) || is_object($entry)) {
                throw new KRESTInvalidInputException('Expected a list of scalar values.');
            }
            $escaped[] = "'" . self::escapeValue(trim((string)$entry, " '\"")) . "'";
        }

        return implode(',', $escaped);
    }
}

// Mint end #193555

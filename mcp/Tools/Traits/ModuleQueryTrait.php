<?php

namespace MintMCP\Tools\Traits;

use MintMCP\Tools\Utils\ToolValidation;
use MintMCP\Tools\Utils\DateTimeConversion;

/**
 * Trait providing utilities for querying module metadata and building SQL WHERE clauses.
 */
trait ModuleQueryTrait
{

    /**
     * Loads the bean, table name, and field definitions for a given module.
     *
     * @param string $moduleName
     * @return array [$bean, $tableName, $fieldDefs]
     */
    private function loadBeanAndDefs(string $moduleName)
    {
        chdir('../legacy');
        $bean = \BeanFactory::getBean($moduleName);
        $tableName = $bean->table_name ?? strtolower($moduleName);
        $fieldDefs = isset($bean->field_defs) ? $bean->field_defs : [];
        chdir('../mcp');

        if (empty($bean) || empty($tableName) || empty($fieldDefs)) {
            throw new \Exception("Module '{$moduleName}' not found or not accessible.");
        }

        return [$bean, $tableName, $fieldDefs];
    }

    public function isDateField(string $fieldType): bool
    {
        return in_array($fieldType, ['date', 'datetime', 'datetimecombo']);
    }

    private function buildWhereClause(string $filtersJson, array $fieldDefs, string $tableName, string $operator): string
    {
        $where = [];
        $validators = [];
        $validators[] = ToolValidation::make($operator, 'operator')->required()->enum(['and', 'or']);

        if ($filtersJson) {
            $queryFilters = json_decode($filtersJson, true) ?? [];

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \InvalidArgumentException("Invalid JSON in filters: " . json_last_error_msg());
            }

            foreach ($queryFilters as $field => $filter) {
                $op = strtoupper($filter['operator'] ?? '=');
                $value = $filter['value'] ?? '';

                $type = $fieldDefs[$field]['type'] ?? '';

                $validators[] = ToolValidation::make(null, $field)->filterField($fieldDefs);

                $values = $this->extractValues($value, $op);

                foreach ($values as &$value) {
                    $validator = ToolValidation::make($value, $field)->fieldType($type);
                    $validators[] = $validator;
                    if ($this->isDateField($type) && $validator->isValid()) {
                        $value = DateTimeConversion::fromUserTZ($value);
                    }
                }

                $where[] = $this->buildWhereCondition($tableName, $field, $op, $values);
            }
        }

        ToolValidation::validateMany($validators);

        // Always exclude deleted records
        $where[] = "$tableName.deleted = 0";

        $glue = (strtoupper($operator) === 'OR') ? ' OR ' : ' AND ';
        return implode($glue, $where);
    }

    /**
     * Builds a single SQL condition for a field, operator, and value.
     *
     * @param string $tableName
     * @param string $field
     * @param string $op
     * @param mixed $value
     * @return string
     * @throws \InvalidArgumentException
     */
    private function buildWhereCondition(string $tableName, string $field, string $op, array $values): string
    {
        // Handle IN and NOT IN operators
        if (in_array($op, ['IN', 'NOT IN'])) {
            return "$tableName.$field $op (" . implode(',', array_map(fn($v) => "'$v'", $values)) . ")";
        }

        // Handle BETWEEN operator
        if ($op === 'BETWEEN') {
            if (count($values) !== 2) {
                throw new \InvalidArgumentException("BETWEEN operator requires two values.");
            }
            return "$tableName.$field BETWEEN '{$values[0]}' AND '{$values[1]}'";
        }

        // Handle LIKE operator
        if ($op === 'LIKE') {
            return "$tableName.$field LIKE '%{$values[0]}%'";
        }

        // Default: simple comparison
        return "$tableName.$field $op '{$values[0]}'";
    }

    /**
     * Validates and extracts values from the filter input.
     *
     * @param array|string $value
     * @param string $op
     * @return array
     */
    private function extractValues(array|string $value, string $op): array
    {
        $values = [];
        if (in_array($op, ['IN', 'NOT IN', 'BETWEEN'])) {
            $values = array_map('trim', explode(',', $value));
        } elseif (is_array($value)) {
            $values = $value;
        } else {
            $values[] = $value;
        }
        return $values;
    }
}

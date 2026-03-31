<?php

namespace MintMCP\Tools\Utils;

/**
 * Validates user-provided KReport filter conditions.
 *
 * Checks:
 *  - filter_name and operator are present (delegated to ToolValidation)
 *  - filter_name exists in the report's configured conditions
 *  - value parameters match the operator (value/value_to for between, values[] for oneof/oneofnot)
 *  - operator is allowed for the filter's DB type
 *  - value(s) match the expected format and allowed values for the DB type
 */
class KReportFilterValidator
{
    private const ALLOWED_OPERATORS = [
        'enum'     => ['oneof', 'oneofnot'],
        'date'     => ['equals', 'notequal', 'before', 'after', 'between'],
        'datetime' => ['equals', 'notequal', 'before', 'after', 'between'],
        'varchar'  => ['equals', 'notequal', 'contains', 'notcontains'],
        'text'     => ['equals', 'notequal', 'contains', 'notcontains'],
        'float'    => ['equals', 'notequal', 'greater', 'greaterequal', 'less', 'lessequal', 'between'],
        'int'      => ['equals', 'notequal', 'greater', 'greaterequal', 'less', 'lessequal', 'between'],
        'bool'     => ['equals'],
        'unknown'  => ['equals', 'notequal'],
    ];

    /**
     * Get allowed operators for a given filter DB type.
     *
     * @param string $dbType DB type key (e.g. 'date', 'float', 'enum'); falls back to 'unknown' if not found
     * @return list<string>
     */
    public static function getAllowedOperators(string $dbType): array
    {
        return self::ALLOWED_OPERATORS[$dbType] ?? self::ALLOWED_OPERATORS['unknown'];
    }

    /**
     * Validate all user-provided filters against report metadata.
     *
     * @param array  $userFilters       List of { filter_name, operator, value|values|value_to }
     * @param array  $conditionsByName  Map of filter name => report condition
     * @param string $reportName        Used in error messages
     * @param array  $filterMetadata    Map of filter name => { dbType, possible_values } (possible_values: [{value, label}] or null)
     * @throws \InvalidArgumentException|\Exception
     */
    public function validate(
        array $userFilters,
        array $conditionsByName,
        string $reportName,
        array $filterMetadata
    ): void {
        $reportLabel = $reportName !== '' ? " in report '{$reportName}'" : '';

        foreach ($userFilters as $rawFilter) {
            $filter = (array) $rawFilter;
            $filterName = $filter['filter_name'] ?? null;
            $operator   = $filter['operator'] ?? null;

            // Validate presence and type of required filter fields
            ToolValidation::validateMany([
                ToolValidation::make($filterName, 'filter_name')->required()->string(),
                ToolValidation::make($operator, 'operator')->required()->string(),
            ]);

            // Validate that the filter name exists in the report's configured conditions
            ToolValidation::validateOne(
                ToolValidation::make($filterName, 'filter_name')->customValidation(
                    fn($name) => isset($conditionsByName[$name]),
                    "Filter '{$filterName}' not found{$reportLabel}."
                )
            );

            $op = strtolower((string) $operator);

            // Validate that the correct value keys are present for the operator
            $this->validateOperatorParams($filter, $filterName, $op);

            // Validate operator and value(s) against the filter's DB type
            if (isset($filterMetadata[$filterName])) {
                $this->validateForMetadata($filter, $filterName, $op, $filterMetadata[$filterName]);
            }
        }
    }

    /**
     * Validate that the filter provides the correct value parameter(s) for the operator.
     *
     * @param array  $filter      Raw filter array containing operator and value key(s)
     * @param string $filterName  Name of the filter (used in error messages)
     * @param string $operator    Lowercase operator string (e.g. 'between', 'oneof', 'equals')
     * @throws \InvalidArgumentException|\Exception
     * @return void
     */
    private function validateOperatorParams(array $filter, string $filterName, string $operator): void
    {
        if ($operator === 'between') {
            ToolValidation::validateOne(
                ToolValidation::make($filter, $filterName)->customValidation(
                    fn($f) => array_key_exists('value', $f) && array_key_exists('value_to', $f),
                    "Filter '{$filterName}' with operator 'between' requires both 'value' and 'value_to'."
                )
            );
            return;
        }

        if ($operator === 'oneof' || $operator === 'oneofnot') {
            ToolValidation::validateOne(
                ToolValidation::make($filter, $filterName)->customValidation(
                    fn($f) => isset($f['values']) && is_array($f['values']) && !empty($f['values']),
                    "Filter '{$filterName}' with operator '{$operator}' requires a non-empty 'values' array."
                )
            );
            return;
        }

        ToolValidation::validateOne(
            ToolValidation::make($filter, $filterName)->customValidation(
                fn($f) => array_key_exists('value', $f),
                "Filter '{$filterName}' requires a 'value' for operator '{$operator}'."
            )
        );
    }

    /**
     * Validate operator and value(s) against the filter's DB type metadata.
     *
     * @param array  $filter      Raw filter array containing operator and value key(s)
     * @param string $filterName  Name of the filter (used in error messages)
     * @param string $operator    Lowercase operator string (e.g. 'between', 'oneof', 'equals')
     * @param array  $metadata    Filter metadata with keys 'dbType' and optional 'possible_values' ([{value, label}])
     * @throws \InvalidArgumentException|\Exception
     * @return void
     */
    private function validateForMetadata(array $filter, string $filterName, string $operator, array $metadata): void
    {
        $dbType = $metadata['dbType'] ?? 'unknown';
        $possibleValuesRaw = $metadata['possible_values'] ?? null;
        // possible_values is [{value, label}]; extract flat list of values for enum validation
        $possibleValues = null;
        if (\is_array($possibleValuesRaw) && !empty($possibleValuesRaw)) {
            $possibleValues = array_column($possibleValuesRaw, 'value');
        }

        $allowedOperators = self::getAllowedOperators($dbType);
        ToolValidation::validateOne(
            ToolValidation::make($operator, $filterName)->customValidation(
                fn($op) => in_array($op, $allowedOperators, true),
                "Filter '{$filterName}' does not support operator '{$operator}' for type '{$dbType}'. " .
                "Allowed: " . implode(', ', $allowedOperators) . "."
            )
        );

        // Validate each value involved in the operator
        if ($operator === 'between') {
            $this->validateFilterValue($filterName, $filter['value'] ?? '', $dbType, $possibleValues);
            $this->validateFilterValue($filterName, $filter['value_to'] ?? '', $dbType, $possibleValues);
        } elseif ($operator === 'oneof' || $operator === 'oneofnot') {
            foreach ($filter['values'] as $value) {
                $this->validateFilterValue($filterName, $value, $dbType, $possibleValues);
            }
        } else {
            $this->validateFilterValue($filterName, $filter['value'] ?? '', $dbType, $possibleValues);
        }
    }

    /**
     * Validate a single value against the filter's DB type and allowed values.
     *
     * @param string      $filterName     Name of the filter being validated (used in error messages)
     * @param mixed       $value          The value to validate
     * @param string      $dbType         DB type of the filter (e.g. 'date', 'float', 'enum')
     * @param array|null  $possibleValues Flat list of allowed values for enum/bool types, or null if unrestricted
     * @throws \InvalidArgumentException|\Exception
     * @return void
     */
    private function validateFilterValue(string $filterName, $value, string $dbType, ?array $possibleValues): void
    {
        $valueStr  = trim((string) $value);
        $validator = ToolValidation::make($valueStr, $filterName);

        ToolValidation::validateOne(
            $validator->customValidation(
                fn($v) => in_array($dbType, ['varchar', 'text'], true) || $v !== '',
                "Filter '{$filterName}' requires a non-empty value."
            )
        );

        switch ($dbType) {
            case 'date':
                ToolValidation::validateOne($validator->dateFormat('Y-m-d'));
                break;
            case 'datetime':
                ToolValidation::validateOne($validator->dateFormat('Y-m-d H:i:s'));
                break;
            case 'int':
            case 'float':
                ToolValidation::validateOne($validator->numeric());
                break;
            case 'enum':
            case 'bool':
                if ($possibleValues !== null) {
                    ToolValidation::validateOne($validator->enum($possibleValues));
                }
                break;
        }
    }
}

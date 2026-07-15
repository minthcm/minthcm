<?php

namespace MintHCM\AI\Tools\Utils;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

/**
 * Validates user-provided KReport filter conditions.
 */
class KReportFilterValidator
{
    private const OP_BETWEEN  = 'between';
    private const OP_ONEOF    = 'oneof';
    private const OP_ONEOFNOT = 'oneofnot';

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

    public static function getAllowedOperators(string $db_type): array
    {
        return self::ALLOWED_OPERATORS[$db_type] ?? self::ALLOWED_OPERATORS['unknown'];
    }

    public function validate(
        array $user_filters,
        array $conditions_by_name,
        string $report_name,
        array $filter_metadata
    ): void {
        $report_label = $report_name !== '' ? " in report '{$report_name}'" : '';

        foreach ($user_filters as $raw_filter) {
            $filter = (array) $raw_filter;
            $filter_name = $filter['filter_name'] ?? null;
            $operator   = $filter['operator'] ?? null;

            ToolValidation::validateMany([
                ToolValidation::make($filter_name, 'filter_name')->required()->string(),
                ToolValidation::make($operator, 'operator')->required()->string(),
            ]);

            ToolValidation::validateOne(
                ToolValidation::make($filter_name, 'filter_name')->customValidation(
                    fn($name) => isset($conditions_by_name[$name]),
                    "Filter '{$filter_name}' not found{$report_label}."
                )
            );

            $op = strtolower((string) $operator);
            $this->validateOperatorParams($filter, $filter_name, $op);

            if (isset($filter_metadata[$filter_name])) {
                $this->validateForMetadata($filter, $filter_name, $op, $filter_metadata[$filter_name]);
            }
        }
    }

    private function validateOperatorParams(array $filter, string $filter_name, string $operator): void
    {
        if ($operator === self::OP_BETWEEN) {
            ToolValidation::validateOne(
                ToolValidation::make($filter, $filter_name)->customValidation(
                    fn($f) => array_key_exists('value', $f) && array_key_exists('value_to', $f),
                    "Filter '{$filter_name}' with operator 'between' requires both 'value' and 'value_to'."
                )
            );
            return;
        }

        if ($operator === self::OP_ONEOF || $operator === self::OP_ONEOFNOT) {
            ToolValidation::validateOne(
                ToolValidation::make($filter, $filter_name)->customValidation(
                    fn($f) => isset($f['values']) && is_array($f['values']) && !empty($f['values']),
                    "Filter '{$filter_name}' with operator '{$operator}' requires a non-empty 'values' array."
                )
            );
            return;
        }

        ToolValidation::validateOne(
            ToolValidation::make($filter, $filter_name)->customValidation(
                fn($f) => array_key_exists('value', $f),
                "Filter '{$filter_name}' requires a 'value' for operator '{$operator}'."
            )
        );
    }

    private function validateForMetadata(array $filter, string $filter_name, string $operator, array $metadata): void
    {
        $db_type = $metadata['dbType'] ?? 'unknown';
        $possible_values_raw = $metadata['possible_values'] ?? null;
        $possible_values = null;
        if (\is_array($possible_values_raw) && !empty($possible_values_raw)) {
            $possible_values = array_column($possible_values_raw, 'value');
        }

        $allowed_operators = self::getAllowedOperators($db_type);
        ToolValidation::validateOne(
            ToolValidation::make($operator, $filter_name)->customValidation(
                fn($op) => in_array($op, $allowed_operators, true),
                "Filter '{$filter_name}' does not support operator '{$operator}' for type '{$db_type}'. Allowed: " . implode(', ', $allowed_operators) . "."
            )
        );

        if ($operator === self::OP_BETWEEN) {
            $this->validateFilterValue($filter_name, $filter['value'] ?? '', $db_type, $possible_values);
            $this->validateFilterValue($filter_name, $filter['value_to'] ?? '', $db_type, $possible_values);
        } elseif ($operator === self::OP_ONEOF || $operator === self::OP_ONEOFNOT) {
            foreach ($filter['values'] as $value) {
                $this->validateFilterValue($filter_name, $value, $db_type, $possible_values);
            }
        } else {
            $this->validateFilterValue($filter_name, $filter['value'] ?? '', $db_type, $possible_values);
        }
    }

    private function validateFilterValue(string $filter_name, $value, string $db_type, ?array $possible_values): void
    {
        $value_str  = trim((string) $value);
        $validator = ToolValidation::make($value_str, $filter_name);

        ToolValidation::validateOne(
            $validator->customValidation(
                fn($v) => in_array($db_type, ['varchar', 'text'], true) || $v !== '',
                "Filter '{$filter_name}' requires a non-empty value."
            )
        );

        switch ($db_type) {
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
                if ($possible_values !== null) {
                    ToolValidation::validateOne($validator->enum($possible_values));
                }
                break;
        }
    }
}

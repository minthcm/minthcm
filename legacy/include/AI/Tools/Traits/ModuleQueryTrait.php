<?php

namespace MintHCM\AI\Tools\Traits;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

use DBManagerFactory;
use MintHCM\AI\Tools\Enums\FilterOperator;
use MintHCM\AI\Tools\Enums\LogicalOperator;
use MintHCM\AI\Tools\Utils\DateTimeConversion;
use MintHCM\AI\Tools\Utils\ToolValidation;

trait ModuleQueryTrait
{
    public const FILTERS_DESCRIPTION = 'Optional list of filters to apply to the query. Each filter has a field_name, operator and value.
        Available operators:
        - Equality: =, <>
        - Comparison: >, <, >=, <=
        - Text matching: LIKE, NOT LIKE
        - Multiple values: IN, NOT IN (use comma-separated string as value: "1,2,3")
        - Range: BETWEEN (use comma-separated string as value: "start,end")

        Date/datetime filtering:
        - Date values must use Y-m-d format (e.g. 2022-01-01); datetime values must use Y-m-d H:i:s format (e.g. 2022-01-01 14:30:00).
        - For specific date searches, always use BETWEEN with the date and next day
        - Example for records on 2022-01-01:
          "field_name": "date_start"
          "operator": "BETWEEN"
          "value": "2022-01-01,2022-01-02"

        Examples:
        {"field_name": "assigned_user_id", "operator": "=", "value": "1"}
        {"field_name": "status", "operator": "IN", "value": "active,pending"}
        {"field_name": "created_date", "operator": "BETWEEN", "value": "2022-01-01,2022-01-31"}

        Important: Use get_module_fields to get available fields in the module. You cannot use fields of type "link" or "relate" in filters, instead use the ID of the related record. You also cannot use fields of source "non-db" in filters, except for email fields (email1, email2) which support filtering via subquery.';

    public const FILTER_SCHEMA = [
        'type' => ['array', 'null'],
        'description' => self::FILTERS_DESCRIPTION,
        'items' => [
            'type' => 'object',
            'properties' => [
                'field_name' => [
                    'type' => 'string',
                    'description' => 'Name of the field to filter by.',
                ],
                'operator' => [
                    'type' => 'string',
                    'description' => 'Operator to use to filter the field.',
                    'enum' => FilterOperator::SCHEMA_VALUES,
                ],
                'value' => [
                    'type' => 'string',
                    'description' => 'Value to filter by. For IN and NOT IN use comma-separated string (e.g. "1,2,3"). For BETWEEN use comma-separated string with exactly two values (e.g. "start,end").',
                ],
            ],
            'required' => ['field_name', 'operator', 'value'],
        ],
        'default' => [],
    ];

    /**
     * @return array{0: mixed, 1: string, 2: array<string, array<string, mixed>>}
     */
    private function loadBeanAndDefs(string $module_name): array
    {
        $bean = \BeanFactory::getBean($module_name);
        if (!$bean) {
            throw new \RuntimeException("Module '{$module_name}' not found or not accessible.");
        }
        $table_name = $bean->table_name ?? strtolower($module_name);
        $field_defs = isset($bean->field_defs) ? (array) $bean->field_defs : [];

        if (empty($table_name) || [] === $field_defs) {
            throw new \RuntimeException("Module '{$module_name}' not found or not accessible.");
        }

        return [$bean, (string) $table_name, $field_defs];
    }

    private function isDateField(string $field_type): bool
    {
        return in_array($field_type, ['date', 'datetime', 'datetimecombo'], true);
    }

    /** @param array<string, array<string, mixed>> $field_defs */
    private function buildWhereClause(
        array|string|null $filters,
        array $field_defs,
        string $table_name,
        LogicalOperator|string $operator
    ): string {
        $where = [];
        $db = DBManagerFactory::getInstance();
        if ($db === false) {
            throw new \RuntimeException('Database connection is not available.');
        }
        $operator_value = $operator instanceof LogicalOperator ? $operator->value : strtolower($operator);
        $validators = [ToolValidation::make($operator_value, 'operator')->required()->enum(['and', 'or'])];

        foreach ($this->normalizeFilters($filters) as $filter) {
            $field = $filter['field'];
            $operator_enum = $filter['operator'];
            $value = $filter['value'];
            $type = (string) ($field_defs[$field]['type'] ?? '');

            $validators[] = ToolValidation::make(null, $field)->filterField($field_defs);

            $values = $this->extractValues($value, $operator_enum);
            foreach ($values as $index => $single_value) {
                $validator = ToolValidation::make($single_value, $field)->fieldType($type);
                $validators[] = $validator;
                if ($this->isDateField($type) && $validator->isValid()) {
                    $values[$index] = DateTimeConversion::fromUserTZ((string) $single_value);
                }
            }

            $where[] = $this->buildWhereCondition($db, $table_name, $field, $operator_enum, $values);
        }

        ToolValidation::validateMany($validators);

        $glue = ($operator_value === LogicalOperator::Or->value) ? ' OR ' : ' AND ';
        $filter_clause = $where !== [] ? '(' . implode($glue, $where) . ') AND ' : '';

        return $filter_clause . "{$table_name}.deleted = 0";
    }

    /**
     * @param array<int, string> $values
     */
    private function buildWhereCondition(\DBManager $db, string $table_name, string $field, FilterOperator $operator, array $values): string
    {
        if (preg_match('/^email\d+$/', $field)) {
            return $this->buildEmailWhereCondition($db, $table_name, $operator, $values);
        }

        if (in_array($operator, [FilterOperator::In, FilterOperator::NotIn], true)) {
            return "{$table_name}.{$field} {$operator->value} (" . $db->implodeQuoted($values) . ')';
        }

        if ($operator === FilterOperator::Between) {
            if (2 !== count($values)) {
                throw new \InvalidArgumentException('BETWEEN operator requires two values.');
            }

            return "{$table_name}.{$field} BETWEEN " . $db->quoted($values[0]) . ' AND ' . $db->quoted($values[1]);
        }

        if (in_array($operator, [FilterOperator::Like, FilterOperator::NotLike], true)) {
            return "{$table_name}.{$field} {$operator->value} " . $db->quoted('%' . $values[0] . '%');
        }

        return "{$table_name}.{$field} {$operator->value} " . $db->quoted($values[0]);
    }

    /**
     * Builds a WHERE condition for email fields using a subquery on email_addresses table.
     *
     * @param array<int, string> $values
     */
    private function buildEmailWhereCondition(\DBManager $db, string $table_name, FilterOperator $operator, array $values): string
    {
        $subquery = "SELECT eabr.bean_id FROM email_addr_bean_rel eabr "
            . "INNER JOIN email_addresses ea ON ea.id = eabr.email_address_id AND ea.deleted = 0 "
            . "WHERE eabr.bean_id = {$table_name}.id AND eabr.deleted = 0";

        if (in_array($operator, [FilterOperator::Like, FilterOperator::NotLike], true)) {
            $condition = "ea.email_address {$operator->value} " . $db->quoted('%' . $values[0] . '%');
        } elseif (in_array($operator, [FilterOperator::In, FilterOperator::NotIn], true)) {
            $condition = "ea.email_address IN (" . $db->implodeQuoted($values) . ")";
        } elseif ($operator === FilterOperator::Equals) {
            $condition = "ea.email_address = " . $db->quoted($values[0]);
        } elseif ($operator === FilterOperator::NotEquals) {
            $condition = "ea.email_address <> " . $db->quoted($values[0]);
        } else {
            $condition = "ea.email_address {$operator->value} " . $db->quoted($values[0]);
        }

        $not = in_array($operator, [FilterOperator::NotLike, FilterOperator::NotIn, FilterOperator::NotEquals], true)
            ? 'NOT ' : '';

        return "{$not}EXISTS ({$subquery} AND {$condition})";
    }

    /**
     * @param array<mixed>|string $value
     *
     * @return array<int, string>
     */
    private function extractValues(array|string $value, FilterOperator $operator): array
    {
        if (in_array($operator, [FilterOperator::In, FilterOperator::NotIn, FilterOperator::Between], true)) {
            return array_values(array_map('trim', explode(',', (string) $value)));
        }

        if (is_array($value)) {
            return array_values(array_map(static fn(mixed $v): string => (string) $v, $value));
        }

        return [(string) $value];
    }

    /**
     * @param array<int, array<string, mixed>>|array<string, array<string, mixed>>|string|null $filters
     *
     * @return array<int, array{field: string, operator: FilterOperator, value: array|string}>
     */
    private function normalizeFilters(array|string|null $filters): array
    {
        if ($filters === null || $filters === '' || $filters === []) {
            return [];
        }

        if (is_string($filters)) {
            $decoded = json_decode($filters, true);
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
                throw new \InvalidArgumentException('Invalid JSON in filters: ' . json_last_error_msg());
            }
            $filters = $decoded;
        }

        $normalized = [];
        foreach ($filters as $key => $filter) {
            $filter_data = (array) $filter;

            if (isset($filter_data['field_name'])) {
                $field = (string) $filter_data['field_name'];
            } else {
                $field = is_string($key) ? $key : '';
            }

            if ($field === '') {
                throw new \InvalidArgumentException('Each filter must contain a non-empty field_name.');
            }

            $operator = $this->parseFilterOperator($filter_data['operator'] ?? FilterOperator::Equals->value, $field);
            $normalized[] = [
                'field' => $field,
                'operator' => $operator,
                'value' => isset($filter_data['value'])
                    ? (is_array($filter_data['value']) ? $filter_data['value'] : (string) $filter_data['value'])
                    : '',
            ];
        }

        return $normalized;
    }

    private function parseFilterOperator(mixed $raw_operator, string $field): FilterOperator
    {
        if ($raw_operator instanceof FilterOperator) {
            return $raw_operator;
        }

        $raw = strtoupper(trim((string) $raw_operator));
        try {
            return FilterOperator::from($raw);
        } catch (\ValueError) {
            $allowed = implode(', ', array_map(
                static fn(FilterOperator $case): string => $case->value,
                FilterOperator::cases()
            ));

            throw new \InvalidArgumentException(
                "Invalid operator '{$raw_operator}' for field '{$field}'. Allowed operators: {$allowed}."
            );
        }
    }
}

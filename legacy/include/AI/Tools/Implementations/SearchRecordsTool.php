<?php

namespace MintHCM\AI\Tools\Implementations;

use MintHCM\AI\Tools\AbstractTool;
use MintHCM\AI\Tools\ToolInterface;
use MintHCM\AI\Tools\ToolSchema;
use MintHCM\AI\Tools\ToolResult;
use MintHCM\AI\Tools\Traits\ModuleQueryTrait;
use MintHCM\AI\Tools\Traits\PaginationTrait;
use MintHCM\AI\Tools\Enums\LogicalOperator;
use MintHCM\AI\Tools\Utils\DateTimeConversion;
use MintHCM\AI\Tools\Utils\ToolValidation;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class SearchRecordsTool extends AbstractTool implements ToolInterface
{
    use ModuleQueryTrait;
    use PaginationTrait;

    public function getName(): string
    {
        return 'search_records';
    }

    public function getSchema(): ToolSchema
    {
        return new ToolSchema(
            name: 'search_records',
            description: 'Retrieve a list of records from a MintHCM module matching given filters. You should use get_module_names to get available modules and get_module_fields to get fields available in the module. Supports pagination via offset and limit parameters.',
            parameters: [
                'type' => 'object',
                'properties' => [
                    'module_name' => ['type' => 'string', 'description' => 'Name of the module in Mint in which the information is to be read.'],
                    'fields' => ['type' => 'array', 'items' => ['type' => 'string'], 'minItems' => 1, 'description' => "List of fields to retrieve from the module. Example: ['id','name','date_start','status']."],
                    'filters' => self::FILTER_SCHEMA,
                    'operator' => ['type' => 'string', 'enum' => [LogicalOperator::And->value, LogicalOperator::Or->value], 'description' => "Operator to use to join all filters. Possible values: 'and','or'. Defaults to 'and'."],
                    'offset' => self::PAGINATION_OFFSET,
                    'limit' => self::PAGINATION_LIMIT,
                ],
                'required' => ['module_name', 'fields']
            ],
        );
    }

    public function execute(array $params): ToolResult
    {
        $args = [];
        if (array_key_exists('module_name', $params)) {
            $args['module_name'] = (string) $params['module_name'];
        }
        if (array_key_exists('fields', $params)) {
            $args['fields'] = (array) $params['fields'];
        }
        if (array_key_exists('filters', $params)) {
            $args['filters'] = $params['filters'];
        }
        if (array_key_exists('operator', $params)) {
            $args['operator'] = is_string($params['operator']) ? LogicalOperator::from($params['operator']) : $params['operator'];
        }
        if (array_key_exists('offset', $params)) {
            $args['offset'] = (int) $params['offset'];
        }
        if (array_key_exists('limit', $params)) {
            $args['limit'] = (int) $params['limit'];
        }
        return $this->searchRecords(...$args);
    }
    public function searchRecords(
        string $module_name,
        array $fields,
        array|string|null $filters = null,
        LogicalOperator $operator = LogicalOperator::And,
        int $offset = 0,
        int $limit = -1,
    ): ToolResult {
        try {
            ToolValidation::validateMany([
                ToolValidation::make($module_name, 'module_name')->required()->string(),
                ToolValidation::make($fields, 'fields')->required()->array(),
            ]);

            [$resolved_offset, $resolved_limit] = $this->processPaginationParams($offset, $limit);
            $this->checkPermissions($module_name);
            [$bean, $table_name, $field_defs] = $this->loadBeanAndDefs($module_name);

            $validators = [];
            foreach ($fields as $field) {
                $validators[] = ToolValidation::make(null, (string) $field)->fieldModule($field_defs, $module_name);
            }
            ToolValidation::validateMany($validators);

            $where_clause = $this->buildWhereClause($filters, $field_defs, $table_name, $operator);
            $result = $bean->get_list('', $where_clause, $resolved_offset, $resolved_limit, $this->getMaxPaginationLimit($resolved_limit));

            $records = [];
            foreach (($result['list'] ?? []) as $row) {
                $record = ['id' => $row->id];
                foreach ($fields as $field) {
                    $field_name = (string) $field;
                    if (!isset($field_defs[$field_name]) || $field_name === 'id') {
                        continue;
                    }
                    $raw_value = $row->{$field_name} ?? null;
                    $record[$field_name] = $this->isDateField((string) ($field_defs[$field_name]['type'] ?? ''))
                        ? DateTimeConversion::formatDate((string) $raw_value)
                        : $raw_value;
                }
                $records[] = $record;
            }

            if ($records === []) {
                return $this->successResult(
                    "No records found in module {$module_name} with given filters",
                    [
                        'records_returned' => 0,
                        'data' => [],
                        'pagination_info' => [
                            'total_count' => 0,
                            'current_offset' => $result['current_offset'] ?? $resolved_offset,
                            'records_returned' => 0,
                            'next_offset' => -1,
                        ],
                    ]
                );
            }

            $payload = $this->formatPaginationData($result, $resolved_offset, [
                'records_returned' => count($records),
                'data' => $records,
            ], $resolved_limit);

            return $this->successResult('Records retrieved successfully.', $payload);
        } catch (\Throwable $e) {
            return $this->handleExecutionException($e);
        }
    }
}

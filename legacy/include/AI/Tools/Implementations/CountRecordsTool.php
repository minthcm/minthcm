<?php

namespace MintHCM\AI\Tools\Implementations;

use MintHCM\AI\Tools\AbstractTool;
use MintHCM\AI\Tools\ToolInterface;
use MintHCM\AI\Tools\ToolSchema;
use MintHCM\AI\Tools\ToolResult;
use DBManagerFactory;
use MintHCM\AI\Tools\Traits\ModuleQueryTrait;
use MintHCM\AI\Tools\Enums\LogicalOperator;
use MintHCM\AI\Tools\Utils\ToolValidation;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class CountRecordsTool extends AbstractTool implements ToolInterface
{
    use ModuleQueryTrait;

    public function getName(): string
    {
        return 'count_records';
    }

    public function getSchema(): ToolSchema
    {
        return new ToolSchema(
            name: 'count_records',
            description: 'Count the number of records in a MintHCM module. Always use get_module_fields first if you are unsure about the available fields for filtering.',
            parameters: [
                'type' => 'object',
                'properties' => [
                    'module_name' => ['type' => 'string', 'description' => 'Name of the module to count records in.'],
                    'filters' => self::FILTER_SCHEMA,
                    'operator' => [
                        'type' => 'string',
                        'description' => "Operator to use to join all filters. Allowed values: 'and', 'or'.",
                        'enum' => [LogicalOperator::And->value, LogicalOperator::Or->value],
                        'default' => LogicalOperator::And->value,
                    ],
                ],
                'required' => ['module_name']
            ],
        );
    }

    public function execute(array $params): ToolResult
    {
        $args = [];
        if (array_key_exists('module_name', $params)) {
            $args['module_name'] = (string) $params['module_name'];
        }
        if (array_key_exists('filters', $params)) {
            $args['filters'] = $params['filters'];
        }
        if (array_key_exists('operator', $params)) {
            $args['operator'] = is_string($params['operator']) ? LogicalOperator::from($params['operator']) : $params['operator'];
        }
        return $this->countRecords(...$args);
    }
    public function countRecords(
        string $module_name,
        array|string|null $filters = null,
        LogicalOperator $operator = LogicalOperator::And,
    ): ToolResult {
        try {
            ToolValidation::validateMany([
                ToolValidation::make($module_name, 'module_name')->required()->string(),
            ]);

            $this->checkPermissions($module_name);
            [$bean, $table_name, $field_defs] = $this->loadBeanAndDefs($module_name);
            $where = $this->buildWhereClause($filters, $field_defs, $table_name, $operator);

            $query = $bean->create_new_list_query('', $where);
            $count_query = $bean->create_list_count_query($query);
            $db = DBManagerFactory::getInstance();
            $result = $db->query($count_query);
            $row = (array) $db->fetchByAssoc($result);

            $count = isset($row['c']) ? (int) $row['c'] : 0;

            return $this->successResult(
                "Count for module '{$module_name}': {$count}",
                ['module' => $module_name, 'count' => $count]
            );
        } catch (\Throwable $e) {
            return $this->handleExecutionException($e);
        }
    }
}

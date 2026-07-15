<?php

namespace MintHCM\AI\Tools\Implementations;

use MintHCM\AI\Tools\AbstractTool;
use MintHCM\AI\Tools\ToolInterface;
use MintHCM\AI\Tools\ToolSchema;
use MintHCM\AI\Tools\ToolResult;
use MintHCM\AI\Tools\Traits\ModuleQueryTrait;
use MintHCM\AI\Tools\Enums\LogicalOperator;
use MintHCM\AI\Tools\Utils\ToolValidation;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class SumRecordsTool extends AbstractTool implements ToolInterface
{
    use ModuleQueryTrait;

    public function getName(): string
    {
        return 'sum_records';
    }

    public function getSchema(): ToolSchema
    {
        return new ToolSchema(
            name: 'sum_records',
            description: 'Sum the values of a specific numeric field for records in a MintHCM module matching given filters.',
            parameters: [
                'type' => 'object',
                'properties' => [
                    'module_name' => ['type' => 'string', 'description' => 'Name of the module in Mint in which the information is to be read.'],
                    'sum_field' => ['type' => 'string', 'description' => 'The name of the numeric field in the module to sum.'],
                    'filters' => self::FILTER_SCHEMA,
                    'operator' => ['type' => 'string', 'enum' => [LogicalOperator::And->value, LogicalOperator::Or->value], 'description' => "Operator to use to join all filters. Possible values: 'and','or'. Defaults to 'and'."],
                ],
                'required' => ['module_name', 'sum_field']
            ],
        );
    }

    public function execute(array $params): ToolResult
    {
        $args = [];
        if (array_key_exists('module_name', $params)) {
            $args['module_name'] = (string) $params['module_name'];
        }
        if (array_key_exists('sum_field', $params)) {
            $args['sum_field'] = (string) $params['sum_field'];
        }
        if (array_key_exists('filters', $params)) {
            $args['filters'] = $params['filters'];
        }
        if (array_key_exists('operator', $params)) {
            $args['operator'] = is_string($params['operator']) ? LogicalOperator::from($params['operator']) : $params['operator'];
        }
        return $this->sumRecords(...$args);
    }
    public function sumRecords(
        string $module_name,
        string $sum_field,
        array|string|null $filters = null,
        LogicalOperator $operator = LogicalOperator::And,
    ): ToolResult {
        try {
            ToolValidation::validateMany([
                ToolValidation::make($module_name, 'module_name')->required()->string(),
                ToolValidation::make($sum_field, 'sum_field')->required()->string(),
            ]);

            $this->checkPermissions($module_name);
            [$bean, $table_name, $field_defs] = $this->loadBeanAndDefs($module_name);

            ToolValidation::validateOne(
                ToolValidation::make(null, $sum_field)->fieldModule($field_defs, $module_name)
            );

            $numeric_types = ['int', 'integer', 'float', 'double', 'decimal', 'currency'];
            $db_type = strtolower((string) ($field_defs[$sum_field]['dbType'] ?? $field_defs[$sum_field]['type'] ?? ''));
            if (!in_array($db_type, $numeric_types, true)) {
                return $this->errorResult("Field '{$sum_field}' is not a valid numeric field.");
            }

            $where_clause = $this->buildWhereClause($filters, $field_defs, $table_name, $operator);

            $base_query = $bean->create_new_list_query('', $where_clause);
            $sum_query = preg_replace('/SELECT.*?FROM/is', "SELECT SUM($table_name.$sum_field) as total_sum FROM ", $base_query, 1);
            $db = $bean->db;
            $result = $db->query($sum_query);
            if ($result === false) {
                throw new \RuntimeException('Failed to execute SUM query.');
            }
            $row = $db->fetchByAssoc($result);
            $total_sum = (float) ($row['total_sum'] ?? 0.0);

            return $this->successResult(
                'Records summed successfully.',
                ['module' => $module_name, 'sum_field' => $sum_field, 'sum' => (string) $total_sum]
            );
        } catch (\Throwable $e) {
            return $this->handleExecutionException($e);
        }
    }
}

<?php

namespace MintMCP\Capabilities\Tools;

use Mcp\Capability\Attribute\McpTool;
use Mcp\Capability\Attribute\Schema;
use Mcp\Schema\Result\CallToolResult;
use MintHCM\AI\Tools\Traits\ModuleQueryTrait;
use MintHCM\AI\Tools\Enums\LogicalOperator;
use MintHCM\AI\Tools\Implementations\CountRecordsTool as AICountRecordsTool;

final class CountRecordsTool extends AbstractMCPTool
{
    use ModuleQueryTrait;
    #[McpTool(
        name: 'count_records',
        description: 'Count the number of records in a MintHCM module. Always use get_module_fields first if you are unsure about the available fields for filtering.'
    )]
    #[Schema(
        type: 'object',
        properties: [
            'module_name' => ['type' => 'string', 'description' => 'Name of the module to count records in.'],
            'filters' => self::FILTER_SCHEMA,
            'operator' => [
                'type' => 'string',
                'description' => "Operator to use to join all filters. Allowed values: 'and', 'or'.",
                'enum' => [LogicalOperator::And->value, LogicalOperator::Or->value],
                'default' => LogicalOperator::And->value,
            ],
        ],
        required: ['module_name'],
    )]
    public function countRecords(
        string $module_name,
        array|string|null $filters = null,
        LogicalOperator $operator = LogicalOperator::And,
    ): CallToolResult {
        return $this->delegate(AICountRecordsTool::class, [
            'module_name' => $module_name,
            'filters' => $filters,
            'operator' => $operator,
        ]);
    }
}

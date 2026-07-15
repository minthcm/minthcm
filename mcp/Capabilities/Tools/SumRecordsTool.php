<?php

namespace MintMCP\Capabilities\Tools;

use Mcp\Capability\Attribute\McpTool;
use Mcp\Capability\Attribute\Schema;
use Mcp\Schema\Result\CallToolResult;
use MintHCM\AI\Tools\Traits\ModuleQueryTrait;
use MintHCM\AI\Tools\Enums\LogicalOperator;
use MintHCM\AI\Tools\Implementations\SumRecordsTool as AISumRecordsTool;

final class SumRecordsTool extends AbstractMCPTool
{
    use ModuleQueryTrait;

    #[McpTool(
        name: 'sum_records',
        description: 'Sum the values of a specific numeric field for records in a MintHCM module matching given filters.'
    )]
    #[Schema(
        type: 'object',
        properties: [
            'module_name' => ['type' => 'string', 'description' => 'Name of the module in Mint in which the information is to be read.'],
            'sum_field' => ['type' => 'string', 'description' => 'The name of the numeric field in the module to sum.'],
            'filters' => self::FILTER_SCHEMA,
            'operator' => ['type' => 'string', 'enum' => [LogicalOperator::And->value, LogicalOperator::Or->value], 'description' => "Operator to use to join all filters. Possible values: 'and','or'. Defaults to 'and'."],
        ],
        required: ['module_name', 'sum_field'],
    )]
    public function sumRecords(
        string $module_name,
        string $sum_field,
        array|string|null $filters = null,
        LogicalOperator $operator = LogicalOperator::And,
    ): CallToolResult {
        return $this->delegate(AISumRecordsTool::class, [
            'module_name' => $module_name,
            'sum_field' => $sum_field,
            'filters' => $filters,
            'operator' => $operator,
        ]);
    }
}

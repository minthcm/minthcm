<?php

namespace MintMCP\Capabilities\Tools;

use Mcp\Capability\Attribute\McpTool;
use Mcp\Capability\Attribute\Schema;
use Mcp\Schema\Result\CallToolResult;
use MintHCM\AI\Tools\Traits\ModuleQueryTrait;
use MintHCM\AI\Tools\Traits\PaginationTrait;
use MintHCM\AI\Tools\Enums\LogicalOperator;
use MintMCP\Capabilities\Resources\SearchRecordsResource;
use MintHCM\AI\Tools\Implementations\SearchRecordsTool as AISearchRecordsTool;


final class SearchRecordsTool extends AbstractMCPTool
{
    use ModuleQueryTrait;
    use PaginationTrait;

    #[McpTool(
        name: 'search_records',
        description: 'Retrieve a list of records from a MintHCM module matching given filters. You should use get_module_names to get available modules and get_module_fields to get fields available in the module. Supports pagination via offset and limit parameters.',
        meta: [
            'ui' => [
                'resourceUri' => SearchRecordsResource::URI,
                'visibility' => ['model', 'app'],
            ],
        ]
    )]
    #[Schema(
        type: 'object',
        properties: [
            'module_name' => ['type' => 'string', 'description' => 'Name of the module in Mint in which the information is to be read.'],
            'fields' => ['type' => 'array', 'items' => ['type' => 'string'], 'minItems' => 1, 'description' => "List of fields to retrieve from the module. Example: ['id','name','date_start','status']."],
            'filters' => self::FILTER_SCHEMA,
            'operator' => ['type' => 'string', 'enum' => [LogicalOperator::And->value, LogicalOperator::Or->value], 'description' => "Operator to use to join all filters. Possible values: 'and','or'. Defaults to 'and'."],
            'offset' => self::PAGINATION_OFFSET,
            'limit' => self::PAGINATION_LIMIT,
        ],
        required: ['module_name', 'fields'],
    )]
    public function searchRecords(
        string $module_name,
        array $fields,
        array|string|null $filters = null,
        LogicalOperator $operator = LogicalOperator::And,
        int $offset = 0,
        int $limit = -1,
    ): CallToolResult {
        return $this->delegate(AISearchRecordsTool::class, [
            'module_name' => $module_name,
            'fields' => $fields,
            'filters' => $filters,
            'operator' => $operator,
            'offset' => $offset,
            'limit' => $limit,
        ]);
    }
}

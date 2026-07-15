<?php

namespace MintMCP\Capabilities\Tools;

use Mcp\Capability\Attribute\McpTool;
use Mcp\Capability\Attribute\Schema;
use Mcp\Schema\Result\CallToolResult;
use MintHCM\AI\Tools\Traits\PaginationTrait;
use MintHCM\AI\Tools\Implementations\ExecuteKReportTool as AIExecuteKReportTool;

final class ExecuteKReportTool extends AbstractMCPTool
{
    use PaginationTrait;

    #[McpTool(
        name: 'execute_report',
        description: 'Execute a report and return its output: the list of output fields and the record rows with their values. Optionally apply filters (each filter: operator + value or values for oneof, or value and value_to for between). For date filters use value in Y-m-d format (e.g. 2025-02-03). For datetime filters use Y-m-d H:i:s (e.g. 2025-02-03 14:30:00); times are interpreted in the current user timezone. Use get_report_details to discover report id, output fields, and filter names/operators.'
    )]
    #[Schema(
        type: 'object',
        properties: [
            'report_id' => ['type' => 'string', 'description' => 'ID of the report to execute.'],
            'filters' => [
                'type' => 'array',
                'description' => 'Optional list of filter conditions to apply. Each item: filter_name or field_id, operator, and value (or values for oneof, or value + value_to for between).',
                'items' => [
                    'type' => 'object',
                    'properties' => [
                        'filter_name' => ['type' => 'string', 'description' => 'Filter name as returned by get_report_details (e.g. "Date", "Status").'],
                        'operator' => ['type' => 'string', 'description' => 'Operator: e.g. equals, notequal, contains, between, oneof, before, after.'],
                        'value' => ['type' => 'string', 'description' => 'Single value for the filter. For date filters use Y-m-d (e.g. 2025-02-03). For datetime filters use Y-m-d H:i:s (e.g. 2025-02-03 14:30:00). For "between", this is the from value.'],
                        'value_to' => ['type' => 'string', 'description' => 'For operator "between": the end value. Use same format as value (Y-m-d for date, Y-m-d H:i:s for datetime).'],
                        'values' => [
                            'type' => 'array',
                            'description' => 'For operator "oneof" or "oneofnot": list of values.',
                            'items' => ['type' => 'string'],
                        ],
                    ],
                    'additionalProperties' => false,
                ],
            ],
            'offset' => self::PAGINATION_OFFSET,
            'limit' => self::PAGINATION_LIMIT,
        ],
    )]
    public function executeReport(
        string $report_id,
        array $filters = [],
        int $offset = 0,
        int $limit = -1,
    ): CallToolResult {
        return $this->delegate(AIExecuteKReportTool::class, [
            'report_id' => $report_id,
            'filters' => $filters,
            'offset' => $offset,
            'limit' => $limit,
        ]);
    }
}

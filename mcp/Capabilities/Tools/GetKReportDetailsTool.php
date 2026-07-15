<?php

namespace MintMCP\Capabilities\Tools;

use Mcp\Capability\Attribute\McpTool;
use Mcp\Capability\Attribute\Schema;
use Mcp\Schema\Result\CallToolResult;
use MintHCM\AI\Tools\Implementations\GetKReportDetailsTool as AIGetKReportDetailsTool;
final class GetKReportDetailsTool extends AbstractMCPTool
{
    #[McpTool(
        name: 'get_report_details',
        description: 'Get details of a specific report available in MintHCM, including for each filter: possible operators (e.g. Equals, Between), value inputs (single value vs From/To), and when applicable the list of possible values for the filter field.'
    )]
    #[Schema(
        type: 'object',
        properties: [
            'report_id' => ['type' => 'string', 'description' => 'ID of the report to get details for.'],
        ],
        required: ['report_id'],
    )]
    public function getReportDetails(
        string $report_id,
    ): CallToolResult {
        return $this->delegate(AIGetKReportDetailsTool::class, [
            'report_id' => $report_id,
        ]);
    }
}

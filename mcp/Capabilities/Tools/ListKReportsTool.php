<?php

namespace MintMCP\Capabilities\Tools;

use Mcp\Capability\Attribute\McpTool;
use Mcp\Capability\Attribute\Schema;
use Mcp\Schema\Result\CallToolResult;
use MintHCM\AI\Tools\Traits\PaginationTrait;
use MintHCM\AI\Tools\Implementations\ListKReportsTool as AIListKReportsTool;


final class ListKReportsTool extends AbstractMCPTool
{
    use PaginationTrait;

    #[McpTool(
        name: 'list_reports',
        description: 'Get list of reports available in MintHCM'
    )]
    #[Schema(
        type: 'object',
        properties: [
            'offset' => self::PAGINATION_OFFSET,
            'limit' => self::PAGINATION_LIMIT,
        ],
    )]
    public function listReports(
        int $offset = 0,
        int $limit = -1,
    ): CallToolResult {
        return $this->delegate(AIListKReportsTool::class, [
            'offset' => $offset,
            'limit' => $limit,
        ]);
    }
}

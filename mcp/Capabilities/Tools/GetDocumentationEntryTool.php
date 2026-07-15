<?php

namespace MintMCP\Capabilities\Tools;

use Mcp\Capability\Attribute\McpTool;
use Mcp\Capability\Attribute\Schema;
use Mcp\Schema\Result\CallToolResult;
use MintHCM\AI\Tools\Implementations\GetDocumentationEntryTool as AIGetDocumentationEntryTool;


final class GetDocumentationEntryTool extends AbstractMCPTool
{
    #[McpTool(
        name: 'get_documentation_entry',
        description: 'Retrieve the full content of a MCP documentation entry by its ID. Use browse_documentation first to find the entry ID.',
    )]
    #[Schema(
        type: 'object',
        properties: [
            'id' => ['type' => 'string', 'description' => 'The ID of the documentation entry to retrieve.'],
        ],
        required: ['id'],
    )]
    public function getDocumentationEntry(string $id): CallToolResult
    {
        return $this->delegate(AIGetDocumentationEntryTool::class, [
            'id' => $id,
        ]);
    }
}

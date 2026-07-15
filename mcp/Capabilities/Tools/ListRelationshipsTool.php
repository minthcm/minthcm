<?php

namespace MintMCP\Capabilities\Tools;

use Mcp\Capability\Attribute\McpTool;
use Mcp\Capability\Attribute\Schema;
use Mcp\Schema\Result\CallToolResult;
use MintHCM\AI\Tools\Implementations\ListRelationshipsTool as AIListRelationshipsTool;

final class ListRelationshipsTool extends AbstractMCPTool
{
    #[McpTool(
        name: 'list_relationships',
        description: 'Get concrete linked records for one source record. Returns relationship_name, related_module, and linked record IDs.'
    )]
    #[Schema(
        type: 'object',
        properties: [
            'module_name' => ['type' => 'string', 'description' => 'Name of the source module.'],
            'record_id' => ['type' => 'string', 'description' => 'ID of the source record to inspect relationships for.'],
        ],
        required: ['module_name', 'record_id'],
    )]
    public function listRelationships(string $module_name, string $record_id): CallToolResult
    {
        return $this->delegate(AIListRelationshipsTool::class, [
            'module_name' => $module_name,
            'record_id' => $record_id,
        ]);
    }
}

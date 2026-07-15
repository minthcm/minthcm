<?php

namespace MintMCP\Capabilities\Tools;

use Mcp\Capability\Attribute\McpTool;
use Mcp\Capability\Attribute\Schema;
use Mcp\Schema\Result\CallToolResult;
use MintHCM\AI\Tools\Implementations\DeleteRelationshipTool as AIDeleteRelationshipTool;

final class DeleteRelationshipTool extends AbstractMCPTool
{
    #[McpTool(
        name: 'delete_relationship',
        description: "Delete a link between existing records. Get valid relationship_name values from get_module_fields(module).linkable_relationships. This tool is only for link relationships."
    )]
    #[Schema(
        type: 'object',
        properties: [
            'module_name' => ['type' => 'string', 'description' => 'Name of the source module (the side from which the relationship is loaded).'],
            'record_id' => ['type' => 'string', 'description' => 'ID of the source record in module_name.'],
            'relationship_name' => ['type' => 'string', 'description' => 'Link field name on the source module. Use get_module_fields(module).linkable_relationships[].relationship_name.'],
            'related_id' => ['type' => 'string', 'description' => 'ID of the related record to unlink.'],
        ],
        required: ['module_name', 'record_id', 'relationship_name', 'related_id'],
    )]
    public function deleteRelationship(
        string $module_name,
        string $record_id,
        string $relationship_name,
        string $related_id
    ): CallToolResult {
        return $this->delegate(AIDeleteRelationshipTool::class, [
            'module_name' => $module_name,
            'record_id' => $record_id,
            'relationship_name' => $relationship_name,
            'related_id' => $related_id,
        ]);
    }
}

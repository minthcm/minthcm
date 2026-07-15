<?php

namespace MintMCP\Capabilities\Tools;

use Mcp\Capability\Attribute\McpTool;
use Mcp\Capability\Attribute\Schema;
use Mcp\Schema\Result\CallToolResult;
use MintHCM\AI\Tools\Implementations\CreateRelationshipTool as AICreateRelationshipTool;

final class CreateRelationshipTool extends AbstractMCPTool
{
    #[McpTool(
        name: 'create_relationship',
        description: "Create a link between existing records. Get valid relationship_name values from get_module_fields(module).linkable_relationships. This tool is only for link relationships; use create_record or update_record for manipulating direct module fields."
    )]
    #[Schema(
        type: 'object',
        properties: [
            'module_name' => ['type' => 'string', 'description' => 'Name of the source module (the side from which the relationship is loaded).'],
            'record_id' => ['type' => 'string', 'description' => 'ID of the source record in module_name.'],
            'relationship_name' => ['type' => 'string', 'description' => 'Link field name on the source module. Use get_module_fields(module).linkable_relationships[].relationship_name.'],
            'related_id' => [
                'type' => 'string',
                'description' => 'ID of the related record to link.',
            ],
            'additional_values' => [
                'type' => ['object', 'array'],
                'anyOf' => [
                    ['type' => 'object', 'additionalProperties' => true],
                    ['type' => 'array', 'items' => ['type' => 'string'], 'maxItems' => 0],
                ],
                'description' => 'Optional extra column values for join-table relationships (key-value).',
            ],
        ],
        required: ['module_name', 'record_id', 'relationship_name', 'related_id'],
    )]
    public function createRelationship(
        string $module_name,
        string $record_id,
        string $relationship_name,
        string $related_id,
        object|array $additional_values = [],
    ): CallToolResult {
        return $this->delegate(AICreateRelationshipTool::class, [
            'module_name' => $module_name,
            'record_id' => $record_id,
            'relationship_name' => $relationship_name,
            'related_id' => $related_id,
            'additional_values' => $additional_values,
        ]);
    }
}

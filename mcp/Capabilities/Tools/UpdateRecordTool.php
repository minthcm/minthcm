<?php

namespace MintMCP\Capabilities\Tools;

use Mcp\Capability\Attribute\McpTool;
use Mcp\Capability\Attribute\Schema;
use Mcp\Schema\Result\CallToolResult;
use MintHCM\AI\Tools\Implementations\UpdateRecordTool as AIUpdateRecordTool;

final class UpdateRecordTool extends AbstractMCPTool
{
    #[McpTool(
        name: 'update_record',
        description: "Update a record in MintHCM module."
    )]
    #[Schema(
        type: 'object',
        properties: [
            'module_name' => ['type' => 'string', 'description' => 'Name of the module in Mint in which the record is to be updated.'],
            'id' => ['type' => 'string', 'description' => 'ID of the record to update.'],
            'attributes' => [
                'type' => ['object', 'array'],
                'anyOf' => [
                    [
                        'type' => 'object',
                        'additionalProperties' => true,
                    ],
                    [
                        // Some clients serialize empty `{}` as `[]`. Accept empty array and treat it as empty attributes.
                        'type' => 'array',
                        'items' => ['type' => 'string'],
                        'maxItems' => 0,
                    ],
                ],
                'description' => 'Attributes to update in key-value format. e.g. {"description": "New Description"}. Date fields must use Y-m-d format (e.g. 2025-02-03); datetime fields must use Y-m-d H:i:s format (e.g. 2025-02-03 14:30:00).',
            ],
        ],
        required: ['module_name', 'id', 'attributes'],
    )]
    public function updateRecord(
        string $module_name,
        string $id,
        array|object $attributes,
    ): CallToolResult {
        return $this->delegate(AIUpdateRecordTool::class, [
            'module_name' => $module_name,
            'id' => $id,
            'attributes' => $attributes,
        ]);
    }
}

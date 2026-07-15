<?php

namespace MintMCP\Capabilities\Tools;

use Mcp\Capability\Attribute\McpTool;
use Mcp\Capability\Attribute\Schema;
use Mcp\Schema\Result\CallToolResult;
use MintHCM\AI\Tools\Implementations\CreateRecordTool as AICreateRecordTool;
final class CreateRecordTool extends AbstractMCPTool
{
    #[McpTool(
        name: 'create_record',
        description: "Create a new record in MintHCM modules, for example new employees, new candidates etc. Don't use this tool for meetings. Use add_meeting for meetings."
    )]
    #[Schema(
        type: 'object',
        properties: [
            'module_name' => ['type' => 'string', 'description' => 'Name of the module in Mint in which the record is to be created.'],
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
                'description' => 'Attributes of new record in key-value format. Date fields must use Y-m-d format (e.g. 2025-02-03); datetime fields must use Y-m-d H:i:s format (e.g. 2025-02-03 14:30:00).',
            ],
        ],
        required: ['module_name', 'attributes'],
    )]
    public function createRecord(
        string $module_name,
        object|array $attributes
    ): CallToolResult
    {
        return $this->delegate(AICreateRecordTool::class, [
            'module_name' => $module_name,
            'attributes' => $attributes,
        ]);
    }
}

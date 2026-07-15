<?php

namespace MintMCP\Capabilities\Tools;

use Mcp\Capability\Attribute\McpTool;
use Mcp\Capability\Attribute\Schema;
use Mcp\Schema\Result\CallToolResult;
use MintHCM\AI\Tools\Implementations\GetModuleFieldsTool as AIGetModuleFieldsTool;

final class GetModuleFieldsTool extends AbstractMCPTool
{

    #[McpTool(
        name: 'get_module_fields',
        description: 'Returns writable fields, required fields, and linkable relationships for a given module. Use get_module_names to get available modules.'
    )]
    #[Schema(
        type: 'object',
        properties: [
            'module_name' => ['type' => 'string', 'description' => 'Name of the module to get fields for.'],
        ],
        required: ['module_name'],
    )]
    public function getModuleFields(
        string $module_name
    ): CallToolResult
    {
        return $this->delegate(AIGetModuleFieldsTool::class, [
            'module_name' => $module_name,
        ]);
    }
}

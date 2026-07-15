<?php

namespace MintMCP\Capabilities\Tools;

use Mcp\Capability\Attribute\McpTool;
use Mcp\Capability\Attribute\Schema;
use Mcp\Schema\Result\CallToolResult;
use MintHCM\AI\Tools\Implementations\DeleteRecordTool as AIDeleteRecordTool;

final class DeleteRecordTool extends AbstractMCPTool
{
    #[McpTool(
        name: 'delete_record',
        description: "Delete record in MintHCM modules. Use search tool to retrieve ID of the record you want to delete if you don't know it."
    )]
    #[Schema(
        type: 'object',
        properties: [
            'module_name' => ['type' => 'string', 'description' => 'Name of the module in Mint in which the record is to be deleted.'],
            'id' => ['type' => 'string', 'description' => 'ID of the record to delete.'],
        ],
        required: ['module_name', 'id'],
    )]
    public function deleteRecord(
        string $module_name,
        string $id
    ): CallToolResult
    {
        return $this->delegate(AIDeleteRecordTool::class, [
            'module_name' => $module_name,
            'id' => $id,
        ]);
    }
}

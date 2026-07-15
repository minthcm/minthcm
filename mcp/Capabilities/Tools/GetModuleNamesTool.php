<?php

namespace MintMCP\Capabilities\Tools;

use Mcp\Capability\Attribute\McpTool;
use Mcp\Schema\Result\CallToolResult;
use MintHCM\AI\Tools\Implementations\GetModuleNamesTool as AIGetModuleNamesTool;


final class GetModuleNamesTool extends AbstractMCPTool
{
    #[McpTool(
        name: 'get_module_names',
        description: 'Returns the names of all available modules in the system.',
    )]
    public function getModuleNames(): CallToolResult
    {
        return $this->delegate(AIGetModuleNamesTool::class, []);
    }
}

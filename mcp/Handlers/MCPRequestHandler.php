<?php

namespace MintMCP\Handlers;

use MintMCP\Tools\AbstractMCPTool;
use MintMCP\Tools\MCPToolRegistry;

class MCPRequestHandler {
    private MCPToolRegistry $toolRegistry;
    
    public function __construct() {
        $this->toolRegistry = new MCPToolRegistry();
    }
    
    /**
     * Handles the tool list request
     */
    public function handleToolsList($params = []) {
        $result = $this->toolRegistry->getParsedTools();
        return $result;
    }
    
    /**
     * Handles a tool call
     */
    public function handleToolCall($params) {
        $toolName = $params['name'] ?? '';
        $arguments = (object)($params['arguments'] ?? []);
        
        if (!$this->toolRegistry->hasTool($toolName)) {
            throw new \InvalidArgumentException("Unknown tool: {$toolName}");
        }
        
        $tool = $this->toolRegistry->getTool($toolName);
        return $tool->execute($arguments);
    }
    
    /**
     * Registers a new tool (for extensions)
     */
    public function registerTool(AbstractMCPTool $tool): void {
        $this->toolRegistry->registerTool($tool);
    }
}

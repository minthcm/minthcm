<?php

namespace MintMCP\Tools;

class MCPToolRegistry {

    /** @var AbstractMCPTool[] */
    private array $tools = [];
    
    public function __construct() {
        $this->registerDefaultTools();
    }
    
    /**
     * Registers default tools
     */
    private function registerDefaultTools(): void {
        $this->registerTool(new ListMeetings());
        $this->registerTool(new AddMeeting());
        $this->registerTool(new Calendar());
        $this->registerTool(new CheckAvailability());
        $this->registerTool(new CountRecords());
        $this->registerTool(new GetModuleNames());
        $this->registerTool(new GetModuleFields());
        $this->registerTool(new ListUsers());
        $this->registerTool(new SumRecords());
        $this->registerTool(new CreateRecord());
        $this->registerTool(new SearchRecords());
        $this->registerTool(new UpdateRecord());
        $this->registerTool(new DeleteRecord());
        
        // Add new tools here:
        // $this->registerTool(new YourNewTool());
    }
    
    /**
     * Registers a new tool
     */
    public function registerTool(AbstractMCPTool $tool): void {
        $this->tools[$tool->getName()] = $tool;
    }
    
    /**
     * Returns all tools as Tool objects
     */
    public function getAllMCPTools(): array {
        return array_map(function($tool) {
            return $tool->createMCPTool();
        }, $this->tools);
    }
    
    /**
     * Returns a tool by name
     */
    public function getTool(string $name): ?AbstractMCPTool {
        return $this->tools[$name] ?? null;
    }
    
    /**
     * Checks if a tool exists
     */
    public function hasTool(string $name): bool {
        return isset($this->tools[$name]);
    }
    
    /**
     * Returns the names of all tools
     */
    public function getToolNames(): array {
        return array_keys($this->tools);
    }

    public function getParsedTools(): array {
        return array_values(array_map(function (AbstractMCPTool $t) {
            return [
                'name'        => $t->getName(),
                'description' => $t->getDescription(),
                'inputSchema'  => $t->getInputSchema(),
            ];
        }, $this->tools));
    }
}
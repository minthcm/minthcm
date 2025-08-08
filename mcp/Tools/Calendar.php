<?php

namespace MintMCP\Tools;

use Mcp\Types\CallToolResult;
use Mcp\Types\ToolInputSchema;
use TimeDate;

class Calendar extends AbstractMCPTool
{
    public function getName(): string
    {
        return 'calendar';
    }

    public function getDescription(): string
    {
        return 'Check the current date and time.';
    }

    public function getInputSchema(): ToolInputSchema
    {
        return ToolInputSchema::fromArray([
            'type' => 'object',
        ]);
    }

    /**
     * Executes the tool's main logic: returns the current date and time in DB format.
     *
     * @param object $arguments
     * @return CallToolResult
     */
    public function execute(object $arguments): CallToolResult
    {
        try {
            $currentDate = TimeDate::getInstance()->nowDb();
            return $this->createResult([
                $this->createTextContent($currentDate)
            ]);
        } catch (\Exception $e) {
            return $this->createResult([
                $this->createTextContent("Error: " . $e->getMessage())
            ]);
        }
    }
}

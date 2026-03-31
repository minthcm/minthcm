<?php

namespace MintMCP\Tools;

use Mcp\Types\ToolInputSchema;
use Mcp\Types\CallToolResult;
use MintHCM\Api\Controllers\Init\Module;
use MintMCP\Tools\Exceptions\ModuleNotAllowedException;

class GetModuleNames extends AbstractMCPTool
{
    public function getName(): string
    {
        return 'get_module_names';
    }

    public function getDescription(): string
    {
        return 'Returns the names of all available modules in the system.';
    }

    public function getInputSchema(): ToolInputSchema
    {
        return ToolInputSchema::fromArray([
            'type' => 'object',
        ]);
    }

    /**
     * Executes the tool: returns the names and labels of all modules available to the user.
     *
     * @param object $arguments Input arguments for the tool (not used)
     * @return CallToolResult
     */
    public function execute(object $arguments): CallToolResult
    {
        /**
         * @var Module
         */
        // $moduleController = ControllerFactory::getInstance()->createController(Module::class);
        // $aclModules = $moduleController->getACLs();

        global $beanList;

        $accessible_modules = array();
        foreach($beanList as $module_name => $bean_name) {
            try {
                $this->checkPermissions($module_name, 'list');
            } catch (ModuleNotAllowedException $e) {
                // Skip blocked modules
                continue;
            }
            $accessible_modules[] = $module_name;
        }

        // Sort modules naturally
        usort($accessible_modules, 'strnatcmp');

        // Format result as a readable list
        if (empty($accessible_modules)) {
            $resultText = "No modules available for this user.";
        } else {
            $resultText = "Available modules:\n";
            foreach ($accessible_modules as $module) {
                $resultText .= "- {$module}\n";
            }
        }

        return $this->createResult([
            $this->createTextContent($resultText)
        ]);
    }
}

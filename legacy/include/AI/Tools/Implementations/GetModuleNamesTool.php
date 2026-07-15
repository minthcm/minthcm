<?php

namespace MintHCM\AI\Tools\Implementations;
use MintHCM\AI\Tools\AbstractTool;
use MintHCM\AI\Tools\ToolInterface;
use MintHCM\AI\Tools\ToolSchema;
use MintHCM\AI\Tools\ToolResult;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class GetModuleNamesTool extends AbstractTool implements ToolInterface
{
    public function getName(): string
    {
        return 'get_module_names';
    }

    public function getSchema(): ToolSchema
    {
        return new ToolSchema(
            name: 'get_module_names',
            description: 'Returns the names of all available modules in the system.',
            parameters: [
            'type' => 'object',
            'properties' => [],
            'required' => []
        ],
        );
    }

    public function execute(array $params): ToolResult
    {
        $args = [];

        return $this->getModuleNames(...$args);
    }

        public function getModuleNames(): ToolResult
    {
        global $bean_list;

        if (!\is_array($bean_list) || [] === $bean_list) {
            return $this->successResult('No modules available for this user.', ['count' => 0, 'modules' => []]);
        }

        $accessible_modules = [];
        foreach ($bean_list as $module_name => $_beanName) {
            try {
                $this->checkPermissions((string) $module_name);
                $accessible_modules[] = (string) $module_name;
            } catch (\Throwable) {
                // Not accessible for current user or blocked by config, skip module.
                continue;
            }
        }

        // Sort modules naturally
        usort($accessible_modules, 'strnatcmp');

        $message = [] === $accessible_modules
            ? 'No modules available for this user.'
            : 'Modules retrieved successfully.';
        return $this->successResult($message, ['count' => \count($accessible_modules), 'modules' => $accessible_modules]);
    }
}

<?php

namespace MintMCP\Tools;

use MintMCP\Tools\Middleware\ToolValidationMiddleware;

use Mcp\Types\ToolInputSchema;
use Mcp\Types\CallToolResult;

class DeleteRecord extends AbstractMCPTool
{

    public function getName(): string
    {
        return 'delete_record';
    }

    public function getDescription(): string
    {
        return "Delete record in MintHCM modules. Use search tool to retrieve ID of the record you want to delete if you don't know it.";
    }

    public function getInputSchema(): ToolInputSchema
    {
        return ToolInputSchema::fromArray([
            'type' => 'object',
            'properties' => [
                'module_name' => [
                    'type' => 'string',
                    'description' => 'Name of the module in Mint in which the record is to be deleted.',
                ],
                'id' => [
                    'type' => 'string',
                    'description' => 'ID of the record to be deleted.',
                ],
            ],
            'required' => ['module_name', 'id'],
        ]);
    }

    /**
     * Executes the tool: deletes a record in the specified module.
     *
     * @param object $arguments Input arguments for the tool
     * @return CallToolResult
     */
    public function execute(object $arguments): CallToolResult
    {
        try {
            ToolValidationMiddleware::validateMany([
                ToolValidationMiddleware::make($arguments->module_name, 'module_name')
                    ->required()
                    ->string(),
                ToolValidationMiddleware::make($arguments->id, 'id')
                    ->required()
                    ->string(),
            ]);
            $moduleName = $arguments->module_name;
            $beanId = $arguments->id;

            $this->checkPermissions($moduleName, 'delete');

            chdir('../legacy');
            $bean = \BeanFactory::getBean($moduleName, $beanId);
            if (!$bean) {
                chdir('../mcp');
                return $this->createResult([
                    $this->createTextContent("Module '{$moduleName}' does not exist.")
                ]);
            }
            if (empty($bean->id)) {
                chdir('../mcp');
                return $this->createResult([
                    $this->createTextContent("Record with ID {$beanId} not found in module {$moduleName}.")
                ]);
            }

            $bean->mark_deleted($beanId);
            chdir('../mcp');

            return $this->createResult([
                $this->createTextContent("Record deleted successfully")
            ]);
        } catch (\Exception $e) {
            return $this->createResult([
                $this->createTextContent($e instanceof \InvalidArgumentException ? $e->getMessage() : ("Error while deleting record: " . $e->getMessage()))
            ]);
        }
    }
}

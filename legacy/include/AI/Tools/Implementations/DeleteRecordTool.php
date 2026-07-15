<?php

namespace MintHCM\AI\Tools\Implementations;

use MintHCM\AI\Tools\AbstractTool;
use MintHCM\AI\Tools\ToolInterface;
use MintHCM\AI\Tools\ToolSchema;
use MintHCM\AI\Tools\ToolResult;
use MintHCM\AI\Tools\Utils\ToolValidation;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class DeleteRecordTool extends AbstractTool implements ToolInterface
{
    public function getName(): string
    {
        return 'delete_record';
    }

    public function getSchema(): ToolSchema
    {
        return new ToolSchema(
            name: 'delete_record',
            description: 'Delete record in MintHCM modules. Use search tool to retrieve ID of the record you want to delete if you don\'t know it.',
            parameters: [
                'type' => 'object',
                'properties' => [
                    'module_name' => ['type' => 'string', 'description' => 'Name of the module in Mint in which the record is to be deleted.'],
                    'id' => ['type' => 'string', 'description' => 'ID of the record to delete.'],
                ],
                'required' => ['module_name', 'id']
            ],
        );
    }

    public function execute(array $params): ToolResult
    {
        $args = [];
        if (array_key_exists('module_name', $params)) {
            $args['module_name'] = (string) $params['module_name'];
        }
        if (array_key_exists('id', $params)) {
            $args['id'] = (string) $params['id'];
        }
        return $this->deleteRecord(...$args);
    }

    public function deleteRecord(
        string $module_name,
        string $id
    ): ToolResult {
        try {
            ToolValidation::validateMany([
                ToolValidation::make($module_name, 'module_name')->required()->string(),
                ToolValidation::make($id, 'id')->required()->string(),
            ]);

            $this->checkPermissions($module_name, 'delete');
            $bean = \BeanFactory::getBean($module_name, $id);
            if (!$bean) {
                return $this->errorResult("Module '{$module_name}' does not exist.");
            }

            if (empty($bean->id)) {
                return $this->errorResult("Record with ID {$id} not found in module {$module_name}.");
            }

            $bean->mark_deleted($id);

            return $this->successResult(
                'Record deleted successfully.',
                ['module' => $module_name, 'id' => $id]
            );
        } catch (\Throwable $e) {
            return $this->handleExecutionException($e);
        }
    }
}

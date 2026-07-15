<?php

namespace MintHCM\AI\Tools\Implementations;

use MintHCM\AI\Tools\AbstractTool;
use MintHCM\AI\Tools\ToolInterface;
use MintHCM\AI\Tools\ToolSchema;
use MintHCM\AI\Tools\ToolResult;
use MintHCM\AI\Tools\Traits\ModuleQueryTrait;
use MintHCM\AI\Tools\Utils\DateTimeConversion;
use MintHCM\AI\Tools\Utils\ToolValidation;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class UpdateRecordTool extends AbstractTool implements ToolInterface
{
    use ModuleQueryTrait;

    public function getName(): string
    {
        return 'update_record';
    }

    public function getSchema(): ToolSchema
    {
        return new ToolSchema(
            name: 'update_record',
            description: 'Update a record in MintHCM module.',
            parameters: [
                'type' => 'object',
                'properties' => [
                    'module_name' => ['type' => 'string', 'description' => 'Name of the module in Mint in which the record is to be updated.'],
                    'id' => ['type' => 'string', 'description' => 'ID of the record to update.'],
                    'attributes' => [
                        'type' => ['object', 'array'],
                        'anyOf' => [
                            [
                                'type' => 'object',
                                'additionalProperties' => true,
                            ],
                            [
                                // Some clients serialize empty `{}` as `[]`. Accept empty array and treat it as empty attributes.
                                'type' => 'array',
                                'items' => ['type' => 'string'],
                                'maxItems' => 0,
                            ],
                        ],
                        'description' => 'Attributes to update in key-value format. e.g. {"description": "New Description"}. Date fields must use Y-m-d format (e.g. 2025-02-03); datetime fields must use Y-m-d H:i:s format (e.g. 2025-02-03 14:30:00).',
                    ],
                ],
                'required' => ['module_name', 'id', 'attributes']
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
        if (array_key_exists('attributes', $params)) {
            $args['attributes'] = (array) $params['attributes'];
        }
        return $this->updateRecord(...$args);
    }
    public function updateRecord(
        string $module_name,
        string $id,
        array|object $attributes,
    ): ToolResult {
        try {
            $attributes = (array) $attributes;
            ToolValidation::validateMany([
                ToolValidation::make($module_name, 'module_name')->required()->string(),
                ToolValidation::make($id, 'id')->required()->string(),
                ToolValidation::make($attributes, 'attributes')->required()->array(),
            ]);

            $this->checkPermissions($module_name, 'edit');
            [$bean, $_tableName, $field_defs] = $this->loadBeanAndDefs($module_name);

            $record = $bean->retrieve($id);
            if (!$record || empty($record->id)) {
                return $this->errorResult("Record with ID {$id} not found in module {$module_name}.");
            }

            $validators = [];
            foreach ($attributes as $field => $value) {
                $field_name = (string) $field;
                $field_module_validator = ToolValidation::make($value, $field_name)->writableField($field_defs);
                if (!$field_module_validator->isValid()) {
                    $validators[] = $field_module_validator;
                    continue;
                }
                $type = (string) ($field_defs[$field_name]['type'] ?? ($field_defs[$field_name]['dbType'] ?? 'unknown'));
                $validators[] = ToolValidation::make($value, $field_name)->fieldType($type);
            }
            ToolValidation::validateMany($validators);

            $changed = false;
            foreach ($attributes as $field => $value) {
                $field_name = (string) $field;
                if (!array_key_exists($field_name, $field_defs)) {
                    continue;
                }
                $next_value = ($this->isDateField((string) ($field_defs[$field_name]['type'] ?? '')) && is_string($value))
                    ? DateTimeConversion::fromUserTZ($value)
                    : $value;
                if ($record->{$field_name} !== $next_value) {
                    $record->{$field_name} = $next_value;
                    $changed = true;
                }
            }

            if (!$changed) {
                return $this->successResult(
                    'No attributes were changed. Please provide valid attributes to update.',
                    ['url' => $this->getRecordUrl($module_name, $id)]
                );
            }

            $saved_id = $record->save();
            if (empty($saved_id)) {
                throw new \RuntimeException("Failed to update record with ID {$id} in module {$module_name}.");
            }

            return $this->successResult('Record updated successfully.', ['url' => $this->getRecordUrl($module_name, $id)]);
        } catch (\Throwable $e) {
            return $this->handleExecutionException($e);
        }
    }
}

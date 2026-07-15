<?php

namespace MintHCM\AI\Tools\Implementations;

use MintHCM\AI\Tools\AbstractTool;
use MintHCM\AI\Tools\ToolInterface;
use MintHCM\AI\Tools\ToolSchema;
use MintHCM\AI\Tools\ToolResult;
use MintHCM\AI\Tools\Traits\ModuleQueryTrait;
use MintHCM\AI\Tools\Utils\DateTimeConversion;
use MintHCM\AI\Tools\Utils\RequiredFieldsResolver;
use MintHCM\AI\Tools\Utils\ToolValidation;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class CreateRecordTool extends AbstractTool implements ToolInterface
{
    use ModuleQueryTrait;

    public function getName(): string
    {
        return 'create_record';
    }

    public function getSchema(): ToolSchema
    {
        return new ToolSchema(
            name: 'create_record',
            description: 'Create a new record in MintHCM modules, for example new employees, new candidates etc. Don\'t use this tool for meetings. Use add_meeting for meetings.',
            parameters: [
                'type' => 'object',
                'properties' => [
                    'module_name' => ['type' => 'string', 'description' => 'Name of the module in Mint in which the record is to be created.'],
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
                        'description' => 'Attributes of new record in key-value format. Date fields must use Y-m-d format (e.g. 2025-02-03); datetime fields must use Y-m-d H:i:s format (e.g. 2025-02-03 14:30:00).',
                    ],
                ],
                'required' => ['module_name', 'attributes']
            ],
        );
    }

    public function execute(array $params): ToolResult
    {
        $args = [];
        if (array_key_exists('module_name', $params)) {
            $args['module_name'] = (string) $params['module_name'];
        }
        if (array_key_exists('attributes', $params)) {
            $args['attributes'] = $params['attributes'];
        }
        return $this->createRecord(...$args);
    }
    public function createRecord(
        string $module_name,
        object|array $attributes
    ): ToolResult {
        try {
            $attributes = (array) $attributes;
            ToolValidation::validateMany([
                ToolValidation::make($module_name, 'module_name')->required()->string(),
                ToolValidation::make($attributes, 'attributes')->required()->array(),
            ]);

            $this->checkPermissions($module_name, 'edit');
            [$bean, $_tableName, $field_defs] = $this->loadBeanAndDefs($module_name);

            $app_list_strings = return_app_list_strings_language($GLOBALS['current_language'] ?? 'en_us');
            $required_fields = RequiredFieldsResolver::resolve($field_defs, $app_list_strings);

            ToolValidation::validateMany($this->buildRequiredValidators($required_fields, $attributes));

            $attribute_validators = [];
            foreach ($attributes as $field => $value) {
                $field_name = (string) $field;
                $field_module_validator = ToolValidation::make($value, $field_name)->fieldModule($field_defs, $module_name);
                if (!$field_module_validator->isValid()) {
                    $attribute_validators[] = $field_module_validator;
                    continue;
                }

                $def = (array) $field_defs[$field_name];
                $type = (string) ($def['type'] ?? ($def['dbType'] ?? 'unknown'));
                $attribute_validators[] = ToolValidation::make($value, $field_name)->fieldType($type);
                $attribute_validators[] = ToolValidation::make($value, $field_name)->writableField($field_defs);
            }
            ToolValidation::validateMany($attribute_validators);

            foreach ($attributes as $field => $value) {
                $field_name = (string) $field;
                if (!array_key_exists($field_name, $field_defs)) {
                    continue;
                }

                $field_def = (array) $field_defs[$field_name];
                $type = (string) ($field_def['type'] ?? '');
                $final_value = ($this->isDateField($type) && is_string($value))
                    ? DateTimeConversion::fromUserTZ($value)
                    : $value;
                $bean->{$field_name} = $final_value;
            }

            $id = $bean->save();

            if (!$id) {
                return $this->errorResult('Failed to create the record.');
            }

            return $this->successResult(
                'Record created successfully.',
                ['id' => $id, 'url' => $this->getRecordUrl($module_name, (string) $id)]
            );
        } catch (\Throwable $e) {
            return $this->handleExecutionException($e);
        }
    }

    /**
     * Builds required-field validators using metadata from the resolver.
     *
     * For `role: 'id'` entries linked to a known module, the "required" message tells the agent
     * which module to search in. For `role: 'type'` entries with a known allow-list, the value
     * is also constrained to the permitted modules (e.g. parent_type).
     *
     * @param list<array{name: string, role: string, type: string, related_module: ?string, options_key: ?string, allowed_values: ?array<string, string>}> $required_fields
     * @param array<string, mixed> $attributes
     *
     * @return list<ToolValidation>
     */
    private function buildRequiredValidators(array $required_fields, array $attributes): array
    {
        $validators = [];
        foreach ($required_fields as $entry) {
            $name = $entry['name'];
            $value = $attributes[$name] ?? null;

            if ($entry['role'] === 'id' && !empty($entry['related_module'])) {
                $module = $entry['related_module'];
                $validator = ToolValidation::make($value, $name)->customValidation(
                    static fn($v): bool => !($v === null || $v === '' || (\is_array($v) && empty($v))),
                    "Field '{$name}' is required (ID of a record in '{$module}' — use search_records to find it)."
                );
            } else {
                $validator = ToolValidation::make($value, $name)->required();
            }

            if (
                $entry['role'] === 'type'
                && !empty($entry['allowed_values'])
                && $value !== null
                && $value !== ''
            ) {
                $validator->enum(array_keys($entry['allowed_values']));
            }

            $validators[] = $validator;
        }

        return $validators;
    }
}

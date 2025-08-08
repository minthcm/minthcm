<?php

namespace MintMCP\Tools;

use MintMCP\Tools\Middleware\ToolValidationMiddleware;

use Mcp\Types\ToolInputSchema;
use Mcp\Types\CallToolResult;
use MintMCP\Tools\Traits\ModuleQueryTrait;

class CreateRecord extends AbstractMCPTool
{
    use ModuleQueryTrait;

    public function getName(): string
    {
        return 'create_record';
    }

    public function getDescription(): string
    {
        return "Create a new record in MintHCM modules, for example new employees, new candidates etc. Don't use this tool for meetings. Use add_meeting for meetings.";
    }

    public function getInputSchema(): ToolInputSchema
    {
        return ToolInputSchema::fromArray([
            'type' => 'object',
            'properties' => [
                'module_name' => [
                    'type' => 'string',
                    'description' => 'Name of the module in Mint in which the record is to be created.',
                ],
                'attributes' => [
                    'type' => 'object',
                    'description' => 'Attributes of new record in key-value format.',
                ],
            ],
            'required' => ['module_name', 'attributes'],
        ]);
    }

    /**
     * Executes the tool: creates a new record in the specified module.
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
                ToolValidationMiddleware::make($arguments->attributes ?? [], 'attributes')
                    ->required()
                    ->array(),
            ]);
            $moduleName = $arguments->module_name;
            $attributes = (array)($arguments->attributes ?? []);

            // Prevent using this tool for meetings
            if (strtolower($moduleName) === 'meetings') {
                return $this->createResult([
                    $this->createTextContent("Error: Use the add_meeting tool to create meetings.")
                ]);
            }

            $this->checkPermissions($moduleName, 'edit');
            [$bean, $tableName, $fieldDefs] = $this->loadBeanAndDefs($moduleName);

            $requiredValidators = [];
            foreach ($fieldDefs as $field => $def) {
                if (!empty($def['required']) && $field !== 'id') {
                    $requiredValidators[] = ToolValidationMiddleware::make($attributes[$field] ?? null, $field)->required();
                }
            }
            ToolValidationMiddleware::validateMany($requiredValidators);

            $attributeValidators = [];
            foreach ($attributes as $field => $value) {
                $fieldModuleValidator = ToolValidationMiddleware::make($value, $field)->fieldModule($fieldDefs, $moduleName);
                if (!$fieldModuleValidator->isValid()) {
                    $attributeValidators[] = $fieldModuleValidator;
                } else {
                    $def = $fieldDefs[$field];
                    $type = $def['type'] ?? ($def['dbType'] ?? 'unknown');
                    $attributeValidators[] = ToolValidationMiddleware::validateByType($value, $field, $type);
                }
            }
            ToolValidationMiddleware::validateMany($attributeValidators);
           
            foreach ($attributes as $field => $value) {
                if (array_key_exists($field, $fieldDefs)) {
                    $bean->$field = $value;
                }
            }

            $attributeValidators = [];
            foreach ($attributes as $field => $value) {
                if (array_key_exists($field, $fieldDefs)) {
                    $attributeValidators[] = ToolValidationMiddleware::make($value, $field)->filterField($fieldDefs);
                }
            }
            ToolValidationMiddleware::validateMany($attributeValidators);
            foreach ($attributes as $field => $value) {
                if (array_key_exists($field, $fieldDefs)) {
                    $bean->$field = $value;
                }
            }

            chdir('../legacy');
            $id = $bean->save();
            chdir('../mcp');

            if ($id) {
                $recordUrl = $this->getRecordUrl($moduleName, $id);
                $result = [
                    "status" => "Record created successfully",
                    "id" => $id,
                    "url" => $recordUrl,
                ];
                return $this->createResult([
                    $this->createJsonContent($result)
                ]);
            } else {
                return $this->createResult([
                    $this->createTextContent("Error: Failed to create the record.")
                ]);
            }
        } catch (\Exception $e) {
            return $this->createResult([
                $this->createTextContent($e instanceof \InvalidArgumentException ? $e->getMessage() : ("Error while creating record: " . $e->getMessage()))
            ]);
        }
    }
}

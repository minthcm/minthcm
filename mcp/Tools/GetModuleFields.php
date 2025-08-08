<?php

namespace MintMCP\Tools;

use Mcp\Types\ToolInputSchema;
use Mcp\Types\CallToolResult;

class GetModuleFields extends AbstractMCPTool
{
    public function getName(): string
    {
        return 'get_module_fields';
    }

    public function getDescription(): string
    {
        return 'Returns a dictionary of all fields and a list of required fields for a given module. Use get_module_names to get available modules.';
    }

    public function getInputSchema(): ToolInputSchema
    {
        return ToolInputSchema::fromArray([
            'type' => 'object',
            'properties' => [
                'module_name' => [
                    'type' => 'string',
                    'description' => 'Name of the module to get fields for.',
                ],
                'exclude_required_id' => [
                    'type' => 'boolean',
                    'description' => "If true, excludes 'id' from required fields.",
                    'default' => false,
                ],
            ],
            'required' => ['module_name'],
        ]);
    }

    /**
     * Executes the tool: returns all fields and required fields for a module.
     *
     * @param object $arguments Input arguments for the tool
     * @return CallToolResult
     */
    public function execute(object $arguments): CallToolResult
    {
        try {
            $moduleName = ucfirst($arguments->module_name);
            $excludeRequiredId = $arguments->exclude_required_id ?? false;

            $this->checkPermissions($moduleName, 'list');

            chdir('../legacy');
            [$allFields, $requiredFields] = $this->getFieldsData($moduleName, $excludeRequiredId);
            chdir('../mcp');

            // Prepare fields as array
            $fieldsArray = [];
            foreach ($allFields as $field => $info) {
                $fieldData = [
                    'name' => $field,
                    'type' => $info['type'] ?? ($info['dbType'] ?? 'unknown'),
                    'required' => $info['required'] ?? false
                ];
                if (!empty($info['enum_values'])) {
                    $fieldData['enum_values'] = $info['enum_values'];
                }
                $fieldsArray[] = $fieldData;
            }

            $result = [
                'module' => $moduleName,
                'fields' => $fieldsArray,
                'required_fields' => $requiredFields,
                'total_fields' => count($fieldsArray),
                'required_fields_count' => count($requiredFields),
                'message' => count($fieldsArray) > 0
                    ? "Fields and required fields for module '{$moduleName}'."
                    : "No fields found for module '{$moduleName}'."
            ];

            return $this->createResult([
                $this->createJsonContent($result)
            ]);
        } catch (\Exception $e) {
            $msg = strpos($e->getMessage(), 'not found') !== false ? $e->getMessage() : "Error: " . $e->getMessage();
            return $this->createResult([
                $this->createJsonContent([
                    'message' => $msg,
                    'fields' => [],
                    'required_fields' => [],
                    'total_fields' => 0,
                    'required_fields_count' => 0,
                ])
            ]);
        }
    }

    /**
     * Retrieves all fields and required fields for a module.
     *
     * @param string $moduleName
     * @param bool $excludeRequiredId
     * @return array [allFields, requiredFields]
     * @throws \Exception
     */
    protected function getFieldsData(string $moduleName, bool $excludeRequiredId = false): array
    {
        $bean = \BeanFactory::getBean($moduleName);
        if (!$bean || !isset($bean->field_defs)) {
            throw new \Exception("Module '{$moduleName}' not found or has no fields.");
        }

        $allowedVardefFields = [
            'type',
            'dbType',
            'source',
            'relationship',
            'default',
            'len',
            'precision',
            'comments',
            'required',
            'vname',
        ];

        $allFields = [];
        $requiredFields = [];

        $language = $GLOBALS['current_language'] ?? 'en_us';
        $appListStrings = return_app_list_strings_language($language);
        $modStrings = return_module_language($language, $moduleName);

        foreach ($bean->field_defs as $fieldName => $fieldDef) {
            $pruned = $this->pruneFieldDef($fieldDef, $allowedVardefFields);

            $labelKey = $pruned['vname'] ?? '';
            $translatedLabel = $labelKey && isset($modStrings[$labelKey]) ? $modStrings[$labelKey] : $fieldName;
            $pruned['label'] = $translatedLabel;

            // Add enum values if field is an enum
            if (
                !empty($pruned['type']) &&
                preg_match('/enum/i', $pruned['type']) &&
                !empty($fieldDef['options']) &&
                !empty($appListStrings[$fieldDef['options']])
            ) {
                $pruned['enum_values'] = $this->getEnumValues($appListStrings[$fieldDef['options']]);
            }

            $allFields[$fieldName] = $pruned;

            if ($pruned['required'] === true) {
                $requiredFields[] = $fieldName;
            }
        }

        if ($excludeRequiredId && in_array('id', $requiredFields, true)) {
            $requiredFields = array_values(array_diff($requiredFields, ['id']));
        }

        return [$allFields, $requiredFields];
    }

    /**
     * Prunes a field definition to only allowed fields.
     *
     * @param array $fieldDef
     * @param array $allowedFields
     * @return array
     */
    protected function pruneFieldDef(array $fieldDef, array $allowedFields): array
    {
        $pruned = [];
        foreach ($fieldDef as $var => $val) {
            if (in_array($var, $allowedFields, true)) {
                $pruned[$var] = $val;
            }
        }
        if (!isset($pruned['required'])) {
            $pruned['required'] = false;
        }
        if (!isset($pruned['dbType'])) {
            $pruned['dbType'] = $pruned['type'] ?? 'unknown';
        }
        return $pruned;
    }

    /**
     * Returns enum values for a field as an associative array.
     *
     * @param array $enumList
     * @return array
     */
    protected function getEnumValues(array $enumList): array
    {
        $result = [];
        foreach ($enumList as $enumKey => $enumLabel) {
            $result[$enumKey] = $enumLabel;
        }
        return $result;
    }

}

<?php

namespace MintHCM\AI\Tools\Implementations;

use MintHCM\AI\Tools\AbstractTool;
use MintHCM\AI\Tools\ToolInterface;
use MintHCM\AI\Tools\ToolSchema;
use MintHCM\AI\Tools\ToolResult;
use MintHCM\AI\Tools\Utils\RequiredFieldsResolver;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class GetModuleFieldsTool extends AbstractTool implements ToolInterface
{
    public function getName(): string
    {
        return 'get_module_fields';
    }

    public function getSchema(): ToolSchema
    {
        return new ToolSchema(
            name: 'get_module_fields',
            description: 'Returns writable fields, required fields, and linkable relationships for a given module. Use get_module_names to get available modules.',
            parameters: [
                'type' => 'object',
                'properties' => [
                    'module_name' => ['type' => 'string', 'description' => 'Name of the module to get fields for.'],
                ],
                'required' => ['module_name']
            ],
        );
    }

    public function execute(array $params): ToolResult
    {
        $args = [];
        if (array_key_exists('module_name', $params)) {
            $args['module_name'] = (string) $params['module_name'];
        }
        return $this->getModuleFields(...$args);
    }

    public function getModuleFields(
        string $module_name
    ): ToolResult {
        try {
            $this->checkPermissions($module_name, 'list');

            [$all_fields, $required_fields, $linkable_relationships] = $this->getFieldsData($module_name);

            $fields_array = [];
            foreach ($all_fields as $field => $info) {
                $raw_type = (string) ($info['type'] ?? $info['dbType'] ?? '');
                $field_data = [
                    'name' => $field,
                    'type' => self::mapTypeToFormat($raw_type),
                    'required' => $info['required'] ?? false,
                    'label' => $info['label'] ?? $field,
                ];
                if (!empty($info['enum_values'])) {
                    $field_data['enum_values'] = $info['enum_values'];
                }
                $fields_array[] = $field_data;
            }

            return $this->successResult(
                count($fields_array) > 0
                    ? "Fields and required fields for module '{$module_name}'."
                    : "No fields found for module '{$module_name}'.",
                [
                    'module' => $module_name,
                    'fields' => $fields_array,
                    'required_fields' => $required_fields,
                    'linkable_relationships' => $linkable_relationships,
                    'total_fields' => count($fields_array),
                    'required_fields_count' => count($required_fields),
                    'linkable_relationships_count' => count($linkable_relationships),
                ]
            );
        } catch (\Throwable $e) {
            $msg = strpos($e->getMessage(), 'not found') !== false ? $e->getMessage() : 'Error: ' . $e->getMessage();

            return $this->errorResult($msg);
        }
    }

    /**
     * @return array{0: array<string, array<string, mixed>>, 1: list<string>, 2: list<array{relationship_name: string, related_module: ?string, label: string}>}
     */
    private function getFieldsData(string $module_name): array
    {
        $bean = \BeanFactory::getBean($module_name);

        if (!$bean) {
            throw new \RuntimeException("Module '{$module_name}' not found or not accessible. Use get_module_names to get available modules.");
        }

        if (!isset($bean->field_defs)) {
            throw new \RuntimeException("Module '{$module_name}' has no fields.");
        }

        $allowed_vardef_fields = [
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

        $language = $GLOBALS['current_language'] ?? 'en_us';
        $app_list_strings = return_app_list_strings_language($language);
        $mod_strings = return_module_language($language, $module_name);

        $field_defs = (array) $bean->field_defs;
        $resolver_entries = RequiredFieldsResolver::resolve($field_defs, $app_list_strings);
        $bumped_required = [];
        foreach ($resolver_entries as $entry) {
            $bumped_required[$entry['name']] = true;
        }

        $all_fields = [];
        foreach ($bean->field_defs as $field_name => $field_def) {
            $def_arr = (array) $field_def;
            $raw_type = (string) ($def_arr['type'] ?? '');
            $source = (string) ($def_arr['source'] ?? '');

            // Hide non-writable virtual fields — relate display fields (e.g. assigned_user_name),
            // link relationship pointers, polymorphic parent display fields. They all carry
            // source=non-db. Underlying writable columns (assigned_user_id, employee_id, etc.)
            // are typed as 'relate' too but have source != 'non-db' and dbType=id, so they stay.
            if ($source === 'non-db') {
                continue;
            }

            $pruned = $this->pruneFieldDef((array) $field_def, $allowed_vardef_fields);

            $label_key = $pruned['vname'] ?? '';
            $translated_label = $label_key && isset($mod_strings[$label_key]) ? $mod_strings[$label_key] : $field_name;
            $pruned['label'] = $translated_label;

            // Keep the raw vardef type here — the public response maps it to a JSON
            // value-format hint via mapTypeToFormat() in getModuleFields().
            $pruned['type'] = $raw_type;

            if (isset($bumped_required[$field_name])) {
                $pruned['required'] = true;
            }

            if (preg_match('/enum/i', $raw_type) || $raw_type === 'parent_type') {
                $enum_values = $this->resolveEnumValues((array) $field_def, $app_list_strings);
                if ($enum_values !== null) {
                    $pruned['enum_values'] = $enum_values;
                }
            }

            $all_fields[$field_name] = $pruned;
        }

        $linkable_relationships = $this->collectLinkableRelationships($module_name, $bean, (array) $bean->field_defs, $mod_strings);

        $required_fields = [];
        foreach ($all_fields as $field_name => $pruned) {
            if (!empty($pruned['required'])) {
                $required_fields[] = $field_name;
            }
        }

        // 'id' is auto-generated on save() and never accepted as a creation/update
        // attribute — drop it from required to avoid misleading the agent.
        if (in_array('id', $required_fields, true)) {
            $required_fields = array_values(array_diff($required_fields, ['id']));
        }

        return [$all_fields, $required_fields, $linkable_relationships];
    }

    /**
     * @param string                                    $module_name
     * @param object                                    $bean
     * @param array<string, array<string, mixed>|mixed> $field_defs
     * @param array<string, string>                     $mod_strings
     *
     * @return list<array{relationship_name: string, related_module: ?string, label: string}>
     */
    private function collectLinkableRelationships(string $module_name, object $bean, array $field_defs, array $mod_strings): array
    {
        $relationships = [];
        foreach ($field_defs as $field_name => $field_def) {
            $def_arr = (array) $field_def;
            if (($def_arr['type'] ?? '') !== 'link') {
                continue;
            }
            if (($def_arr['source'] ?? '') !== 'non-db') {
                continue;
            }
            $relationship_name = (string) ($def_arr['relationship'] ?? '');
            if ($relationship_name === '') {
                continue;
            }
            $relationship_def = $this->getRelationshipDefinition($relationship_name);
            if (!$this->isCreateRelationCompatible($module_name, $relationship_def, $field_defs)) {
                continue;
            }
            $related_module = $def_arr['module'] ?? $def_arr['bean_name'] ?? null;
            if ((!is_string($related_module) || $related_module === '')
                && method_exists($bean, 'load_relationship')
                && $bean->load_relationship((string) $field_name)
                && isset($bean->{$field_name})
                && method_exists($bean->{$field_name}, 'getRelatedModuleName')
            ) {
                $resolved_module = $bean->{$field_name}->getRelatedModuleName();
                if (is_string($resolved_module) && $resolved_module !== '') {
                    $related_module = $resolved_module;
                }
            }
            if (!is_string($related_module) || $related_module === '') {
                $related_module = $this->resolveRelatedModuleFromRelationshipDefinition($relationship_name, $module_name);
            }
            if (!is_string($related_module) || $related_module === '') {
                continue;
            }

            $label_key = (string) ($def_arr['vname'] ?? '');
            $label = $label_key !== '' && isset($mod_strings[$label_key]) ? $mod_strings[$label_key] : (string) $field_name;
            $relationships[] = [
                'relationship_name' => (string) $field_name,
                'related_module' => $related_module,
                'label' => $label,
            ];
        }

        usort(
            $relationships,
            static fn(array $a, array $b): int => strcmp((string) $a['relationship_name'], (string) $b['relationship_name'])
        );

        return array_values($relationships);
    }

    private function resolveRelatedModuleFromRelationshipDefinition(string $relationship_name, string $module_name): ?string
    {
        $definition = $this->getRelationshipDefinition($relationship_name);
        if (!is_array($definition)) {
            return null;
        }

        $lhs_module = isset($definition['lhs_module']) ? (string) $definition['lhs_module'] : '';
        $rhs_module = isset($definition['rhs_module']) ? (string) $definition['rhs_module'] : '';
        if ($lhs_module === '' || $rhs_module === '') {
            return null;
        }

        if (strcasecmp($module_name, $lhs_module) === 0) {
            return $rhs_module;
        }
        if (strcasecmp($module_name, $rhs_module) === 0) {
            return $lhs_module;
        }

        return null;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function getRelationshipDefinition(string $relationship_name): ?array
    {
        if (class_exists('\SugarRelationshipFactory')) {
            $factory_def = \SugarRelationshipFactory::getInstance()->getRelationshipDef($relationship_name);
            if (is_array($factory_def)) {
                return $factory_def;
            }
        }

        $relationships = $GLOBALS['dictionary']['relationships'] ?? [];
        if (is_array($relationships) && isset($relationships[$relationship_name]) && is_array($relationships[$relationship_name])) {
            return $relationships[$relationship_name];
        }

        foreach ((array) ($GLOBALS['dictionary'] ?? []) as $entry) {
            if (!is_array($entry) || !isset($entry['relationships']) || !is_array($entry['relationships'])) {
                continue;
            }
            if (isset($entry['relationships'][$relationship_name]) && is_array($entry['relationships'][$relationship_name])) {
                return $entry['relationships'][$relationship_name];
            }
        }

        return null;
    }

    /**
     * Keep only relations suitable for create_relationship:
     * - exclude polymorphic parent links (relationship_role_column set),
     * - exclude direct DB FK links on the current module side (handled via create/update record),
     * - require relationship metadata to be present.
     *
     * @param array<string, mixed>|null                 $relationship_def
     * @param array<string, array<string, mixed>|mixed> $field_defs
     */
    private function isCreateRelationCompatible(string $module_name, ?array $relationship_def, array $field_defs): bool
    {
        if (!is_array($relationship_def)) {
            return false;
        }

        $role_column = (string) ($relationship_def['relationship_role_column'] ?? '');
        if ($role_column !== '') {
            return false;
        }

        $relationship_type = (string) ($relationship_def['relationship_type'] ?? '');
        $rhs_module = (string) ($relationship_def['rhs_module'] ?? '');
        $rhs_key = (string) ($relationship_def['rhs_key'] ?? '');
        if (
            $relationship_type === 'one-to-many'
            && $rhs_module !== ''
            && strcasecmp($module_name, $rhs_module) === 0
            && $rhs_key !== ''
        ) {
            $rhs_key_def = (array) ($field_defs[$rhs_key] ?? []);
            $rhs_key_source = (string) ($rhs_key_def['source'] ?? '');
            if ($rhs_key_def !== [] && $rhs_key_source !== 'non-db') {
                return false;
            }
        }

        return true;
    }

    /**
     * Maps a SugarCRM vardef type to a simple JSON value-format hint that an LLM
     * can act on directly when constructing payloads for create_record / update_record.
     * The output replaces `type` in the public response — the LLM does not need to
     * know SugarCRM-specific type names.
     */
    private static function mapTypeToFormat(string $type): string
    {
        return match (strtolower($type)) {
            'int', 'integer', 'tinyint', 'short', 'long' => 'integer',
            'float', 'decimal', 'double', 'currency' => 'float',
            'bool', 'coloredbool' => 'boolean',
            'date' => 'string (YYYY-MM-DD)',
            'datetime', 'datetimecombo' => 'string (YYYY-MM-DD HH:MM:SS)',
            'time' => 'string (HH:MM:SS)',
            'enum', 'dynamicenum', 'radioenum', 'coloredenum', 'parent_type' => 'enum',
            'multienum' => 'string (^key1^,^key2^)',
            'file', 'image' => 'base64 string',
            default => 'string',
        };
    }

    /**
     * @param array<string, mixed> $field_def
     * @param string[] $allowed_fields
     *
     * @return array<string, mixed>
     */
    private function pruneFieldDef(array $field_def, array $allowed_fields): array
    {
        $pruned = [];
        foreach ($field_def as $var => $val) {
            if (in_array($var, $allowed_fields, true)) {
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
     * @param array<string, string> $enum_list
     *
     * @return array<string, string>
     */
    private function getEnumValues(array $enum_list): array
    {
        $result = [];
        foreach ($enum_list as $enum_key => $enum_label) {
            $result[$enum_key] = $enum_label;
        }

        return $result;
    }

    /**
     * Resolves enum options from a vardef. First tries the static `options` key against
     * `app_list_strings`; falls back to invoking a `function` callback for vardefs whose
     * options are populated dynamically (e.g. `getDictionary` reading from the `dictionaries`
     * table, `getCompanies` reading from securitygroups).
     *
     * Callbacks declaring `returns: 'html'` are skipped — those produce widget markup,
     * not key/value option maps.
     *
     * @param array<string, mixed>                 $field_def
     * @param array<string, array<string, string>> $app_list_strings
     *
     * @return array<string, string>|null
     */
    private function resolveEnumValues(array $field_def, array $app_list_strings): ?array
    {
        $options_key = isset($field_def['options']) ? (string) $field_def['options'] : '';
        if ($options_key !== '' && !empty($app_list_strings[$options_key])) {
            return $this->getEnumValues($app_list_strings[$options_key]);
        }

        if (empty($field_def['function'])) {
            return null;
        }

        $function = $field_def['function'];
        if (is_array($function)) {
            $function_name = (string) ($function['name'] ?? '');
            $include = (string) ($function['include'] ?? '');
            $additional_params = $function['additional_params'] ?? '';
            $returns = (string) ($function['returns'] ?? '');
        } else {
            $function_name = (string) $function;
            $include = '';
            $additional_params = '';
            $returns = '';
        }

        if ($function_name === '' || $returns === 'html') {
            return null;
        }

        if ($include !== '' && file_exists($include)) {
            require_once $include;
        }

        if (!function_exists($function_name)) {
            return null;
        }

        // Standard SugarCRM signature for option-providing callbacks:
        // ($focus, $name, $value, $view, $additional_params).
        $values = $function_name(null, '', '', 'MCP', $additional_params);

        if (!is_array($values) || $values === []) {
            return null;
        }

        $normalized = [];
        foreach ($values as $key => $label) {
            $normalized[(string) $key] = (string) $label;
        }

        return $normalized;
    }
}

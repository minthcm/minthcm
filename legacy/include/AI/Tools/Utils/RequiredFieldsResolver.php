<?php

namespace MintHCM\AI\Tools\Utils;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

/**
 * Pure resolver mapping SugarBean `field_defs` to MCP-friendly required-field entries.
 *
 * For required `relate` fields, emits the `id_name` (e.g. `recruitment_id`) instead of the
 * relate field itself — relate fields have `source: non-db` and cannot be persisted, so the
 * agent must supply the related record's ID.
 *
 * For required `parent` fields, emits two entries — `id_name` and `type_name` (e.g. `parent_id`
 * + `parent_type`) — because the relation is polymorphic and the type is needed for persistence.
 *
 * For other required fields, emits the field as-is.
 *
 * When `$app_list_strings` is provided, the entry for a `parent` `type_name` is enriched with
 * `allowed_values` taken from the configured options key, so callers can validate the input
 * against the allow-list of permitted modules.
 *
 * No DB access, no globals — easy to unit-test.
 */
final class RequiredFieldsResolver
{
    /**
     * @param array<string, array<string, mixed>>     $field_defs        Raw `bean->field_defs`.
     * @param array<string, array<string, string>>    $app_list_strings   Optional translations map (e.g. from `return_app_list_strings_language`).
     *
     * @return list<array{
     *     name: string,
     *     role: 'id'|'type'|'plain',
     *     type: string,
     *     related_module: ?string,
     *     options_key: ?string,
     *     allowed_values: ?array<string, string>,
     * }>
     *
     * @throws \RuntimeException When a required relate/parent field is missing required vardef metadata.
     */
    public static function resolve(array $field_defs, array $app_list_strings = []): array
    {
        $entries = [];

        foreach ($field_defs as $field_name => $raw_def) {
            if ($field_name === 'id' || $field_name === 'deleted') {
                continue;
            }

            $def = (array) $raw_def;
            if (empty($def['required'])) {
                continue;
            }

            $type = (string) ($def['type'] ?? '');

            if ($type === 'relate') {
                $entries[] = self::resolveRelate((string) $field_name, $def, $field_defs);
                continue;
            }

            if ($type === 'parent') {
                foreach (self::resolveParent((string) $field_name, $def, $field_defs, $app_list_strings) as $entry) {
                    $entries[] = $entry;
                }
                continue;
            }

            $entries[] = [
                'name'           => (string) $field_name,
                'role'           => 'plain',
                'type'           => $type,
                'related_module' => null,
                'options_key'    => null,
                'allowed_values' => null,
            ];
        }

        return self::dedupeByName($entries);
    }

    /**
     * @param array<string, mixed>                 $def
     * @param array<string, array<string, mixed>>  $field_defs
     *
     * @return array{
     *     name: string,
     *     role: 'id',
     *     type: string,
     *     related_module: ?string,
     *     options_key: null,
     *     allowed_values: null,
     * }
     */
    private static function resolveRelate(string $field_name, array $def, array $field_defs): array
    {
        $id_name = isset($def['id_name']) ? (string) $def['id_name'] : '';
        if ($id_name === '' || !isset($field_defs[$id_name])) {
            throw new \RuntimeException(
                "Module misconfigured: required relate field '{$field_name}' has no resolvable id_name."
            );
        }

        $id_def = (array) $field_defs[$id_name];
        $related_module = isset($def['module']) ? (string) $def['module'] : null;

        return [
            'name'           => $id_name,
            'role'           => 'id',
            'type'           => (string) ($id_def['type'] ?? 'id'),
            'related_module' => $related_module !== '' ? $related_module : null,
            'options_key'    => null,
            'allowed_values' => null,
        ];
    }

    /**
     * @param array<string, mixed>                  $def
     * @param array<string, array<string, mixed>>   $field_defs
     * @param array<string, array<string, string>>  $app_list_strings
     *
     * @return list<array{
     *     name: string,
     *     role: 'id'|'type',
     *     type: string,
     *     related_module: null,
     *     options_key: ?string,
     *     allowed_values: ?array<string, string>,
     * }>
     */
    private static function resolveParent(string $field_name, array $def, array $field_defs, array $app_list_strings): array
    {
        $id_name = isset($def['id_name']) ? (string) $def['id_name'] : '';
        $type_name = isset($def['type_name']) ? (string) $def['type_name'] : '';
        $options_key = isset($def['options']) ? (string) $def['options'] : '';

        if ($id_name === '' || !isset($field_defs[$id_name])) {
            throw new \RuntimeException(
                "Module misconfigured: required parent field '{$field_name}' has no resolvable id_name."
            );
        }
        if ($type_name === '' || !isset($field_defs[$type_name])) {
            throw new \RuntimeException(
                "Module misconfigured: required parent field '{$field_name}' has no resolvable type_name."
            );
        }
        if ($options_key === '') {
            throw new \RuntimeException(
                "Module misconfigured: required parent field '{$field_name}' has no 'options' key."
            );
        }

        $id_def = (array) $field_defs[$id_name];
        $type_def = (array) $field_defs[$type_name];

        $allowed_values = null;
        if (isset($app_list_strings[$options_key]) && is_array($app_list_strings[$options_key]) && $app_list_strings[$options_key] !== []) {
            $allowed_values = $app_list_strings[$options_key];
        }

        return [
            [
                'name'           => $id_name,
                'role'           => 'id',
                'type'           => (string) ($id_def['type'] ?? 'id'),
                'related_module' => null,
                'options_key'    => null,
                'allowed_values' => null,
            ],
            [
                'name'           => $type_name,
                'role'           => 'type',
                'type'           => (string) ($type_def['type'] ?? 'enum'),
                'related_module' => null,
                'options_key'    => $options_key,
                'allowed_values' => $allowed_values,
            ],
        ];
    }

    /**
     * Deduplicates entries by `name` — first occurrence wins.
     *
     * Multiple `relate` fields may share the same `id_name` (e.g. `currency_name` and
     * `currency_symbol` both pointing at `currency_id`). We surface the input only once.
     *
     * @param list<array{name: string, role: string, type: string, related_module: ?string, options_key: ?string, allowed_values: ?array<string, string>}> $entries
     *
     * @return list<array{name: string, role: string, type: string, related_module: ?string, options_key: ?string, allowed_values: ?array<string, string>}>
     */
    private static function dedupeByName(array $entries): array
    {
        $deduped = [];
        $seen = [];
        foreach ($entries as $entry) {
            $name = $entry['name'];
            if (isset($seen[$name])) {
                continue;
            }
            $seen[$name] = true;
            $deduped[] = $entry;
        }

        return $deduped;
    }
}

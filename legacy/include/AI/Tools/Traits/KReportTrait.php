<?php

namespace MintHCM\AI\Tools\Traits;

use MintHCM\AI\Tools\Utils\KReportFilterValidator;

trait KReportTrait
{
    private function getOperatorsForType(string $db_type): array
    {
        return KReportFilterValidator::getAllowedOperators($db_type);
    }

    private function getRestHandler(): ?object
    {
        if (!defined('sugarEntry') || !sugarEntry) {
            return null;
        }

        if (!class_exists('KReporterRESTHandler')) {
            require_once 'modules/KReports/KReportRESTHandler.php';
        }

        if (!class_exists('KReportPresentationManager')) {
            require_once 'modules/KReports/KReportPresentationManager.php';
        }

        return new \KReporterRESTHandler();
    }

    private function resolveFieldDbType(string $path): ?string
    {
        global $bean_files, $bean_list, $db;

        if ($path === '') {
            return null;
        }

        $path_array = explode('::', $path);
        if (count($path_array) < 2) {
            return null;
        }

        $field_array = explode(':', $path_array[count($path_array) - 1]);
        $module_array = explode(':', $path_array[count($path_array) - 2]);

        if (empty($module_array[1]) || empty($field_array[1])) {
            return null;
        }

        if (empty($bean_list[$module_array[1]]) || empty($bean_files[$bean_list[$module_array[1]]])) {
            return null;
        }

        require_once $bean_files[$bean_list[$module_array[1]]];
        $parent_module = new $bean_list[$module_array[1]];

        if ($module_array[0] === 'link') {
            $parent_module->load_relationship($module_array[2]);
            $module_array_el = $module_array[2];
            $related_module = $parent_module->$module_array_el->getRelatedModuleName();
            if (empty($bean_list[$related_module]) || empty($bean_files[$bean_list[$related_module]])) {
                return null;
            }
            require_once $bean_files[$bean_list[$related_module]];
            $module = new $bean_list[$related_module];
        } elseif ($module_array[0] === 'relate') {
            require_once $bean_files[$bean_list[$module_array[1]]];
            $node_module = new $bean_list[$module_array[1]]();
            $this_module_name = $node_module->field_defs[$module_array[2]]['module'] ?? '';
            if (empty($this_module_name) || empty($bean_list[$this_module_name])) {
                return null;
            }
            require_once $bean_files[$bean_list[$this_module_name]];
            $module = new $bean_list[$this_module_name]();
        } else {
            $module = $parent_module;
        }

        $field_name = $field_array[1];
        if (empty($module->field_defs[$field_name])) {
            return null;
        }

        $field_def = $module->field_defs[$field_name];
        $type = $field_def['kreporttype'] ?? $field_def['type'] ?? null;

        return $db ? $db->getFieldType($field_def) : ($field_def['dbType'] ?? $field_def['dbtype'] ?? $type);
    }

    /**
     * @param array<string, array<string, mixed>> $conditions_by_name
     *
     * @return array<string, array{dbType: string, possible_values: array<int, array{value: string, label: string}>|null}>
     */
    private function getFilterMetadata(array $conditions_by_name): array
    {
        $handler = $this->getRestHandler();
        if (!$handler) {
            return [];
        }

        $metadata = [];
        foreach ($conditions_by_name as $filter_name => $condition) {
            $path = $condition['path'] ?? null;
            $grouping = $condition['grouping'] ?? null;

            if ($path === null || $path === '') {
                continue;
            }

            $db_type = $this->resolveFieldDbType($path);
            $db_type = ($db_type !== null && $db_type !== '') ? $db_type : 'unknown';
            $possible_values = null;

            if ($db_type === 'enum' || $db_type === 'bool') {
                try {
                    $options = $handler->getEnumOptions($path, $grouping ?? '', []);
                    if (\is_array($options) && !empty($options)) {
                        $possible_values = array_values(array_filter(
                            array_map(static fn($opt): array => [
                                'value' => $opt['value'] ?? '',
                                'label' => $opt['text'] ?? $opt['value'] ?? '',
                            ], $options),
                            static fn(array $v): bool => $v['value'] !== ''
                        ));
                    }
                } catch (\Throwable $e) {
                    $GLOBALS['log']->fatal('KReportTrait getFilterMetadata: ' . $e->getMessage());
                }
            }

            $metadata[$filter_name] = [
                'dbType' => $db_type,
                'possible_values' => $possible_values,
            ];
        }

        return $metadata;
    }
}

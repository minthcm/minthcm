<?php

namespace MintMCP\Tools\Traits;

use MintMCP\Tools\Utils\KReportFilterValidator;

/**
 * Shared functionality for KReport tools.
 */

trait KReportTrait
{
    /**
     * Get allowed operators for a given filter type.
     *
     * @param string $dbType Filter type (date, datetime, enum, varchar, text, float, bool)
     * @return list<string>
     */
    private function getOperatorsForType(string $dbType): array
    {
        return KReportFilterValidator::getAllowedOperators($dbType);
    }

    /**
     * Load KReport REST handler. Must be called from legacy context.
     *
     * @return \KReporterRESTHandler|null
     */
    private function getRestHandler(): ?object
    {
        if (!defined('sugarEntry') || !sugarEntry) {
            return null;
        }

        if (!class_exists('KReporterRESTHandler')) {
            require_once 'modules/KReports/KReportRESTHandler.php';
        }

        return new \KReporterRESTHandler();
    }

    /**
     * Resolve DB type for a KReport filter field from its path.
     * Must be called from legacy context (uses global $beanFiles, $beanList, $db).
     *
     * @param string $path KReport field path (e.g. root::module:Accounts::field:name)
     * @return string|null dbtype of the field or null if the field is not found
     */
    private function resolveFieldDbType(string $path): ?string
    {
        global $beanFiles, $beanList, $db;

        if ($path === '') {
            return null;
        }

        // Path format: "root::module:Accounts::field:name" or with link/relate segments
        $pathArray = explode('::', $path);
        if (count($pathArray) < 2) {
            return null;
        }

        // Last segment = field (e.g. "field:name"), second-to-last = module (e.g. "module:Accounts")
        $fieldArray = explode(':', $pathArray[count($pathArray) - 1]);
        $moduleArray = explode(':', $pathArray[count($pathArray) - 2]);

        if (empty($moduleArray[1]) || empty($fieldArray[1])) {
            return null;
        }

        if (empty($beanList[$moduleArray[1]]) || empty($beanFiles[$beanList[$moduleArray[1]]])) {
            return null;
        }

        require_once $beanFiles[$beanList[$moduleArray[1]]];
        $parentModule = new $beanList[$moduleArray[1]];

        // Resolve the target module depending on segment type (link, relate, or direct)
        if ($moduleArray[0] === 'link') {
            // Linked relationship — traverse to the related module
            $parentModule->load_relationship($moduleArray[2]);
            $moduleArrayEl = $moduleArray[2];
            $relatedModule = $parentModule->$moduleArrayEl->getRelatedModuleName();
            if (empty($beanList[$relatedModule]) || empty($beanFiles[$beanList[$relatedModule]])) {
                return null;
            }
            require_once $beanFiles[$beanList[$relatedModule]];
            $module = new $beanList[$relatedModule];
        } elseif ($moduleArray[0] === 'relate') {
            // Relate field — look up the target module from field_defs
            require_once $beanFiles[$beanList[$moduleArray[1]]];
            $nodeModule = new $beanList[$moduleArray[1]]();
            $thisModuleName = $nodeModule->field_defs[$moduleArray[2]]['module'] ?? '';
            if (empty($thisModuleName) || empty($beanList[$thisModuleName])) {
                return null;
            }
            require_once $beanFiles[$beanList[$thisModuleName]];
            $module = new $beanList[$thisModuleName]();
        } else {
            // Direct field on the parent module
            $module = $parentModule;
        }

        $fieldName = $fieldArray[1];
        if (empty($module->field_defs[$fieldName])) {
            return null;
        }

        // Prefer DB-level type resolution; fall back to vardefs metadata
        $fieldDef = $module->field_defs[$fieldName];
        $type = $fieldDef['kreporttype'] ?? $fieldDef['type'] ?? null;
        return $db ? $db->getFieldType($fieldDef) : ($fieldDef['dbType'] ?? $fieldDef['dbtype'] ?? $type);
    }

    /**
     * Build filter metadata for a report (type and possible values per filter).
     * Must be called from legacy context.
     *
     * @param array $conditionsByName
     * @return array<string, array{dbType: string, possible_values: array<int, array{value: string, label: string}>|null}>
     */
    private function getFilterMetadata(array $conditionsByName): array
    {
        $handler = $this->getRestHandler();
        if (!$handler) {
            return [];
        }

        $metadata = [];
        foreach ($conditionsByName as $filterName => $condition) {
            $path = $condition['path'] ?? null;
            $grouping = $condition['grouping'] ?? null;

            if ($path === null || $path === '') {
                continue;
            }

            $dbType = $this->resolveFieldDbType($path);
            $dbType = ($dbType !== null && $dbType !== '') ? $dbType : 'unknown';

            $possibleValues = null;

            // For enum/bool fields, fetch dropdown options and normalize to value/label pairs
            if ($dbType === 'enum' || $dbType === 'bool') {
                try {
                    $options = $handler->getEnumOptions($path, $grouping ?? '', []);

                    if (\is_array($options) && !empty($options)) {
                        $possibleValues = array_values(array_filter(
                            array_map(static fn($opt) => [
                                'value' => $opt['value'] ?? '',
                                'label' => $opt['text'] ?? $opt['value'] ?? '',
                            ], $options),
                            static fn($v) => $v['value'] !== ''
                        ));
                    }
                } catch (\Throwable $e) {
                    $this->logger->debug('getEnumOptions failed for filter: {filter}', [
                        'filter' => $filterName,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            $metadata[$filterName] = [
                'dbType' => $dbType,
                'possible_values' => $possibleValues,
            ];
        }

        return $metadata;
    }
}

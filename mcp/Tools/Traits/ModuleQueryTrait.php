<?php

namespace MintMCP\Tools\Traits;

trait ModuleQueryTrait
{

    const NOT_ALLOWED_RELATE_TYPES = [
        'link',
        'relate',
    ];

    /**
     * Loads the bean, table name, and field definitions for a given module.
     *
     * @param string $moduleName
     * @return array [$bean, $tableName, $fieldDefs]
     */
    protected function loadBeanAndDefs(string $moduleName)
    {
        chdir('../legacy');
        $bean = \BeanFactory::getBean($moduleName);
        $tableName = $bean->table_name ?? strtolower($moduleName);
        $fieldDefs = isset($bean->field_defs) ? $bean->field_defs : [];
        chdir('../mcp');

        if (empty($bean) || empty($tableName) || empty($fieldDefs)) {
            throw new \Exception("Module '{$moduleName}' not found or not accessible.");
        }
        
        return [$bean, $tableName, $fieldDefs];
    }

    protected function buildWhereClause(string $filtersJson, array $fieldDefs, string $tableName, string $operator): string {
        $availableFields = array_keys($fieldDefs);
        $where = [];

        if ($filtersJson) {
            $filtersArr = json_decode($filtersJson, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \InvalidArgumentException("Invalid JSON in filters: " . json_last_error_msg());
            }
            $queryFilters = $filtersArr['filters'] ?? [];
            foreach ($queryFilters as $field => $filter) {
                if (!in_array($field, $availableFields)) {
                    throw new \InvalidArgumentException(
                        "Field {$field} is not available in the module. " .
                            "Use get_module_fields to get list of fields available in the module."
                    );
                }
                $op = strtoupper($filter['operator'] ?? '=');
                $value = $filter['value'] ?? '';

                $this->validateFieldValue($field, $value, $fieldDefs);

                $where[] = $this->buildWhereCondition($tableName, $field, $op, $value);
            }
        }

        // Always exclude deleted records
        $where[] = "$tableName.deleted = 0";

        $glue = (strtoupper($operator) === 'OR') ? ' OR ' : ' AND ';
        return implode($glue, $where);
    }

    /**
     * Builds a single SQL condition for a field, operator, and value.
     *
     * @param string $tableName
     * @param string $field
     * @param string $op
     * @param mixed $value
     * @return string
     * @throws \InvalidArgumentException
     */
    protected function buildWhereCondition(string $tableName, string $field, string $op, $value): string
    {
        // Handle IN and NOT IN operators
        if (in_array($op, ['IN', 'NOT IN'])) {
            $valuesArr = is_array($value) ? array_map('trim', $value) : array_map('trim', explode(',', $value));
            $valueStr = "'" . implode("','", $valuesArr) . "'";
            return "$tableName.$field $op ($valueStr)";
        }
        // Handle BETWEEN operator
        if ($op === 'BETWEEN') {
            $vals = is_array($value) ? array_map('trim', $value) : array_map('trim', explode(',', $value));
            if (count($vals) !== 2) {
                throw new \InvalidArgumentException("BETWEEN operator requires two values.");
            }
            return "$tableName.$field BETWEEN '{$vals[0]}' AND '{$vals[1]}'";
        }

        // Default: simple comparison
        return "$tableName.$field $op '{$value}'";
    }
}

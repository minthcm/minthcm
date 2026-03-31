<?php

namespace MintMCP\Tools;

use MintMCP\Tools\Utils\ToolValidation;
use MintMCP\Tools\Traits\ModuleQueryTrait;
use MintMCP\Tools\Traits\PaginationTrait;
use MintMCP\Tools\Utils\DateTimeConversion;
use Mcp\Types\ToolInputSchema;
use Mcp\Types\CallToolResult;

class SearchRecords extends AbstractMCPTool
{
    use ModuleQueryTrait;
    use PaginationTrait;

    public function getName(): string
    {
        return 'search_records';
    }

    public function getDescription(): string
    {
        return 'Retrieve a list of records from a MintHCM module matching given filters. You should use get_module_names to get available modules and get_module_fields to get fields available in the module. Supports pagination via offset and limit parameters.';
    }

    public function getInputSchema(): ToolInputSchema
    {
        $properties = [
            'module_name' => [
                'type' => 'string',
                'description' => 'Name of the module in Mint in which the information is to be read.',
            ],
            'filters' => [
                'type' => 'string',
                'description' => 'Dict with filters to apply to the query. Optional dictionary to filter query results. Each filter has an operator and value.
                    Structure: {"field_name": {"operator": "OPERATOR", "value": "VALUE"}}

                    Available operators:
                    - Equality: =, <>
                    - Comparison: >, <, >=, <=
                    - Text matching: LIKE, NOT LIKE
                    - Multiple values: IN, NOT IN (use comma-separated string: "1,2,3")
                    - Range: BETWEEN (use comma-separated string: "start,end")

                    Date/datetime filtering:
                    - For specific date searches, always use BETWEEN with the date and next day
                    - Example for records on 2022-01-01: {"date_start": {"operator": "BETWEEN", "value": "2022-01-01,2022-01-02"}}

                    Examples:
                    {"assigned_user_id": {"operator": "=", "value": "1"}}
                    {"status": {"operator": "IN", "value": "active,pending"}}
                    {"created_date": {"operator": "BETWEEN", "value": "2022-01-01,2022-01-31"}}

Important: Use get_module_fields to get available fields in the module. You cannot use fields of type "link" or "relate" in filters, instead use the ID of the related record. You also cannot use fields of source "non-db" in filters.',
            ],
            'operator' => [
                'type' => 'string',
                'enum' => ['and', 'or'],
                'description' => "Operator to use to join all filters. Possible values: 'and','or'. Defaults to 'and'.",
                'default' => 'and',
            ],
            'fields' => [
                'type' => 'array',
                'items' => ['type' => 'string'],
                'description' => "List of fields to retrieve from the module. Example: ['id','name','date_start','status']."
            ],
        ];

        $properties = array_merge($properties, $this->getPaginationSchemaProperties());

        return ToolInputSchema::fromArray([
            'type' => 'object',
            'properties' => $properties,
            'required' => ['module_name', 'fields'],
        ]);
    }

    /**
     * Executes the tool: retrieves a list of records from a module based on input filters.
     * @param object $arguments Input arguments for the tool
     * @return CallToolResult
     */
    public function execute(object $arguments): CallToolResult
    {
        try {
            ToolValidation::validateMany([
                ToolValidation::make($arguments->module_name, 'module_name')->required()->string(),
                ToolValidation::make($arguments->fields ?? [], 'fields')->required()->array(),
            ]);

            $this->checkPermissions($arguments->module_name);

            $operator = $arguments->operator ?? 'and';
            
            [$offset, $limit] = $this->processPaginationParams($arguments);

            [$bean, $tableName, $fieldDefs] = $this->loadBeanAndDefs($arguments->module_name);

            $fields = $arguments->fields ?? [];

            $this->validateFieldsExist($fields, $fieldDefs, $arguments->module_name);

            $filters = $arguments->filters ?? '';
            $whereClause = $this->buildWhereClause($filters, $fieldDefs, $tableName, $operator);

            chdir('../legacy');

            $result = $bean->get_list('', $whereClause, $offset, $limit, $this->getMaxPaginationLimit($limit));

            chdir('../mcp');

            $list = $result['list'] ?? [];
            $returnData = $this->formatRecords($list, $fields, $fieldDefs);

            if (empty($returnData)) {
                $resultData = [
                    'status' => 'success',
                    'message' => "No records found in module {$arguments->module_name} with given filters",
                    'total_count' => 0,
                    'current_offset' => $result['current_offset'] ?? $offset,
                    'next_offset' => -1,
                    'records_returned' => 0,
                    'data' => []
                ];
            } else {
                $resultData = $this->formatPaginationData($result, $offset, [
                    'status' => 'success',
                    'data' => $returnData,
                ], $limit);
            }

            return $this->createResult([
                $this->createJsonContent($resultData),
            ]);
        } catch (\Exception $e) {
            return $this->createResult([
                $this->createTextContent(
                    $e instanceof \InvalidArgumentException ? $e->getMessage() : "Error while searching records: " . $e->getMessage()
                ),
            ]);
        }
    }

    /**
     * Formats the list of beans into an array of records with specified fields.
     *
     * @param array $list
     * @param array $fields
     * @param array $fieldDefs
     * @return array
     */
    protected function formatRecords(array $list, array $fields, array $fieldDefs): array
    {
        $returnData = [];
        if ($list) {
            foreach ($list as $row) {
                $record = ['id' => $row->id];
                foreach ($fields as $field) {
                    if (!isset($fieldDefs[$field])) {
                        continue;
                    }
                    $value = isset($fieldDefs[$field]['type']) && $this->isDateField($fieldDefs[$field]['type']) ? DateTimeConversion::formatDate($row->$field) : ($row->$field ?? null);
                    if ($field !== 'id') {
                        $record[$field] = $value;
                    }
                }
                $returnData[] = $record;
            }
        }

        return $returnData;
    }

    /**
     * Validates that the specified fields exist in the module's field definitions.
     *
     * @param array $fields
     * @param array $fieldDefs
     * @param string $moduleName
     * @return void
     * @throws \InvalidArgumentException if any field does not exist
     */
    protected function validateFieldsExist(array $fields, array $fieldDefs, string $moduleName): void
    {
        $validators = [];
        foreach ($fields as $field) {
            $validators[] = ToolValidation::make(null, $field)->fieldModule($fieldDefs, $moduleName);
        }
        ToolValidation::validateMany($validators);
    }
}
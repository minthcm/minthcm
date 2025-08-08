<?php

namespace MintMCP\Tools;

use MintMCP\Tools\Middleware\ToolValidationMiddleware;

use MintMCP\Tools\Traits\ModuleQueryTrait;
use Mcp\Types\ToolInputSchema;
use Mcp\Types\CallToolResult;

class SearchRecords extends AbstractMCPTool
{
    use ModuleQueryTrait;

    public function getName(): string
    {
        return 'search_records';
    }

    public function getDescription(): string
    {
        return 'Retrieve a list of records from a MintHCM module matching given filters. You should use get_module_names to get available modules and get_module_fields to get fields available in the module.';
    }

    public function getInputSchema(): ToolInputSchema
    {
        return ToolInputSchema::fromArray([
            'type' => 'object',
            'properties' => [
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

Important: Use get_module_fields to get available fields in the module. You cannot use fields of type "link" or "relate" in filters, instead use the ID of the related record.',
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
            ],
            'required' => ['module_name', 'fields'],
        ]);
    }

    /**
     * Executes the tool: retrieves records from a module based on filters and selected fields.
     *
     * @param object $arguments Input arguments for the tool
     * @return CallToolResult
     */
    public function execute(object $arguments): CallToolResult
    {
        try {
            // Validate module_name, fields, and operator using ToolValidationMiddleware
            ToolValidationMiddleware::validateMany([
                ToolValidationMiddleware::make($arguments->module_name, 'module_name')
                    ->required()
                    ->string(),
                ToolValidationMiddleware::make($arguments->fields ?? [], 'fields')
                    ->required()
                    ->array(),
                ToolValidationMiddleware::make($arguments->operator ?? 'and', 'operator')
                    ->enum(['and', 'or'])
            ]);
            $this->checkPermissions($arguments->module_name);
            $operator = $arguments->operator ?? 'and';

            [$bean, $tableName, $fieldDefs] = $this->loadBeanAndDefs($arguments->module_name);


            $fields = $arguments->fields ?? [];
            $fieldValidators = [];
            foreach ($fields as $field) {
                $fieldValidators[] = ToolValidationMiddleware::make(null, $field)->fieldModule($fieldDefs, $arguments->module_name);
            }
            ToolValidationMiddleware::validateMany($fieldValidators);

            // Validate filters structure and operators
            $filters = $arguments->filters ?? '';
            $filtersArr = json_decode($filters, true);
            $filterValidators = [];
            if (!empty($filtersArr) && is_array($filtersArr)) {
                foreach ($filtersArr as $field => $filter) {
                    $filterValidators[] = ToolValidationMiddleware::make(null, $field)->fieldModule($fieldDefs, $arguments->module_name);
                    $filterValidators[] = ToolValidationMiddleware::make($filter['operator'] ?? null, 'operator')->required()->enum(['=', '<>', '>', '<', '>=', '<=', 'LIKE', 'NOT LIKE', 'IN', 'NOT IN', 'BETWEEN']);
                }
                ToolValidationMiddleware::validateMany($filterValidators);
            }

            $whereClause = $this->buildWhereClause(
                $filters,
                $fieldDefs,
                $tableName,
                $operator
            );

            chdir('../legacy');
            $list = $bean->get_full_list('', $whereClause);
            chdir('../mcp');

            $returnData = $this->formatRecords($list, $fields);

            $result = empty($returnData)
                ? ["status" => "success", "message" => "No records found in module {$arguments->module_name} with given filters"]
                : ["status" => "success", "data" => $returnData];

            return $this->createResult([
                $this->createJsonContent($result)
            ]);
        } catch (\Exception $e) {
            return $this->createResult([
                $this->createTextContent($e instanceof \InvalidArgumentException ? $e->getMessage() : ("Error while searching records: " . $e->getMessage()))
            ]);
        }
    }

    /**
     * Formats the list of beans into an array of associative arrays with selected fields.
     *
     * @param array|null $list List of beans/records
     * @param array $fields Fields to include in the result
     * @return array
     */
    protected function formatRecords($list, array $fields): array
    {
        $returnData = [];
        if ($list) {
            foreach ($list as $row) {
                $record = ['id' => $row->id];
                foreach ($fields as $field) {
                    if ($field !== 'id') {
                        $record[$field] = $row->$field ?? null;
                    }
                }
                $returnData[] = $record;
            }
        }
        return $returnData;
    }
}

<?php

namespace MintMCP\Tools;


use MintMCP\Tools\Traits\ModuleQueryTrait;
use Mcp\Types\CallToolResult;
use Mcp\Types\ToolInputSchema;
use MintMCP\Tools\Middleware\ToolValidationMiddleware;

class SumRecords extends AbstractMCPTool
{
    use ModuleQueryTrait;

    public function getName(): string
    {
        return 'sum_records';
    }

    public function getDescription(): string
    {
        return 'Sum the values of a specific numeric field for records in a MintHCM module matching given filters.';
    }

    public function getInputSchema(): ToolInputSchema
    {
        return ToolInputSchema::fromArray([
            'type' => 'object',
            'properties' => [
                'module_name' => [
                    'type' => 'string',
                    'description' => 'Name of the module in Mint in which the information is to be summed.',
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
                'sum_field' => [
                    'type' => 'string',
                    'description' => 'The name of the numeric field in the module to sum.',
                ],
            ],
            'required' => ['module_name', 'sum_field'],
        ]);
    }

    /**
     * Executes the sum operation.
     *
     * @param object $arguments
     * @return CallToolResult
     */
    public function execute(object $arguments): CallToolResult
    {
        try {
            ToolValidationMiddleware::validateMany([
                ToolValidationMiddleware::make($arguments->module_name, 'module_name')->required()->string(),
                ToolValidationMiddleware::make($arguments->sum_field, 'sum_field')->required()->string()
            ]);

            $this->checkPermissions($arguments->module_name);

            [$bean, $tableName, $fieldDefs] = $this->loadBeanAndDefs($arguments->module_name);

            $sumField = $arguments->sum_field;
            ToolValidationMiddleware::validateOne(
                ToolValidationMiddleware::make(null, $sumField)->fieldModule($fieldDefs, $arguments->module_name)
            );

            // Acceptable numeric types
            $numericTypes = ['int', 'integer', 'float', 'double', 'decimal', 'currency'];
            $dbType = strtolower($fieldDefs[$sumField]['dbType'] ?? $fieldDefs[$sumField]['type'] ?? '');
            $validator = ToolValidationMiddleware::make($dbType, $sumField);
            if (!in_array($dbType, $numericTypes, true)) {
                $validator->enum($numericTypes);
            }
            if (!$validator->isValid()) {
                return $this->createResult([
                    $this->createTextContent("Field '{$sumField}' is not a valid numeric field.")
                ]);
            }

            // Validate filters structure and operators
            $filters = $arguments->filters ?? '';
            if (!empty($filters) && is_array($filters)) {
                foreach ($filters as $field => $filter) {
                    ToolValidationMiddleware::validateMany([
                        ToolValidationMiddleware::make(null, $field)->fieldModule($fieldDefs, $arguments->module_name),
                        ToolValidationMiddleware::make($filter['operator'] ?? null, 'operator')->required()->enum(['=', '<>', '>', '<', '>=', '<=', 'LIKE', 'NOT LIKE', 'IN', 'NOT IN', 'BETWEEN']),
                    ]);
                }
            }

            $whereClause = $this->buildWhereClause(
                $filters,
                $fieldDefs,
                $tableName,
                $arguments->operator ?? 'and'
            );

            chdir('../legacy');
            $baseQuery = $bean->create_new_list_query('', $whereClause);
            $pattern = '/SELECT.*?FROM/is';
            $replacement = "SELECT SUM($tableName.$sumField) as total_sum FROM ";
            $sumQuery = preg_replace($pattern, $replacement, $baseQuery, 1);
            $db = $bean->db;
            $result = $db->query($sumQuery);
            $row = $db->fetchByAssoc($result);
            $totalSum = $row['total_sum'] ?? 0;
            chdir('../mcp');

            $result = [
                "Sum" => (string)$totalSum,
            ];

            return $this->createResult([
                $this->createJsonContent($result)
            ]);
        } catch (\Exception $e) {
            return $this->createResult([
                $this->createTextContent($e instanceof \InvalidArgumentException ? $e->getMessage() : ("Error while summing records: " . $e->getMessage()))
            ]);
        }
    }

    /**
     * Sums the values of a given field in a list of beans.
     *
     * @param array|null $list
     * @param string $sumField
     * @return array [totalSum, skippedRecords]
     */
    protected function sumFieldValues($list, string $sumField): array
    {
        $totalSum = 0;
        $skippedRecords = 0;
        if ($list) {
            foreach ($list as $row) {
                $value = $row->$sumField ?? null;
                if ($value !== null && $value !== '' && is_numeric($value)) {
                    $totalSum += $value;
                } else {
                    $skippedRecords++;
                }
            }
        }
        return [$totalSum, $skippedRecords];
    }
}

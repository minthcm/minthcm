<?php

namespace MintMCP\Tools;

use MintMCP\Tools\Middleware\ToolValidationMiddleware;

use DBManagerFactory;
use Mcp\Types\CallToolResult;
use Mcp\Types\ToolInputSchema;
use MintMCP\Tools\Traits\ModuleQueryTrait;

class CountRecords extends AbstractMCPTool
{
    use ModuleQueryTrait;

    public function getName(): string
    {
        return 'count_records';
    }

    public function getDescription(): string
    {
        return 'Count the number of records in a MintHCM module. Always use get_module_fields first if you are unsure about the available fields for filtering.';
    }

    public function getInputSchema(): ToolInputSchema
    {
        return ToolInputSchema::fromArray([
            'type' => 'object',
            'properties' => [
                'module_name' => [
                    'type' => 'string',
                    'description' => 'Name of the module in Mint in which the information is to be counted.',
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
            ],
            'required' => ['module_name'],
        ]);
    }

    /**
     * Executes the count operation.
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
                ToolValidationMiddleware::make($arguments->operator ?? 'and', 'operator')
                    ->enum(['and', 'or'])
            ]);
            $this->checkPermissions($arguments->module_name);
            $operator = $arguments->operator ?? 'and';

            [$bean, $tableName, $fieldDefs] = $this->loadBeanAndDefs($arguments->module_name);

            $filters = $arguments->filters ?? '';

            // Validate filters structure and operators
            if (!empty($filters) && is_array($filters)) {
                foreach ($filters as $field => $filter) {
                    ToolValidationMiddleware::validateMany([
                        ToolValidationMiddleware::make(null, $field)->fieldModule($fieldDefs, $arguments->module_name),
                        ToolValidationMiddleware::make($filter['operator'] ?? null, 'operator')->required()->enum(['=', '<>', '>', '<', '>=', '<=', 'LIKE', 'NOT LIKE', 'IN', 'NOT IN', 'BETWEEN']),
                    ]);
                }
            }

            $where = $this->buildWhereClause($filters, $fieldDefs, $tableName, $operator);

            chdir('../legacy');
            $query = $bean->create_new_list_query('', $where);
            $countQuery = $bean->create_list_count_query($query);
            $db = DBManagerFactory::getInstance();
            $result = $db->query($countQuery);
            $row = $db->fetchByAssoc($result);
            chdir('../mcp');

            $count = isset($row['c']) ? (int)$row['c'] : 0;
            $msg = "Count for module '{$arguments->module_name}': $count";
            return $this->createResult([
                $this->createTextContent($msg)
            ]);
        } catch (\Exception $e) {
            return $this->createResult([
                $this->createTextContent($e instanceof \InvalidArgumentException ? $e->getMessage() : ("Error while counting records: " . $e->getMessage()))
            ]);
        }
    }
}

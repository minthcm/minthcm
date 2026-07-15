<?php

namespace MintHCM\AI\Tools\Implementations;

use MintHCM\AI\Tools\AbstractTool;
use MintHCM\AI\Tools\ToolInterface;
use MintHCM\AI\Tools\ToolSchema;
use MintHCM\AI\Tools\ToolResult;
use MintHCM\AI\Tools\Traits\KReportTrait;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class GetKReportDetailsTool extends AbstractTool implements ToolInterface
{
    use KReportTrait;

    public function getName(): string
    {
        return 'get_report_details';
    }

    public function getSchema(): ToolSchema
    {
        return new ToolSchema(
            name: 'get_report_details',
            description: 'Get details of a specific report available in MintHCM, including for each filter: possible operators (e.g. Equals, Between), value inputs (single value vs From/To), and when applicable the list of possible values for the filter field.',
            parameters: [
                'type' => 'object',
                'properties' => [
                    'report_id' => ['type' => 'string', 'description' => 'ID of the report to get details for.'],
                ],
                'required' => ['report_id']
            ],
        );
    }

    public function execute(array $params): ToolResult
    {
        $args = [];
        if (array_key_exists('report_id', $params)) {
            $args['report_id'] = (string) $params['report_id'];
        }
        return $this->getReportDetails(...$args);
    }

    public function getReportDetails(
        string $report_id,
    ): ToolResult {
        try {
            $this->checkPermissions('KReports');

            if ($report_id === '') {
                throw new \RuntimeException('report_id is required');
            }
            $bean = \BeanFactory::getBean('KReports', $report_id);
            if (!$bean || !$bean->id) {
                throw new \RuntimeException("Report with id '{$report_id}' not found");
            }

            $report = $bean->toArray();

            return $this->successResult(
                'Report details retrieved successfully.',
                [
                    'id' => $report['id'],
                    'name' => $report['name'],
                    'description' => $report['description'],
                    'output_fields' => $this->extractOutputFields($report),
                    'filters' => $this->extractFilters($report),
                ]
            );
        } catch (\Throwable $e) {
            return $this->handleExecutionException($e);
        }
    }

    /**
     * @param array<string, mixed> $report
     *
     * @return array<int, array<string, string>>
     */
    private function extractOutputFields(array $report): array
    {
        $list_fields = json_decode(html_entity_decode((string) ($report['listfields'] ?? '[]')), true);
        if (!\is_array($list_fields)) {
            return [];
        }

        $output_fields = [];
        foreach ($list_fields as $field) {
            if (($field['display'] ?? '') !== 'yes' || !isset($field['fieldname'])) {
                continue;
            }
            $output_fields[] = [
                'field_name' => (string) $field['fieldname'],
                'field_description' => !empty($field['presentdescription']) ? trim((string) $field['presentdescription']) : '',
            ];
        }

        return $output_fields;
    }

    /**
     * @param array<string, mixed> $report
     *
     * @return array<int, array<string, mixed>>
     */
    private function extractFilters(array $report): array
    {
        $where_conditions = json_decode(html_entity_decode((string) ($report['whereconditions'] ?? '[]')), true);
        if (!\is_array($where_conditions)) {
            return [];
        }

        $conditions_by_name = [];
        foreach ($where_conditions as $condition) {
            if (isset($condition['name'])) {
                $conditions_by_name[$condition['name']] = $condition;
            }
        }

        $filter_metadata = $this->getFilterMetadata($conditions_by_name);
        $filters = [];

        foreach ($where_conditions as $condition) {
            $filter_name = $condition['name'] ?? null;
            if ($filter_name === null) {
                continue;
            }
            $editable = $condition['usereditable'] ?? 'no';
            if ($editable === 'no') {
                continue;
            }

            $meta = $filter_metadata[$filter_name] ?? null;
            $db_type = $meta['dbType'] ?? 'unknown';
            $filter_info = [
                'filter_name' => $filter_name,
                'filter_type' => $db_type,
                'filter_options' => [],
            ];

            if ($editable === 'yes') {
                $filter_info['filter_options']['operators'] = $this->getOperatorsForType($db_type);
            } elseif ($editable === 'yfo') {
                $filter_info['filter_options']['operators'] = [$condition['operator'] ?? ''];
            } else {
                continue;
            }

            if ($meta !== null && !empty($meta['possible_values'])) {
                $filter_info['filter_options']['possible_values'] = $meta['possible_values'];
                $filter_info['filter_type'] = 'enum';
            }

            $filters[] = $filter_info;
        }

        return $filters;
    }
}

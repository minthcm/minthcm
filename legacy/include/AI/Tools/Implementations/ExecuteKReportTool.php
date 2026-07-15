<?php

namespace MintHCM\AI\Tools\Implementations;

use MintHCM\AI\Tools\AbstractTool;
use MintHCM\AI\Tools\ToolInterface;
use MintHCM\AI\Tools\ToolSchema;
use MintHCM\AI\Tools\ToolResult;
use MintHCM\AI\Tools\Traits\KReportTrait;
use MintHCM\AI\Tools\Traits\PaginationTrait;
use MintHCM\AI\Tools\Utils\KReportFilterValidator;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class ExecuteKReportTool extends AbstractTool implements ToolInterface
{
    use KReportTrait;
    use PaginationTrait;

    public function getName(): string
    {
        return 'execute_report';
    }

    public function getSchema(): ToolSchema
    {
        return new ToolSchema(
            name: 'execute_report',
            description: 'Execute a report and return its output: the list of output fields and the record rows with their values. Optionally apply filters (each filter: operator + value or values for oneof, or value and value_to for between). For date filters use value in Y-m-d format (e.g. 2025-02-03). For datetime filters use Y-m-d H:i:s (e.g. 2025-02-03 14:30:00); times are interpreted in the current user timezone. Use get_report_details to discover report id, output fields, and filter names/operators.',
            parameters: [
                'type' => 'object',
                'properties' => [
                    'report_id' => ['type' => 'string', 'description' => 'ID of the report to execute.'],
                    'filters' => [
                        'type' => 'array',
                        'description' => 'Optional list of filter conditions to apply. Each item: filter_name or field_id, operator, and value (or values for oneof, or value + value_to for between).',
                        'items' => [
                            'type' => 'object',
                            'properties' => [
                                'filter_name' => ['type' => 'string', 'description' => 'Filter name as returned by get_report_details (e.g. "Date", "Status").'],
                                'operator' => ['type' => 'string', 'description' => 'Operator: e.g. equals, notequal, contains, between, oneof, before, after.'],
                                'value' => ['type' => 'string', 'description' => 'Single value for the filter. For date filters use Y-m-d (e.g. 2025-02-03). For datetime filters use Y-m-d H:i:s (e.g. 2025-02-03 14:30:00). For "between", this is the from value.'],
                                'value_to' => ['type' => 'string', 'description' => 'For operator "between": the end value. Use same format as value (Y-m-d for date, Y-m-d H:i:s for datetime).'],
                                'values' => [
                                    'type' => 'array',
                                    'description' => 'For operator "oneof" or "oneofnot": list of values.',
                                    'items' => ['type' => 'string'],
                                ],
                            ],
                            'additionalProperties' => false,
                        ],
                    ],
                    'offset' => self::PAGINATION_OFFSET,
                    'limit' => self::PAGINATION_LIMIT,
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
        if (array_key_exists('filters', $params)) {
            $args['filters'] = (array) $params['filters'];
        }
        if (array_key_exists('offset', $params)) {
            $args['offset'] = (int) $params['offset'];
        }
        if (array_key_exists('limit', $params)) {
            $args['limit'] = (int) $params['limit'];
        }
        return $this->executeReport(...$args);
    }
    public function executeReport(
        string $report_id,
        array $filters = [],
        int $offset = 0,
        int $limit = -1,
    ): ToolResult {
        try {
            $this->checkPermissions('KReports');
            [$resolved_offset, $resolved_limit] = $this->processPaginationParams($offset, $limit);

            $conditions = $this->buildConditionsArray($report_id, $filters);
            $request_params = [
                'start' => $resolved_offset,
                'limit' => $resolved_limit,
            ];
            if ($conditions !== []) {
                $request_params['whereConditions'] = json_encode($conditions);
            }

            $handler = $this->getRestHandler();
            if (!$handler) {
                throw new \RuntimeException('KReport REST handler not available.');
            }

            $ret_data = $handler->getPresentation($report_id, $request_params);
            $formatted = $this->formatExecutionResult($ret_data);
            $records = $formatted['records'];
            $total_count = $formatted['count'];

            $response = [
                'row_count' => $total_count,
                'current_offset' => $resolved_offset,
                'next_offset' => ($resolved_offset + count($records)) < $total_count ? $resolved_offset + count($records) : -1,
                'list' => $records,
            ];

            $payload = $this->formatPaginationData($response, $resolved_offset, [
                'records_returned' => count((array) ($response['list'] ?? [])),
                'records' => $response['list'] ?? [],
            ], $resolved_limit);

            return $this->successResult('Report executed successfully', $payload);
        } catch (\Throwable $e) {
            return $this->handleExecutionException($e);
        }
    }

    /**
     * @param array<string, mixed> $ret_data
     *
     * @return array{records: array<int, array<string, mixed>>, count: int}
     */
    private function formatExecutionResult(array $ret_data): array
    {
        $records = $ret_data['records'] ?? [];
        $count = (int) ($ret_data['count'] ?? 0);
        $report_meta_fields = $ret_data['reportmetadata']['fields'] ?? null;
        if (!\is_array($report_meta_fields) || $report_meta_fields === []) {
            throw new \RuntimeException('Error getting the report metadata fields.');
        }

        $id_to_name = [];
        foreach ($report_meta_fields as $field) {
            if (($field['display'] ?? '') !== 'yes') {
                continue;
            }
            $id_to_name[$field['fieldid']] = $field['fieldname'];
        }

        $result_records = array_map(static function ($record) use ($id_to_name): array {
            $result_record = [];
            foreach ($record as $field_id => $value) {
                $display_name = $id_to_name[$field_id] ?? $id_to_name[trim((string) $field_id, ':')] ?? null;
                if ($display_name !== null) {
                    $result_record[$display_name] = $value;
                }
            }

            return $result_record;
        }, $records);

        return ['records' => $result_records, 'count' => $count];
    }

    /**
     * @return array{conditions: array<int, array<string, mixed>>, conditions_by_name: array<string, array<string, mixed>>, report_name: string}
     */
    private function loadReportConditions(string $report_id): array
    {
        $bean = \BeanFactory::getBean('KReports');
        $report = $bean->retrieve($report_id);
        if (!$report || !$report->id) {
            return ['conditions' => [], 'conditions_by_name' => [], 'report_name' => ''];
        }

        $where_conditions = json_decode(html_entity_decode((string) ($report->whereconditions ?? '[]')), true);
        if (!\is_array($where_conditions)) {
            return ['conditions' => [], 'conditions_by_name' => [], 'report_name' => $report->name ?? ''];
        }

        $conditions_by_name = [];
        foreach ($where_conditions as $condition) {
            if (isset($condition['name'])) {
                $conditions_by_name[$condition['name']] = $condition;
            }
        }

        return [
            'conditions' => $where_conditions,
            'conditions_by_name' => $conditions_by_name,
            'report_name' => $report->name ?? '',
        ];
    }

    /**
     * @param array<int, mixed> $user_filters
     *
     * @return array<int, array<string, mixed>>
     */
    private function buildConditionsArray(string $report_id, array $user_filters): array
    {
        if ($user_filters === []) {
            return [];
        }

        $data = $this->loadReportConditions($report_id);
        $conditions_by_name = $data['conditions_by_name'];
        $report_name = $data['report_name'];
        $filter_metadata = $this->getFilterMetadata($conditions_by_name);

        (new KReportFilterValidator())->validate($user_filters, $conditions_by_name, $report_name, $filter_metadata);

        $override = [];
        foreach ($user_filters as $filter) {
            $filter_data = (array) $filter;
            $filter_name = (string) $filter_data['filter_name'];
            if (!isset($conditions_by_name[$filter_name])) {
                continue;
            }
            $matched_condition = $conditions_by_name[$filter_name];

            $entry = [
                'fieldid' => $matched_condition['fieldid'],
                'operator' => $filter_data['operator'],
            ];

            if (isset($filter_data['values']) && \is_array($filter_data['values'])) {
                $val = implode(',', $filter_data['values']);
                $entry['value'] = $val;
                $entry['valuekey'] = $val;
            } elseif (array_key_exists('value', $filter_data)) {
                $entry['value'] = $filter_data['value'];
                $entry['valuekey'] = $filter_data['value'];
                if (isset($filter_data['value_to'])) {
                    $entry['valueto'] = $filter_data['value_to'];
                    $entry['valuetokey'] = $filter_data['value_to'];
                }
            }

            $override[] = $entry;
        }

        return $override;
    }
}

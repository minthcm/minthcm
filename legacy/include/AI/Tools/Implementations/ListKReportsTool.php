<?php

namespace MintHCM\AI\Tools\Implementations;

use MintHCM\AI\Tools\AbstractTool;
use MintHCM\AI\Tools\ToolInterface;
use MintHCM\AI\Tools\ToolSchema;
use MintHCM\AI\Tools\ToolResult;
use MintHCM\AI\Tools\Traits\PaginationTrait;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class ListKReportsTool extends AbstractTool implements ToolInterface
{
    use PaginationTrait;

    public function getName(): string
    {
        return 'list_reports';
    }

    public function getSchema(): ToolSchema
    {
        return new ToolSchema(
            name: 'list_reports',
            description: 'Get list of reports available in MintHCM',
            parameters: [
                'type' => 'object',
                'properties' => [
                    'offset' => self::PAGINATION_OFFSET,
                    'limit' => self::PAGINATION_LIMIT,
                ],
                'required' => []
            ],
        );
    }

    public function execute(array $params): ToolResult
    {
        $args = [];
        if (array_key_exists('offset', $params)) {
            $args['offset'] = (int) $params['offset'];
        }
        if (array_key_exists('limit', $params)) {
            $args['limit'] = (int) $params['limit'];
        }
        return $this->listReports(...$args);
    }
    public function listReports(
        int $offset = 0,
        int $limit = -1,
    ): ToolResult {
        try {
            $this->checkPermissions('KReports');
            [$resolved_offset, $resolved_limit] = $this->processPaginationParams($offset, $limit);

            $bean = \BeanFactory::getBean('KReports');
            if (!$bean) {
                throw new \RuntimeException('KReports module unavailable.');
            }

            $reports_data = $bean->get_list('', '', $resolved_offset, $resolved_limit, $this->getMaxPaginationLimit($resolved_limit));

            $reports = $reports_data['list'] ?? [];
            if ($reports === []) {
                return $this->successResult(
                    'There are no reports available',
                    [
                        'records_returned' => 0,
                        'reports' => [],
                        'pagination_info' => [
                            'total_count' => 0,
                            'current_offset' => $reports_data['current_offset'] ?? $resolved_offset,
                            'records_returned' => 0,
                            'next_offset' => -1,
                        ],
                    ]
                );
            }

            $formatted_reports = [];
            foreach ($reports as $report) {
                $formatted_reports[] = [
                    'id' => $report->id,
                    'name' => $report->name,
                    'description' => $report->description,
                ];
            }

            $payload = $this->formatPaginationData($reports_data, $resolved_offset, [
                'records_returned' => count($formatted_reports),
                'reports' => $formatted_reports,
            ], $resolved_limit);

            return $this->successResult('Reports retrieved successfully', $payload);
        } catch (\Throwable $e) {
            return $this->handleExecutionException($e);
        }
    }
}

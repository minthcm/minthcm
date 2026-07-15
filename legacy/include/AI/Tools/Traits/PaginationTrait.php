<?php

namespace MintHCM\AI\Tools\Traits;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

use MintHCM\AI\Tools\Config\ToolConfig;

trait PaginationTrait
{
    public const PAGINATION_OFFSET = [
        'type' => 'integer',
        'description' => 'Number of records to skip. Used for pagination. Default is 0.',
        'minimum' => 0,
        'default' => 0,
    ];

    public const PAGINATION_LIMIT = [
        'type' => 'integer',
        'description' => 'Maximum number of records to return per page. Default is -1 (uses system default).',
        'minimum' => -1,
        'default' => -1,
    ];

    protected function getMaxPaginationLimit(?int $requested_limit): int
    {
        $max_limit = ToolConfig::getInstance()->get('max_pagination_limit', 100);
        if ($requested_limit > 0) {
            return min($requested_limit, $max_limit);
        }

        return $max_limit;
    }

    /**
     * @return array{0: int, 1: int}
     */
    protected function processPaginationParams(?int $offset, ?int $limit): array
    {
        $resolved_offset = $offset ?? 0;
        $resolved_limit = $this->getMaxPaginationLimit($limit ?? -1);

        return [$resolved_offset, $resolved_limit];
    }

    /**
     * @param array<string, mixed> $result
     * @param array<string, mixed> $response_data
     *
     * @return array<string, mixed>
     */
    protected function formatPaginationData(array $result, int $offset, array $response_data = [], int $requested_limit = -1): array
    {
        $row_count = (int) ($result['row_count'] ?? 0);
        $next_offset = (int) ($result['next_offset'] ?? -1);
        $current_offset = (int) ($result['current_offset'] ?? $offset);
        $record_count = count((array) ($result['list'] ?? []));

        if ($record_count < $this->getMaxPaginationLimit($requested_limit)) {
            $next_offset = -1;
        }

        $response_data['pagination_info'] = [
            'message' => implode("\n", [
                "Your query returned {$row_count} records.",
                '',
                'To retrieve meaningful and manageable results, please use pagination parameters:',
                "- Use 'limit' parameter to specify maximum number of records to retrieve per page",
                "- Use 'offset' parameter to skip records and retrieve next page",
                '',
            ]),
            'total_count' => $row_count,
            'current_offset' => $current_offset,
            'records_returned' => $record_count,
            'next_offset' => $next_offset,
        ];

        return $response_data;
    }
}

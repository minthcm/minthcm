<?php

namespace MintMCP\Tools\Traits;

use MintMCP\Config\Config;

/**
 * Trait providing pagination utilities for tools.
 */
trait PaginationTrait
{
    /**
     * Get pagination schema properties for input schema
     * 
     * @return array
     */
    protected function getPaginationSchemaProperties(): array
    {
        return [
            'offset' => [
                'type' => 'integer',
                'description' => 'Number of records to skip. Used for pagination. Default is 0.',
                'default' => 0,
            ],
            'limit' => [
                'type' => 'integer',
                'description' => 'Maximum number of records to return per page. Default is -1 (uses system default). Maximum allowed is ' . $this->getMaxPaginationLimit(null) . '.',
                'default' => -1,
            ],
        ];
    }

    /**
     * Process pagination parameters ensuring they respect MAX_PAGINATION_LIMIT
     * 
     * @param object|array $arguments Input arguments
     * @return array [int $offset, int $limit]
     */
    protected function processPaginationParams($arguments): array
    {
        $offset = $arguments->offset ?? ($arguments->offset ?? 0);
        $limit = $arguments->limit ?? ($arguments->limit ?? -1);

        $limit = $this->getMaxPaginationLimit($limit);

        return [$offset, $limit];
    }

    /**
     * Format pagination-related data for response with proper handling of limits and large result sets
     * 
     * @param array $result The query result array from get_list()
     * @param int $offset Original offset value
     * @param array $responseData Base response data array to extend
     * @param int $requestedLimit The limit originally requested by the client
     * @return array Updated response data with pagination info
     */
    protected function formatPaginationData(array $result, int $offset, array $responseData = [], int $requestedLimit = -1): array
    {
        $rowCount = $result['row_count'] ?? 0;
        $nextOffset = $result['next_offset'] ?? -1;
        $currentOffset = $result['current_offset'] ?? $offset;
        $recordCount = count($result['list'] ?? []);

        if ($recordCount < $this->getMaxPaginationLimit($requestedLimit)) {
            $nextOffset = -1; // No more records to fetch
        }

         $messageLines = [
                "Your query returned {$rowCount} records.",
                '',
                "To retrieve meaningful and manageable results, please use pagination parameters:",
                "- Use 'limit' parameter to specify maximum number of records to retrieve per page",
                "- Use 'offset' parameter to skip records and retrieve next page",
                '',
            ];

                $responseData['pagination_info'] = [
                'message' => implode("\n", $messageLines),
                'total_count' => $rowCount,
                'current_offset' => $currentOffset,
                'records_returned' => $recordCount,
                'next_offset' => $nextOffset,
            ];

            

        return $responseData;
    }

    /**
     * Get the maximum pagination limit
     * 
     * @return int
     */
    protected function getMaxPaginationLimit(?int $requestedLimit): int
    {
        $maxLimit = Config::getInstance()->get('max_pagination_limit', 100);
        if ($requestedLimit > 0) {
            return min($requestedLimit, $maxLimit);
        }
        return $maxLimit;
    }
}

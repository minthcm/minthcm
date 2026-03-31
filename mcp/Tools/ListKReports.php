<?php

namespace MintMCP\Tools;


use Mcp\Types\CallToolResult;
use Mcp\Types\ToolInputSchema;
use MintMCP\Tools\Traits\PaginationTrait;

class ListKReports extends AbstractMCPTool
{
    use PaginationTrait;

    public function getName(): string
    {
        return 'list_reports';
    }

    public function getDescription(): string
    {
        return 'Get list of reports available in MintHCM';
    }

    public function getInputSchema(): ToolInputSchema
    {
        $properties = $this->getPaginationSchemaProperties();   

        return ToolInputSchema::fromArray([
            'type' => 'object',
            'properties' => $properties,
        ]);
    }

    /**
     * Get list of reports from KReports module available in MintHCM
     *
     * @param object $arguments Input argument
     * @return CallToolResult
     */
    public function execute(object $arguments): CallToolResult
    {
        try {
            $this->checkPermissions('KReports');

            [$offset, $limit] = $this->processPaginationParams($arguments);

            chdir('../legacy');
            $reportsData = $this->getReportList($offset, $limit);
            chdir('../mcp');

            $result = $this->formatReports($reportsData, $offset, $limit);

            return $this->createResult([
                $this->createJsonContent($result)
            ]);
        } catch (\Exception $e) {
            return $this->createResult([
                $this->createTextContent("Error while getting list of reports: " . $e->getMessage())
            ]);
        }
    }

    /**
     * Retrieve raw reports list from KReports module.
     *
     * @param int $offset
     * @param int $limit
     * @return array
     */
    private function getReportList(int $offset, int $limit): array
    {
        try {
            $bean = \BeanFactory::getBean('KReports');
            $maxLimit = $this->getMaxPaginationLimit($limit);

            return $bean->get_list(
                '',
                '',
                $offset,
                $limit,
                $maxLimit
            );
        } catch (\Exception $e) {
            return [
                'list' => [],
                'row_count' => 0,
                'next_offset' => -1,
                'current_offset' => $offset,
            ];
        }
    }

    /**
     * Formats the list of reports into a readable array.
     *
     * @param array $reportsData
     * @param int $offset
     * @param int $requestedLimit
     * @return array
     */
    private function formatReports(array $reportsData, int $offset, int $requestedLimit): array
    {
        $reports = $reportsData['list'] ?? [];
        $currentOffset = $reportsData['current_offset'] ?? $offset;
        if (empty($reports)) {
            return [
                'status' => 'success',
                'message' => 'There are no reports available',
                'total_count' => 0,
                'current_offset' => $currentOffset,
                'next_offset' => -1,
                'records_returned' => 0,
                'reports' => [],
            ];
        }

        $formattedReports = [];
        foreach ($reports as $report) {
            $formattedReports[] = [
                'id' => $report->id,
                'name' => $report->name,
                'description' => $report->description,
            ];
        }
        return $this->formatPaginationData($reportsData, $currentOffset, [
            'status' => 'success',
            'message' => 'Reports retrieved successfully',
            'records_returned' => count($formattedReports),
            'reports' => $formattedReports,
        ], $requestedLimit);
    }
}

<?php

namespace MintMCP\Tools;

use Mcp\Types\CallToolResult;
use Mcp\Types\ToolInputSchema;
use MintMCP\Tools\Traits\KReportTrait;

class GetKReportDetails extends AbstractMCPTool
{
    use KReportTrait;

    public function getName(): string
    {
        return 'get_report_details';
    }

    public function getDescription(): string
    {
        return 'Get details of a specific report available in MintHCM, including for each filter: possible operators (e.g. Equals, Between), value inputs (single value vs From/To), and when applicable the list of possible values for the filter field.';
    }

    public function getInputSchema(): ToolInputSchema
    {
        return ToolInputSchema::fromArray([
            'type' => 'object',
            'properties' => [
                'report_id' => [
                    'type' => 'string',
                    'description' => 'ID of the report to get details for.',
                ],
            ],
            'required' => ['report_id'],
        ]);
    }

    /**
     * Get details of a specific report.
     *
     * @param object $arguments Input argument
     * @return CallToolResult
     */
    public function execute(object $arguments): CallToolResult
    {
        try {
            $this->checkPermissions('KReports');
            chdir('../legacy');

            $report = $this->loadReport($arguments->report_id);
            $result = $this->formatReport($report);

            chdir('../mcp');

            return $this->createResult([
                $this->createJsonContent($result)
            ]);
        } catch (\Exception $e) {
            return $this->createResult([
                $this->createTextContent("Error while getting report details: " . $e->getMessage())
            ]);
        }
    }

    /**
     * Load report bean by ID.
     *
     * @param string $reportId
     * @return array
     * @throws \Exception
     */
    private function loadReport(string $reportId): array
    {
        if (!$reportId) {
            throw new \Exception("report_id is required");
        }

        $report = \BeanFactory::getBean('KReports', $reportId);

        if (!$report || !$report->id) {
            throw new \Exception("Report with id '{$reportId}' not found");
        }

        $this->logger->debug("Loaded report: {id}", ['id' => $report->id]);

        return $report->toArray();
    }

    /**
     * Format report data for API response.
     *
     * @param array $report
     * @return array
     */
    private function formatReport(array $report): array
    {
        return [
            'id' => $report['id'],
            'name' => $report['name'],
            'description' => $report['description'],
            'output_fields' => $this->extractOutputFields($report),
            'filters' => $this->extractFilters($report),
        ];
    }

    /**
     * Extract output fields from report listfields.
     *
     * @param array $report
     * @return array
     */
    private function extractOutputFields(array $report): array
    {
        $listFields = json_decode(html_entity_decode($report['listfields'] ?? '[]'), true);

        if (!\is_array($listFields)) {
            return [];
        }

        $outputFields = [];
        foreach ($listFields as $field) {
            if (($field['display'] ?? '') !== 'yes' || !isset($field['fieldname'])) {
                continue;
            }

            $outputFields[] = [
                'field_name' => $field['fieldname'],
                'field_description' => !empty($field['presentdescription'])
                    ? trim($field['presentdescription'])
                    : "",
            ];
        }

        return $outputFields;
    }

    /**
     * Extract user-editable filters from report where conditions.
     *
     * @param array $report
     * @return array
     */
    private function extractFilters(array $report): array
    {
        $whereConditions = json_decode(html_entity_decode($report['whereconditions'] ?? '[]'), true);

        if (!\is_array($whereConditions)) {
            return [];
        }

        $conditionsByName = [];
        foreach ($whereConditions as $condition) {
            if (isset($condition['name'])) {
                $conditionsByName[$condition['name']] = $condition;
            }
        }

        $filterMetadata = $this->getFilterMetadata($conditionsByName);
        $filters = [];

        foreach ($whereConditions as $condition) {
            $filterName = $condition['name'] ?? null;

            if ($filterName === null) {
                $this->logger->error("No name found for filter: {path}", ['path' => $condition['path'] ?? '']);
                continue;
            }

            $editable = $condition['usereditable'] ?? 'no';

            // Skip non-editable filters
            if ($editable === 'no') {
                continue;
            }

            $meta = $filterMetadata[$filterName] ?? null;
            $dbType = $meta['dbType'] ?? 'unknown';

            $filterInfo = [
                'filter_name' => $filterName,
                'filter_type' => $dbType,
                'filter_options' => [],
            ];

            if ($editable === 'yes') {
                $filterInfo['filter_options']['operators'] = $this->getOperatorsForType($dbType);
            } elseif ($editable === 'yfo') {
                // Fixed operator for (editable = value only)
                $filterInfo['filter_options']['operators'] = [$condition['operator'] ?? ''];
            } else {
                $this->logger->error("Unknown filter editable value: {editable}", ['editable' => $editable]);
                continue;
            }

            // Add possible values for enum types
            if ($meta !== null && !empty($meta['possible_values'])) {
                $filterInfo['filter_options']['possible_values'] = $meta['possible_values'];
                $filterInfo['filter_type'] = 'enum';
            }

            $filters[] = $filterInfo;
        }

        return $filters;
    }
}

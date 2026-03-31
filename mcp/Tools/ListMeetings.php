<?php

namespace MintMCP\Tools;

use Mcp\Types\ToolInputSchema;
use MintMCP\Tools\Utils\DateTimeConversion;
use MintMCP\Tools\Traits\PaginationTrait;

class ListMeetings extends AbstractMCPTool
{
    use PaginationTrait;

    public function getName(): string
    {
        return 'list_meetings';
    }

    public function getDescription(): string
    {
        return 'Retrieves a list of meetings from the MintHCM system.';
    }

    public function getInputSchema(): ToolInputSchema
    {
        $properties = $this->getPaginationSchemaProperties();
        
        $properties['search'] = [
            'type' => 'string',
            'description' => 'Search phrase in meeting name'
        ];
        
        $properties['date_from'] = [
            'type' => 'string',
            'description' => 'Start date (YYYY-MM-DD)',
            'format' => 'date'
        ];
        
        $properties['date_to'] = [
            'type' => 'string',
            'description' => 'End date (YYYY-MM-DD)',
            'format' => 'date'
        ];
        
        return ToolInputSchema::fromArray([
            'type' => 'object',
            'properties' => $properties
        ]);
    }

    /**
     * Executes the tool: retrieves a list of meetings based on input filters.
     *
     * @param object $arguments Input arguments for the tool
     * @return \Mcp\Types\CallToolResult
     */
    public function execute($arguments): \Mcp\Types\CallToolResult
    {
        try {
            $this->checkPermissions('Meetings');

            chdir('../legacy');
            $searchParams = $this->buildSearchParams($arguments);
            $meetings = $this->getMeetingsList($searchParams);
            $result = $this->formatMeetings($meetings, $arguments);
            chdir('../mcp');

            return $this->createResult([
                $this->createJsonContent($result)
            ]);
        } catch (\Exception $e) {
            return $this->createResult([
                $this->createTextContent("Error while retrieving the list of meetings: " . $e->getMessage())
            ]);
        }
    }

    private function buildSearchParams($arguments): array
    {
        [$offset, $limit] = $this->processPaginationParams($arguments);
        
        return [
            'module' => 'Meetings',
            'limit' => $limit,
            'offset' => $offset,
            'where' => $this->buildWhereString($arguments),
        ];
    }

    private function buildWhereString($arguments): string
    {
        $where = $this->buildWhereClause($arguments);
        return !empty($where) ? implode(' AND ', $where) : '';
    }

    /**
     * Builds the WHERE clause for meeting search based on input arguments.
     *
     * @param object $arguments
     * @return array
     */
    private function buildWhereClause($arguments): array
    {
        $where = [];
        $db = \DBManagerFactory::getInstance();

        if (!empty($arguments->search)) {
            $where[] = "meetings.name LIKE '%" . $db->quote($arguments->search) . "%'";
        }
        if (!empty($arguments->date_from)) {
            $where[] = "meetings.date_start >= '" . $db->quote(DateTimeConversion::fromUserTZ($arguments->date_from)) . " 00:00:00'";
        }
        if (!empty($arguments->date_to)) {
            $where[] = "meetings.date_start <= '" . $db->quote(DateTimeConversion::fromUserTZ($arguments->date_to)) . " 23:59:59'";
        }

        return $where;
    }

    /**
     * Retrieves the list of meetings using the provided search parameters.
     *
     * @param array $searchParams
     * @return array
     */
    private function getMeetingsList(array $searchParams): array
    {
        try {
            $bean = \BeanFactory::getBean('Meetings');
            $limit = $searchParams['limit'] ?? -1;
            $offset = $searchParams['offset'] ?? 0;
            $where = $searchParams['where'] ?? '';

            $result = $bean->get_list(
                '',
                $where,
                $offset,
                $limit,
                $this->getMaxPaginationLimit($limit)
            );
            
            return $result;
        } catch (\Exception $e) {
            return [
                'list' => [],
                'row_count' => 0,
                'next_offset' => -1,
                'current_offset' => $offset ?? 0,
            ];
        }
    }

    /**
     * Formats the list of meetings into a readable array.
     *
     * @param array $meetingsData
     * @param object $arguments Original arguments
     * @return array
     */
    private function formatMeetings(array $meetingsData, $arguments): array
    {
        $meetings = $meetingsData['list'] ?? [];
        $offset = $meetingsData['current_offset'] ?? 0;
        
        if (empty($meetings)) {
            return [
                'status' => 'success',
                'message' => 'No meetings found',
                'total_count' => 0,
                'current_offset' => $meetingsData['current_offset'] ?? 0,
                'next_offset' => -1,
                'records_returned' => 0,
                'meetings' => []
            ];
        }

        $formattedMeetings = [];
        foreach ($meetings as $meeting) {
            $participants = $this->getParticipants($meeting);

            $formattedMeetings[] = [
                'id' => $meeting->id,
                'name' => $meeting->name,
                'description' => $meeting->description,
                'assigned_user_id' => $meeting->assigned_user_id,
                'assigned_user_name' => $meeting->assigned_user_name ?? '',
                'location' => $meeting->location,
                'date_start' => DateTimeConversion::formatDate($meeting->date_start),
                'date_end' => DateTimeConversion::formatDate($meeting->date_end),
                'duration_hours' => $meeting->duration_hours ?? 0,
                'duration_minutes' => $meeting->duration_minutes ?? 0,
                'status' => $meeting->status ?? '',
                'join_url' => $meeting->join_url ?? '',
                'creator' => $meeting->creator ?? '',
                'date_modified' => DateTimeConversion::formatDate($meeting->date_modified),
                'participants' => $participants
            ];
        }

        return $this->formatPaginationData($meetingsData, $offset, [
            'status' => 'success',
            'message' => 'Meetings retrieved successfully',
            'records_returned' => count($formattedMeetings),
            'meetings' => $formattedMeetings
        ], $arguments->limit ?? -1);
    }

    /**
     * Collects all participants for a meeting.
     *
     * @param object $meeting
     * @return array
     */
    private function getParticipants($meeting): array
    {
        $participants = [];

        if (!empty($meeting->assigned_user_id)) {
            $assignedUser = \BeanFactory::getBean('Users', $meeting->assigned_user_id);
            if (!empty($assignedUser->id)) {
                $participants[] = [
                    'id' => $assignedUser->id,
                    'name' => $assignedUser->full_name ?? '',
                    'module' => 'Users'
                ];
            }
        }

        // Add related participants
        $participants = array_merge(
            $participants,
            $this->getRelatedParticipants($meeting, 'users', 'Users'),
            $this->getRelatedParticipants($meeting, 'candidates', 'Candidates'),
            $this->getRelatedParticipants($meeting, 'contacts', 'Contacts'),
            $this->getRelatedParticipants($meeting, 'leads', 'Leads')
        );

        return $participants;
    }

    /**
     * Loads related beans and returns them as participants.
     *
     * @param object $meeting
     * @param string $relName
     * @param string $module
     * @return array
     */
    private function getRelatedParticipants($meeting, string $relName, string $module): array
    {
        $participants = [];
        if ($meeting->load_relationship($relName)) {
            $beans = $meeting->$relName->getBeans();
            foreach ($beans as $bean) {
                $name = $bean->name ?? trim(($bean->first_name ?? '') . ' ' . ($bean->last_name ?? ''));
                $participants[] = [
                    'id' => $bean->id,
                    'name' => $name,
                    'module' => $module
                ];
            }
        }
        return $participants;
    }
}

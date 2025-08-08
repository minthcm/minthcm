<?php

namespace MintMCP\Tools;

use Mcp\Types\ToolInputSchema;

class ListMeetings extends AbstractMCPTool
{
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
        return ToolInputSchema::fromArray([
            'type' => 'object',
            'properties' => [
                'limit' => [
                    'type' => 'integer',
                    'description' => 'Maximum number of meetings to retrieve',
                    'default' => 20
                ],
                'offset' => [
                    'type' => 'integer',
                    'description' => 'Offset for pagination',
                    'default' => 0
                ],
                'search' => [
                    'type' => 'string',
                    'description' => 'Search phrase in meeting name'
                ],
                'date_from' => [
                    'type' => 'string',
                    'description' => 'Start date (YYYY-MM-DD)',
                    'format' => 'date'
                ],
                'date_to' => [
                    'type' => 'string',
                    'description' => 'End date (YYYY-MM-DD)',
                    'format' => 'date'
                ]
            ]
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
            $result = $this->formatMeetings($meetings['list'] ?? []);
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
        return [
            'module' => 'Meetings',
            'limit' => $arguments->limit ?? 20,
            'offset' => $arguments->offset ?? 0,
            'where' => $this->buildWhereString($arguments)
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
            $where[] = "meetings.date_start >= '" . $db->quote($arguments->date_from) . " 00:00:00'";
        }
        if (!empty($arguments->date_to)) {
            $where[] = "meetings.date_start <= '" . $db->quote($arguments->date_to) . " 23:59:59'";
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
            $limit = $searchParams['limit'] ?? 20;
            $offset = $searchParams['offset'] ?? 0;
            $where = $searchParams['where'] ?? '';

            $list = $bean->get_full_list('', $where, $offset, $limit);

            return [
                'list' => $list ?? [],
                'total_count' => count($list ?? [])
            ];
        } catch (\Exception $e) {
            return [
                'list' => [],
                'total_count' => 0
            ];
        }
    }

    /**
     * Formats the list of meetings into a readable array.
     *
     * @param array $meetings
     * @return array
     */
    private function formatMeetings(array $meetings): array
    {
        if (empty($meetings)) {
            return [
                'message' => 'No meetings found',
                'count' => 0,
                'meetings' => []
            ];
        }

        $result = [
            'message' => 'Meetings retrieved successfully',
            'count' => count($meetings),
            'meetings' => []
        ];

        foreach ($meetings as $meeting) {
            $participants = $this->getParticipants($meeting);

            $result['meetings'][] = [
                'id' => $meeting->id,
                'name' => $meeting->name,
                'description' => $meeting->description,
                'assigned_user_id' => $meeting->assigned_user_id,
                'assigned_user' => $meeting->assigned_user_name ?? '',
                'location' => $meeting->location,
                'date_start' => $meeting->date_start,
                'date_end' => $meeting->date_end,
                'duration_hours' => $meeting->duration_hours ?? 0,
                'duration_minutes' => $meeting->duration_minutes ?? 0,
                'status' => $meeting->status ?? '',
                'join_url' => $meeting->join_url ?? '',
                'creator' => $meeting->creator ?? '',
                'date_modified' => $meeting->date_modified,
                'participants' => $participants
            ];
        }

        return $result;
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

        // Assigned user
        if (!empty($meeting->assigned_user_id)) {

            $assignedUser = \BeanFactory::getBean('Users', $meeting->assigned_user_id);
            if (empty($assignedUser->id)) {
                throw new \Exception("Assigned user to meeting with ID {$meeting->id} not found.");
            }

            $participants[] = [
                'id' => $assignedUser->id,
                'name' => $assignedUser->full_name ?? '',
                'module' => 'Users'
            ];
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

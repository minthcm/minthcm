<?php

namespace MintMCP\Tools;

use MintMCP\Tools\Utils\ToolValidation;

use Mcp\Types\CallToolResult;
use Mcp\Types\ToolInputSchema;
use MintMCP\Tools\Utils\DateTimeConversion;
use TimeDate;

class CheckAvailability extends AbstractMCPTool
{

    /**
     * Statuses that are excluded from busy slots (not considered as busy).
     */
    const EXCLUDED_STATUSES = ['Held', 'Not Held'];

    public function getName(): string
    {
        return 'check_availability';
    }

    public function getDescription(): string
    {
        return 'Check the availability of a person in a given period of time based on their meetings and/or calls. Response includes: 
                - List of busy periods with start and end times
                - Type of activity (meeting or call)
                - Name of the activity';
    }

    public function getInputSchema(): ToolInputSchema
    {
        return ToolInputSchema::fromArray([
            'type' => 'object',
            'properties' => [
                'mint_user_id' => [
                    'type' => 'string',
                    'description' => 'ID of the user to check availability for.',
                ],
                'start_date' => [
                    'type' => 'string',
                    'description' => 'Start of the period in YYYY-MM-DD HH:MM:SS format. If not provided, current date/time is used.',
                    'format' => 'datetime',
                ],
                'end_date' => [
                    'type' => 'string',
                    'description' => 'End of the period in YYYY-MM-DD HH:MM:SS format.',
                    'format' => 'datetime',
                ],
                'modules' => [
                    'type' => 'array',
                    'description' => "List of modules to check availability for, can include 'meetings' and/or 'calls'.",
                    'items' => [
                        'type' => 'string',
                        'enum' => ['meetings', 'calls'],
                    ],
                ],
            ],
            'required' => ['mint_user_id', 'end_date', 'modules'],
        ]);
    }

    /**
     * Checks the user's availability for the given period and modules.
     *
     * @param object $arguments Input arguments for the tool
     * @return CallToolResult
     */
    public function execute(object $arguments): CallToolResult
    {
        try {
            $this->checkPermissions('Meetings');
            $this->checkPermissions('Calls');

            ToolValidation::validateMany([
                ToolValidation::make($arguments->mint_user_id, 'mint_user_id')
                    ->required()
                    ->string(),
                ToolValidation::make($arguments->end_date, 'end_date')
                    ->required()
                    ->string()
                    ->date(),
                ToolValidation::make($arguments->modules, 'modules')
                    ->required()
                    ->array(),
            ]);
            if (!empty($arguments->start_date)) {
                ToolValidation::validateOne(
                    ToolValidation::make($arguments->start_date, 'start_date')
                        ->string()
                        ->date()
                        ->isBefore($arguments->end_date, 'end_date')
                );
            }

            $userId = $arguments->mint_user_id;
            $startDate = DateTimeConversion::fromUserTZ($arguments->start_date) ?? null;
            $endDate = DateTimeConversion::fromUserTZ($arguments->end_date) ?? null;
            $modules = $arguments->modules;

            chdir('../legacy');
            $userBean = \BeanFactory::getBean('Users', $userId);
            if (!$userBean || empty($userBean->id)) {
                throw new \InvalidArgumentException("User with id '{$userId}' does not exist.");
            }

            $busySlots = [];

            if (in_array('meetings', $modules)) {
                $busySlots = array_merge($busySlots, $this->getBusySlotsFromMeetings($userId, $startDate, $endDate));
            }
            if (in_array('calls', $modules)) {
                $busySlots = array_merge($busySlots, $this->getBusySlotsFromCalls($userId, $startDate, $endDate));
            }

            chdir('../mcp');

            $response = [
                'message' => empty($busySlots)
                    ? 'The user is available for the entire period.'
                    : 'The user is busy during the following periods.',
                'busy_periods' => $busySlots,
            ];

            return $this->createResult([
                $this->createJsonContent($response)
            ]);
        } catch (\Exception $e) {
            return $this->createResult([
                $this->createTextContent($e instanceof \InvalidArgumentException ? $e->getMessage() : ("Error while checking availability: " . $e->getMessage()))
            ]);
        }
    }

    /**
     * Retrieves busy slots from meetings for the given user and period.
     *
     * @param string $userId
     * @param string|null $startDate
     * @param string $endDate
     * @return array
     */
    private function getBusySlotsFromMeetings($userId, $startDate, $endDate): array
    {
        if (!$startDate) {
            $startDate = TimeDate::getInstance()->nowDb();
        }
        $meetingBean = \BeanFactory::getBean('Meetings');
        $where = [
            "meetings.deleted = 0",
            "meetings.status NOT IN ('" . implode("', '", self::EXCLUDED_STATUSES) . "')",
            "meetings.date_start <= '{$endDate}'",
        ];
        if ($startDate) {
            $where[] = "meetings.date_end >= '{$startDate}'";
        }

        // Get all meetings matching the date/status criteria
        $meetingsData = $meetingBean->get_list(
            '',
            implode(' AND ', $where)
        );

        $meetings = $meetingsData['list'] ?? [];

        $busy = [];
        if ($meetings) {
            foreach ($meetings as $meeting) {
                // Check if user is related via meetings_users
                $meeting->load_relationship('users');
                $userIds = $meeting->users->get();
                if (in_array($userId, $userIds)) {
                    $busy[] = [
                        'start' => DateTimeConversion::formatDate($meeting->date_start),
                        'end' => DateTimeConversion::formatDate($meeting->date_end),
                        'type' => 'meeting',
                        'name' => $meeting->name,
                    ];
                }
            }
        }
        return $busy;
    }

    /**
     * Retrieves busy slots from calls for the given user and period.
     *
     * @param string $userId
     * @param string|null $startDate
     * @param string $endDate
     * @return array
     */
    private function getBusySlotsFromCalls($userId, $startDate, $endDate): array
    {
        if (!$startDate) {
            $startDate = \TimeDate::getInstance()->nowDb();
        }
        $callBean = \BeanFactory::getBean('Calls');
        $where = [
            "calls.deleted = 0",
            "calls.status NOT IN ('" . implode("', '", self::EXCLUDED_STATUSES) . "')",
            "calls.date_start <= '{$endDate}'",
        ];
        if ($startDate) {
            $where[] = "calls.date_end >= '{$startDate}'";
        }

        // Get all calls matching the date/status criteria
        $callsData = $callBean->get_list(
            '',
            implode(' AND ', $where)
        );
        $calls = $callsData['list'] ?? [];

        $busy = [];
        if ($calls) {
            foreach ($calls as $call) {
                // Check if user is related via calls_users
                $call->load_relationship('users');
                $userIds = $call->users->get();
                if (in_array($userId, $userIds)) {
                    $busy[] = [
                        'start' => DateTimeConversion::formatDate($call->date_start),
                        'end' => DateTimeConversion::formatDate($call->date_end),
                        'type' => 'call',
                        'name' => $call->name,
                    ];
                }
            }
        }
        return $busy;
    }
}

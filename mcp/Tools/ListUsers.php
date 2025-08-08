<?php

namespace MintMCP\Tools;

use Mcp\Types\ToolInputSchema;
use Mcp\Types\CallToolResult;

class ListUsers extends AbstractMCPTool
{

    public function getName(): string
    {
        return 'list_users';
    }

    public function getDescription(): string
    {
        return 'Retrieve a list of users and their details available in MintHCM.';
    }

    public function getInputSchema(): ToolInputSchema
    {
        return ToolInputSchema::fromArray([
            'type' => 'object',
        ]);
    }

    /**
     * Executes the tool: retrieves a list of users and their details.
     *
     * @param object $arguments Input arguments for the tool (not used)
     * @return CallToolResult
     */
    public function execute(object $arguments): CallToolResult
    {
        try {
            $this->checkPermissions('Users', 'list');

            chdir('../legacy');
            $bean = \BeanFactory::getBean('Users');
            $users = $bean->get_full_list('', "users.deleted = 0");
            chdir('../mcp');

            $result = [];
            if ($users) {
                foreach ($users as $user) {
                    $result[] = [
                        'id' => $user->id ?? '',
                        'name' => trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')),
                        'supervisor_id' => $user->reports_to_id ?? '',
                        'phone_home' => $user->phone_home ?? '',
                        'phone_mobile' => $user->phone_mobile ?? '',
                        'phone_work' => $user->phone_work ?? '',
                        'phone_other' => $user->phone_other ?? '',
                        'email' => $user->email1 ?? '',
                        'address' => [
                            'street' => $user->address_street ?? '',
                            'city' => $user->address_city ?? '',
                            'state' => $user->address_state ?? '',
                            'country' => $user->address_country ?? '',
                            'postal_code' => $user->address_postalcode ?? '',
                        ],
                        'user_type' => $user->UserType ?? '',
                        'employee_status' => $user->employee_status ?? '',
                        'position' => $user->position_name ?? '',
                        'status' => $user->status ?? '',
                    ];
                }
            }

            $response = [
                'message' => empty($result) ? 'No users found.' : 'List of users',
                'users' => $result
            ];

            return $this->createResult([
                $this->createJsonContent($response)
            ]);
        } catch (\Exception $e) {
            return $this->createResult([
                $this->createJsonContent([
                    'message' => "Error while retrieving users: " . $e->getMessage(),
                    'users' => []
                ])
            ]);
        }
    }
}

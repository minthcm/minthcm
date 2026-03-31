<?php

namespace MintMCP\Tools;

use Mcp\Types\ToolInputSchema;
use Mcp\Types\CallToolResult;
use MintMCP\Tools\Traits\PaginationTrait;

class ListUsers extends AbstractMCPTool
{
    use PaginationTrait;

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
        $properties = $this->getPaginationSchemaProperties();
        
        $properties['only_active'] = [
            'type' => 'boolean',
            'description' => 'If true, only active users are returned. Default is true.',
            'default' => true,
        ];
        
        return ToolInputSchema::fromArray([
            'type' => 'object',
            'properties' => $properties,
        ]);
    }

    /**
     * Executes the tool: retrieves a list of users and their details.
     *
     * @param object $arguments Input arguments for the tool
     * @return CallToolResult
     */
    public function execute(object $arguments): CallToolResult
    {
        try {
            $this->checkPermissions('Users', 'list');

            [$offset, $limit] = $this->processPaginationParams($arguments);
            
            $onlyActive = $arguments->only_active ?? false;

            chdir('../legacy');
            $bean = \BeanFactory::getBean('Users');
            $result = $bean->get_list(
                '', 
                "users.deleted = 0" . ($onlyActive ? " AND users.status = 'Active'" : ""), 
                $offset, 
                $limit, 
                $this->getMaxPaginationLimit($limit)
            );
            chdir('../mcp');

            $users = $result['list'] ?? [];
            $formattedUsers = [];
            
            if ($users) {
                foreach ($users as $user) {
                    $formattedUsers[] = [
                        'id' => $user->id ?? '',
                        'first_name' => $user->first_name ?? '',
                        'last_name' => $user->last_name ?? '',
                        'reports_to_id' => $user->reports_to_id ?? '',
                        'phone_home' => $user->phone_home ?? '',
                        'phone_mobile' => $user->phone_mobile ?? '',
                        'phone_work' => $user->phone_work ?? '',
                        'phone_other' => $user->phone_other ?? '',
                        'email1' => $user->email1 ?? '',
                        'address_street' => $user->address_street ?? '',
                        'address_city' => $user->address_city ?? '',
                        'address_state' => $user->address_state ?? '',
                        'address_country' => $user->address_country ?? '',
                        'address_postalcode' => $user->address_postalcode ?? '',
                        'employee_status' => $user->employee_status ?? '',
                        'position_name' => $user->position_name ?? '',
                        'status' => $user->status ?? '',
                    ];
                }
            }

            $message = empty($formattedUsers) ? 'No users found.' : 'List of users';
            
            $response = $this->formatPaginationData($result, $offset, [
                'status' => 'success',
                'message' => $message,
                'records_returned' => count($formattedUsers),
                'users' => $formattedUsers
            ], $limit);

            return $this->createResult([
                $this->createJsonContent($response)
            ]);
        } catch (\Exception $e) {
            return $this->createResult([
                $this->createJsonContent([
                    'status' => 'error',
                    'message' => "Error while retrieving users: " . $e->getMessage(),
                    'users' => []
                ])
            ]);
        }
    }
}
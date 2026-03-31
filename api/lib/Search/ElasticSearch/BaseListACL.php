<?php

namespace MintHCM\Lib\Search\ElasticSearch;

use MintHCM\Lib\Search\ElasticSearch\ModulePrefixer;
use MintHCM\Lib\Search\ElasticSearch\ESListACLHelper;
use MintHCM\Data\BeanFactory;
use MintHCM\Utils\LegacyConnector;

#[\AllowDynamicProperties]
class BaseListACL
{
    protected $module;
    protected $acl_helper;
    protected $prefixer;

    public function __construct(string $module)
    {
        $this->module = $module;
        $this->acl_helper = new ESListACLHelper;
        $this->prefixer = new ModulePrefixer($this->module);
    }

    public function getAccessRestrictionFilter(string $user_id): array
    {
        $acl_filters = array_merge(
            $this->getFiltersByOwner($user_id),
            $this->getFiltersBySecurityGroups($user_id),
            $this->getCustomFilters($user_id),
        );

        return $this->wrapFiltersWithOrClause($acl_filters);
    }

    protected function getFiltersByOwner(string $user_id): array
    {
        $bean = BeanFactory::newBean($this->module);
        if (!$bean->bean_implements('ACL') || (
            !\ACLController::requireOwner($bean->module_dir, 'list')
            && !\ACLController::requireSecurityGroup($bean->module_dir, 'list')
        )) {
            return [];
        }

        $filters = [];
        $owner_ids = $this->getOwnerIds($user_id);
        
        if (empty($owner_ids)) {
            $owner_ids = [$user_id];
        }
            if (isset($bean->field_defs['assigned_user_id'])) {
                $filters[] = [
                    'terms' => [ $this->prefixer->modify('meta.assigned.user_id') => array_values($owner_ids) ],
                ];
            }

            if ($this->acl_helper->doesModuleUseEmployeeRelationship($this->module)) {
                $filters[] = [
                    'terms' => [ $this->prefixer->modify('employee_id') => array_values($owner_ids) ],
                ];
            }

        if (empty($filters) && isset($bean->field_defs['created_by'])) {
            $filters[] = [
                'term' => [ $this->prefixer->modify('meta.created.user_id') => $user_id ],
            ];
        }
        return $filters;
    }

    protected function getFiltersBySecurityGroups(string $user_id): array
    {
        $bean = BeanFactory::newBean($this->module);
        if (!$bean->bean_implements('ACL') || !\ACLController::requireSecurityGroup($bean->module_dir, 'list')) {
            return [];
        }

        $group_ids = $this->getSecurityGroupIds($user_id);
        if (empty($group_ids)) {
            return [];
        }

        return [
            [
                'nested' => [
                    'path' => 'security_groups',
                    'query' => [
                        'terms' => [
                            'security_groups.id.keyword' => array_values($group_ids),
                        ],
                    ],
                    'ignore_unmapped' => true,
                ]
            ]
        ];
    }

    // To be overrided
    protected function getCustomFilters(string $user_id): array
    {
        return [];
    }

    protected function wrapFiltersWithOrClause(array $filters)
    {

        return [
            [
                'bool' => [
                    'should' => $filters,
                ],
            ]
        ];
    }

    protected function getOwnerIds(string $user_id)
    {
        $controller_factory = new LegacyConnector('ControllerFactory');
        /** @var \UsersController $controller */
        $controller = $controller_factory::getController('Users');
        $subordinates_ids = $controller::getIDOfSubordinates([$user_id]);
        $all_ids = array_merge([$user_id], is_array($subordinates_ids) ? $subordinates_ids : []);
        return array_values(array_filter($all_ids, fn($id) => $id !== null && $id !== ''));
    }

    protected function getSecurityGroupIds(string $user_id): array
    {
        $db = \DBManagerFactory::getInstance();
        $result = $db->query("SELECT secu.securitygroup_id
            FROM securitygroups_users secu
            WHERE secu.user_id = '{$user_id}'
                AND secu.deleted = 0    
        ");

        $group_ids = [];
        while ($row = $db->fetchByAssoc($result)) {
            $group_ids[] = $row['securitygroup_id'];
        }

        return array_values(array_filter(array_unique($group_ids), fn($id) => $id !== null && $id !== ''));
    }
}

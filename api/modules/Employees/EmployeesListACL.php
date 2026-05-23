<?php

namespace MintHCM\Modules\Employees;

use MintHCM\Lib\Search\ElasticSearch\BaseListACL;
use MintHCM\Data\BeanFactory;
use MintHCM\Utils\LegacyConnector;

class EmployeesListACL extends BaseListACL
{
    protected function getFiltersByOwner(string $user_id): array
    {
        $filters = parent::getFiltersByOwner($user_id);
        $bean = BeanFactory::newBean($this->module);
        /** @var \ACLController $acl_controller */
        $acl_controller = new LegacyConnector('ACLController');
        if (!$bean->bean_implements('ACL') || (
            !$acl_controller::requireOwner($bean->module_dir, 'list')
            && !$acl_controller::requireSecurityGroup($bean->module_dir, 'list')
        )) {
            return [];
        }
        $filters[] = [
            'term' => [ '_id' => $user_id ],
        ];
        return $filters;
    }
}

<?php

require_once 'include/ESListView/BaseListACL.php';

class EmployeesListACL extends BaseListACL
{
    protected function getFiltersByOwner(string $user_id): array
    {
        $filters = parent::getFiltersByOwner($user_id);
        $bean = BeanFactory::newBean($this->module);
        if (!$bean->bean_implements('ACL') || (
            !ACLController::requireOwner($bean->module_dir, 'list')
            && !ACLController::requireSecurityGroup($bean->module_dir, 'list')
        )) {
            return [];
        }
        $filters[] = [
            'term' => [ '_id' => $user_id ],
        ];
        return $filters;
    }
}

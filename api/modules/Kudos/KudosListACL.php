<?php

namespace MintHCM\Modules\Kudos;

use MintHCM\Lib\Search\ElasticSearch\BaseListACL;
use MintHCM\Data\BeanFactory;
use MintHCM\Utils\LegacyConnector;

class KudosListACL extends BaseListACL
{

    protected function getFiltersByOwner(string $user_id): array
    {
        $bean = BeanFactory::newBean($this->module);
        global $current_user;

        if ($current_user->isAdmin()) {
            return [];
        }

        chdir('../legacy/'); 
        $acl_controller = new LegacyConnector('ACLController');
        if (!$bean->bean_implements('ACL') || (
            !$acl_controller::requireOwner($bean->module_dir, 'list')
            && !$acl_controller::requireSecurityGroup($bean->module_dir, 'list')
        )) {
            return [];
        }
        chdir('../api/');
        $filters = [];

        if (isset($bean->field_defs['assigned_user_id'])) {
            $filters[] = [
                'terms' => [$this->prefixer->modify('meta.assigned.user_id.keyword') => [$user_id]],
            ];
        }

        if ($this->acl_helper->doesModuleUseEmployeeRelationship($this->module)) {
            $filters[] = [
                'terms' => [$this->prefixer->modify('employee_id.keyword') => [$user_id]],
            ];
        }

        if (isset($bean->field_defs['created_by'])) {
            $filters[] = [
                'term' => [$this->prefixer->modify('meta.created.user_id.keyword') => $user_id],
            ];
        }
        return $filters;
    }
}

<?php

namespace MintHCM\Modules\Kudos;

use MintHCM\Lib\Search\ElasticSearch\BaseListACL;
use MintHCM\Data\BeanFactory;

class KudosListACL extends BaseListACL
{

    protected function getFiltersByOwner(string $user_id): array
    {
        $bean = BeanFactory::newBean($this->module);
        global $current_user;

        $filters = [];

        if ($current_user->isAdmin()) {
            return $filters;
        }

        if (isset($bean->field_defs['assigned_user_id'])) {
            $filters[] = [
                'terms' => [$this->prefixer->modify('meta.assigned.user_id.keyword') => $this->getOwnerIds($user_id)],
            ];
        }

        if ($this->acl_helper->doesModuleUseEmployeeRelationship($this->module)) {
            $filters[] = [
                'terms' => [$this->prefixer->modify('employee_id.keyword') => $this->getOwnerIds($user_id)],
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

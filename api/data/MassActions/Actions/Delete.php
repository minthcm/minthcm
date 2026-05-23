<?php

namespace MintHCM\Data\MassActions\Actions;

use MintHCM\Data\MassActions\MassAction;

class Delete extends MassAction
{
    const ICON = 'mdi-delete';
    const LABEL = 'LBL_DELETE';

    public function execute()
    {
        $result = [
            'success' => false,
            'beans_access' => false,
        ];

        if (!$this->hasAccess()) {
            return $result;
        }

        $beans = $this->getBeans();
    
        chdir('../legacy');
        foreach ($beans as $bean) {
            if (!$bean->ACLAccess('delete')) {
                return $result;
            }
        }

        $result['beans_access'] = true;

        foreach ($beans as $bean) {
            $bean->mark_deleted($bean->id);
        }
        chdir('../api');

        $result['success'] = true;
        return $result;
    }

    public function hasAccess()
    {
        chdir('../legacy');
        $has_access = \ACLController::checkAccess($this->module_name, 'delete', true);
        chdir('../api');
        return $has_access;
    }
}

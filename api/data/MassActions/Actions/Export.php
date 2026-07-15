<?php

namespace MintHCM\Data\MassActions\Actions;

use MintHCM\Data\MassActions\MassAction;

class Export extends MassAction
{
    const ICON = 'mdi-export';
    const LABEL = 'LBL_EXPORT';
    
    public function execute()
    {
        $_SESSION['uids'] = implode(',', $this->ids);
        return ['success' => true, 'module' => $this->module_name];
    }

    public function hasAccess()
    {
        chdir('../legacy');
        $has_access = \ACLController::checkAccess($this->module_name, 'export', true);
        chdir('../api');
        return $has_access;
    }
}

<?php

namespace MintHCM\Data\MassActions\Actions;

use MintHCM\Data\MassActions\MassAction;

class Merge extends MassAction
{
    const ICON = 'mdi-merge';
    const LABEL = 'LBL_MERGE_DUPLICATES';
    
    public function execute()
    {
        throw new \Exception('Merge is implemented in legacy code.');
    }

    public function hasAccess()
    {
        chdir('../legacy');
        global $dictionary;
        $has_access = (
            !empty($dictionary[$this->module_name]['duplicate_merge'])
            && \ACLController::checkAccess($this->module_name, 'edit', true)
            && \ACLController::checkAccess($this->module_name, 'delete', true)
        );
        chdir('../api');
        return $has_access;
    }
}

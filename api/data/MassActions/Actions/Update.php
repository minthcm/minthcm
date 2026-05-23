<?php

namespace MintHCM\Data\MassActions\Actions;

use MintHCM\Data\MassActions\MassAction;

class Update extends MassAction
{
    const ICON = 'mdi-playlist-edit';
    const LABEL = 'LBL_UPDATE';
    protected $array_fields = [];

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

        $result['beans_access'] = true;
        foreach ($beans as $bean) {
            if (!$bean->ACLAccess('edit')) {
                continue;
            }
            foreach ($this->array_fields as $field => $value) {
                $bean->$field = $value;
            }
            $bean->save();
        }
        chdir('../api');

        $result['success'] = true;
        return $result;
    }

    public function hasAccess()
    {
        chdir('../legacy');
        $has_access = \ACLController::checkAccess($this->module_name, 'massupdate', true);
        chdir('../api');
        return $has_access;
    }

    public function setArrayFields($array)
    {
        $this->array_fields = $array;
    }
}

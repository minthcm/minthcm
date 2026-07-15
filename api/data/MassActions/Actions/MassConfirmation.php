<?php

namespace MintHCM\Data\MassActions\Actions;

use MintHCM\Data\MassActions\MassAction;
use MintHCM\Utils\LegacyConnector;

class MassConfirmation extends MassAction
{
    const ICON = 'mdi-check';
    const LABEL = 'LBL_MASS_CONFIRMATION';
    
    public function execute()
    {
        $connector = new LegacyConnector('MassConfirmation', 'modules/WorkSchedules/MassConfirmation.php');
        $connector->schedule(implode(',', $this->ids), '', '');
        return ['success' => true];
    }

    public function hasAccess()
    {
        chdir('../legacy');
        $has_access = \ACLController::checkAccess($this->module_name, 'export', true);
        chdir('../api');
        return $has_access;
    }
}
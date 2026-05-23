<?php

namespace MintHCM\MintCLI\Services;

use RepairAndClear;
use SugarApplication;

class RepairAndRebuildLegacyService
{
    public function run(): void
    {
        if (!defined('sugarEntry')) {
            define('sugarEntry', true);
        }

        include 'include/MVC/preDispatch.php';
        require_once 'include/entryPoint.php';
        ob_start();
        require_once 'include/MVC/SugarApplication.php';
        require_once 'modules/SugarFeed/SugarFeed.php';

        $app = new SugarApplication();
        $app->startSession();

        global $current_user;
        $current_user->retrieve('1');

        require_once 'modules/Administration/QuickRepairAndRebuild.php';
        $repair = new RepairAndClear();

        $autoexecute = true;
        $show_output = false;
        $repair->repairAndClearAll(['clearAll'], [\translate('LBL_ALL_MODULES')], $autoexecute, $show_output);
    }
}

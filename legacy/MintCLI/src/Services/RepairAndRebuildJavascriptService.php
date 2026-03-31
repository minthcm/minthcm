<?php

namespace MintHCM\MintCLI\Services;

use Configurator;
use LanguageManager;
use SugarApplication;

class RepairAndRebuildJavascriptService
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

        $app = new SugarApplication();
        $app->startSession();

        global $current_user;
        $current_user->retrieve('1');

        LanguageManager::removeJSLanguageFiles();
        LanguageManager::clearLanguageCache();

        $_REQUEST['js_admin_repair'] = 'replace';
        $_REQUEST['root_directory'] = getcwd();
        include 'modules/Administration/callJSRepair.php';

        $_REQUEST['js_admin_repair'] = 'concat';
        $_REQUEST['root_directory'] = getcwd();
        include 'modules/Administration/callJSRepair.php';

        $_REQUEST['js_admin_repair'] = 'mini';
        $_REQUEST['root_directory'] = getcwd();
        include 'modules/Administration/callJSRepair.php';

        $_REQUEST['js_admin_repair'] = 'repair';
        $_REQUEST['root_directory'] = getcwd();
        include 'modules/Administration/callJSRepair.php';

        $developerMode = $GLOBALS['sugar_config']['developerMode'];
        $this->setDeveloperMode('developerMode', true);
        include 'rebuild_vtools.php';
        $this->setDeveloperMode('developerMode', $developerMode);
    }

    protected function setDeveloperMode(string $name, $value)
    {
        require_once 'modules/Configurator/Configurator.php';
        $configurator = new Configurator();
        $configurator->config[$name] = $value;
        $configurator->handleOverride();
    }
}

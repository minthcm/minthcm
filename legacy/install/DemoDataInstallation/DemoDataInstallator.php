<?php

namespace DemoDataInstallation;
require_once('install/DemoDataInstallation/Install/DemoDataInstall.php');
require_once('install/DemoDataInstallation/Services/DemoDataService.php');

use DemoDataInstallation\Install\DemoDataInstall;
use DemoDataInstallation\Services\DemoDataService;

class DemoDataInstallator {
    protected $DDInstall;
    const DEMO_DATA_STARTING_FILES_PATH = 'install/demo_data/files';
    const DEMO_DATA_DESTINATION_FILES_PATH = 'upload';

    public function run()
    {
        $this->DDInstall = new DemoDataInstall($GLOBALS['log'], new DemoDataService());
        $tables = $this->DDInstall->getTables();
        foreach ($tables as $table) {
            $this->DDInstall->installTable($table);
        }
        $this->DDInstall->installFiles(
            self::DEMO_DATA_STARTING_FILES_PATH, 
            self::DEMO_DATA_DESTINATION_FILES_PATH
        );
    }
}
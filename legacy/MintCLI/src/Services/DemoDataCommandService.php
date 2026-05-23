<?php

namespace MintHCM\MintCLI\Services;

require_once('legacy/install/DemoDataInstallation/Services/DemoDataService.php');
use DemoDataInstallation\Services\DemoDataService;

class DemoDataCommandService extends DemoDataService
{
    const CONFIG_FILE_PATH = 'legacy/install/DemoDataInstallation/Configs/demo_data.php';
    const SQL_FILES_PATH = 'legacy/install/demo_data';
    const DEMO_DATA_TIMESTAMP_PATH = 'legacy/install/DemoDataInstallation/timestamp.php';
}

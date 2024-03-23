<?php

namespace MintHCM\MintCLI\Installer;

use MintHCM\MintCLI\Services\ConfigOverrideService;
use MintHCM\MintCLI\Services\HtaccessService;
use MintHCM\MintCLI\Services\ServerService;
use MintHCM\MintCLI\Services\ElasticsearchService;

class Installer
{
    const INSTANCE_DIR = './legacy';
    const FRONTEND_DIR = './vue';
    const CLI_DIR = './legacy/MintCLI/src';
    const INSTALL_LOG_FILE = './install.log';

    protected $rootDirectory;
    protected $serverService;
    protected $htaccessService;
    protected $configOverrideService;

    public function __construct($rootDirectory)
    {
        $this->rootDirectory = $rootDirectory;
        $this->serverService = new ServerService();
        $this->htaccessService = new HtaccessService();
        $this->configOverrideService = new ConfigOverrideService();
    }

    public function prepareConfigurationFile($userData)
    {
        copy(self::CLI_DIR . '/Assets/config_si.php', self::INSTANCE_DIR . '/config_si.php');
        $config = file_get_contents(self::INSTANCE_DIR . '/config_si.php');

        $configData = [
            '_DB_HOST_' => $userData['databaseHost'],
            '_DB_PORT_' => $userData['databasePort'],
            '_DB_USER_' => $userData['databaseUsername'],
            '_DB_PASSWORD_' => $userData['databasePassword'],
            '_DB_NAME_' => $userData['databaseName'],
            '_ES_HOST_' => $userData['elasticsearchHost'],
            '_ES_PORT_' => $userData['elasticsearchPort'],
            '_ES_USERNAME_' => $userData['elasticsearchUsername'],
            '_ES_PASSWORD_' => $userData['elasticsearchPassword'],
            '_DB_COLLATION_' => $userData['databaseCollation'],
            '_ELASTIC_HOST_' => $userData['elasticsearchHost'] . ":" . $userData['elasticsearchPort'],
            '_ELASTIC_USER_' => $userData['elasticsearchUsername'],
            '_ELASTIC_PASS_' => $userData['elasticsearchPassword'],
            '_INSTALL_DD_' => $userData['demoData'] ? 'yes' : 'no',
            '_MINT_USER_' => $userData['systemAdminName'],
            '_MINT_PASS_' => $userData['systemAdminPassword'],
            '_SETUP_SYSTEM_NAME_' => 'MintHCM',
            '_SITE_URL_' => $userData['siteUrl'],
        ];
        $config = str_replace(array_keys($configData), array_values($configData), $config);
        file_put_contents(self::INSTANCE_DIR . '/config_si.php', $config);
    }

    public function setupFilesPermissions()
    {
        exec("chmod -R 777 " . self::INSTANCE_DIR);
        exec("chown -R www-data:www-data " . self::INSTANCE_DIR);
    }

    public function setupHtaccess()
    {
        $basePath = $this->serverService->getSystemBasePath($this->rootDirectory);
        $this->htaccessService->setupLegacyHtaccess($basePath);
        $this->htaccessService->setupApplicationHtaccess($basePath);
    }

    public function installBackendApplication()
    {
        chdir(self::INSTANCE_DIR);
        global $argv;
        $argv[1] = 'SilentInstall';
        $argv[2] = 'true';
        include 'install.php';
        chdir('../');
        file_put_contents(self::INSTALL_LOG_FILE, "Installing MintHCM System Core...\n\n");
        $this->setupApiBasePath();
        return true;
    }

    public function installFrontendApplication()
    {
        //chdir(self::FRONTEND_DIR);
        //exec("npm install 2>&1", $installationResult, $installationStatus);
        //chdir('../');
        file_put_contents(self::INSTALL_LOG_FILE, "\n\nInstalling MintHCM UX...\n\n", FILE_APPEND);
        //file_put_contents(self::INSTALL_LOG_FILE, implode("\n", $installationResult), FILE_APPEND);
        // if ($installationStatus !== 0) {
            // return false;
        // }
        return $this->moveFrontendFilesToPublic();
        
        return $this->buildFrontendApplication();
    }

    protected function moveFrontendFilesToPublic(){
        $basePath = $this->serverService->getSystemBasePath($this->rootDirectory);
        $basePath = $basePath == '/' ? '/api' : $basePath . '/api';
        exec("cp -r vue/dist/* ./");
        return true;
    }

    protected function buildFrontendApplication()
    {
        // chdir(self::FRONTEND_DIR);
        // exec("npm run build:repo 2>&1", $buildingResult, $buildingStatus);
        // chdir('../');
        // file_put_contents(self::INSTALL_LOG_FILE, "\n\nBuilding MintHCM UX...\n\n", FILE_APPEND);
        // file_put_contents(self::INSTALL_LOG_FILE, implode("\n", $buildingResult), FILE_APPEND);
        // if ($buildingStatus !== 0) {
            // return false;
        // }
        return true;
    }

    public function setupApiBasePath()
    {
        $basePath = $this->serverService->getSystemBasePath($this->rootDirectory);
        $basePath = $basePath == '/' ? '/api' : $basePath . '/api';
        $originalConfigFile = file_get_contents('./api/app/Config/AppConfig.php');
        $pattern = '/return .*?api.*?\n/i';
        $replacement = "return '$basePath';\n";
        $configFile = preg_replace($pattern, $replacement, $originalConfigFile);
        file_put_contents('./api/app/Config/AppConfig.php', $configFile);
    }

    public function reindexElastic()
    {
        $elasticSearchService = new ElasticSearchService;
        $elasticSearchService->reindexElastic();
    }

    public function setupApiConfigOverride(array $userData): void
    {
        $this->configOverrideService->writeConfigOverride('./api/configs/mint/config_override.php', [
            'database' => [
                'host' => $userData['databaseHost'],
                'port' => $userData['databasePort'],
                'user' => $userData['databaseUsername'],
                'password' => $userData['databasePassword'],
                'dbname' => $userData['databaseName'],
            ],
            'search' => [
                'engines' => [
                    'ElasticSearch' => [
                        [
                            'host' => $userData['elasticsearchHost'],
                            'port' => $userData['elasticsearchPort'],
                            'user' => $userData['elasticsearchUsername'],
                            'pass' => $userData['elasticsearchPassword'],
                        ]
                    ]
                ],
            ],
        ]);
    }
}

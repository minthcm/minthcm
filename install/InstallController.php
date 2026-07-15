<?php

require_once 'InstallService.php';
require_once 'helpers/VersionValidator.php';
require '../legacy/MintCLI/vendor/autoload.php';
require '../api/vendor/autoload.php';
require './Installer.php';

use MintHCM\MintCLI\Services\DatabaseService;
use MintHCM\MintCLI\Services\ElasticsearchService;

#[\AllowDynamicProperties]
class InstallController
{
    const LAST_BACKEND_STEP = 19;

    private $service;
    private $elasticService;

    public function __construct()
    {
        $this->service = new InstallService();
        $this->elasticService = new ElasticsearchService();
    }

    public function redirectToInstaller()
    {
        http_response_code(307);
        die;
    }

    public function getInitialData()
    {
        $installStatus = $this->service->readMintInstallStatus();
        $isInstalling = !empty($installStatus['step']);

        require_once '../legacy/minthcm_version.php';
        return [
            'version' => $minthcm_version,
            'license' => trim(file_get_contents('../LICENSE')),
            'environment' => (new VersionValidator)->runValidations(),
            'isInstalling' => (bool)$isInstalling,
            'installError' => $installStatus['error'] ?? null,
        ];
    }

    public function validateDb($data)
    {
        $dbService = new DatabaseService();

        $result = $dbService->testDBname($data['dbname']);
        if (!$result['status']) {
            return ["status" => 0, "message" => $result['message']];
        }
        $result = $dbService->testConnection($data['host'], $data['port'], $data['username'], $data['password']);
        if (!$result['status']) {
            return ["status" => 0, "message" => $result['message']];
        }
        if (!$dbService->testDatabaseExistance($data['host'], $data['port'], $data['username'], $data['password'], $data['dbname'])['status']) {
            return ["status" => 0, "message" => "Database with that name already exists"];
        }
        return ["status" => 1, "message" => "ok"];
        
    }

    public function validateElastic($data)
    {
        $response = $this->elasticService->testConnection($data['host'], $data['port'], $data['username'], $data['password']);
        if (!$response['status']) {
            $message = $response['message'] ?? 'Elastic connection failed';
            return ["status" => 0, "message" => $message];
        }

        $capacityResponse = $this->elasticService->checkShardCapacity($data['host'], $data['port'], $data['username'], $data['password']);
        if (!$capacityResponse['status']) {
            return ["status" => 0, "message" => $capacityResponse['message']];
        }

        return ["status" => 1, "message" => "ok"];
    }

    public function checkStatus()
    {
        return json_decode(file_get_contents('./assets/status.json'), true);
    }

    public function install($data)
    {
        $cfg = [
            'databaseHost' => $data['db']['host'],
            'databasePort' => $data['db']['port'],
            'databaseUsername' => $data['db']['username'],
            'databasePassword' => $data['db']['password'],
            'databaseName' => $data['db']['dbname'],
            'databaseCollation' => $data['db']['collation'],

            'elasticsearchHost' => $data['elastic']['host'],
            'elasticsearchPort' => $data['elastic']['port'],
            'elasticsearchUsername' => $data['elastic']['username'],
            'elasticsearchPassword' => $data['elastic']['password'],
            
            'demoData' => $data['site']['demodata'],
            'systemAdminName' => $data['site']['username'],
            'systemAdminPassword' => $data['site']['password'],
            'siteUrl' => $data['site']['url'],
        ];

        try {
            chdir('../');

            $installer = new Installer($_SERVER['DOCUMENT_ROOT']);
            $installer->prepareConfigurationFile($cfg);

            $this->service->clearStatusJson();

            $this->service->setMintInstallStatus(1, "Setting up Doctrine...");

            $installer->setupApiConfigOverride($cfg);

            $this->service->setMintInstallStatus(2, "Modifying file permissions...");
            $installer->setupFilesPermissions();

            $this->service->setMintInstallStatus(3, "Starting backend installation...");
            $installer->installBackendApplication($cfg);

            $lastStep = $this->service->readMintInstallStatus();
            if($lastStep["step"] != self::LAST_BACKEND_STEP){
                $this->service->setMintInstallError("Backend installation failed at step " . $lastStep["step"]);
                return ["status" => 0, "message" => "Installation failed.", "error" => "Backend installation failed"];
            }

            // Sudden progress jump due to backend doing a lot of other stuff
            $this->service->setMintInstallStatus(20, "Starting frontend installation...");
            $installer->installFrontendApplication();

            $this->service->setMintInstallStatus(21, "Setting up file permissions...");
            $installer->setupFilesPermissions();

            $this->service->setMintInstallStatus(22, "Reindexing ElasticSearch");
            $installer->reindexElastic();

            $this->service->setMintInstallStatus(23, "Creating OAuth2 keys and Frontend client...");
            $installer->setupOAuth2();

            $this->service->setMintInstallStatus(24, "Setting up htacess...");
            $installer->setupHtaccess();

            return ["status" => 1, "message" => "Installation finished successfully."];
        } catch (\Exception $e) {
            $this->service->setMintInstallError($e->getMessage());
            return ["status" => 0, "message" => "Installation failed.", "error" => $e->getMessage()];
        }
    }
}

<?php

namespace DemoDataInstallation\Services;

class DemoDataService
{
    protected $config;
    protected $tables;
    protected $schema_name;

    const CONFIG_FILE_PATH = 'install/DemoDataInstallation/Configs/demo_data.php';
    const MINT_CONFIG_FILE_PATH = 'config.php';
    const SQL_FILES_PATH = 'install/demo_data';

    public function __construct()
    {   
        $this->loadConfig();
        $this->loadTablesWithSQLFilesInfo();
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getTables() {
        return $this->tables;
    }

    public function getSchemaName() {
        return $this->schema_name;
    }
    protected function loadConfig(){
        require static::CONFIG_FILE_PATH;
        $this->config = $config;

        require static::CONFIG_FILE_PATH;
        $this->schema_name = $sugar_config['dbconfig']['db_name'];
    }

    protected function loadTablesWithSQLFilesInfo() {
        foreach(array_filter(glob(static::SQL_FILES_PATH.'/*.sql'), 'is_file') as $file) {
            $this->tables[] = 
            [
                'file_name' => pathinfo($file, PATHINFO_FILENAME), 
                'file_path' => $file,
            ];
        }
    }
}

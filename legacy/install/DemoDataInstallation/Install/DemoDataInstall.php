<?php

namespace DemoDataInstallation\Install;

use Datetime;

class DemoDataInstall 
{
    protected $mysql_connection;
    public $logger;
    const DATE_TRESHOLD = '-3 days';
    protected $DDService;
    
    public function __construct($logger_interface, $demo_data_service, $db_connection = null)
    {
        $this->mysql_connection = $db_connection ?? $GLOBALS['db'];
        $this->logger = $logger_interface;
        $this->DDService = $demo_data_service;
    }

    public function getTables()
    {
        return $this->DDService->getTables();
    }

    protected function pushDates($table_name)
    {  
        $sql = "SELECT DISTINCT(COLUMN_NAME) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '{$table_name}' AND (DATA_TYPE = 'datetime' OR DATA_TYPE = 'date') AND TABLE_SCHEMA='{$this->DDService->getSchemaName()}'";
        $result = $this->mysql_connection->query($sql);
        if (!$result) {
            $this->logger->error("SQL error \n {$this->mysql_connection->error}");
            return;
        }

        $fields = $result->fetch_all(MYSQLI_ASSOC);
        foreach($fields as $field) {
		    $sql = "SELECT MIN({$field['COLUMN_NAME']}) FROM {$table_name}";
            $result = $this->mysql_connection->query($sql);
            if (!$result) {
                $this->logger->error("SQL error \n {$this->mysql_connection->error}");
                continue;
            }

            $date_start = $result->fetch_assoc();
            $date_start = reset($date_start);

            if(!$date_start){
                continue;
            }

            $today = new DateTime();
            $date_start_treshold = $today->modify(self::DATE_TRESHOLD);
            $date_start = new DateTime($date_start);
            if($date_start_treshold > $date_start){
                $diff = $date_start_treshold->diff($date_start);
                $sql = "UPDATE {$table_name} SET {$field['COLUMN_NAME']} = DATE_ADD({$field['COLUMN_NAME']}, INTERVAL {$diff->format('%d')} DAY)";
                $this->mysql_connection->query($sql);
                if (!$result) {
                    $this->logger->error("SQL error \n {$this->mysql_connection->error}");
                    return;
                }
            }
        }
        
    }

    public function installTable($table)
    {
        $sql_file = file_get_contents($table['file_path']);

        if(!empty($sql_file))
        {
            $sql_file = explode(";\n", $sql_file);
            foreach($sql_file as $sql)
            {
                if(!empty($sql)){
                    $result = $this->mysql_connection->query($sql);
                    if (!$result) {
                        $this->logger->error("SQL error \n {$this->mysql_connection->error}");
                        return;
                    }
                }
            }
            $this->pushDates($table['file_name']);
        }
        
        
    }

    public function installFiles($demoDataStartingFilesPath, $demoDataDestinationFilesPath)
    {
        $config = $this->DDService->getConfig();
        if (!file_exists($demoDataStartingFilesPath))
        {
            return;
        }

        foreach($config['files'] as $file_config){
            $sql = "SELECT CONCAT({$file_config['column']}, '{$file_config['postfix']}') AS file_name FROM {$file_config['table']}";
            $result = $this->mysql_connection->query($sql);
            if (!$result) {
                $this->logger->error("SQL error \n {$sql}");
                return;
            }
            while ($row = $result->fetch_assoc()) {
                $sqls[] = $row['file_name'];
                if(!file_exists($demoDataStartingFilesPath."/{$row['file_name']}")){
                    $this->logger->warning("File {$row['file_name']} does not exist in the demo data directory.");
                } elseif (!copy($demoDataStartingFilesPath."/{$row['file_name']}", $demoDataDestinationFilesPath."/{$row['file_name']}")){
                    $this->logger->error("Failed to copy file {$row['file_name']}");
                }
            }
        }
    }

}

<?php

namespace MintHCM\MintCLI\Commands;

use MintHCM\MintCLI\Services\DatabaseService;
use MintHCM\MintCLI\Services\DemoDataCommandService;
use MintHCM\MintCLI\Services\VardefsService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DemoDataDumpCommand extends Command
{
    protected static $defaultName = 'demodata:dump';
    protected static $defaultDescription = 'Demo Data Dump';
    protected $mysql_connection;
    protected $io;

    protected static $demoDataDestinationPath = 'legacy/install/demo_data';
    protected static $demoDataDestinationFilesPath = 'legacy/install/demo_data/files';

    protected function configure()
    {
        $this
            ->setHelp('This command allows you to dump data from system to file.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);

        $connection_detail = $this->getDatabaseConnection();
        if (!$connection_detail['status']) {
            $this->io->error($connection_detail['message']);
            return Command::FAILURE;
        }
        $this->mysql_connection = $connection_detail['connection'];

        $this->io->title("Provide all of the information to start dumping data.");
        $userData = $this->collectUserData($input, $output);

        $this->io->section("Demo data is dumping. It will take a while.");
        $this->dumpDemoData();
        if ($userData['withFiles']) {
            $this->io->section('Dumping files...');
            $this->dumpFiles();
        }

        $this->io->success('Demo Data has been dumped successfuly');
        return Command::SUCCESS;
    }

    private function collectUserData($input, $output)
    {
        $QH = $this->getHelper('question');

        $question = new \MintHCM\MintCLI\Questions\WithFiles($QH, $input, $output);
        $withFiles = $question->ask();

        return [
            'withFiles' => $withFiles,
        ];
    }
    protected function dumpFiles()
    {
        $DDService = new DemoDataCommandService();
        $config = $DDService->getConfig();
        if (!file_exists(self::$demoDataDestinationFilesPath)) {
            mkdir(self::$demoDataDestinationFilesPath, 0777, true);
        }
        foreach($config['files'] as $file_config){
            $sql = "SELECT CONCAT({$file_config['column']}, '{$file_config['postfix']}') AS file_name FROM {$file_config['table']} WHERE deleted=0";
            $result = $this->mysql_connection->query($sql);
            if (!$result) {
                $this->io->error("SQL error \n {$this->mysql_connection->error}");
                return;
            }
            while ($row = $result->fetch_assoc()) {
                $sqls[] = $row['file_name'];
                if(!copy("legacy/upload/{$row['file_name']}",self::$demoDataDestinationFilesPath."/{$row['file_name']}")){
                    $this->io->error("Failed to copy file {$row['file_name']}");
                }
            }
        }
    }
    protected function dumpDemoData()
    {
        $VardefsService = new VardefsService();
        $tables = $this->createTablesArray();
        $step_count = count($tables);
        $this->io->progressStart($step_count);
        foreach ($tables as $table) {
            $this->dumpTable($table, $VardefsService);
            $this->io->progressAdvance();
        }
        $this->io->progressFinish();
    }

    protected function createTablesArray()
    {
        $sql = 'SHOW TABLES';
        $result = $this->mysql_connection->query($sql);
        if (!$result) {
            $this->io->error("SQL error \n {$this->mysql_connection->error}");
            return;
        }

        while($tabel = $result->fetch_array())
        {
            $all_tables[] = $tabel[0];
        }

        $DDService = new DemoDataCommandService();
        $config = $DDService->getConfig();

        return array_diff($all_tables, $config['blacklist_tables']);
    }

    protected function dumpTable($table, $VardefsService)
    {
        $fields = $VardefsService->getFields($table);
        if (empty($fields)) {
            $sql = $this->getDataFromDatabseDirectly($table);
        } else {
            $sql = $this->getDataFromDatabaseByVardefs($table, $fields);
        }
        if(!$sql){
            return;
        }
        $result = $this->mysql_connection->query($sql);
        if (!$result) {
            $this->io->error("Table: {$table}, SQL error \n {$this->mysql_connection->error}");
            return;
        }
        $sqls = [];
        while ($row = $result->fetch_assoc()) {
            $sqls[] = $row['insert_statement'];
        }
        $this->writeToFile($table, $sqls);
    }

    protected function writeToFile($table, $sqls)
    {
        if (!file_exists(self::$demoDataDestinationPath)) {
            mkdir(self::$demoDataDestinationPath, 0777, true);
        }
        if(!empty($sqls))
        {
            $file = fopen(self::$demoDataDestinationPath . "/{$table}.sql", "w");
            foreach ($sqls as $sql) {
                $sql = $this->repairQueryString($sql);
                fwrite($file, $sql . "\n");
            }
            fclose($file);
        }
    }

    protected function repairQueryString($query_string)
    {
        $exploded_query = explode("VALUES", $query_string);
        $exploded_query[1] = $this->IgnoreJSONCommas($exploded_query[1]);
        $exploded_query[1]  = explode(',', $exploded_query[1]);
        foreach($exploded_query[1] as $key => &$query)
        {
            if($key == 0) {
                $query = substr($query, 2);
            }

            if($key == count($exploded_query[1]))
            {
                $query = substr($query, -2);
            }

            if(substr($query, -1) == "'" && substr($query, 0, 1) == "'")
            {
                $query = substr($query, 1, -1);
                $query = $this->mysql_connection->real_escape_string($query);
                $query = "'".$query."'";
            }

            if($key == 0) {
                $query = " (".$query;
            }

            if($key == count($exploded_query[1]))
            {
                $query = $query.");";
            }
            
        }
        $exploded_query[1] = implode(',', $exploded_query[1]);
        $exploded_query[1] = str_replace('*delimiter*', ',', $exploded_query[1]); 
        return implode("VALUES", $exploded_query);
    }

    protected function IgnoreJSONCommas($string) 
    {
        $comma_pos = strpos($string, ',');
        while($comma_pos !== false)
        {
            if($string[$comma_pos-1] !== "'" || $string[$comma_pos-1] !== "'")
            {
                $string = substr($string, 0, $comma_pos).'*delimiter*'.substr($string, $comma_pos+1);
            }
            $comma_pos = strpos($string, ',', $comma_pos+1);
        }

        return $string;
    }

    protected function getDataFromDatabseDirectly($table)
    {
        $fields_in_db = [];
        $sql = "DESCRIBE {$table}";
        $result = $this->mysql_connection->query($sql);
        if(!$result){
            $this->io->warning("Table {$table} does not exist in the database.");
            return false;
        }
        $fields = $result->fetch_all(MYSQLI_ASSOC);
        foreach ($fields as $field) {
            $fields_in_db[] = $field['Field'];
        }
        return $this->getInsertStatement($table, $fields_in_db);
    }

    protected function getDataFromDatabaseByVardefs($table, $fields)
    {
        $fields_in_db = [];
        foreach ($fields as $field) {
            if (isset($field['source']) && in_array($field['source'],[ 'non-db', 'function', 'custom_fields'])) {
                continue;
            }
            $fields_in_db[] = $field['name'];
        }
        $sql = "DESCRIBE {$table}";
        $result = $this->mysql_connection->query($sql);
        if(!$result){
            $this->io->warning("Table {$table} does not exist in the database.");
            return false;
        }
        $sql = $this->getInsertStatement($table, $fields_in_db);
        if($table == 'users'){
            $sql .= " WHERE id != '1'";
        }
        return $sql;
    }

    protected function getInsertStatement($table, $columns)
    {
        $insert_into_columns = implode(", ", $columns);
        $value_columns = array_map(function ($field) {
            return "IFNULL(CONCAT('\'', {$field}, '\''), 'NULL')";
        }, $columns);
        $value_columns_imploded = implode(",',',", $value_columns);
        $sql = "SELECT
        CONCAT(
            'REPLACE INTO {$table} ({$insert_into_columns}) VALUES (',$value_columns_imploded, ');'
        ) AS insert_statement
    FROM
    {$table}";
        return $sql;
    }

    protected function getDatabaseConnection()
    {
        include 'legacy/config.php';
        include 'legacy/config_override.php';
        $DBService = new DatabaseService();
        return $DBService->getConnection($sugar_config['dbconfig']['db_host_name'], $sugar_config['dbconfig']['db_user_name'], $sugar_config['dbconfig']['db_password'], $sugar_config['dbconfig']['db_name'], $sugar_config['dbconfig']['db_port']);
    }
}

<?php
namespace MintHCM\MintCLI\Installer;

use Exception;
use MintHCM\MintCLI\Installer\Exceptions\DBConnectionException;
use MintHCM\MintCLI\Installer\Exceptions\DBCreationException;
use MintHCM\MintCLI\Installer\Exceptions\ModuleTableCreationException;
use MintHCM\MintCLI\Installer\Exceptions\RelationshipTableCreationException;
use MintHCM\MintCLI\Installer\Support\InstallerHelper;

class DatabaseInstaller
{
    protected $db;
    protected $db_config;
    protected InstallerHelper $helper;

    public function setHelper(InstallerHelper $helper)
    {
        $this->helper = $helper;
    }


    public function initiateDBManager()
    {
        $setup_db_type = 'mysql';
        $setup_db_manager = \DBManagerFactory::getManagerByType($setup_db_type);
        $this->db = $this->createDbManagerInstance($setup_db_type,$setup_db_manager);
    }

    public function getDb()
    {
        return $this->db;
    }

    public function setDbConfig($db_config)
    {
        $this->db_config = $db_config;
    }
    
    private function createDbManagerInstance($setup_db_type, $setup_db_manager)
    {
        return \DBManagerFactory::getTypeInstance($setup_db_type, array("db_manager" => $setup_db_manager));
    }

    public function handleDbCreateDatabase($setup_db_database_name)
    {
        if (!empty($_SESSION['setup_db_options'])) {
            $this->db->setOptions($_SESSION['setup_db_options']);
        }
        $dbconfig = $this->db_config;
        unset($dbconfig["db_name"]);
        try {
            $this->db->connect($dbconfig, true);
        } 
        catch(\Exception $ex) {
            throw new DBConnectionException("Could not establish connection to database", 144 , $ex);
        }
        
        try{
            $this->db->createDatabase($setup_db_database_name);
        } 
        catch(\Exception $ex) {
            throw new DBCreationException("Could not create database with name ". $setup_db_database_name, 233 , $ex);
        }

        $this->db->disconnect();

        try {
            $this->db->connect($this->db_config, true);
        } 
        catch(\Exception $ex) {
            throw new DBConnectionException("Could not establish connection to database", 377 , $ex);
        }        
    }
    
    public function createBeanTables()
    {
        global $beanFiles,$mod_strings;

        foreach ($beanFiles as $bean => $file) {
            require_once($file);
        }

        $focus = 0;
        $processed_tables = array(); // for keeping track of the tables we have worked on
        $empty = array();

        // add non-module Beans to this array to keep the installer from erroring.
        $nonStandardModules = [ //'Tracker',
        ];
        
        /**
         * loop through all the Beans and create their tables
         */
        $this->helper->setInstallStatus([6, "Setting up basic bean tables..."]);
        installLog("looping through all the Beans and create their tables");
        //start by clearing out the vardefs
        \VardefManager::clearVardef();
        
        foreach ($beanFiles as $bean => $file) {
            $doNotInit = ['\Scheduler', '\SchedulersJob', '\ProjectTask', '\jjwg_Maps','\jjwg_Address_Cache', '\jjwg_Areas', '\jjwg_Markers'];

            if (in_array($bean, $doNotInit)) {
                $focus = new $bean(false);
            } else {
                $focus = new $bean();
            }

            if ($bean == 'Configurator') {
                continue;
            }

            $table_name = $focus->table_name;
            $this->helper->setInstallStatus([7, "Creating the database table..."]);
            installLog("processing table " . $table_name);
            try {
                // check to see if we have already setup this table
                if (!in_array($table_name, $processed_tables)) {
                    if (!file_exists("modules/" . $focus->module_dir . "/vardefs.php")) {
                        continue;
                    }
                    if (!in_array($bean, $nonStandardModules)) {
                        require_once("modules/" . $focus->module_dir . "/vardefs.php"); // load up $dictionary
                        if (isset($dictionary[$focus->object_name]['table']) && $dictionary[$focus->object_name]['table'] == 'does_not_exist') {
                            continue; // support new vardef definitions
                        }
                    } else {
                        continue; //no further processing needed for ignored beans.
                    }

                    // table has not been setup...we will do it now and remember that
                    $processed_tables[] = $table_name;
                    $focus->db->database = $this->db->database; // set db connection so we do not need to reconnect

                    if (create_table_if_not_exist($focus)) {
                        installLog("creating table " . $focus->table_name);
                    }
                    installLog("creating Relationship Meta for " . $focus->getObjectName());
                    \SugarBean::createRelationshipMeta($focus->getObjectName(), $this->db, $table_name, $empty, $focus->module_dir);

                } // end if()
            } 
            catch(\Exception $ex) {
                throw new ModuleTableCreationException("Could not create table ". $table_name, 610 , $ex);
            }  
        }
        ////    END TABLE STUFF
    }

    public function createRelationshipTables($rel_dictionary)
    {
        try {
            $this->helper->setInstallStatus([8, "Setting up relationships..."]);

            ksort($rel_dictionary);
            foreach ($rel_dictionary as $rel_name => $rel_data) {
                $table = $rel_data['table'];
                if (!$this->db->tableExists($table)) {
                    $this->db->createTableParams($table, $rel_data['fields'], $rel_data['indices']);
                }
                \SugarBean::createRelationshipMeta($rel_name, $this->db, $table, $rel_dictionary, '');
            }
        } 
        catch(\Exception $ex) {
            throw new RelationshipTableCreationException("Could not create relationship table ".$table, 987 , $ex);
        }  
    }
}
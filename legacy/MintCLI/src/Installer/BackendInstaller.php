<?php
namespace MintHCM\MintCLI\Installer;
require 'legacy/MintCLI/vendor/autoload.php';
require 'api/vendor/autoload.php';
use MintHCM\MintCLI\Installer\Support\InstallerHelper;
use MintHCM\MintCLI\Installer\Exceptions\CreatingDefaulSettingsException;
use MintHCM\MintCLI\Installer\Exceptions\CreatingDirectoriesException;
use MintHCM\MintCLI\Installer\Exceptions\RebuildingViewtoolsException;
use MintHCM\MintCLI\Installer\Exceptions\DictionariesKreportsException;
use MintHCM\MintCLI\Installer\Exceptions\DemoDataInstallationException;
use MintHCM\MintCLI\Installer\Exceptions\BackendFinalizationException;

#[\AllowDynamicProperties]
class BackendInstaller
{
    protected $db;
    private $startTime;
    protected $current_language;
    protected FileManager $file_manager;
    protected InstallerHelper $helper;
    protected BaseConfigurationInstaller $base_configuration;
    protected DatabaseInstaller $db_installer;

    public function __construct(protected array $install_configuration)
    {
        $this->file_manager = new FileManager();
        $this->helper = new InstallerHelper($this);
        $this->base_configuration = new BaseConfigurationInstaller();
        $this->base_configuration->setHelper($this->helper);
        $this->db_installer = new DatabaseInstaller();
        $this->db_installer->setHelper($this->helper);
    }

    public function getDbConfigArr()
    {
        $dbconfig = array();
        $dbconfig["db_host_name"] = $this->install_configuration['databaseHost'];
        $dbconfig["db_host_instance"] =  null;
        $dbconfig["db_user_name"] = $this->install_configuration['databaseUsername'];
        $dbconfig["db_password"] = $this->install_configuration['databasePassword'];
        $dbconfig["db_port"] = $this->install_configuration['databasePort'];
        $dbconfig["db_name"] = $this->install_configuration['databaseName'];
        return $dbconfig;
    }

    public function process()
    {
        $this->startTime = microtime(true);
        $this->helper->preInstallSettings();
        $this->helper->includeRequiredClasses();
        $GLOBALS['locale'] = new \Localization();
        $GLOBALS['log'] = \LoggerManager::getLogger();
        $GLOBALS['timedate'] = \TimeDate::getInstance();
        $trackerManager = \TrackerManager::getInstance();
        $trackerManager->pause();

        $this->db_installer->initiateDBManager();
        
        $this->current_language = $this->helper->setLanguages();

        //check to see if the script files need to be rebuilt, add needed variables to request array
        $_REQUEST['root_directory'] = getcwd();
        $_REQUEST['js_rebuild_concat'] = 'rebuild';

        //Set whether the install is silent or not
        $install_script = $silent = $silentInstall = true;
        
        $setup_sugar_version = $minthcm_version ?? '3.1.5'; // PHP compatibility

        global $mod_strings;       

        $this->db_installer->setDbConfig($this->getDbConfigArr());


        if (empty($sugar_config['cache_dir']) && !empty($_SESSION['cache_dir'])) {
            $sugar_config['cache_dir'] = $_SESSION['cache_dir'];
        }
        if (!isset($_SESSION['setup_system_name']) || empty($_SESSION['setup_system_name'])) {
            $_SESSION['setup_system_name'] = 'MintHCM';
        }
        if (!isset($_SESSION['setup_site_session_path']) || empty($_SESSION['setup_site_session_path'])) {
            $_SESSION['setup_site_session_path'] = (isset($sugar_config['session_dir'])) ? $sugar_config['session_dir'] : '';
        }
        if (!isset($_SESSION['setup_site_log_dir']) || empty($_SESSION['setup_site_log_dir'])) {
            $_SESSION['setup_site_log_dir'] = (isset($sugar_config['log_dir'])) ? $sugar_config['log_dir'] : '.';
        }
        if (!isset($_SESSION['setup_site_guid']) || empty($_SESSION['setup_site_guid'])) {
            $_SESSION['setup_site_guid'] = (isset($sugar_config['unique_key'])) ? $sugar_config['unique_key'] : '';
        }
        if (!isset($_SESSION['cache_dir']) || empty($_SESSION['cache_dir'])) {
            $_SESSION['cache_dir'] = isset($sugar_config['cache_dir']) ? $sugar_config['cache_dir'] : 'cache/';
        }

        $si_errors = false;
        pullSilentInstallVarsIntoSession();

        if (!empty($_SESSION['setup_site_specify_guid']) && !empty($_SESSION['setup_site_guid'])) {
            $this->install_configuration['setup_site_specify_guid'] = $sugar_config['unique_key'] = $_SESSION['setup_site_guid'];
        } else {
            $this->install_configuration['setup_site_specify_guid'] = $sugar_config['unique_key'] = md5(create_guid());
        }

        require_once('jssource/minify.php');

        try {
            $this->file_manager->createInstanceSubDirectories();
            // cache dir
            $this->file_manager->createCacheDirectories();
        }
        catch(\Exception $ex) {
            throw new CreatingDirectoriesException("Problem with creating directories ", 4181 , $ex);
        }

        //----------------------------------------------------

        $this->helper->setInstallStatus([4, 'Installing...']);
        
        global $cache_dir;
        $cache_dir = sugar_cached("");
        $rel_dictionary = $this->dictionary; // sourced by modules/TableDictionary.php

        $this->setGlobalVariables();
        //added because some of included files expect these to be set in the global scope, we can refactor those files later to pull from the installer configuration instead of globals
        global $setup_db_admin_password,$setup_db_admin_user_name,$setup_db_create_database, $setup_db_create_sugarsales_user,
        $setup_db_database_name,$setup_db_drop_tables,$setup_db_host_instance,$setup_db_port_num,$setup_db_host_name,$demoData,
        $setup_db_sugarsales_password,$setup_db_sugarsales_user,$setup_site_admin_user_name,$setup_site_admin_password,
        $setup_site_guid,$setup_site_url,$setup_site_host_name,$setup_site_log_dir,$setup_site_log_file,$setup_site_log_level,
        $setup_site_session_path;

        $this->helper->setInstallStatus([5, 'Setting up basic configuration...'],"calling handleSugarConfig()");

        $setup_site_log_level = 'fatal';
        handleSugarConfig();

        (new ServerConfigManager)->configureServer();

        installLog("calling handleDbCreateDatabase()");
        $this->db_installer->handleDbCreateDatabase($setup_db_database_name);

        $this->db = $this->db_installer->getDb();
        
        $this->db_installer->createBeanTables();
        ////    START RELATIONSHIP CREATION
        $this->db_installer->createRelationshipTables($this->dictionary);
        
        $this->helper->setInstallStatus([9, "Installing basic settings..."]);

    
        $this->helper->setInstallStatus([],"Begin creating Defaults - insert defaults into config table");

        try {
            insert_default_settings();

            installLog($mod_strings['LBL_PERFORM_DEFAULT_USERS']);
            create_default_users();

            // default OOB schedulers

            installLog($mod_strings['LBL_PERFORM_DEFAULT_SCHEDULER']);
            $scheduler = \BeanFactory::newBean('Schedulers');
            $scheduler->rebuildDefaultSchedulers();

            installDelegationPDFTemplate();

            install_mint_dashlets($this->db);
        }
        catch(\Exception $ex) {
            throw new CreatingDefaulSettingsException("Could not creat default User/Schedulers/Reports/Dashlets ", 6765 , $ex);
        }

        try {

        $this->helper->setInstallStatus([10, "Rebuilding view tools..."],$mod_strings['LBL_PERFORM_VIEW_TOOLS']);
        rebuildWithViewTools(false);
        }
        catch(\Exception $ex) {
            throw new RebuildingViewtoolsException("Rebuilding Viewtools problem ", 10946 , $ex);
        }

        try{
            // Enable Sugar Feeds and add all feeds by default
            installLog("Enable SugarFeeds");

            $this->helper->setInstallStatus([11, "Enabling SugarFeeds..."]);
            enableSugarFeeds();

            include 'install/seed_data/Advanced_Password_SeedData.php';

            installLog("Installation has completed *********");

            $this->helper->setInstallStatus([12, "Installing default dashlets..."]);

            $this->base_configuration->configureDefaultTabs();
            $this->base_configuration->installDefaultDashlets();
            
            include_once 'install/suite_install/suite_install.php';
            
            $this->helper->setInstallStatus([13, "Adding missing config information..."]);
            //install default KReports
            installDefaultKReports();
            //install default Dictionaries
            installDefaultDictionaries();

        
        }
        catch(\Exception $ex) {
            throw new DictionariesKreportsException("Feeds / Kreports / Dictionaries problem", 28657 , $ex);
        }

        try{
            $this->base_configuration->installDemoData($this->install_configuration['demoData']);
        }
        catch(\Exception $ex) {
            throw new DemoDataInstallationException("Demo Data instalation problem ", 46368 , $ex);
        }

        try{
            
            $this->helper->setInstallStatus([15, "Deploying mint dashlets..."]);
            deploy_mint_dashlets();

            handleSugarConfig(true);

            
            installDefaultRoles();

            // restore previously posted form
            $_REQUEST = array_merge($_REQUEST, $_SESSION);
            $_POST = array_merge($_POST, $_SESSION);

            $this->helper->setInstallStatus([16, 'Finishing backend installation...'],'Save configuration settings..');

            $this->base_configuration->saveConfigurationSettings();

            $this->base_configuration->deleteCurrencySetAsDefault();

            $this->helper->setInstallStatus([17, "Setting up user settings..."]);
            installLog('Save user settings..');

            // load admin
            $this->helper->loadAdminUser();
            global $current_user;
            

            // set local settings -  if neccessary you can set here more fields as named in User module / EditView form...
            if (isset($_REQUEST['timezone']) && $_REQUEST['timezone']) {
                $current_user->setPreference('timezone', $_REQUEST['timezone']);
            }

            $this->helper->setInstallStatus([18, "Setting up ACLs..."]);
            $this->base_configuration->installACLActions();


            $this->helper->setInstallStatus([19, "Backend installation successful - continuing..."]);
            $endTime = microtime(true);
            $deltaTime = $endTime - $this->startTime;

        }
        catch(\Exception $ex) {
            throw new BackendFinalizationException("Problem with final configurations ", 75025 , $ex);
        }
    }

    private function setGlobalVariables()
    {
        global $setup_db_admin_password,$setup_db_admin_user_name,$setup_db_create_database, $setup_db_create_sugarsales_user,
        $setup_db_database_name,$setup_db_drop_tables,$setup_db_host_instance,$setup_db_port_num,$setup_db_host_name,$demoData,
        $setup_db_sugarsales_password,$setup_db_sugarsales_user,$setup_site_admin_user_name,$setup_site_admin_password,
        $setup_site_guid,$setup_site_url,$setup_site_host_name,$setup_site_log_dir,$setup_site_log_file,$setup_site_log_level,
        $setup_site_session_path;
        $setup_db_admin_password = $this->install_configuration['databasePassword'];
        $setup_db_admin_user_name = $this->install_configuration['databaseUsername'];
        $setup_db_create_database = true;
        $setup_db_create_sugarsales_user = false;
        $setup_db_database_name = $this->install_configuration['databaseName'];
        $setup_db_drop_tables = false;
        $setup_db_host_instance = $_SESSION['setup_db_host_instance'];
        $setup_db_port_num = $this->install_configuration['databasePort'];
        $setup_db_host_name = $this->install_configuration['databaseHost'];
        $demoData = $this->install_configuration['demoData'];
        $setup_db_sugarsales_password = $this->install_configuration['databasePassword'];
        $setup_db_sugarsales_user = $this->install_configuration['databaseUsername'];
        $setup_site_admin_user_name = $this->install_configuration['systemAdminName']; 
        $setup_site_admin_password = $this->install_configuration['systemAdminPassword']; 
        $setup_site_guid = $this->install_configuration['setup_site_specify_guid'];
        $setup_site_url = $this->install_configuration['siteUrl'];
        $parsed_url = parse_url($setup_site_url); 
        $setup_site_host_name = $parsed_url['host'];
        $setup_site_log_dir = isset($_SESSION['setup_site_custom_log_dir']) ? $_SESSION['setup_site_log_dir'] : '.'; 
        $setup_site_log_file = 'minthcm.log';  // may be an option later
        $setup_site_log_level = 'fatal';
        $setup_site_session_path = $_SESSION['setup_site_custom_session_path'] ?? '';
    }

}

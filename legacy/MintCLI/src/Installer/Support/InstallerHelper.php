<?php
namespace MintHCM\MintCLI\Installer\Support;
use MintHCM\MintCLI\Installer\BackendInstaller;
class InstallerHelper
{
    protected BackendInstaller $installer; 
   
    public function setInstallStatus(array $mint = [] , string $install_log = '')
    {
        if(!empty($mint))
        {
            setMintInstallStatus(...$mint);
        }
        if(!empty($install_log))
        {
            installLog($install_log);
        }       
    }

    public function __construct(BackendInstaller $installer)
    {
        $this->installer = $installer;
    }
    /**
     * Start session safely (no error suppression).
     */
    public function safeSessionStart(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public function preInstallSettings()
    {
        define('SUGARCRM_IS_INSTALLING', true);
        $this->safeSessionStart();

        $GLOBALS['installing'] = true;

        $this->installer->dictionary = (static function() {    
            include 'modules/TableDictionary.php';
            return isset($dictionary) ? $dictionary : [];
        })();

        $backtrack_limit = ini_get('pcre.backtrack_limit');
        if (!empty($backtrack_limit)) {
            ini_set('pcre.backtrack_limit', '-1');
        }

        $GLOBALS['sql_queries'] = 0;
        $_SESSION['install_type'] = $_REQUEST['install_type']  = 'custom';

        set_time_limit(3600);
        //ignore_user_abort(true);
        ini_set("output_buffering", "1");
        
        // flush after each output so the user can see the progress in real-time
        
    }

    public function loadAdminUser()
    {
        $GLOBALS['current_user'] = \BeanFactory::newBean('Users');
        $GLOBALS['current_user']->retrieve(1);
        $GLOBALS['current_user']->is_admin = '1';
    }

    public function includeRequiredClasses()
    {
        require_once('include/SugarLogger/LoggerManager.php');
        require_once('sugar_version.php');
        require_once('suitecrm_version.php');
        require_once('minthcm_version.php');
        require_once('install/install_utils.php');
        require_once('install/install_defaults.php');
        require_once('include/TimeDate.php');
        require_once('include/Localization/Localization.php');
        require_once('include/SugarTheme/SugarTheme.php');
        require_once('include/utils/LogicHook.php');
        require_once('data/SugarBean.php');
        require_once('include/entryPoint.php');
        require_once 'install/performSetupUtils.php';
    }


    public function setLanguages()
    {
        require_once 'include/utils.php';
        
        $supportedLanguages = $this->getSupportedInstallLanguages();

        // after install language is selected, use that pack
        $default_lang = 'en_us';
        if (!isset($_POST['language']) && (!isset($_SESSION['language']) && empty($_SESSION['language']))) {
            if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && !empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                $lang = \parseAcceptLanguage();
                if (isset($supportedLanguages[$lang])) {
                    $_POST['language'] = $lang;
                } else {
                    $_POST['language'] = $default_lang;
                }
            }
        }

        if (isset($_POST['language'])) {
            $_SESSION['language'] = str_replace('-', '_', $_POST['language']);
        }

        $current_language = isset($_SESSION['language']) ? $_SESSION['language'] : $default_lang;

        if (file_exists("install/language/{$current_language}.lang.php")) {
            include("install/language/{$current_language}.lang.php");
        } else {
            include("install/language/{$default_lang}.lang.php");
        }
        $GLOBALS['mod_strings'] = $mod_strings;
        if ($current_language != 'en_us') {
            $my_mod_strings = $mod_strings;
            include('install/language/en_us.lang.php');
            $GLOBALS['mod_strings'] = sugarLangArrayMerge($mod_strings, $my_mod_strings);
        }

        return $current_language;
    }

    protected function getSupportedInstallLanguages()
    {
        $supportedLanguages = array(
            'en_us'	=> 'English (US)',
        );
        if (file_exists('install/lang.config.php')) {
            include('install/lang.config.php');
            if (!empty($config['languages'])) {
                foreach ($config['languages'] as $k=>$v) {
                    if (file_exists('install/language/' . $k . '.lang.php')) {
                        $supportedLanguages[$k] = $v;
                    }
                }
            }
        }
        return $supportedLanguages;
    }
}

<?php
namespace MintHCM\MintCLI\Installer;

use MintHCM\MintCLI\Installer\Support\InstallerHelper;

class BaseConfigurationInstaller
{
    protected InstallerHelper $helper;

    public function setHelper(InstallerHelper $helper)
    {
        $this->helper = $helper;
    }

    public function saveConfigurationSettings()
    {
        $admin_bean = \BeanFactory::newBean('Administration');
        $admin_bean->saveSetting('system', 'adminwizard', 1);
        $admin_bean->saveSetting('system', 'name', $_SESSION['setup_system_name']);
        $admin_bean->saveSetting('Update', 'CheckUpdates', 'manual');
        $admin_bean->saveConfig();

        installLog('new Configurator');
        $configurator = new \Configurator();
        installLog('populateFromPost');
        $configurator->populateFromPost();

        installLog('handleOverride');
        // add local settings to config overrides
        if (!empty($_SESSION['default_date_format'])) {
            $sugar_config['default_date_format'] = $_SESSION['default_date_format'];
        }

        if (!empty($_SESSION['default_time_format'])) {
            $sugar_config['default_time_format'] = $_SESSION['default_time_format'];
        }

        if (!empty($_SESSION['default_language'])) {
            $sugar_config['default_language'] = $_SESSION['default_language'];
        }

        if (!empty($_SESSION['default_locale_name_format'])) {
            $sugar_config['default_locale_name_format'] = $_SESSION['default_locale_name_format'];
        }

        // save current web-server user for the cron user check mechanism:
        installLog('addCronAllowedUser');
        addCronAllowedUser(getRunningUser());

        installLog('saveConfig');
        $configurator->saveConfig();
    }

    public function deleteCurrencySetAsDefault()
    {
        // Bug 37310 - Delete any existing currency that matches the one we've just set the default to during the admin wizard

        installLog('new Currency');
        $currency = new \Currency;
        $currency->retrieve($currency->retrieve_id_by_name($_REQUEST['default_currency_name']));
        if (!empty($currency->id) && $currency->symbol == $_REQUEST['default_currency_symbol'] && $currency->iso4217 == $_REQUEST['default_currency_iso4217'] ) 
        {
            $currency->deleted = 1;
            $currency->save();
        }
    }

    public function installDefaultDashlets()
    {
        $defaultDashlets = array(
            'TodaysWorkScheduleDashlet' => 'Home',
            'MyMeetingsDashlet' => 'Meetings',
            'LeaveOfAbsenceDashlet' => 'Home',
            'CalendarDashlet' => 'Calendar',
        );

        //Write the dashlets to custom so that the dashlets are not shown for the un-selected scenarios
        $fileContents = "<?php \n" . '$defaultDashlets =' . var_export($defaultDashlets, true) . ';';
        sugar_file_put_contents('custom/modules/Home/dashlets.php', $fileContents);
    }

    public function configureDefaultTabs()
    {
        //Beginning of the scenario implementations
        //We need to load the tabs so that we can remove those which are scenario based and un-selected
        //Remove the custom tabConfig as this overwrites the complete list containined in the include/tabConfig.php
        if (file_exists('custom/include/tabConfig.php')) {
            unlink('custom/include/tabConfig.php');
        }
        require_once 'include/tabConfig.php';

        //Remove the custom dashlet so that we can use the complete list of defaults to filter by category
        
        //Write the tabstructure to custom so that the grouping are not shown for the un-selected scenarios
        $fileContents = "<?php \n" . '$GLOBALS["tabStructure"] =' . var_export($GLOBALS['tabStructure'], true) . ';';
        sugar_file_put_contents('custom/include/tabConfig.php', $fileContents);
    }

    public function installDemoData($should_install)
    {
        global $mod_strings;
        if($should_install)
        {
            installLog("populating the db with seed data");
            $this->helper->setInstallStatus([14, "Adding demo data..."]);

            $this->helper->loadAdminUser();
            global $current_user;
            
            include("install/populateSeedDataFromSQL.php");
        }
    }

    public function installACLActions()
    {
        global $current_user, $beanList;

        $installed_classes = array();

        if (is_admin($current_user)) {
            foreach ($beanList as $module => $class) {
                if (empty($installed_classes[$class]) && $class !== 'Tracker') {
                    $bean = \BeanFactory::newBean($module);
                    if ($bean) {
                        if (empty($bean->acl_display_only) && $bean->bean_implements('ACL')) {
                            if (!empty($bean->acltype)) {
                                \ACLAction::addActions($bean->getACLCategory(), $bean->acltype);
                            } else {
                                \ACLAction::addActions($bean->getACLCategory());
                            }
                            $installed_classes[$class] = true;
                        }
                    }
                }
            }
        }
    }
}
<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * MintHCM is a Human Capital Management software based on SuiteCRM developed by MintHCM,
 * Copyright (C) 2018-2023 MintHCM
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by SugarCRM"
 * logo and "Supercharged by SuiteCRM" logo and "Reinvented by MintHCM" logo.
 * If the display of the logos is not reasonably feasible for technical reasons, the
 * Appropriate Legal Notices must display the words "Powered by SugarCRM" and
 * "Supercharged by SuiteCRM" and "Reinvented by MintHCM".
 */
require_once 'install/performSetupUtils.php';

// This file will load the configuration settings from session data,
// write to the config file, and execute any necessary database steps.
$GLOBALS['installing'] = true;
if (!isset($install_script) || !$install_script) {
    die($mod_strings['ERR_NO_DIRECT_SCRIPT']);
}
ini_set("output_buffering", "0");
set_time_limit(3600);
// flush after each output so the user can see the progress in real-time
ob_implicit_flush();

require_once 'install/install_utils.php';

require_once 'modules/TableDictionary.php';

$trackerManager = TrackerManager::getInstance();
$trackerManager->pause();

global $cache_dir; $cache_dir = sugar_cached("");
$line_entry_format = "&nbsp&nbsp&nbsp&nbsp&nbsp<b>";
$line_exit_format = "... &nbsp&nbsp</b>";
$rel_dictionary = $dictionary; // sourced by modules/TableDictionary.php
$render_table_close = "";
$render_table_open = "";
global $setup_db_admin_password; $setup_db_admin_password = $_SESSION['setup_db_admin_password'];
global $setup_db_admin_user_name; $setup_db_admin_user_name = $_SESSION['setup_db_admin_user_name'];
global $setup_db_create_database; $setup_db_create_database = $_SESSION['setup_db_create_database'];
global $setup_db_create_sugarsales_user; $setup_db_create_sugarsales_user = $_SESSION['setup_db_create_sugarsales_user'];
global $setup_db_database_name; $setup_db_database_name = $_SESSION['setup_db_database_name'];
global $setup_db_drop_tables; $setup_db_drop_tables = $_SESSION['setup_db_drop_tables'];
global $setup_db_host_instance; $setup_db_host_instance = $_SESSION['setup_db_host_instance'];
global $setup_db_port_num; $setup_db_port_num = $_SESSION['setup_db_port_num'];
global $setup_db_host_name; $setup_db_host_name = $_SESSION['setup_db_host_name'];
global $demoData; $demoData = $_SESSION['demoData'];
global $setup_db_sugarsales_password; $setup_db_sugarsales_password = $_SESSION['setup_db_sugarsales_password'];
global $setup_db_sugarsales_user; $setup_db_sugarsales_user = $_SESSION['setup_db_sugarsales_user'];
global $setup_site_admin_user_name; $setup_site_admin_user_name = $_SESSION['setup_site_admin_user_name'];
global $setup_site_admin_password; $setup_site_admin_password = $_SESSION['setup_site_admin_password'];
global $setup_site_guid; $setup_site_guid = (isset($_SESSION['setup_site_specify_guid']) && $_SESSION['setup_site_specify_guid']!= '') ? $_SESSION['setup_site_guid'] : '';
global $setup_site_url; $setup_site_url = $_SESSION['setup_site_url'];
$parsed_url = parse_url($setup_site_url);
global $setup_site_host_name; $setup_site_host_name = $parsed_url['host'];
global $setup_site_log_dir; $setup_site_log_dir = isset($_SESSION['setup_site_custom_log_dir']) ? $_SESSION['setup_site_log_dir']
: '.';
global $setup_site_log_file; $setup_site_log_file = 'minthcm.log';  // may be an option later
global $setup_site_log_level; $setup_site_log_level = 'fatal';
global $setup_site_session_path; $setup_site_session_path = isset($_SESSION['setup_site_custom_session_path']) ? $_SESSION['setup_site_session_path']
: '';

//$bottle = handleSugarConfig();

///////////////////////////////////////////////////////////////////////////////
////    START CREATE DEFAULTS
installStatus($mod_strings['STAT_CREATE_DEFAULT_SETTINGS'], null, false, '');
setMintInstallStatus(9, "Installing basic settings...");
installLog("Begin creating Defaults");
installerHook('pre_createDefaultSettings');
installLog("insert defaults into config table");
insert_default_settings();
installerHook('post_createDefaultSettings');

$new_tables = 1; // is there ever a scenario where we DON'T create the admin user?

installerHook('pre_createUsers');
if ($new_tables) {
    installLog($mod_strings['LBL_PERFORM_DEFAULT_USERS']);
    create_default_users();
} else {
    installLog($mod_strings['LBL_PERFORM_ADMIN_PASSWORD']);
    $db->setUserName($setup_db_sugarsales_user);
    $db->setUserPassword($setup_db_sugarsales_password);
    set_admin_password($setup_site_admin_password);
}
installerHook('post_createUsers');

installDefaultKReports();

// default OOB schedulers

installLog($mod_strings['LBL_PERFORM_DEFAULT_SCHEDULER']);
$scheduler = BeanFactory::newBean('Schedulers');
installerHook('pre_createDefaultSchedulers');
$scheduler->rebuildDefaultSchedulers();
installerHook('post_createDefaultSchedulers');

installDelegationPDFTemplate();

install_mint_dashlets($db);
installLog($mod_strings['LBL_PERFORM_VIEW_TOOLS']);
installStatus($mod_strings['STAT_PERFORM_VIEW_TOOLS']);
setMintInstallStatus(10,"Rebuilding view tools...");
rebuildWithViewTools(false);

// Enable Sugar Feeds and add all feeds by default
installLog("Enable SugarFeeds");
installStatus($mod_strings['STAT_ENABLE_SUGARFEEDS']);
setMintInstallStatus(11,"Enabling SugarFeeds...");
enableSugarFeeds();

///////////////////////////////////////////////////////////////////////////
////    FINALIZE LANG PACK INSTALL
if (isset($_SESSION['INSTALLED_LANG_PACKS']) && is_array($_SESSION['INSTALLED_LANG_PACKS'])
    && !empty($_SESSION['INSTALLED_LANG_PACKS'])) {
    updateUpgradeHistory();
}

//require_once('modules/Connectors/InstallDefaultConnectors.php');
///////////////////////////////////////////////////////////////////////////////
////    INSTALL PASSWORD TEMPLATES
include 'install/seed_data/Advanced_Password_SeedData.php';

///////////////////////////////////////////////////////////////////////////////
////    SETUP DONE
installLog("Installation has completed *********");

$memoryUsed = '';
if (function_exists('memory_get_usage')) {
    $memoryUsed = $mod_strings['LBL_PERFORM_OUTRO_5'] . memory_get_usage() . $mod_strings['LBL_PERFORM_OUTRO_6'];
}

$errTcpip = '';

if (isset($_SESSION['setup_site_sugarbeet_automatic_checks']) && $_SESSION['setup_site_sugarbeet_automatic_checks']
    == true) {
    set_CheckUpdates_config_setting('automatic');
} else {
    set_CheckUpdates_config_setting('manual');
}
if (!empty($_SESSION['setup_system_name'])) {
    $admin=BeanFactory::newBean('Administration');
    $admin->saveSetting('system', 'name', $_SESSION['setup_system_name']);
}

setMintInstallStatus(12,"Installing default dashlets...");

// Bug 28601 - Set the default list of tabs to show
$enabled_tabs = array();
$enabled_tabs[] = 'Home';
$enabled_tabs[] = 'Accounts';
$enabled_tabs[] = 'Notes';
$enabled_tabs[] = 'Opportunities';
$enabled_tabs[] = 'SecurityGroups';
$enabled_tabs[] = 'Calendar';
$enabled_tabs[] = 'ResourceCalendar';
$enabled_tabs[] = 'Documents';
$enabled_tabs[] = 'Emails';
$enabled_tabs[] = 'Calls';
$enabled_tabs[] = 'Meetings';
$enabled_tabs[] = 'Tasks';
$enabled_tabs[] = 'Project';
$enabled_tabs[] = 'AM_ProjectTemplates';
$enabled_tabs[] = 'FP_events';
$enabled_tabs[] = 'FP_Event_Locations';
$enabled_tabs[] = 'AOS_PDF_Templates';
$enabled_tabs[] = 'AOR_Reports';
$enabled_tabs[] = 'AOK_KnowledgeBase';
$enabled_tabs[] = 'AOK_Knowledge_Base_Categories';
$enabled_tabs[] = 'Surveys';
$enabled_tabs[] = 'Delegations';
$enabled_tabs[] = 'KTemplates';
$enabled_tabs[] = 'PDFTemplates';
$enabled_tabs[] = 'WorkSchedules';
$enabled_tabs[] = 'WorkingMonths';
$enabled_tabs[] = 'NonWorkingDays';
$enabled_tabs[] = 'KReports';
$enabled_tabs[] = 'Candidates';
$enabled_tabs[] = 'Candidatures';
$enabled_tabs[] = 'Positions';
$enabled_tabs[] = 'Recruitments';
$enabled_tabs[] = 'Reservations';
$enabled_tabs[] = 'Resources';
$enabled_tabs[] = 'Contracts';
$enabled_tabs[] = 'TermsOfEmployment';
$enabled_tabs[] = 'PeriodsOfEmployment';
$enabled_tabs[] = 'Trainings';
$enabled_tabs[] = 'Positions';
$enabled_tabs[] = 'OnboardingTemplates';
$enabled_tabs[] = 'OffboardingTemplates';
$enabled_tabs[] = 'ExitInterviews';
$enabled_tabs[] = 'EmployeeRoles';
$enabled_tabs[] = 'Benefits';
$enabled_tabs[] = 'Responsibilities';
$enabled_tabs[] = 'Onboardings';
$enabled_tabs[] = 'Offboardings';
$enabled_tabs[] = 'Competencies';
$enabled_tabs[] = 'CompetencyRatings';
$enabled_tabs[] = 'Goals';
$enabled_tabs[] = 'Appraisals';
$enabled_tabs[] = 'News';
$enabled_tabs[] = 'Ideas';
$enabled_tabs[] = 'Conclusions';
$enabled_tabs[] = 'ResponsibilityActivities';
$enabled_tabs[] = 'Problems';
$enabled_tabs[] = 'Improvements';
$enabled_tabs[] = 'ReservationsCalendar';
$enabled_tabs[] = 'Certificates';
$enabled_tabs[] = 'Applications';
//
$enabled_tabs[] = 'Skills';
$enabled_tabs[] = 'Knowledge';
$enabled_tabs[] = 'Attitudes';
$enabled_tabs[] = 'ProspectLists';
$enabled_tabs[] = 'Campaigns';
//
//Beginning of the scenario implementations
//We need to load the tabs so that we can remove those which are scenario based and un-selected
//Remove the custom tabConfig as this overwrites the complete list containined in the include/tabConfig.php
if (file_exists('custom/include/tabConfig.php')) {
    unlink('custom/include/tabConfig.php');
}
require_once 'include/tabConfig.php';

//Remove the custom dashlet so that we can use the complete list of defaults to filter by category
if (file_exists('custom/modules/Home/dashlets.php')) {
    unlink('custom/modules/Home/dashlets.php');
}
//Check if the folder is in place
if (!file_exists('custom/modules/Home')) {
    sugar_mkdir('custom/modules/Home', 0775);
}
//Check if the folder is in place
if (!file_exists('custom/include')) {
    sugar_mkdir('custom/include', 0775);
}

require_once 'modules/Home/dashlets.php';

if (isset($_SESSION['installation_scenarios'])) {
    foreach ($_SESSION['installation_scenarios'] as $scenario) {
        //If the item is not in $_SESSION['scenarios'], then unset them as they are not required
        if (!in_array($scenario['key'], $_SESSION['scenarios'])) {
            foreach ($scenario['modules'] as $module) {
                if (($removeKey = array_search($module, $enabled_tabs)) !== false) {
                    unset($enabled_tabs[$removeKey]);
                }
            }

            //Loop through the dashlets to remove from the default home page based on this scenario
            foreach ($scenario['dashlets'] as $dashlet) {
                //if (($removeKey = array_search($dashlet, $defaultDashlets)) !== false) {
                //    unset($defaultDashlets[$removeKey]);
                // }
                if (isset($defaultDashlets[$dashlet])) {
                    unset($defaultDashlets[$dashlet]);
                }

            }

            //If the scenario has an associated group tab, remove accordingly (by not adding to the custom tabconfig.php
            if (isset($scenario['groupedTabs'])) {
                unset($GLOBALS['tabStructure'][$scenario['groupedTabs']]);
            }
        }
    }
}

//Have a 'core' options, with accounts / contacts if no other scenario is selected
if (!is_null($_SESSION['scenarios'])) {
    unset($GLOBALS['tabStructure']['LBL_TABGROUP_DEFAULT']);
}

//Write the tabstructure to custom so that the grouping are not shown for the un-selected scenarios
$fileContents = "<?php \n" .'$GLOBALS["tabStructure"] ='.var_export($GLOBALS['tabStructure'], true).';';
sugar_file_put_contents('custom/include/tabConfig.php', $fileContents);

//Write the dashlets to custom so that the dashlets are not shown for the un-selected scenarios
$fileContents = "<?php \n" .'$defaultDashlets ='.var_export($defaultDashlets, true).';';
sugar_file_put_contents('custom/modules/Home/dashlets.php', $fileContents);

// End of the scenario implementations

include_once 'install/suite_install/suite_install.php';

post_install_modules();
//install default KReports
installDefaultKReports();
//install default Dictionaries
installDefaultDictionaries();
//Call rebuildSprites
/* if(function_exists('imagecreatetruecolor'))
{
require_once('modules/UpgradeWizard/uw_utils.php');
rebuildSprites(true);
} */

setMintInstallStatus(13,"Adding missing config information...");
installStatus('', array('function' => 'next_step', 'step' => 2, 'skip_minify' => true)); //mn

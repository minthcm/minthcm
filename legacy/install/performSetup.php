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

$cache_dir = sugar_cached("");
$line_entry_format = "&nbsp&nbsp&nbsp&nbsp&nbsp<b>";
$line_exit_format = "... &nbsp&nbsp</b>";
$rel_dictionary = $dictionary; // sourced by modules/TableDictionary.php
$render_table_close = "";
$render_table_open = "";
$setup_db_admin_password = $_SESSION['setup_db_admin_password'];
$setup_db_admin_user_name = $_SESSION['setup_db_admin_user_name'];
$setup_db_create_database = $_SESSION['setup_db_create_database'];
$setup_db_create_sugarsales_user = $_SESSION['setup_db_create_sugarsales_user'];
$setup_db_database_name = $_SESSION['setup_db_database_name'];
$setup_db_drop_tables = $_SESSION['setup_db_drop_tables'];
$setup_db_host_instance = $_SESSION['setup_db_host_instance'];
$setup_db_port_num = $_SESSION['setup_db_port_num'];
$setup_db_host_name = $_SESSION['setup_db_host_name'];
$demoData = $_SESSION['demoData'];
$setup_db_sugarsales_password = $_SESSION['setup_db_sugarsales_password'];
$setup_db_sugarsales_user = $_SESSION['setup_db_sugarsales_user'];
$setup_site_admin_user_name = $_SESSION['setup_site_admin_user_name'];
$setup_site_admin_password = $_SESSION['setup_site_admin_password'];
$setup_site_guid = (isset($_SESSION['setup_site_specify_guid']) && $_SESSION['setup_site_specify_guid'] != '') ? $_SESSION['setup_site_guid'] : '';
$setup_site_url = $_SESSION['setup_site_url'];
$parsed_url = parse_url($setup_site_url);
$setup_site_host_name = $parsed_url['host'];
$setup_site_log_dir = isset($_SESSION['setup_site_custom_log_dir']) ? $_SESSION['setup_site_log_dir'] : '.';
$setup_site_log_file = 'minthcm.log'; // may be an option later
$setup_site_session_path = isset($_SESSION['setup_site_custom_session_path']) ? $_SESSION['setup_site_session_path'] : '';
$setup_site_log_level = 'fatal';
/*sugar_cache_clear('TeamSetsCache');
if ( file_exists($cache_dir .'modules/Teams/TeamSetCache.php') ) {
unlink($cache_dir.'modules/Teams/TeamSetCache.php');
}

sugar_cache_clear('TeamSetsMD5Cache');
if ( file_exists($cache_dir.'modules/Teams/TeamSetMD5Cache.php') ) {
unlink($cache_dir.'modules/Teams/TeamSetMD5Cache.php');
}*/
$langHeader = get_language_header();

$bottle = handleSugarConfig(true);

$errTcpip = '';
if (isset($fp) && (!isset($_SESSION['oc_install']) || $_SESSION['oc_install'] == false)) {
    @fclose($fp);
    if ($next_step == 9999) {
        $next_step = 8;
    }

    $fpResult = <<<FP
     <form action="install.php" method="post" name="form" id="form">
     <input type="hidden" name="current_step" value="{$next_step}">
     <input class="button" type="submit" name="goto" value="{$mod_strings['LBL_NEXT']}" id="button_next2"/>
     </form>
FP;
} else {
    $fpResult = <<<FP
            <form action="index.php" method="post" name="formFinish" id="formFinish">
                <input type="hidden" name="default_user_name" value="admin" />
                <input class="button" type="submit" name="next" value="{$mod_strings['LBL_PERFORM_FINISH']}" id="button_next2"/>
            </form>
FP;
}

installDefaultRoles();
/////////////////////////////////////////////////////////////
//// Store information by installConfig.php form

// save current superglobals and vars
$varStack['GLOBALS'] = $GLOBALS;
$varStack['defined_vars'] = get_defined_vars();

// restore previously posted form
$_REQUEST = array_merge($_REQUEST, $_SESSION);
$_POST = array_merge($_POST, $_SESSION);

setMintInstallStatus(16, 'Finishing backend installation...');
installStatus($mod_strings['STAT_INSTALL_FINISH']);
installLog('Save configuration settings..');

//      <--------------------------------------------------------
//          from ConfigurationConroller->action_saveadminwizard()
//          ---------------------------------------------------------->

installLog('save locale');

//global $current_user;
installLog('new Administration');
$focus=BeanFactory::newBean('Administration');
installLog('retrieveSettings');
//$focus->retrieveSettings();
// switch off the adminwizard (mark that we have got past this point)
installLog('AdminWizard OFF');
$focus->saveSetting('system', 'adminwizard', 1);

installLog('saveConfig');
$focus->saveConfig();

installLog('new Configurator');
$configurator = new Configurator();
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

//$configurator->handleOverride();

// save current web-server user for the cron user check mechanism:
installLog('addCronAllowedUser');
addCronAllowedUser(getRunningUser());

installLog('saveConfig');
$configurator->saveConfig();

// Bug 37310 - Delete any existing currency that matches the one we've just set the default to during the admin wizard
installLog('new Currency');
$currency = new Currency; 
installLog('retrieve');
$currency->retrieve($currency->retrieve_id_by_name($_REQUEST['default_currency_name']));
if (!empty($currency->id)
    && $currency->symbol == $_REQUEST['default_currency_symbol']
    && $currency->iso4217 == $_REQUEST['default_currency_iso4217']) {
    $currency->deleted = 1;
    installLog('DBG: save currency');
    $currency->save();
}

setMintInstallStatus(17,"Setting up user settings...");
installLog('Save user settings..');

//      <------------------------------------------------
//          from UsersController->action_saveuserwizard()
//          ---------------------------------------------------------->

// set all of these default parameters since the Users save action will undo the defaults otherwise

// load admin
$current_user = BeanFactory::newBean('Users');
$current_user->retrieve(1);
$current_user->is_admin = '1';
// $sugar_config = get_sugar_config_defaults();

// set local settings -  if neccessary you can set here more fields as named in User module / EditView form...
if (isset($_REQUEST['timezone']) && $_REQUEST['timezone']) {
    $current_user->setPreference('timezone', $_REQUEST['timezone']);
}

if (file_exists(__DIR__ . '/../modules/ACL/install_actions.php')) {
    require_once(__DIR__ . '/../modules/ACL/install_actions.php');
}
$_POST['dateformat'] = $_REQUEST['default_date_format'];
$_POST['record'] = $current_user->id;
$_POST['is_admin'] = ($current_user->is_admin ? 'on' : '');
$_POST['use_real_names'] = true;
$_POST['reminder_checked'] = '1';
$_POST['reminder_time'] = 1800;
$_POST['email_reminder_time'] = 3600;
$_POST['mailmerge_on'] = 'on';
$_POST['receive_notifications'] = $current_user->receive_notifications;
setMintInstallStatus(18,"Setting up currencies...");
installLog('DBG: SugarThemeRegistry::getDefault');
$_POST['user_theme'] = (string) SugarThemeRegistry::getDefault();

// save and redirect to new view
$_REQUEST['do_not_redirect'] = true;

// restore superglobals and vars
$GLOBALS = $varStack['GLOBALS'];
foreach ($varStack['defined_vars'] as $__key => $__value) {
    $$__key = $__value;
}

$endTime = microtime(true);
$deltaTime = $endTime - $startTime;

if (!is_array($bottle) || !is_object($bottle)) {
    $bottle = (array) $bottle;
    LoggerManager::getLogger()->warn('Bottle needs to be an array to perform setup');
}

if (count($bottle) > 0) {
    foreach ($bottle as $bottle_message) {
        $bottleMsg .= "{$bottle_message}\n";
    }
} else {
    $bottleMsg = $mod_strings['LBL_PERFORM_SUCCESS'];
}
installerHook('post_installModules');



$loginURL = str_replace('install.php', 'index.php', "//$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
setMintInstallStatus(19,"Backend installation successful - continuing...");
installStatus(sprintf($mod_strings['STAT_INSTALL_FINISH_LOGIN'], $loginURL ) , array('function' => 'redirect', 'arguments' => $loginURL) );
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


require_once('install/install_utils.php');

require_once('modules/TableDictionary.php');


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
////    START DEMO DATA
// populating the db with seed data
installLog("populating the db with seed data");
if ($_SESSION['demoData'] != 'no') {
    installerHook('pre_installDemoData');

    installStatus($mod_strings['LBL_PERFORM_DEMO_DATA'], null, false, '');
    setMintInstallStatus(14,"Adding demo data...");

    global $current_user;
    $current_user = BeanFactory::newBean('Users');
    $current_user->retrieve(1);
    include("install/populateSeedData.php");
    installerHook('post_installDemoData');
}
deploy_mint_dashlets();

setMintInstallStatus(15,"Deploying mint dashlets...");
installStatus('', array('function' => 'next_step', 'step' => 3, 'skip_minify' => true)); //mn
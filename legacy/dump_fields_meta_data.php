<?php
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2020 SalesAgility Ltd.
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

if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}

include 'include/MVC/preDispatch.php';
$startTime = microtime(true);
require_once 'include/entryPoint.php';
ob_start();
require_once 'include/MVC/SugarApplication.php';

require_once('include/SugarMetric/Manager.php');
SugarMetric_Manager::getInstance()->setMetricClass('background')->setTransactionName('index');

$app = new SugarApplication();
$app->startSession();

global $db, $sugar_config;

$db_host = $sugar_config['dbconfig']['db_host_name'];
$db_user = $sugar_config['dbconfig']['db_user_name'];
$db_pass = $sugar_config['dbconfig']['db_password'];
$db_name = $sugar_config['dbconfig']['db_name'];
$db_port = $sugar_config['dbconfig']['db_port'];

$last_dump_query = "SELECT value FROM config WHERE category = 'eVolpe' AND name = 'last_fields_meta_data_dump'";
$last_dump_date = $db->getOne($last_dump_query);

$drop_dump_table = "DROP TABLE IF EXISTS fields_meta_data_dump";
$db->query($drop_dump_table);

$create_dump_table = "CREATE TABLE fields_meta_data_dump LIKE fields_meta_data";
$db->query($create_dump_table);

$insert_dump_data = "INSERT INTO fields_meta_data_dump SELECT * FROM fields_meta_data";
if($last_dump_date) {
    $insert_dump_data .= " WHERE date_modified > '{$last_dump_date}'";
} else {
    $insert_config_query = "INSERT INTO config (category, name, value) VALUES ('eVolpe', 'last_fields_meta_data_dump', '')";
    $db->query($insert_config_query);
}
$db->query($insert_dump_data);

$date = date('YmdHis');
$dump_file = "fields_meta_data_{$date}.sql";
$dump_command = "mysqldump --host={$db_host} --user={$db_user} --password={$db_pass} --port={$db_port} --no-create-info --skip-comments --skip-add-locks --skip-set-charset --skip-disable-keys --skip-add-drop-table --compact --skip-triggers --skip-routines {$db_name} fields_meta_data_dump > {$dump_file}";
exec($dump_command, $output, $return_var);
if ($return_var !== 0) {
    echo "Error executing mysqldump command: " . implode("\n", $output);
}

$update_last_dump_query = "UPDATE config SET value = UTC_TIMESTAMP() WHERE category = 'eVolpe' AND name = 'last_fields_meta_data_dump'";
$db->query($update_last_dump_query);

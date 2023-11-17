<?php


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

$dictionary['DashboardHistory'] = array(
   'table' => 'dashboardhistory',
   'audited' => false,
   'inline_edit' => false,
   'duplicate_merge' => false,
   'fields' => array(
      'user_count' => array(
         'type' => 'int',
         'name' => 'user_count',
         'vname' => 'LBL_USER_COUNT',
         'massupdate' => false,
         'importable' => true,
         'default' => '0',
         'no_default' => false,
      ),
      "dashboardmanager" => array(
         'name' => 'dashboardmanager',
         'type' => 'link',
         'relationship' => 'dashboardhistory_dashboardmanager',
         'source' => 'non-db',
         'module' => 'DashboardManager',
         'bean_name' => false,
         'vname' => 'LBL_DASHBOARDMANAGER',
      ),
      "dashboardmanager_name" => array(
         'id_name' => 'dashboardmanager_id',
         'name' => 'dashboardmanager_name',
         'type' => 'relate',
         'source' => 'non-db',
         'vname' => 'LBL_DASHBOARDMANAGER_NAME',
         'link' => 'dashboardmanager',
         'table' => 'dashboardmanager',
         'module' => 'DashboardManager',
         'rname' => 'name',
      ),
      "dashboardmanager_id" => array(
         'name' => 'dashboardmanager_id',
         'type' => 'id',
         'vname' => 'LBL_DASHBOARDMANAGER_ID',
         'link' => 'dashboardmanager',
         'table' => 'dashboardmanager',
         'module' => 'DashboardManager',
         'rname' => 'id',
         'reportable' => false,
         'massupdate' => false,
         'duplicate_merge' => 'disabled',
         'hideacl' => true,
      ),
      "dashboardbackups" => array(
         'name' => 'dashboardbackups',
         'type' => 'link',
         'relationship' => 'dashboardbackups_dashboardhistory',
         'source' => 'non-db',
         'module' => 'DashboardBackups',
         'bean_name' => 'DashboardBackups',
         'vname' => 'LBL_DASHBOARDBACKUPS',
      ),
   ),
   'relationships' => array(
      'dashboardbackups_dashboardhistory' =>
      array(
         'lhs_module' => 'DashboardHistory',
         'lhs_table' => 'dashboardhistory',
         'lhs_key' => 'id',
         'rhs_module' => 'DashboardBackups',
         'rhs_table' => 'dashboardbackups',
         'rhs_key' => 'dashboardhistory_id',
         'relationship_type' => 'one-to-many',
      ),
   ),
   'optimistic_locking' => true,
   'unified_search' => false,
);
if ( !class_exists('VardefManager') ) {
   require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('DashboardHistory', 'DashboardHistory', array( 'basic', 'assignable' ));

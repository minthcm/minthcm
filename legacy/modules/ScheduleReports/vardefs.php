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

$dictionary['ScheduleReports'] = array(
   'table' => 'schedulereports',
   'audited' => true,
   'duplicate_merge' => true,
   'fields' => array(
      'frequency_performance' =>
      array(
         'required' => false,
         'name' => 'frequency_performance',
         'vname' => 'LBL_FREQUENCY_PERFORMANCE',
         'type' => 'enum',
         'massupdate' => '1',
         'default' => 'every_day',
         'no_default' => false,
         'comments' => '',
         'help' => '',
         'importable' => 'false',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'audited' => false,
         'reportable' => true,
         'unified_search' => false,
         'merge_filter' => 'disabled',
         'len' => 100,
         'size' => '20',
         'options' => 'frequency_performance_list',
         'studio' => 'visible',
         'dependency' => false,
      ),
      'active' =>
      array(
         'required' => false,
         'name' => 'active',
         'vname' => 'LBL_ACTIVE',
         'type' => 'bool',
         'massupdate' => 0,
         'default' => '1',
         'no_default' => false,
         'comments' => '',
         'help' => '',
         'importable' => 'false',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'audited' => false,
         'reportable' => true,
         'unified_search' => false,
         'merge_filter' => 'disabled',
         'len' => '255',
         'size' => '20',
      ),
      'template_id' =>
      array(
         'required' => true,
         'name' => 'template_id',
         'vname' => 'LBL_TEMPLATE_ID',
         'type' => 'enum',
         'massupdate' => 0,
         'default' => 'Default',
         'no_default' => false,
         'comments' => '',
         'help' => '',
         'importable' => 'false',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'audited' => false,
         'reportable' => true,
         'unified_search' => false,
         'merge_filter' => 'disabled',
         'len' => 100,
         'size' => '20',
         'options' => 'template_id_list',
         'studio' => 'visible',
         'dependency' => false,
         'function' => array
            ( 'name' => 'kreport_getArray', 'include' => 'include/utils.php' ),
      ),
      'email_template_id' =>
      array(
         'required' => true,
         'name' => 'email_template_id',
         'vname' => 'LBL_EMAIL_TEMPLATE_ID',
         'type' => 'enum',
         'massupdate' => 0,
         'default' => 'Default',
         'no_default' => false,
         'comments' => '',
         'help' => '',
         'importable' => 'false',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'audited' => false,
         'reportable' => true,
         'unified_search' => false,
         'merge_filter' => 'disabled',
         'len' => 100,
         'size' => '20',
         'options' => 'email_template_id_list',
         'studio' => 'visible',
         'dependency' => false,
         'function' => array
            ( 'name' => 'kreport_getEmailTemplateArray', 'include' => 'include/utils.php' ),
      ),
      'date_send' =>
      array(
         'required' => true,
         'name' => 'date_send',
         'vname' => 'LBL_DATE_SEND',
         'type' => 'date',
         'massupdate' => 0,
         'comments' => '',
         'help' => '',
         'importable' => 'true',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'audited' => false,
         'reportable' => true,
         'size' => '20',
         'enable_range_search' => false,
         'display_default' => 'now',
      ),
      "schedulereports_schedulereportslogs" => array(
         'name' => 'schedulereports_schedulereportslogs',
         'type' => 'link',
         'relationship' => 'schedulereports_schedulereportslogs',
         'source' => 'non-db',
         'module' => 'ScheduleReportsLogs',
         'bean_name' => false,
         'side' => 'right',
         'vname' => 'LBL_SCHEDULEREPORTS_SCHEDULEREPORTSLOGS_FROM_SCHEDULEREPORTSLOGS_TITLE',
      ),
      "schedulereports_kreports" => array(
         'name' => 'schedulereports_kreports',
         'type' => 'link',
         'relationship' => 'schedulereports_kreports',
         'source' => 'non-db',
         'module' => 'KReports',
         'bean_name' => 'KReport',
         'vname' => 'LBL_SCHEDULEREPORTS_KREPORTS_FROM_KREPORTS_TITLE',
         'id_name' => 'kreport_id',
      ),
      "kreport_name" => array(
         'name' => 'kreport_name',
         'type' => 'relate',
         'source' => 'non-db',
         'vname' => 'LBL_SCHEDULEREPORTS_KREPORTS_FROM_KREPORTS_TITLE',
         'save' => true,
         'required' => true,
         'id_name' => 'kreport_id',
         'link' => 'schedulereports_kreports',
         'table' => 'kreports',
         'module' => 'KReports',
         'rname' => 'name',
      ),
      "kreport_id" => array(
         'name' => 'kreport_id',
         'type' => 'id',
         'relationship' => 'schedulereports_kreports',
         'reportable' => false,
         'required' => true,
         'vname' => 'LBL_SCHEDULEREPORTS_KREPORTS_FROM_SCHEDULEREPORTS_TITLE',
         'rname' => 'id',
         'isnull' => 'true',
         'dbType' => 'id'
      ),
      'users' => array(
         'name' => 'users',
         'type' => 'link',
         'relationship' => 'users_schedulereports',
         'source' => 'non-db',
         'module' => 'Users',
         'bean_name' => 'User',
         'vname' => 'LBL_USERS',
      ),
   ),
   'indices' => array(
      array(
         'name' => 'idx_schedule',
         'type' => 'index',
         'fields' => array( 'kreport_id' ),
      ),
   ),
   'relationships' => array(
      'schedulereports_kreports' =>
      array(
         'lhs_module' => 'KReports',
         'lhs_table' => 'kreports',
         'lhs_key' => 'id',
         'rhs_module' => 'ScheduleReports',
         'rhs_table' => 'schedulereports',
         'rhs_key' => 'kreport_id',
         'relationship_type' => 'one-to-many',
      ),
   ),
   'optimistic_locking' => true,
   'unified_search' => true,
);
if ( !class_exists('VardefManager') ) {
   require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('ScheduleReports', 'ScheduleReports', array( 'basic', 'assignable' ));

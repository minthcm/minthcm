<?php

/* * *******************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
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
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
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
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 * ****************************************************************************** */

$dictionary['ScheduleReportsLogs'] = array(
   'table' => 'schedulereportslogs',
   'audited' => true,
   'duplicate_merge' => true,
   'fields' => array(
      'status' =>
      array(
         'required' => false,
         'name' => 'status',
         'vname' => 'LBL_STATUS',
         'type' => 'enum',
         'massupdate' => 0,
         'default' => 'not run',
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
         'options' => 'schedulers_times_dom',
         'studio' => 'visible',
         'dependency' => false,
      ),
      'execute_data' =>
      array(
         'required' => false,
         'name' => 'execute_data',
         'vname' => 'LBL_EXECUTE_DATA',
         'type' => 'datetimecombo',
         'massupdate' => 0,
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
         'size' => '20',
         'enable_range_search' => false,
         'dbType' => 'datetime',
      ),
      "schedulereports_schedulereportslogs" => array(
         'name' => 'schedulereports_schedulereportslogs',
         'type' => 'link',
         'relationship' => 'schedulereports_schedulereportslogs',
         'source' => 'non-db',
         'module' => 'ScheduleReports',
         'bean_name' => false,
         'vname' => 'LBL_SCHEDULE_REPORT',
         'id_name' => 'schedule_report_id',
      ),
      "schedule_report_name" => array(
         'name' => 'schedule_report_name',
         'type' => 'relate',
         'source' => 'non-db',
         'vname' => 'LBL_SCHEDULE_REPORT',
         'save' => true,
         'id_name' => 'schedule_report_id',
         'link' => 'schedulereports_schedulereportslogs',
         'table' => 'schedulereports',
         'module' => 'ScheduleReports',
         'rname' => 'name',
      ),
      "schedule_report_id" => array(
         'name' => 'schedule_report_id',
         'type' => 'id',
         'relationship' => 'schedulereports_schedulereportslogs',
         'reportable' => false,
         'vname' => 'LBL_SCHEDULE_REPORT_ID',
         'rname' => 'id',
         'isnull' => 'true',
         'dbType' => 'id'
      ),
   ),
   'indices' => array(
      array(
         'name' => 'idx_schedulelogs',
         'type' => 'index',
         'fields' => array( 'schedule_report_id' ),
      ),
   ),
   'relationships' => array(
      'schedulereports_schedulereportslogs' =>
      array(
         'lhs_module' => 'ScheduleReports',
         'lhs_table' => 'schedulereports',
         'lhs_key' => 'id',
         'rhs_module' => 'ScheduleReportsLogs',
         'rhs_table' => 'schedulereportslogs',
         'rhs_key' => 'schedule_report_id',
         'relationship_type' => 'one-to-many',
      ),
   ),
   'optimistic_locking' => true,
   'unified_search' => true,
);
if ( !class_exists('VardefManager') ) {
   require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('ScheduleReportsLogs', 'ScheduleReportsLogs', array( 'basic' ));

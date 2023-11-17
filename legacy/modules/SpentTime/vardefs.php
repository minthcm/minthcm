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

$dictionary['SpentTime'] = array(
    'table' => 'spenttime',
    'audited' => true,
    'duplicate_merge' => false,
    'fields' => array(
        'date_start' => array(
            'name' => 'date_start',
            'vname' => 'LBL_DATE',
            'type' => 'datetimecombo',
            'dbType' => 'datetime',
            'comment' => 'Date of start of meeting',
            'importable' => 'required',
            'required' => true,
            'enable_range_search' => true,
            'options' => 'date_range_search_dom',
            'audited' => true,
            'validation' => array(
                'type' => 'isbefore',
                'compareto' => 'date_end',
                'blank' => false,
            ),
            'vt_validation' => "AEM(equals(callCustomApi('SpentTime','getCountOfSpendTimeRecordsInGivenFrame',\$id,\$workschedule_id,\$date_start,\$date_end,1),0),'LBL_SPENT_TIME_RECORD_FOR_THIS_PERIOD_ALREADY_EXISTS')",
        ),
        'date_end' => array(
            'name' => 'date_end',
            'vname' => 'LBL_DATE_END',
            'type' => 'datetimecombo',
            'dbType' => 'datetime',
            'massupdate' => false,
            'comment' => 'Date meeting ends',
            'audited' => true,
            'enable_range_search' => true,
            'options' => 'date_range_search_dom',
            'importable' => 'required',
            'required' => true,
            'vt_validation' => "AEM(isAfter(\$date_end,\$date_start),'LBL_START_DATE_AFTER_END_DATE')",
        ),
        'status' => array(
            'name' => 'status',
            'vname' => 'LBL_STATUS',
            'type' => 'enum',
            'len' => 100,
            'options' => 'spenttime_status_dom',
            'comment' => 'Spent time record status',
            'default' => '',
            'audited' => true,
        ),
        'work_date' => array(
            'importable' => 'required',
            'required' => true,
            'name' => 'work_date',
            'vname' => 'LBL_WORK_DATE',
            'type' => 'date',
            'massupdate' => 0,
            'comments' => '',
            'help' => '',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'reportable' => true,
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'date_range_search_dom',
        ),
        'spent_time' => array(
            'importable' => 'required',
            'required' => true,
            'name' => 'spent_time',
            'vname' => 'LBL_SPENT_TIME',
            'type' => 'float',
            'massupdate' => 0,
            'comments' => '',
            'help' => '',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'reportable' => true,
            'len' => '5',
            'precision' => '2',
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'numeric_range_search_dom',
            'default' => '0',
            'min' => 0,
            'max' => 1000,
            'validation' => array(
                'type' => 'range',
                'min' => 0,
                'max' => 1000,
            ),
        ),
        'done_ratio' => array(
            'importable' => 'required',
            'required' => true,
            'name' => 'done_ratio',
            'vname' => 'LBL_DONE_RATIO',
            'type' => 'enum',
            'massupdate' => 0,
            'comments' => '',
            'help' => '',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'reportable' => true,
            'len' => '255',
            'size' => '20',
            'options' => 'spenttime_done_ratio_dom',
        ),
        'remaining_hours' => array(
            'importable' => 'required',
            'required' => true,
            'name' => 'remaining_hours',
            'vname' => 'LBL_REMAINIG_HOURS',
            'type' => 'float',
            'massupdate' => 0,
            'comments' => '',
            'help' => '',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'reportable' => true,
            'len' => '18',
            'precision' => '2',
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'numeric_range_search_dom',
            'default' => '0',
            'min' => 0,
            'max' => 1000,
            'validation' => array(
                'type' => 'range',
                'min' => 0,
                'max' => 1000,
            ),
        ),
        'current_done_ratio' => array(
            'importable' => 'required',
            'required' => false,
            'name' => 'current_done_ratio',
            'vname' => 'LBL_CURRENT_DONE_RATIO',
            'type' => 'int',
            'massupdate' => 0,
            'comments' => '',
            'help' => '',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'reportable' => true,
            'len' => '255',
            'size' => '20',
            'options' => 'spenttime_done_ratio_dom',
        ),
        'current_remaining_hours' => array(
            'importable' => 'required',
            'required' => false,
            'name' => 'current_remaining_hours',
            'vname' => 'LBL_CURRENT_REMAINIG_HOURS',
            'type' => 'float',
            'massupdate' => 0,
            'comments' => '',
            'help' => '',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'reportable' => true,
            'len' => '18',
            'precision' => '2',
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'numeric_range_search_dom',
            'default' => '0',
        ),
        'estimated_task_time' => array(
            'importable' => 'required',
            'required' => false,
            'name' => 'estimated_task_time',
            'vname' => 'LBL_ESTIMATED_TASK_TIME',
            'type' => 'float',
            'massupdate' => 0,
            'comments' => '',
            'help' => '',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'reportable' => true,
            'len' => '18',
            'precision' => '2',
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'numeric_range_search_dom',
            'default' => '0',
        ),
        'worked_task_time' => array(
            'importable' => 'required',
            'required' => false,
            'name' => 'worked_task_time',
            'vname' => 'LBL_WORKED_TASK_TIME',
            'type' => 'float',
            'massupdate' => 0,
            'comments' => '',
            'help' => '',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'reportable' => false,
            'len' => '18',
            'precision' => '2',
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'numeric_range_search_dom',
            'default' => '0',
        ),
        'employees' => array(
            'name' => 'employees',
            'type' => 'link',
            'relationship' => 'spenttime_employees',
            'source' => 'non-db',
            'vname' => 'LBL_EMPLOYEES',
        ),
        'employee_name' => array(
            'name' => 'employee_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_EMPLOYEE_NAME',
            'save' => true,
            'id_name' => 'employee_id',
            'link' => 'employees',
            'table' => 'users',
            'module' => 'Employees',
            'rname' => 'name',
        ),
        'employee_id' => array(
            'name' => 'employee_id',
            'type' => 'id',
            'relationship' => 'spenttime_employees',
            'reportable' => false,
            'vname' => 'LBL_EMPLOYEE_ID',
            'rname' => 'id',
            'isnull' => 'true',
            'dbType' => 'id',
            'audited' => true,
        ),
        'projecttask_issue_tracker' => array(
            'name' => 'projecttask_issue_tracker',
            'type' => 'varchar',
            'source' => 'non-db',
            'vname' => 'LBL_PROJECTTASK_ISSUE_TRACKER',
        ),
        'type' => array(
            'name' => 'type',
            'vname' => 'LBL_SPENT_TIME_TYPE',
            'type' => 'enum',
            'len' => 36,
            'options' => 'spenttime_type_dom',
            'comment' => '',
            'importable' => 'false',
            'massupdate' => false,
            'reportable' => true,
            'studio' => 'false',
            'default' => 'other',
        ),
        'category' => array(
            'name' => 'category',
            'vname' => 'LBL_SPENT_TIME_CATEGORY',
            'type' => 'enum',
            'importable' => true,
            'massupdate' => true,
            'reportable' => true,
            'audited' => true,
            'function' => ['name' => 'getDictionary',
                'additional_params' => 'SpentTime-category',
                'include' => 'include/utils/getDictionary.php'],
        ),
        'workschedule_name' => array(
            'name' => 'workschedule_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_WORKSCHEDULE_NAME',
            'save' => true,
            'id_name' => 'workschedule_id',
            'link' => 'workschedules',
            'table' => 'workschedules',
            'module' => 'WorkSchedules',
            'rname' => 'name',
            'importable' => 'required',
            'required' => true,
            'vt_validation' => "AEM(callCustomApi('SpentTime','canLogToWorkOffSchedule',\$workschedule_id),'LBL_ERR_CANT_LOG_TO_WORK_OFF_SCHEDULE')",
        ),
        'workschedule_id' => array(
            'name' => 'workschedule_id',
            'type' => 'id',
            'relationship' => 'workschedules_spenttime',
            'source' => 'non-db',
            'reportable' => false,
            'side' => 'right',
            'vname' => 'LBL_WORKSCHEDULE_ID',
        ),
        'workschedules' => array(
            'name' => 'workschedules',
            'type' => 'link',
            'relationship' => 'workschedules_spenttime',
            'source' => 'non-db',
            'module' => 'WorkSchedules',
            'bean_name' => 'WorkSchedules',
            'vname' => 'LBL_WORKSCHEDULES',
        ),
    ),
    'relationships' => array(
        'spenttime_employees' => array(
            'lhs_module' => 'Employees',
            'lhs_table' => 'users',
            'lhs_key' => 'id',
            'rhs_module' => 'SpentTime',
            'rhs_table' => 'spenttime',
            'rhs_key' => 'employee_id',
            'relationship_type' => 'one-to-many',
        ),
    ),
    'indices' => array(
        array(
            'name' => 'spenttime_employee_idx',
            'type' => 'index',
            'fields' => array(
                'employee_id',
            ),
        ),
    ),
    'optimistic_locking' => true,
    'unified_search' => true,
);

if (!class_exists('VardefManager')) {
    require_once 'include/SugarObjects/VardefManager.php';
}

VardefManager::createVardef('SpentTime', 'SpentTime', array('basic', 'assignable', 'security_groups'));

$dictionary['SpentTime']['fields']['description']['required'] = true;
$dictionary['SpentTime']['fields']['description']['rows'] = '3';
$dictionary['SpentTime']['fields']['description']['cols'] = '80';
$dictionary['SpentTime']['fields']['description']['db_type'] = 'varchar';
$dictionary['SpentTime']['fields']['description']['len'] = 255;
$dictionary['SpentTime']['fields']['description']['unified_search'] = true;
$dictionary['SpentTime']['fields']['description']['full_text_search'] = array('boost' => 1);

<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
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
$dictionary['Call'] = array(
   'table' => 'calls',
   'comment' => 'A Call is an activity representing a phone call',
   'audited' => true,
   'unified_search' => true,
   'full_text_search' => true,
   'unified_search_default_enabled' => true,
   'full_text_search_meta_field' => 'date_start',
   'fields' => array(
      'name' => array(
         'name' => 'name',
         'vname' => 'LBL_SUBJECT',
         'dbType' => 'varchar',
         'type' => 'name',
         'len' => '50',
         'comment' => 'Brief description of the call',
         'unified_search' => true,
         'full_text_search' => array('boost' => 3),
         'required' => true,
         'importable' => 'required',
         'audited' => true,
      ),
      'duration_hours' =>
      array(
         'name' => 'duration_hours',
         'vname' => 'LBL_DURATION_HOURS',
         'type' => 'int',
         'len' => '2',
         'comment' => 'Call duration, hours portion',
         'required' => true,
         'audited' => true,
      ),
      'duration_minutes' =>
      array(
         'name' => 'duration_minutes',
         'vname' => 'LBL_DURATION_MINUTES',
         'type' => 'int',
         'function' => array('name' => 'getDurationMinutesOptions', 'returns' => 'html', 'include' => 'modules/Calls/CallHelper.php'),
         'len' => '2',
         'group' => 'duration_hours',
         'importable' => 'required',
         'comment' => 'Call duration, minutes portion',
         'audited' => true,
      ),
      'date_start' =>
      array(
         'name' => 'date_start',
         'vname' => 'LBL_DATE',
         'type' => 'datetimecombo',
         'dbType' => 'datetime',
         'comment' => 'Date in which call is schedule to (or did) start',
         'importable' => 'required',
         'required' => true,
         'enable_range_search' => true,
         'options' => 'date_range_search_dom',
         'audited' => true,
      ),
      'date_end' =>
      array(
         'name' => 'date_end',
         'vname' => 'LBL_DATE_END',
         'type' => 'datetimecombo',
         'dbType' => 'datetime',
         'massupdate' => false,
         'comment' => 'Date is which call is scheduled to (or did) end',
         'enable_range_search' => true,
         'options' => 'date_range_search_dom',
         'audited' => true,
      ),
      'parent_type' =>
      array(
         'name' => 'parent_type',
         'vname' => 'LBL_PARENT_TYPE',
         'type' => 'parent_type',
         'dbType' => 'varchar',
         'required' => false,
         'group' => 'parent_name',
         'options' => 'parent_type_display',
         'len' => 255,
         'comment' => 'The Sugar object to which the call is related',
         'audited' => true,
      ),
      'parent_name' =>
      array(
         'name' => 'parent_name',
         'parent_type' => 'record_type_display',
         'type_name' => 'parent_type',
         'id_name' => 'parent_id',
         'vname' => 'LBL_LIST_RELATED_TO',
         'type' => 'parent',
         'group' => 'parent_name',
         'source' => 'non-db',
         'options' => 'parent_type_display',
         'audited' => true,
      ),
      'status' =>
      array(
         'name' => 'status',
         'vname' => 'LBL_STATUS',
         'type' => 'ColoredEnum',
         'dbType' => 'varchar',
         'len' => 100,
         'options' => 'call_status_dom',
         'options_colors' => 'call_status_dom_colored',
         'comment' => 'The status of the call (Held, Not Held, etc.)',
         'required' => true,
         'importable' => 'required',
         'default' => 'Planned',
         'studio' => array('detailview' => false),
         'audited' => true,
      ),
      'direction' =>
      array(
         'name' => 'direction',
         'vname' => 'LBL_DIRECTION',
         'type' => 'enum',
         'len' => 100,
         'options' => 'call_direction_dom',
         'comment' => 'Indicates whether call is inbound or outbound',
         'audited' => true,
      ),
      'parent_id' =>
      array(
         'name' => 'parent_id',
         'vname' => 'LBL_LIST_RELATED_TO_ID',
         'type' => 'id',
         'group' => 'parent_name',
         'reportable' => false,
         'comment' => 'The ID of the parent Sugar object identified by parent_type',
      ),
      'reminder_checked' => array(
         'name' => 'reminder_checked',
         'vname' => 'LBL_REMINDER',
         'type' => 'bool',
         'source' => 'non-db',
         'comment' => 'checkbox indicating whether or not the reminder value is set (Meta-data only)',
         'massupdate' => false,
         'studio' => false,
         'audited' => true,
      ),
      'reminder_time' =>
      array(
         'name' => 'reminder_time',
         'vname' => 'LBL_REMINDER_TIME',
         'type' => 'enum',
         'dbType' => 'int',
         'options' => 'reminder_time_options',
         'reportable' => false,
         'massupdate' => false,
         'default' => -1,
         'comment' => 'Specifies when a reminder alert should be issued; -1 means no alert; otherwise the number of seconds prior to the start',
         'studio' => false,
         'audited' => true,
      ),
      'email_reminder_checked' => array(
         'name' => 'email_reminder_checked',
         'vname' => 'LBL_EMAIL_REMINDER',
         'type' => 'bool',
         'source' => 'non-db',
         'comment' => 'checkbox indicating whether or not the email reminder value is set (Meta-data only)',
         'massupdate' => false,
         'studio' => false,
         'audited' => true,
      ),
      'email_reminder_time' =>
      array(
         'name' => 'email_reminder_time',
         'vname' => 'LBL_EMAIL_REMINDER_TIME',
         'type' => 'enum',
         'dbType' => 'int',
         'options' => 'reminder_time_options',
         'reportable' => false,
         'massupdate' => false,
         'default' => -1,
         'comment' => 'Specifies when a email reminder alert should be issued; -1 means no alert; otherwise the number of seconds prior to the start',
         'studio' => false,
         'audited' => true,
      ),
      'email_reminder_sent' => array(
         'name' => 'email_reminder_sent',
         'vname' => 'LBL_EMAIL_REMINDER_SENT',
         'default' => 0,
         'type' => 'bool',
         'comment' => 'Whether email reminder is already sent',
         'studio' => false,
         'massupdate' => false,
         'audited' => true,
      ),
      'reminders' => array(
         'required' => false,
         'name' => 'reminders',
         'vname' => 'LBL_REMINDER',
         'type' => 'function',
         'source' => 'non-db',
         'massupdate' => 0,
         'importable' => 'false',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => 0,
         'audited' => false,
         'reportable' => false,
         'function' =>
         array(
            'name' => 'Reminder::getRemindersListView',
            'returns' => 'html',
            'include' => 'modules/Reminders/Reminder.php'
         ),
      ),
      'outlook_id' =>
      array(
         'name' => 'outlook_id',
         'vname' => 'LBL_OUTLOOK_ID',
         'type' => 'varchar',
         'len' => '255',
         'reportable' => false,
         'comment' => 'When the Sugar Plug-in for Microsoft Outlook syncs an Outlook appointment, this is the Outlook appointment item ID',
         'audited' => false,
      ),
      'accept_status' => array(
         'name' => 'accept_status',
         'vname' => 'LBL_ACCEPT_STATUS',
         'dbType' => 'varchar',
         'type' => 'varchar',
         'len' => '20',
         'source' => 'non-db',
         'audited' => true,
      ),
      //bug 39559
      'set_accept_links' => array(
         'name' => 'accept_status',
         'vname' => 'LBL_ACCEPT_LINK',
         'dbType' => 'varchar',
         'type' => 'varchar',
         'len' => '20',
         'source' => 'non-db',
         'audited' => true,
      ),
      'leads' =>
      array(
         'name' => 'leads',
         'type' => 'link',
         'relationship' => 'calls_leads',
         'source' => 'non-db',
         'vname' => 'LBL_LEADS',
      ),
      // Bug #42619 Missed back-relation from Project module
      'project' => array(
         'name' => 'project',
         'type' => 'link',
         'relationship' => 'projects_calls',
         'source' => 'non-db',
         'vname' => 'LBL_PROJECTS'
      ),
      'case' =>
      array(
         'name' => 'case',
         'type' => 'link',
         'relationship' => 'case_calls',
         'source' => 'non-db',
         'link_type' => 'one',
         'vname' => 'LBL_CASE',
      ),
      'accounts' =>
      array(
         'name' => 'accounts',
         'type' => 'link',
         'relationship' => 'account_calls',
         'module' => 'Accounts',
         'bean_name' => 'Account',
         'source' => 'non-db',
         'vname' => 'LBL_ACCOUNT',
      ),
      'aos_contracts' =>
      array(
         'name' => 'aos_contracts',
         'type' => 'link',
         'relationship' => 'aos_contracts_calls',
         'source' => 'non-db',
         'vname' => 'LBL_CONTRACT',
      ),
      'users' =>
      array(
         'name' => 'users',
         'type' => 'link',
         'relationship' => 'calls_users',
         'source' => 'non-db',
         'vname' => 'LBL_USERS',
      ),
      'notes' =>
      array(
         'name' => 'notes',
         'type' => 'link',
         'relationship' => 'calls_notes',
         'module' => 'Notes',
         'bean_name' => 'Note',
         'source' => 'non-db',
         'vname' => 'LBL_NOTES',
      ),
      'created_by_link' =>
      array(
         'name' => 'created_by_link',
         'type' => 'link',
         'relationship' => 'calls_created_by',
         'vname' => 'LBL_CREATED_BY_USER',
         'link_type' => 'one',
         'module' => 'Users',
         'bean_name' => 'User',
         'source' => 'non-db',
      ),
      'modified_user_link' =>
      array(
         'name' => 'modified_user_link',
         'type' => 'link',
         'relationship' => 'calls_modified_user',
         'vname' => 'LBL_MODIFIED_BY_USER',
         'link_type' => 'one',
         'module' => 'Users',
         'bean_name' => 'User',
         'source' => 'non-db',
      ),
      'assigned_user_link' =>
      array(
         'name' => 'assigned_user_link',
         'type' => 'link',
         'relationship' => 'calls_assigned_user',
         'vname' => 'LBL_ASSIGNED_TO_USER',
         'link_type' => 'one',
         'module' => 'Users',
         'bean_name' => 'User',
         'source' => 'non-db',
      ),
      'repeat_type' =>
      array(
         'name' => 'repeat_type',
         'vname' => 'LBL_REPEAT_TYPE',
         'type' => 'enum',
         'len' => 36,
         'options' => 'repeat_type_dom',
         'comment' => 'Type of recurrence',
         'importable' => 'false',
         'massupdate' => false,
         'reportable' => false,
         'studio' => 'false',
         'audited' => true,
      ),
      'repeat_interval' =>
      array(
         'name' => 'repeat_interval',
         'vname' => 'LBL_REPEAT_INTERVAL',
         'type' => 'int',
         'len' => 3,
         'default' => 1,
         'comment' => 'Interval of recurrence',
         'importable' => 'false',
         'massupdate' => false,
         'reportable' => false,
         'studio' => 'false',
         'audited' => true,
      ),
      'repeat_dow' =>
      array(
         'name' => 'repeat_dow',
         'vname' => 'LBL_REPEAT_DOW',
         'type' => 'varchar',
         'len' => 7,
         'comment' => 'Days of week in recurrence',
         'importable' => 'false',
         'massupdate' => false,
         'reportable' => false,
         'studio' => 'false',
         'audited' => true,
      ),
      'repeat_until' =>
      array(
         'name' => 'repeat_until',
         'vname' => 'LBL_REPEAT_UNTIL',
         'type' => 'date',
         'comment' => 'Repeat until specified date',
         'importable' => 'false',
         'massupdate' => false,
         'reportable' => false,
         'studio' => 'false',
         'audited' => true,
      ),
      'repeat_count' =>
      array(
         'name' => 'repeat_count',
         'vname' => 'LBL_REPEAT_COUNT',
         'type' => 'int',
         'len' => 7,
         'comment' => 'Number of recurrence',
         'importable' => 'false',
         'massupdate' => false,
         'reportable' => false,
         'studio' => 'false',
         'audited' => true,
      ),
      'repeat_parent_id' =>
      array(
         'name' => 'repeat_parent_id',
         'vname' => 'LBL_REPEAT_PARENT_ID',
         'type' => 'id',
         'len' => 36,
         'comment' => 'Id of the first element of recurring records',
         'importable' => 'false',
         'massupdate' => false,
         'reportable' => false,
         'studio' => 'false',
      ),
      'recurring_source' =>
      array(
         'name' => 'recurring_source',
         'vname' => 'LBL_RECURRING_SOURCE',
         'type' => 'varchar',
         'len' => 36,
         'comment' => 'Source of recurring call',
         'importable' => false,
         'massupdate' => false,
         'reportable' => false,
         'studio' => false,
         'audited' => true,
      ),
      'reschedule_history' =>
      array(
         'required' => false,
         'name' => 'reschedule_history',
         'vname' => 'LBL_RESCHEDULE_HISTORY',
         'type' => 'varchar',
         'source' => 'non-db',
         'studio' => 'visible',
         'massupdate' => 0,
         'importable' => 'false',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => 0,
         'audited' => false,
         'reportable' => false,
         'function' =>
         array(
            'name' => 'reschedule_history',
            'returns' => 'html',
            'include' => 'modules/Calls/reschedule_history.php',
         ),
      ),
      'reschedule_count' =>
      array(
         'required' => false,
         'name' => 'reschedule_count',
         'vname' => 'LBL_RESCHEDULE_COUNT',
         'type' => 'varchar',
         'source' => 'non-db',
         'studio' => 'visible',
         'massupdate' => 0,
         'importable' => 'false',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => 0,
         'audited' => false,
         'reportable' => false,
         'function' =>
         array(
            'name' => 'reschedule_count',
            'returns' => 'html',
            'include' => 'modules/Calls/reschedule_history.php',
         ),
      ),
      'calls_reschedule' => array(
         'name' => 'calls_reschedule',
         'vname' => 'LBL_CALLS_RESCHEDULE',
         'type' => 'link',
         'relationship' => 'calls_reschedule',
         'module' => 'Calls_Reschedule',
         'bean_name' => 'Calls_Reschedule',
         'source' => 'non-db',
      ),
      'candidates' => array(
         'name' => 'candidates',
         'type' => 'link',
         'relationship' => 'calls_candidates',
         'source' => 'non-db',
         'vname' => 'LBL_CANDIDATES',
      ),
      'reservations' => array(
         'name' => 'reservations',
         'type' => 'link',
         'relationship' => 'reservations_calls',
         'module' => 'Reservations',
         'bean_name' => 'Reservations',
         'source' => 'non-db',
         'vname' => 'LBL_RESERVATIONS_CALLS',
      ),
      'resources' => array(
         'name' => 'resources',
         'type' => 'link',
         'relationship' => 'calls_resources',
         'source' => 'non-db',
         'vname' => 'LBL_RESOURCES',
      ),
   ),
   'indices' => array(
      array(
         'name' => 'idx_call_name',
         'type' => 'index',
         'fields' => array('name'),
      ),
      array(
         'name' => 'idx_status',
         'type' => 'index',
         'fields' => array('status'),
      ),
      array(
         'name' => 'idx_calls_date_start',
         'type' => 'index',
         'fields' => array('date_start'),
      ),
      array(
         'name' => 'idx_calls_date_end',
         'type' => 'index',
         'fields' => array('date_end'),
      ),
      array(
         'name' => 'idx_calls_duration_minutes',
         'type' => 'index',
         'fields' => array('duration_minutes'),
      ),
      array(
         'name' => 'idx_calls_duration_hours',
         'type' => 'index',
         'fields' => array('duration_hours'),
      ),
      array(
         'name' => 'idx_calls_par_del',
         'type' => 'index',
         'fields' => array('parent_id', 'parent_type', 'deleted')
      ),
      array(
         'name' => 'idx_calls_assigned_del',
         'type' => 'index',
         'fields' => array('deleted', 'assigned_user_id')),
   ),
   'relationships' => array(
      'calls_assigned_user' => array(
         'lhs_module' => 'Users',
         'lhs_table' => 'users',
         'lhs_key' => 'id',
         'rhs_module' => 'Calls',
         'rhs_table' => 'calls',
         'rhs_key' => 'assigned_user_id',
         'relationship_type' => 'one-to-many'
      ),
      'calls_modified_user' => array(
         'lhs_module' => 'Users',
         'lhs_table' => 'users',
         'lhs_key' => 'id',
         'rhs_module' => 'Calls',
         'rhs_table' => 'calls',
         'rhs_key' => 'modified_user_id',
         'relationship_type' => 'one-to-many'
      ),
      'calls_created_by' => array(
         'lhs_module' => 'Users',
         'lhs_table' => 'users',
         'lhs_key' => 'id',
         'rhs_module' => 'Calls',
         'rhs_table' => 'calls',
         'rhs_key' => 'created_by',
         'relationship_type' => 'one-to-many'
      ),
      'calls_notes' => array(
         'lhs_module' => 'Calls',
         'lhs_table' => 'calls',
         'lhs_key' => 'id',
         'rhs_module' => 'Notes',
         'rhs_table' => 'notes',
         'rhs_key' => 'parent_id',
         'relationship_type' => 'one-to-many',
         'relationship_role_column' => 'parent_type',
         'relationship_role_column_value' => 'Calls',
      ),
      'calls_reschedule' => array(
         'lhs_module' => 'Calls',
         'lhs_table' => 'calls',
         'lhs_key' => 'id',
         'rhs_module' => 'Calls_Reschedule',
         'rhs_table' => 'calls_reschedule',
         'rhs_key' => 'call_id',
         'relationship_type' => 'one-to-many',
      ),
   ),
//This enables optimistic locking for Saves From EditView
   'optimistic_locking' => true,
);

VardefManager::createVardef('Calls', 'Call', array('default', 'assignable', 'security_groups',
));

$dictionary['Call']['fields']['outlook_id']['audited'] = false;

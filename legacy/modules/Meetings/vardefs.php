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
$dictionary['Meeting'] = array(
   'table' => 'meetings',
   'audited' => true,
   'unified_search' => true,
   'full_text_search' => true,
   'unified_search_default_enabled' => true,
   'comment' => 'Meeting activities',
   'full_text_search_meta_field' => 'date_start',
   'fields' => array(
      'name' => array(

         'name' => 'name',
         'vname' => 'LBL_SUBJECT',
         'required' => true,
         'type' => 'name',
         'dbType' => 'varchar',
         'unified_search' => true,
         'full_text_search' =>array( 'boost' => 3 ),
         'len' => '255', 
         'comment' => 'Meeting name',
         'importable' => 'required',
         'audited' => true,
      ),
      'accept_status' =>array(
         'name' => 'accept_status',
         'vname' => 'LBL_ACCEPT_STATUS',
         'type' => 'varchar',
         'dbType' => 'varchar',
         'len' => '20',
         'source' => 'non-db',
         'audited' => true,
      ),
      //bug 39559
      'set_accept_links' =>array(
         'name' => 'accept_status',
         'vname' => 'LBL_ACCEPT_LINK',
         'type' => 'varchar',
         'dbType' => 'varchar',
         'len' => '20',
         'source' => 'non-db',
         'audited' => true,
      ),
      'location' =>
     array(
         'name' => 'location',
         'vname' => 'LBL_LOCATION',
         'type' => 'varchar',
         'len' => '50',
         'comment' => 'Meeting location',
         'audited' => true,
      ),
      'password' =>
     array(
         'name' => 'password',
         'vname' => 'LBL_PASSWORD',
         'type' => 'varchar',
         'len' => '50',
         'comment' => 'Meeting password',
         'studio' => 'false',
         'audited' => false,
      ),
      'join_url' =>
     array(
         'name' => 'join_url',
         'vname' => 'LBL_URL',
         'type' => 'varchar',
         'len' => '200',
         'comment' => 'Join URL',
         'studio' => 'false',
         'reportable' => false,
      ),
      'host_url' =>
     array(
         'name' => 'host_url',
         'vname' => 'LBL_HOST_URL',
         'type' => 'varchar',
         'len' => '400',
         'comment' => 'Host URL',
         'studio' => 'false',
         'reportable' => false,
         'audited' => true,
      ),
      'displayed_url' =>
     array(
         'name' => 'displayed_url',
         'vname' => 'LBL_DISPLAYED_URL',
         'type' => 'url',
         'len' => '400',
         'comment' => 'Meeting URL',
         'studio' => 'false',
         'audited' => true,
      ),
      'creator' =>
     array(
         'name' => 'creator',
         'vname' => 'LBL_CREATOR',
         'type' => 'varchar',
         'len' => '50',
         'comment' => 'Meeting creator',
         'studio' => 'false',
         'audited' => true,
      ),
      'external_id' =>
     array(
         'name' => 'external_id',
         'vname' => 'LBL_EXTERNALID',
         'type' => 'varchar',
         'len' => '50',
         'comment' => 'Meeting ID for external app API',
         'studio' => 'false',
         'audited' => false,
      ),
      'duration_hours' =>
     array(
         'name' => 'duration_hours',
         'vname' => 'LBL_DURATION_HOURS',
         'type' => 'int',
         'group' => 'duration',
         'len' => '3',
         'comment' => 'Duration (hours)',
         'importable' => 'required',
         'required' => true,
         'studio' => 'false',
         'audited' => true,
      ),
      'duration_minutes' =>
     array(
         'name' => 'duration_minutes',
         'vname' => 'LBL_DURATION_MINUTES',
         'type' => 'int',
         'group' => 'duration',
         'len' => '2',
         'comment' => 'Duration (minutes)',
         'studio' => 'false',
         'audited' => true,
      ),
      'date_start' =>
     array(
         'name' => 'date_start',
         'vname' => 'LBL_DATE',
         'type' => 'datetimecombo',
         'dbType' => 'datetime',
         'comment' => 'Date of start of meeting',
         'importable' => 'required',
         'required' => true,
         'enable_range_search' => true,
         'options' => 'date_range_search_dom',
         'validation' => array('type' => 'isbefore', 'compareto' => 'date_end', 'blank' => false),
         'audited' => true,
      ),
      'date_end' =>
     array(
         'name' => 'date_end',
         'vname' => 'LBL_DATE_END',
         'type' => 'datetimecombo',
         'dbType' => 'datetime',
         'massupdate' => false,
         'comment' => 'Date meeting ends',
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
         'group' => 'parent_name',
         'options' => 'parent_type_display',
         'len' => 100,
         'comment' => 'Module meeting is associated with',
         'studio' =>array( 'searchview' => false ),
         'audited' => true,
      ),
      'status' =>
     array(
         'name' => 'status',
         'vname' => 'LBL_STATUS',
         'type' => 'ColoredEnum',
         'dbType' => 'varchar',
         'len' => 100,
         'options' => 'meeting_status_dom',
         'options_colors' => 'meeting_status_dom_colored',
         'comment' => 'Meeting status (ex: Planned, Held, Not held)',
         'default' => 'Planned',
         'massupdate' => 1,
         'audited' => true,
      ),
      // Bug 24170 - Added only to allow the sidequickcreate form to work correctly
      'direction' =>
     array(
         'name' => 'direction',
         'vname' => 'LBL_DIRECTION',
         'type' => 'enum',
         'len' => 100,
         'options' => 'call_direction_dom',
         'comment' => 'Indicates whether call is inbound or outbound',
         'source' => 'non-db',
         'importable' => 'false',
         'massupdate' => false,
         'reportable' => false,
         'studio' => 'false',
         'audited' => true,
      ),
      'parent_id' =>
     array(
         'name' => 'parent_id',
         'vname' => 'LBL_PARENT_ID',
         'type' => 'id',
         'group' => 'parent_name',
         'reportable' => false,
         'comment' => 'ID of item indicated by parent_type',
         'studio' =>array( 'searchview' => false ),
      ),
      'reminder_checked' =>array(
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
      'email_reminder_checked' =>array(
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
      'email_reminder_sent' =>array(
         'name' => 'email_reminder_sent',
         'vname' => 'LBL_EMAIL_REMINDER_SENT',
         'default' => 0,
         'type' => 'bool',
         'comment' => 'Whether email reminder is already sent',
         'studio' => false,
         'massupdate' => false,
         'audited' => true,
      ),
      'reminders' =>array(
         'required' => false,
         'name' => 'reminders',
         'vname' => 'LBL_REMINDERS',
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
      'sequence' =>
     array(
         'name' => 'sequence',
         'vname' => 'LBL_SEQUENCE',
         'type' => 'int',
         'len' => '11',
         'reportable' => false,
         'default' => 0,
         'comment' => 'Meeting update sequence for meetings as per iCalendar standards',
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
      'users' =>
     array(
         'name' => 'users',
         'type' => 'link',
         'relationship' => 'meetings_users',
         'source' => 'non-db',
         'vname' => 'LBL_USERS',
      ),
      'accounts' =>
     array(
         'name' => 'accounts',
         'type' => 'link',
         'relationship' => 'account_meetings',
         'source' => 'non-db',
         'vname' => 'LBL_ACCOUNT',
      ),
      'leads' =>
     array(
         'name' => 'leads',
         'type' => 'link',
         'relationship' => 'meetings_leads',
         'source' => 'non-db',
         'vname' => 'LBL_LEADS',
      ),
      'opportunity' =>
     array(
         'name' => 'opportunity',
         'type' => 'link',
         'relationship' => 'opportunity_meetings',
         'source' => 'non-db',
         'vname' => 'LBL_OPPORTUNITY',
      ),
      'case' =>
     array(
         'name' => 'case',
         'type' => 'link',
         'relationship' => 'case_meetings',
         'source' => 'non-db',
         'vname' => 'LBL_CASE',
      ),
      'aos_contracts' =>
     array(
         'name' => 'aos_contracts',
         'type' => 'link',
         'relationship' => 'aos_contracts_meetings',
         'source' => 'non-db',
         'vname' => 'LBL_CONTRACT',
      ),
      'notes' =>
     array(
         'name' => 'notes',
         'type' => 'link',
         'relationship' => 'meetings_notes',
         'module' => 'Notes',
         'bean_name' => 'Note',
         'source' => 'non-db',
         'vname' => 'LBL_NOTES',
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
         'comment' => 'Source of recurring meeting',
         'importable' => false,
         'massupdate' => false,
         'reportable' => false,
         'studio' => false,
         'audited' => true,
      ),
      'duration' =>
     array(
         'name' => 'duration',
         'vname' => 'LBL_DURATION',
         'type' => 'enum',
         'options' => 'duration_dom',
         'comment' => 'Duration handler dropdown',
         'massupdate' => false,
         'reportable' => false,
         'importable' => false,
         'audited' => true,
      ),
      'gsync_id' =>
     array(
         'name' => 'gsync_id',
         'vname' => 'LBL_GSYNC_ID',
         'type' => 'varchar',
         'len' => 1024,
         'comment' => 'The internal Google ID of the event record',
         'isnull' => 'true',
         'massupdate' => false,
         'reportable' => false,
         'importable' => false,
         'studio' => false,
         'audited' => false,
      ),
      'gsync_lastsync' =>array(
         'name' => 'gsync_lastsync',
         'vname' => 'LBL_GSYNC_LASTSYNC',
         'type' => 'int',
         'comment' => 'The last time this record was synced with Google Account as unix time',
         'isnull' => 'true',
         'massupdate' => false,
         'reportable' => false,
         'importable' => false,
         'studio' => false,
         'audited' => false,
      ),
      'type' =>array(
         'name' => 'type',
         'vname' => 'LBL_TYPE',
         'required' => false,
         'type' => 'enum',
         'len' => 255,
         'comment' => 'Meeting type',
         'massupdate' => false,
         'audited' => true,
         'function' => ['name' => 'getDictionary', 
         'additional_params' => 'Meetings-type',
         'include' => 'include/utils/getDictionary.php'],
      ),
      'repeat_pane' =>array(
         'name' => 'repeat_pane',
         'vname' => 'LBL_REPEAT_PANE',
         'type' => 'varchar',
         'source' => 'non-db',
         'comment' => 'Repeat Pane',
         'importable' => 'false',
         'massupdate' => false,
         'reportable' => false,
         'studio' => 'false',
         'required' => false,
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
      ),
      'repeat_type' =>array(
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
      'repeat_interval' =>array(
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
      'repeat_dow' =>array(
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
      'repeat_until' =>array(
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
      'repeat_count' =>array(
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
      'repeat_parent_id' =>array(
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
      'trainings' =>array(
         'name' => 'trainings',
         'type' => 'link',
         'relationship' => 'trainings_meetings',
         'source' => 'non-db',
         'module' => 'Trainings',
         'bean_name' => false,
         'vname' => 'LBL_TRAININGS',
      ),
      'exitinterviews' =>array(
         'name' => 'exitinterviews',
         'type' => 'link',
         'relationship' => 'exitinterviews_meetings',
         'source' => 'non-db',
         'module' => 'ExitInterviews',
         'bean_name' => false,
         'vname' => 'LBL_EXITINTERVIEWS',
      ),
      'appraisals' =>array(
         'name' => 'appraisals',
         'type' => 'link',
         'relationship' => 'appraisals_meetings',
         'source' => 'non-db',
         'module' => 'ExitInterviews',
         'bean_name' => false,
         'vname' => 'LBL_APPRAISALS',
      ),
      'candidates' =>array(
         'name' => 'candidates',
         'type' => 'link',
         'relationship' => 'meetings_candidates',
         'source' => 'non-db',
         'vname' => 'LBL_CANDIDATES',
      ),
      'conclusions' =>array(
         'name' => 'conclusions',
         'type' => 'link',
         'relationship' => 'conclusions_meetings',
         'source' => 'non-db',
         'module' => 'Conclusions',
         'bean_name' => 'Conclusions',
         'vname' => 'LBL_CONCLUSIONS',
         'side' => 'right',
      ),
      'reservations' =>array(
         'name' => 'reservations',
         'type' => 'link',
         'relationship' => 'reservations_meetings',
         'module' => 'Reservations',
         'bean_name' => 'Reservations',
         'source' => 'non-db',
         'vname' => 'LBL_RESERVATIONS',
      ),
      'resources' =>array(
         'name' => 'resources',
         'type' => 'link',
         'relationship' => 'meetings_resources',
         'source' => 'non-db',
         'vname' => 'LBL_RESOURCES',
      ),
   ),
   'relationships' =>array(
      'meetings_assigned_user' =>array(
         'lhs_module' => 'Users',
         'lhs_table' => 'users',
         'lhs_key' => 'id',
         'rhs_module' => 'Meetings',
         'rhs_table' => 'meetings',
         'rhs_key' => 'assigned_user_id',
         'relationship_type' => 'one-to-many'
      ),
      'meetings_modified_user' =>array(
         'lhs_module' => 'Users',
         'lhs_table' => 'users',
         'lhs_key' => 'id',
         'rhs_module' => 'Meetings',
         'rhs_table' => 'meetings',
         'rhs_key' => 'modified_user_id',
         'relationship_type' => 'one-to-many'
      ),
      'meetings_created_by' =>array(
         'lhs_module' => 'Users',
         'lhs_table' => 'users',
         'lhs_key' => 'id',
         'rhs_module' => 'Meetings',
         'rhs_table' => 'meetings',
         'rhs_key' => 'created_by',
         'relationship_type' => 'one-to-many'
      ),
      'meetings_notes' =>array( 'lhs_module' => 'Meetings',
         'lhs_table' => 'meetings',
         'lhs_key' => 'id',
         'rhs_module' => 'Notes',
         'rhs_table' => 'notes',
         'rhs_key' => 'parent_id',
         'relationship_type' => 'one-to-many',
         'relationship_role_column' => 'parent_type',
         'relationship_role_column_value' => 'Meetings'
      )
   )
   , 'indices' =>array(

     array( 'name' => 'idx_mtg_name', 'type' => 'index', 'fields' =>array( 'name' ) ),
     array( 'name' => 'idx_meet_par_del', 'type' => 'index', 'fields' =>array( 'parent_id', 'parent_type', 'deleted' ) ),
     array( 'name' => 'idx_meet_stat_del', 'type' => 'index', 'fields' =>array( 'assigned_user_id', 'status', 'deleted' ) ),
     array( 'name' => 'idx_meet_date_start', 'type' => 'index', 'fields' =>array( 'date_start' ) ),
   )
//This enables optimistic locking for Saves From EditView
   , 'optimistic_locking' => true,
);

VardefManager::createVardef('Meetings', 'Meeting',array( 'default', 'assignable', 'security_groups',
));


$dictionary['Meeting']['fields']['assigned_user_id']['audited'] = false;
$dictionary['Meeting']['fields']['assigned_user_name']['audited'] = true;
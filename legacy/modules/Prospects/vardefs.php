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

$dictionary['Prospect'] = array(

    'table' => 'prospects',
    'unified_search' => true,
    'fields' => array(
        'tracker_key' => array(
            'name' => 'tracker_key',
            'vname' => 'LBL_TRACKER_KEY',
            'type' => 'int',
            'len' => '11',
            'required' => true,
            'auto_increment' => true,
            'importable' => 'false',
            'studio' => array('editview' => false),
        ),
        'birthdate' =>
            array(
                'name' => 'birthdate',
                'vname' => 'LBL_BIRTHDATE',
                'massupdate' => false,
                'type' => 'date',
            ),
        'do_not_call' =>
            array(
                'name' => 'do_not_call',
                'vname' => 'LBL_DO_NOT_CALL',
                'type' => 'bool',
                'default' => '0',
            ),
        'lead_id' =>
            array(
                'name' => 'lead_id',
                'type' => 'id',
                'reportable' => false,
                'vname' => 'LBL_LEAD_ID',
            ),
        'campaign_id' =>
            array(
                'name' => 'campaign_id',
                'comment' => 'Campaign that generated lead',
                'vname' => 'LBL_CAMPAIGN_ID',
                'rname' => 'id',
                'id_name' => 'campaign_id',
                'type' => 'id',
                'table' => 'campaigns',
                'isnull' => 'true',
                'module' => 'Campaigns',
                //'dbType' => 'char',
                'reportable' => false,
                'massupdate' => false,
                'duplicate_merge' => 'disabled',
            ),
        'email_addresses' =>
            array(
                'name' => 'email_addresses',
                'type' => 'link',
                'relationship' => 'prospects_email_addresses',
                'module' => 'EmailAddress',
                'source' => 'non-db',
                'vname' => 'LBL_EMAIL_ADDRESSES',
                'reportable' => false,
                'rel_fields' => array('primary_address' => array('type' => 'bool')),
            ),
        'email_addresses_primary' =>
            array(
                'name' => 'email_addresses_primary',
                'type' => 'link',
                'relationship' => 'prospects_email_addresses_primary',
                'source' => 'non-db',
                'vname' => 'LBL_EMAIL_ADDRESS_PRIMARY',
                'duplicate_merge' => 'disabled',
            ),
        'campaigns' =>
            array(
                'name' => 'campaigns',
                'type' => 'link',
                'relationship' => 'prospect_campaign_log',
                'module' => 'CampaignLog',
                'bean_name' => 'CampaignLog',
                'source' => 'non-db',
                'vname' => 'LBL_CAMPAIGNLOG',
            ),
        'prospect_lists' =>
            array(
                'name' => 'prospect_lists',
                'type' => 'link',
                'relationship' => 'prospect_list_prospects',
                'module' => 'ProspectLists',
                'source' => 'non-db',
                'vname' => 'LBL_PROSPECT_LIST',
            ),
        'calls' =>
            array(
                'name' => 'calls',
                'type' => 'link',
                'relationship' => 'prospect_calls',
                'source' => 'non-db',
                'vname' => 'LBL_CALLS',
            ),
        'meetings' =>
            array(
                'name' => 'meetings',
                'type' => 'link',
                'relationship' => 'prospect_meetings',
                'source' => 'non-db',
                'vname' => 'LBL_MEETINGS',
            ),
        'notes' =>
            array(
                'name' => 'notes',
                'type' => 'link',
                'relationship' => 'prospect_notes',
                'source' => 'non-db',
                'vname' => 'LBL_NOTES',
            ),
        'tasks' =>
            array(
                'name' => 'tasks',
                'type' => 'link',
                'relationship' => 'prospect_tasks',
                'source' => 'non-db',
                'vname' => 'LBL_TASKS',
            ),
        'emails' =>
            array(
                'name' => 'emails',
                'type' => 'link',
                'relationship' => 'emails_prospects_rel',
                'source' => 'non-db',
                'vname' => 'LBL_EMAILS',
            ),
        'fp_events_prospects_1' =>
            array(
                'name' => 'fp_events_prospects_1',
                'type' => 'link',
                'relationship' => 'fp_events_prospects_1',
                'source' => 'non-db',
                'vname' => 'LBL_FP_EVENTS_PROSPECTS_1_FROM_FP_EVENTS_TITLE',
            ),
        'e_invite_status_fields' =>
            array(
                'name' => 'e_invite_status_fields',
                'rname' => 'id',
                'relationship_fields' =>
                    array(
                        'id' => 'event_invite_id',
                        'invite_status' => 'event_status_name',
                    ),
                'vname' => 'LBL_CONT_INVITE_STATUS',
                'type' => 'relate',
                'link' => 'fp_events_prospects_1',
                'link_type' => 'relationship_info',
                'join_link_name' => 'fp_events_prospects_1',
                'source' => 'non-db',
                'importable' => 'false',
                'duplicate_merge' => 'disabled',
                'studio' => false,
            ),
        'event_status_name' =>
            array(
                'massupdate' => false,
                'name' => 'event_status_name',
                'type' => 'enum',
                'studio' => 'false',
                'source' => 'non-db',
                'vname' => 'LBL_LIST_INVITE_STATUS_EVENT',
                'options' => 'fp_event_invite_status_dom',
                'importable' => 'false',
            ),
        'event_invite_id' =>
            array(
                'name' => 'event_invite_id',
                'type' => 'varchar',
                'source' => 'non-db',
                'vname' => 'LBL_LIST_INVITE_STATUS',
                'studio' =>
                    array(
                        'listview' => false,
                    ),
            ),
        'e_accept_status_fields' =>
            array(
                'name' => 'e_accept_status_fields',
                'rname' => 'id',
                'relationship_fields' =>
                    array(
                        'id' => 'event_status_id',
                        'accept_status' => 'event_accept_status',
                    ),
                'vname' => 'LBL_CONT_ACCEPT_STATUS',
                'type' => 'relate',
                'link' => 'fp_events_prospects_1',
                'link_type' => 'relationship_info',
                'join_link_name' => 'fp_events_prospects_1',
                'source' => 'non-db',
                'importable' => 'false',
                'duplicate_merge' => 'disabled',
                'studio' => false,
            ),
        'event_accept_status' =>
            array(
                'massupdate' => false,
                'name' => 'event_accept_status',
                'type' => 'enum',
                'studio' => 'false',
                'source' => 'non-db',
                'vname' => 'LBL_LIST_ACCEPT_STATUS_EVENT',
                'options' => 'fp_event_status_dom',
                'importable' => 'false',
            ),
        'event_status_id' =>
            array(
                'name' => 'event_status_id',
                'type' => 'varchar',
                'source' => 'non-db',
                'vname' => 'LBL_LIST_ACCEPT_STATUS',
                'studio' =>
                    array(
                        'listview' => false,
                    ),
            ),
    ),

    'indices' =>
        array(
            array(
                'name' => 'prospect_auto_tracker_key',
                'type' => 'index',
                'fields' => array('tracker_key')
            ),
            array('name' => 'idx_prospects_last_first',
                'type' => 'index',
                'fields' => array(
                    'last_name',
                    'first_name',
                    'deleted'
                )
            ),
            array(
                'name' => 'idx_prospecs_del_last',
                'type' => 'index',
                'fields' => array(
                    'last_name',
                    'deleted'
                )
            ),
            array('name' => 'idx_prospects_id_del', 'type' => 'index', 'fields' => array('id', 'deleted')),
            array('name' => 'idx_prospects_assigned', 'type' => 'index', 'fields' => array('assigned_user_id')),

        ),

    'relationships' => array(
        'prospect_tasks' => array('lhs_module' => 'Prospects', 'lhs_table' => 'prospects', 'lhs_key' => 'id',
            'rhs_module' => 'Tasks', 'rhs_table' => 'tasks', 'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many', 'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'Prospects'),
        'prospect_notes' => array('lhs_module' => 'Prospects', 'lhs_table' => 'prospects', 'lhs_key' => 'id',
            'rhs_module' => 'Notes', 'rhs_table' => 'notes', 'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many', 'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'Prospects'),
        'prospect_meetings' => array('lhs_module' => 'Prospects', 'lhs_table' => 'prospects', 'lhs_key' => 'id',
            'rhs_module' => 'Meetings', 'rhs_table' => 'meetings', 'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many', 'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'Prospects'),
        'prospect_calls' => array('lhs_module' => 'Prospects', 'lhs_table' => 'prospects', 'lhs_key' => 'id',
            'rhs_module' => 'Calls', 'rhs_table' => 'calls', 'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many', 'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'Prospects'),
        'prospect_emails' => array('lhs_module' => 'Prospects', 'lhs_table' => 'prospects', 'lhs_key' => 'id',
            'rhs_module' => 'Emails', 'rhs_table' => 'emails', 'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many', 'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'Prospects'),
        'prospect_campaign_log' => array(
            'lhs_module' => 'Prospects',
            'lhs_table' => 'prospects',
            'lhs_key' => 'id',
            'rhs_module' => 'CampaignLog',
            'rhs_table' => 'campaign_log',
            'rhs_key' => 'target_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'target_type',
            'relationship_role_column_value' => 'Prospects'
        ),

    )
);
VardefManager::createVardef('Prospects', 'Prospect', array('default', 'assignable', 'security_groups',
    'person'));

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
 * Copyright (C) 2018-2019 MintHCM
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
/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/06_Customer_Center/10_Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */

$dictionary['Candidates']                                         = array(
    'table' => 'candidates',
    'audited' => true,
    'activity_enabled' => false,
    'duplicate_merge' => true,
    'fields' => array(
        'first_name' => array(
            'name' => 'first_name',
            'vname' => 'LBL_FIRST_NAME',
            'type' => 'varchar',
            'len' => '100',
            'unified_search' => true,
            'duplicate_on_record_copy' => 'always',
            'full_text_search' => array('enabled' => true, 'boost' => 3),
            'comment' => 'First name of the contact',
            'merge_filter' => 'selected',
            'audited' => true,
        ),
        'last_name' => array(
            'name' => 'last_name',
            'vname' => 'LBL_LAST_NAME',
            'type' => 'varchar',
            'len' => '100',
            'unified_search' => true,
            'duplicate_on_record_copy' => 'always',
            'full_text_search' => array('enabled' => true, 'boost' => 3),
            'comment' => 'Last name of the contact',
            'merge_filter' => 'selected',
            'required' => true,
            'importable' => 'required',
            'audited' => true,
        ),
        'phone_mobile' => array(
            'name' => 'phone_mobile',
            'vname' => 'LBL_MOBILE_PHONE',
            'type' => 'phone',
            'dbType' => 'varchar',
            'len' => 100,
            'unified_search' => true,
            'full_text_search' => array('enabled' => true, 'boost' => 1),
            'comment' => 'Mobile phone number of the contact',
            'merge_filter' => 'enabled',
            'duplicate_on_record_copy' => 'always',
            'audited' => true,
        ),
        "candidatures" => array(
            'name' => 'candidatures',
            'type' => 'link',
            'relationship' => 'candidates_candidatures',
            'source' => 'non-db',
            'module' => 'Candidatures',
            'bean_name' => 'Candidatures',
            'vname' => 'LBL_CANDIDATES_TITLE',
            'id_name' => 'candidate_id',
            'link-type' => 'many',
            'side' => 'left',
        ),
        'collaboration' => array(
            'name' => 'collaboration',
            'label' => 'LBL_COLLABORATION',
            'vname' => 'LBL_COLLABORATION',
            'comments' => '',
            'help' => '',
            'type' => 'bool',
            'max_size' => '255',
            'require_option' => '0',
            'default_value' => '0',
            'deleted' => '0',
            'importable' => false,
            'reportable' => false,
            'studio' => false,
            'audited' => false,
            'massupdate' => false,
            'duplicate_merge' => false,
            'full_text_search' => array(
                'boost' => '0',
                'enabled' => false,
            ),
            'enforced' => '',
            'dependency' => '',
        ),
        'documents' => array(
            'name' => 'documents',
            'type' => 'link',
            'relationship' => 'documents_candidates',
            'source' => 'non-db',
            'vname' => 'LBL_DOCUMENTS',
        ),
        'relocation' => array(
            'name' => 'relocation',
            'label' => 'LBL_RELOCATION',
            'vname' => 'LBL_RELOCATION',
            'comments' => '',
            'help' => '',
            'type' => 'bool',
            'max_size' => '255',
            'require_option' => '0',
            'default_value' => '0',
            'deleted' => '0',
            'audited' => true,
            'massupdate' => true,
            'duplicate_merge' => '1',
            'reportable' => '1',
            'importable' => 'true',
            'enforced' => '',
            'dependency' => '',
        ),
        "calls" => array(
            'name' => 'calls',
            'type' => 'link',
            'relationship' => 'candidates_calls',
            'source' => 'non-db',
            'module' => 'Calls',
            'bean_name' => 'Call',
            'vname' => 'LBL_CALLS_TITLE',
        ),
        "meetings" => array(
            'name' => 'meetings',
            'type' => 'link',
            'relationship' => 'candidates_meetings',
            'source' => 'non-db',
            'module' => 'Meetings',
            'bean_name' => 'Meeting',
            'vname' => 'LBL_MEETINGS_TITLE',
        ),
        "notes" => array(
            'name' => 'notes',
            'type' => 'link',
            'relationship' => 'candidates_notes',
            'source' => 'non-db',
            'module' => 'Notes',
            'bean_name' => 'Note',
            'vname' => 'LBL_NOTES_TITLE',
        ),
        "tasks" => array(
            'name' => 'tasks',
            'type' => 'link',
            'relationship' => 'candidates_tasks',
            'source' => 'non-db',
            'module' => 'Tasks',
            'bean_name' => 'Task',
            'vname' => 'LBL_TASKS_TITLE',
        ),
        "emails" => array(
            'name' => 'emails',
            'type' => 'link',
            'relationship' => 'candidates_emails',
            'source' => 'non-db',
            'module' => 'Emails',
            'bean_name' => 'Email',
            'vname' => 'LBL_EMAILS_TITLE',
        ),
        'primary_address_street' => array(
            'name' => 'primary_address_street',
            'vname' => 'LBL_PRIMARY_ADDRESS_STREET',
            'type' => 'text',
            'dbType' => 'varchar',
            'len' => '150',
            'comment' => 'The street address used for primary address',
            'group' => 'primary_address',
            'merge_filter' => 'enabled',
            'duplicate_on_record_copy' => 'always',
            'audited' => true,
        ),
        'primary_address_city' => array(
            'name' => 'primary_address_city',
            'vname' => 'LBL_PRIMARY_ADDRESS_CITY',
            'type' => 'varchar',
            'len' => '100',
            'group' => 'primary_address',
            'comment' => 'City for primary address',
            'merge_filter' => 'enabled',
            'duplicate_on_record_copy' => 'always',
            'audited' => true,
        ),
        'primary_address_state' => array(
            'name' => 'primary_address_state',
            'vname' => 'LBL_PRIMARY_ADDRESS_STATE',
            'type' => 'varchar',
            'len' => '100',
            'group' => 'primary_address',
            'comment' => 'State for primary address',
            'merge_filter' => 'enabled',
            'duplicate_on_record_copy' => 'always',
            'audited' => true,
        ),
        'primary_address_postalcode' => array(
            'name' => 'primary_address_postalcode',
            'vname' => 'LBL_PRIMARY_ADDRESS_POSTALCODE',
            'type' => 'varchar',
            'len' => '20',
            'group' => 'primary_address',
            'comment' => 'Postal code for primary address',
            'merge_filter' => 'enabled',
            'duplicate_on_record_copy' => 'always',
            'audited' => true,
        ),
        'primary_address_country' => array(
            'name' => 'primary_address_country',
            'vname' => 'LBL_PRIMARY_ADDRESS_COUNTRY',
            'type' => 'varchar',
            'group' => 'primary_address',
            'comment' => 'Country for primary address',
            'merge_filter' => 'enabled',
            'duplicate_on_record_copy' => 'always',
            'audited' => true,
            'default' => 'Polska',
        ),
        'alt_address_street' => array(
            'name' => 'alt_address_street',
            'vname' => 'LBL_ALT_ADDRESS_STREET',
            'type' => 'text',
            'dbType' => 'varchar',
            'len' => '150',
            'group' => 'alt_address',
            'comment' => 'Street address for alternate address',
            'merge_filter' => 'enabled',
            'duplicate_on_record_copy' => 'always',
            'audited' => true,
        ),
        'alt_address_city' => array(
            'name' => 'alt_address_city',
            'vname' => 'LBL_ALT_ADDRESS_CITY',
            'type' => 'varchar',
            'len' => '100',
            'group' => 'alt_address',
            'comment' => 'City for alternate address',
            'merge_filter' => 'enabled',
            'duplicate_on_record_copy' => 'always',
            'audited' => true,
        ),
        'alt_address_state' => array(
            'name' => 'alt_address_state',
            'vname' => 'LBL_ALT_ADDRESS_STATE',
            'type' => 'varchar',
            'len' => '100',
            'group' => 'alt_address',
            'comment' => 'State for alternate address',
            'merge_filter' => 'enabled',
            'duplicate_on_record_copy' => 'always',
            'audited' => true,
        ),
        'alt_address_postalcode' => array(
            'name' => 'alt_address_postalcode',
            'vname' => 'LBL_ALT_ADDRESS_POSTALCODE',
            'type' => 'varchar',
            'len' => '20',
            'group' => 'alt_address',
            'comment' => 'Postal code for alternate address',
            'merge_filter' => 'enabled',
            'duplicate_on_record_copy' => 'always',
            'audited' => true,
        ),
        'alt_address_country' => array(
            'name' => 'alt_address_country',
            'vname' => 'LBL_ALT_ADDRESS_COUNTRY',
            'type' => 'varchar',
            'group' => 'alt_address',
            'comment' => 'Country for alternate address',
            'merge_filter' => 'enabled',
            'duplicate_on_record_copy' => 'always',
            'audited' => true,
            'default' => 'Polska',
        ),
        'birthdate' => array(
            'name' => 'birthdate',
            'label' => 'LBL_BIRTHDATE',
            'vname' => 'LBL_BIRTHDATE',
            'comments' => '',
            'help' => 'In case of no informations about date of birth, type oriented year with 1st January',
            'type' => 'date',
            'required' => true,
            'audited' => false,
            'mass_update' => false,
            'duplicate_merge' => '1',
            'reportable' => true,
            'importable' => true,
            'options' => 'date_range_search_dom',
            'enable_range_search' => '1',
        ),
        'goldenline' => array(
            'name' => 'goldenline',
            'label' => 'LBL_GOLDENLINE',
            'vname' => 'LBL_GOLDENLINE',
            'comments' => '',
            'type' => 'url',
            'max_size' => '255',
            'audited' => false,
            'mass_update' => false,
            'duplicate_merge' => '1',
            'reportable' => false,
            'importable' => true,
            'link_target' => '_blank',
            'full_text_search' => array(
                'boost' => '0',
                'enabled' => false,
            ),
        ),
        'linkedin' => array(
            'name' => 'linkedin',
            'label' => 'LBL_LINKEDIN_ACCOUNT',
            'vname' => 'LBL_LINKEDIN_ACCOUNT',
            'comments' => '',
            'type' => 'url',
            'max_size' => '255',
            'audited' => false,
            'mass_update' => false,
            'duplicate_merge' => '1',
            'reportable' => false,
            'importable' => true,
            'link_target' => '_blank',
            'full_text_search' => array(
                'boost' => '0',
                'enabled' => false,
            ),
        ),
        'skype' => array(
            'name' => 'skype',
            'label' => 'LBL_SKYPE',
            'vname' => 'LBL_SKYPE',
            'comments' => '',
            'type' => 'varchar',
            'max_size' => '255',
            'audited' => false,
            'mass_update' => false,
            'duplicate_merge' => '1',
            'reportable' => false,
            'importable' => true,
            'full_text_search' => array(
                'boost' => '0',
                'enabled' => false,
            ),
        ),
        'facebook' => array(
            'name' => 'facebook',
            'label' => 'LBL_FACEBOOK',
            'vname' => 'LBL_FACEBOOK',
            'comments' => '',
            'type' => 'url',
            'max_size' => '255',
            'audited' => false,
            'mass_update' => false,
            'duplicate_merge' => '1',
            'reportable' => false,
            'importable' => true,
            'full_text_search' => array(
                'boost' => '0',
                'enabled' => false,
            ),
        ),
        'potential' => array(
            'required' => false,
            'name' => 'potential',
            'vname' => 'LBL_POTENTIAL',
            'type' => 'enum',
            'audited' => true,
            'default' => 'Not_applicable',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'massupdate' => false,
            'importable' => false,
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => 100,
            'size' => '20',
            'options' => 'potential_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'm_accept_status_fields' =>
        array(
            'name' => 'm_accept_status_fields',
            'rname' => 'id',
            'relationship_fields' => array(
                'id' => 'accept_status_id',
                'accept_status' => 'accept_status_name'
            ),
            'vname' => 'LBL_LIST_ACCEPT_STATUS',
            'type' => 'relate',
            'link' => 'meetings',
            'link_type' => 'relationship_info',
            'source' => 'non-db',
            'importable' => 'false',
            'hideacl' => true,
            'duplicate_merge' => 'disabled',
            'studio' => false,
        ),
        'c_accept_status_fields' =>
        array(
            'name' => 'c_accept_status_fields',
            'rname' => 'id',
            'relationship_fields' => array(
                'id' => 'accept_status_id',
                'accept_status' => 'accept_status_name'
            ),
            'vname' => 'LBL_LIST_ACCEPT_STATUS',
            'type' => 'relate',
            'link' => 'calls',
            'link_type' => 'relationship_info',
            'source' => 'non-db',
            'importable' => 'false',
            'hideacl' => true,
            'duplicate_merge' => 'disabled',
            'studio' => false,
        ),
        'accept_status_id' =>
        array(
            'name' => 'accept_status_id',
            'type' => 'varchar',
            'source' => 'non-db',
            'vname' => 'LBL_LIST_ACCEPT_STATUS',
            'studio' => array(
                'listview' => false
            ),
        ),
        'accept_status_name' =>
        array(
            'massupdate' => false,
            'name' => 'accept_status_name',
            'type' => 'enum',
            'source' => 'non-db',
            'vname' => 'LBL_LIST_ACCEPT_STATUS',
            'options' => 'dom_meeting_accept_status',
            'importable' => 'false',
        ),
        'meetings' =>
        array(
            'name' => 'meetings',
            'type' => 'link',
            'relationship' => 'meetings_candidates',
            'source' => 'non-db',
            'vname' => 'LBL_MEETINGS',
        ),
        'calls' =>
        array(
            'name' => 'calls',
            'type' => 'link',
            'relationship' => 'calls_candidates',
            'source' => 'non-db',
            'vname' => 'LBL_CALLS',
        ),
        'employees' => array(
            'name' => 'employees',
            'type' => 'link',
            'relationship' => 'candidates_employees',
            'source' => 'non-db',
            'module' => 'Employees',
            'bean_name' => 'Employee',
            'vname' => 'LBL_CANDIDATE_EMPLOYEE_LINK_FROM_CANDIDATE',
            'id_name' => 'employee_id',
        ),
        'employee_name' => array(
            'name' => 'employee_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_CANDIDATE_EMPLOYEE_RELATE_FROM_CANDIDATE',
            'save' => true,
            'id_name' => 'employee_id',
            'link' => 'employees',
            'table' => 'users',
            'module' => 'Employees',
            'rname' => 'name',
        ),
        'employee_id' => array(
            'name' => 'employee_id',
            'type' => 'link',
            'relationship' => 'candidates_employees',
            'source' => 'non-db',
            'reportable' => false,
            'side' => 'left',
            'vname' => 'LBL_CANDIDATE_EMPLOYEE_ID_FROM_CANDIDATE',
            'audited' => true,
        ),
        "certificates" => array(
            'name' => 'certificates',
            'type' => 'link',
            'relationship' => 'candidates_certificates',
            'source' => 'non-db',
            'module' => 'Certificates',
            'bean_name' => 'Certificates',
            'vname' => 'LBL_RELATIONSHIP_CERTIFICATES_NAME',
            'side' => 'right',
        ),
    ),
    'relationships' => array(
        'candidates_candidatures' =>
        array(
            'lhs_module' => 'Candidates',
            'lhs_table' => 'candidates',
            'lhs_key' => 'id',
            'rhs_module' => 'Candidatures',
            'rhs_table' => 'candidatures',
            'rhs_key' => 'candidate_id',
            'relationship_type' => 'one-to-many',
        ),
        'candidates_calls' =>
        array(
            'lhs_module' => 'Candidates',
            'lhs_table' => 'candidates',
            'lhs_key' => 'id',
            'rhs_module' => 'Calls',
            'rhs_table' => 'calls',
            'relationship_role_column_value' => 'Candidates',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
        ),
        'candidates_meetings' =>
        array(
            'lhs_module' => 'Candidates',
            'lhs_table' => 'candidates',
            'lhs_key' => 'id',
            'rhs_module' => 'Meetings',
            'rhs_table' => 'meetings',
            'relationship_role_column_value' => 'Candidates',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
        ),
        'candidates_emails' =>
        array(
            'lhs_module' => 'Candidates',
            'lhs_table' => 'candidates',
            'lhs_key' => 'id',
            'rhs_module' => 'Emails',
            'rhs_table' => 'emails',
            'relationship_role_column_value' => 'Candidates',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'emails_beans',
            'join_key_rhs' => 'email_id',
            'join_key_lhs' => 'bean_id',
            'relationship_role_column' => 'bean_module',
        ),
        'candidates_notes' =>
        array(
            'lhs_module' => 'Candidates',
            'lhs_table' => 'candidates',
            'lhs_key' => 'id',
            'rhs_module' => 'Notes',
            'rhs_table' => 'notes',
            'relationship_role_column_value' => 'candidates',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
        ),
        'candidates_tasks' =>
        array(
            'lhs_module' => 'Candidates',
            'lhs_table' => 'candidates',
            'lhs_key' => 'id',
            'rhs_module' => 'Tasks',
            'rhs_table' => 'tasks',
            'relationship_role_column_value' => 'Candidates',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
        ),
    ),
    'optimistic_locking' => true,
    'unified_search' => true,
);
$dictionary['Candidates']['fields']['do_not_call']['mass_update'] = false;
$dictionary['Candidates']['fields']['phone_work']['audited']      = false;

if (!class_exists('VardefManager')) {
    require_once 'include/SugarObjects/VardefManager.php';
}
VardefManager::createVardef('Candidates', 'Candidates',
    array('basic', 'assignable', 'person', 'security_groups'));

$dictionary['Candidates']['fields']['date_reviewed']['audited']          = false;
$dictionary['Candidates']['fields']['date_reviewed']['reportable']       = false;
$dictionary['Candidates']['fields']['lawful_basis_source']['audited']    = false;
$dictionary['Candidates']['fields']['lawful_basis_source']['reportable'] = false;
$dictionary['Candidates']['fields']['lawful_basis']['audited']           = false;
$dictionary['Candidates']['fields']['lawful_basis']['reportable']        = false;

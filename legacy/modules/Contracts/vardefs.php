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
$module_name              = 'Contracts';
$dictionary[$module_name] = array(
    'table' => 'contracts',
    'audited' => true,
    'inline_edit' => true,
    'duplicate_merge' => true,
    'fields' => array(
        'date_of_signing' =>
        array(
            'required' => false,
            'name' => 'date_of_signing',
            'vname' => 'LBL_DATE_OF_SIGNING',
            'type' => 'date',
            'massupdate' => '1',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '1',
            'audited' => true,
            'inline_edit' => '',
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'date_range_search_dom',
        ),
        'contract_starting_date' =>
        array(
            'required' => false,
            'name' => 'contract_starting_date',
            'vname' => 'LBL_CONTRACT_STARTING_DATE',
            'type' => 'date',
            'massupdate' => '1',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '1',
            'audited' => true,
            'inline_edit' => '',
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'date_range_search_dom',
        ),
        'contract_ending_date' =>
        array(
            'required' => false,
            'name' => 'contract_ending_date',
            'vname' => 'LBL_CONTRACT_ENDING_DATE',
            'type' => 'date',
            'massupdate' => '1',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '1',
            'audited' => true,
            'inline_edit' => '',
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'date_range_search_dom',
        ),
        'status' =>
        array(
            'required' => false,
            'name' => 'status',
            'vname' => 'LBL_STATUS',
            'type' => 'enum',
            'massupdate' => '1',
            'default' => 'active',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '1',
            'audited' => true,
            'inline_edit' => '',
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => 100,
            'size' => '20',
            'options' => 'contract_status_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'contract_type' =>
        array(
            'required' => true,
            'name' => 'contract_type',
            'vname' => 'LBL_CONTRACT_TYPE',
            'type' => 'enum',
            'massupdate' => '1',
            'default' => '',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '1',
            'audited' => true,
            'inline_edit' => '',
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => 100,
            'size' => '20',
            'options' => 'contract_type_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'daily_working_time' =>
        array(
            'required' => false,
            'name' => 'daily_working_time',
            'vname' => 'LBL_DAILY_WORKING_TIME',
            'type' => 'enum',
            'massupdate' => '1',
            'default' => '',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '1',
            'audited' => true,
            'inline_edit' => '',
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => 100,
            'size' => '20',
            'options' => 'daily_working_time_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'termsofemployment' => array(
            'name' => 'termsofemployment',
            'type' => 'link',
            'relationship' => 'contracts_termsofemployment',
            'source' => 'non-db',
            'module' => 'TermsOfEmployment',
            'bean_name' => 'TermsOfEmployment',
            'vname' => 'LBL_TERMSOFEMPLOYMENT',
            'side' => 'right',
        ),
        'periodsofemployment' => array(
            'name' => 'periodsofemployment',
            'type' => 'link',
            'relationship' => 'periodsofemployment_contracts',
            'source' => 'non-db',
            'module' => 'PeriodsOfEmployment',
            'bean_name' => 'PeriodsOfEmployment',
            'vname' => 'LBL_PERIODSOFEMPLOYMENT',
            'id_name' => 'periodofemployment_id',
        ),
        'periodofemployment_name' => array(
            'name' => 'periodofemployment_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_PERIODOFEMPLOYMENT_NAME',
            'save' => true,
            'id_name' => 'periodofemployment_id',
            'link' => 'periodsofemployment_contracts',
            'table' => 'periodsofemployment',
            'module' => 'PeriodsOfEmployment',
            'rname' => 'name',
        ),
        'periodofemployment_id' => array(
            'name' => 'periodofemployment_id',
            'type' => 'id',
            'relationship' => 'periodsofemployment_contracts',
            'reportable' => false,
            'rname' => 'id',
            'isnull' => 'true',
            'dbType' => 'id',
            'vname' => 'LBL_PERIODOFEMPLOYMENT_ID',
        ),
        'documents' => array(
            'name' => 'documents',
            'type' => 'link',
            'relationship' => 'documents_contracts',
            'source' => 'non-db',
            'vname' => 'LBL_DOCUMENTS',
        ),
    ),
    'relationships' => array(
        "periodsofemployment_contracts" => array(
            'lhs_module' => 'PeriodsOfEmployment',
            'lhs_table' => 'periodsofemployment',
            'lhs_key' => 'id',
            'rhs_module' => $module_name,
            'rhs_table' => 'contracts',
            'rhs_key' => 'periodofemployment_id',
            'relationship_type' => 'one-to-many',
        ),
    ),
    'optimistic_locking' => true,
    'unified_search' => true,
);
if (!class_exists('VardefManager')) {
    require_once('include/SugarObjects/VardefManager.php');
}

VardefManager::createVardef($module_name, $module_name,
    array(
    'basic',
    'assignable',
    'security_groups',
    'employee_related'
));

$dictionary[$module_name]['fields']['employee_name']['required'] = true;


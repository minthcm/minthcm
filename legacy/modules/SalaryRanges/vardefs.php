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

$dictionary['SalaryRanges'] = array(
    'table' => 'salaryranges',
    'audited' => true,
    'duplicate_merge' => true,
    'fields' => array(
        'start_date' => array(
            'name' => 'start_date',
            'vname' => 'LBL_START_DATE',
            'label' => 'LBL_START_DATE',
            'type' => 'date',
            'required' => true,
            'importable' => 'required',
            'reportable' => true,
            'audited' => true,
            'validation' => array(
                'type' => 'isbefore',
                'compareto' => 'end_date',
                'blank' => true,
            ),
            'enable_range_search' => true,
            'options' => 'date_range_search_dom',
        ),
        'end_date' => array(
            'name' => 'end_date',
            'vname' => 'LBL_END_DATE',
            'label' => 'LBL_END_DATE',
            'type' => 'date',
            'importable' => 'true',
            'reportable' => true,
            'audited' => true,
            'enable_range_search' => true,
            'options' => 'date_range_search_dom',
        ),
        'gross_value_from' => array(
            'name' => 'gross_value_from',
            'vname' => 'LBL_GROSS_VALUE_FROM',
            'label' => 'LBL_GROSS_VALUE_FROM',
            'type' => 'currency',
            'dbType' => 'currency',
            'enable_range_search' => true,
            'related_fields' => array(
                'currency_id',
                'base_rate',
            ),
            'convertToBase' => true,
            'showTransactionalAmount' => true,
            'required' => true,
            'importable' => 'required',
            'reportable' => true,
            'audited' => true,
        ),
        'gross_value_from_usdollar' => array(
            'name' => 'gross_value_from_usdollar',
            'vname' => 'LBL_GROSS_VALUE_FROM_USDOLLAR',
            'label' => 'LBL_GROSS_VALUE_FROM_USDOLLAR',
            'type' => 'currency',
            'group' => 'gross_value_from',
            'dbType' => 'currency',
            'disable_num_format' => true,
            'duplicate_merge' => 'disabled',
            'studio' => false,
            'readonly' => true,
            'is_base_currency' => true,
            'related_fields' => array(
                'currency_id',
                'base_rate',
            ),
            'formula' => 'ifElse(isNumeric($gross_value_from), currencyDivide($gross_value_from, $base_rate), "")',
            'calculated' => true,
            'enforced' => true,
            'reportable' => false,
            'audited' => false,
            'importable' => false,
        ),
        'gross_value_to' => array(
            'name' => 'gross_value_to',
            'vname' => 'LBL_GROSS_VALUE_TO',
            'label' => 'LBL_GROSS_VALUE_TO',
            'type' => 'currency',
            'dbType' => 'currency',
            'enable_range_search' => true,
            'related_fields' => array(
                'currency_id',
                'base_rate',
            ),
            'convertToBase' => true,
            'showTransactionalAmount' => true,
            'required' => true,
            'importable' => 'required',
            'reportable' => true,
            'audited' => true,
        ),
        'gross_value_to_usdollar' => array(
            'name' => 'gross_value_to_usdollar',
            'vname' => 'LBL_GROSS_VALUE_TO_USDOLLAR',
            'label' => 'LBL_GROSS_VALUE_TO_USDOLLAR',
            'type' => 'currency',
            'group' => 'gross_value_to',
            'dbType' => 'currency',
            'disable_num_format' => true,
            'duplicate_merge' => 'disabled',
            'studio' => false,
            'readonly' => true,
            'is_base_currency' => true,
            'related_fields' => array(
                'currency_id',
                'base_rate',
            ),
            'formula' => 'ifElse(isNumeric($gross_value_to), currencyDivide($gross_value_to, $base_rate), "")',
            'calculated' => true,
            'enforced' => true,
            'reportable' => false,
            'audited' => false,
            'importable' => false,
        ),
        'currency_id' => array(
            'name' => 'currency_id',
            'dbType' => 'id',
            'vname' => 'LBL_CURRENCY_ID',
            'type' => 'currency_id',
            'function' => 'getCurrencies',
            'function_bean' => 'Currencies',
            'required' => false,
            'reportable' => false,
            'default' => '-99',
            'importable' => false,
        ),
        'net_value_from' => array(
            'name' => 'net_value_from',
            'vname' => 'LBL_NET_VALUE_FROM',
            'label' => 'LBL_NET_VALUE_FROM',
            'type' => 'currency',
            'dbType' => 'currency',
            'enable_range_search' => true,
            'related_fields' => array(
                'currency_id',
                'base_rate',
            ),
            'convertToBase' => true,
            'showTransactionalAmount' => true,
            'required' => true,
            'importable' => 'required',
            'reportable' => true,
            'audited' => true,
        ),
        'net_value_from_usdollar' => array(
            'name' => 'net_value_from_usdollar',
            'vname' => 'LBL_NET_VALUE_FROM_USDOLLAR',
            'label' => 'LBL_NET_VALUE_FROM_USDOLLAR',
            'type' => 'currency',
            'group' => 'net_value_from',
            'dbType' => 'currency',
            'disable_num_format' => true,
            'duplicate_merge' => 'disabled',
            'studio' => false,
            'readonly' => true,
            'is_base_currency' => true,
            'related_fields' => array(
                'currency_id',
                'base_rate',
            ),
            'formula' => 'ifElse(isNumeric($net_value_from), currencyDivide($net_value_from, $base_rate), "")',
            'calculated' => true,
            'enforced' => true,
            'reportable' => false,
            'audited' => false,
            'importable' => false,
        ),
        'net_value_to' => array(
            'name' => 'net_value_to',
            'vname' => 'LBL_NET_VALUE_TO',
            'label' => 'LBL_NET_VALUE_TO',
            'type' => 'currency',
            'dbType' => 'currency',
            'enable_range_search' => true,
            'related_fields' => array(
                'currency_id',
                'base_rate',
            ),
            'convertToBase' => true,
            'showTransactionalAmount' => true,
            'required' => true,
            'importable' => 'required',
            'reportable' => true,
            'audited' => true,
        ),
        'net_value_to_usdollar' => array(
            'name' => 'net_value_to_usdollar',
            'vname' => 'LBL_NET_VALUE_TO_USDOLLAR',
            'label' => 'LBL_NET_VALUE_TO_USDOLLAR',
            'type' => 'currency',
            'group' => 'net_value_to',
            'dbType' => 'currency',
            'disable_num_format' => true,
            'duplicate_merge' => 'disabled',
            'studio' => false,
            'readonly' => true,
            'is_base_currency' => true,
            'related_fields' => array(
                'currency_id',
                'base_rate',
            ),
            'formula' => 'ifElse(isNumeric($net_value_to), currencyDivide($net_value_to, $base_rate), "")',
            'calculated' => true,
            'enforced' => true,
            'reportable' => false,
            'audited' => false,
            'importable' => false,
        ),
        'employer_costs_from' => array(
            'name' => 'employer_costs_from',
            'vname' => 'LBL_EMPLOYER_COSTS_FROM',
            'label' => 'LBL_EMPLOYER_COSTS_FROM',
            'type' => 'currency',
            'dbType' => 'currency',
            'enable_range_search' => true,
            'related_fields' => array(
                'currency_id',
                'base_rate',
            ),
            'convertToBase' => true,
            'showTransactionalAmount' => true,
            'required' => true,
            'importable' => 'required',
            'reportable' => true,
            'audited' => true,
        ),
        'employer_costs_from_usdollar' => array(
            'name' => 'employer_costs_from_usdollar',
            'vname' => 'LBL_EMPLOYER_COSTS_FROM_USDOLLAR',
            'label' => 'LBL_EMPLOYER_COSTS_FROM_USDOLLAR',
            'type' => 'currency',
            'group' => 'employer_costs_from',
            'dbType' => 'currency',
            'disable_num_format' => true,
            'duplicate_merge' => 'disabled',
            'studio' => false,
            'readonly' => true,
            'is_base_currency' => true,
            'related_fields' => array(
                'currency_id',
                'base_rate',
            ),
            'formula' => 'ifElse(isNumeric($employer_costs_from), currencyDivide($employer_costs_from, $base_rate), "")',
            'calculated' => true,
            'enforced' => true,
            'reportable' => false,
            'audited' => false,
            'importable' => false,
        ),
        'employer_costs_to' => array(
            'name' => 'employer_costs_to',
            'vname' => 'LBL_EMPLOYER_COSTS_TO',
            'label' => 'LBL_EMPLOYER_COSTS_TO',
            'type' => 'currency',
            'dbType' => 'currency',
            'enable_range_search' => true,
            'related_fields' => array(
                'currency_id',
                'base_rate',
            ),
            'convertToBase' => true,
            'showTransactionalAmount' => true,
            'required' => true,
            'importable' => 'required',
            'reportable' => true,
            'audited' => true,
        ),
        'employer_costs_to_usdollar' => array(
            'name' => 'employer_costs_to_usdollar',
            'vname' => 'LBL_EMPLOYER_COSTS_TO_USDOLLAR',
            'label' => 'LBL_EMPLOYER_COSTS_TO_USDOLLAR',
            'type' => 'currency',
            'group' => 'employer_costs_to',
            'dbType' => 'currency',
            'disable_num_format' => true,
            'duplicate_merge' => 'disabled',
            'studio' => false,
            'readonly' => true,
            'is_base_currency' => true,
            'related_fields' => array(
                'currency_id',
                'base_rate',
            ),
            'formula' => 'ifElse(isNumeric($employer_costs_to), currencyDivide($employer_costs_to, $base_rate), "")',
            'calculated' => true,
            'enforced' => true,
            'reportable' => false,
            'audited' => false,
            'importable' => false,
        ),
        'positions' => array(
            'name' => 'positions',
            'type' => 'link',
            'relationship' => 'position_salaryranges',
            'source' => 'non-db',
            'module' => 'Positions',
            'bean_name' => 'Positions',
            'vname' => 'LBL_POSITIONS',
            'label' => 'LBL_POSITIONS',
            'id_link' => 'position_id',
        ),
        'position_id' => array(
            'name' => 'position_id',
            'relationship' => 'position_salaryranges',
            'type' => 'id',
            'vname' => 'LBL_POSITION_ID',
            'label' => 'LBL_POSITION_ID',
            'audited' => true,
            'importable' => true,
            'reportable' => true,
        ),
        'position_name' => array(
            'name' => 'position_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_POSITION_NAME',
            'label' => 'LBL_POSITION_NAME',
            'id_name' => 'position_id',
            'link' => 'position',
            'module' => 'Positions',
            'table' => 'positions',
            'rname' => 'name',
            'importable' => true,
            'reportable' => true,
            'audited' => true,
            'required' => true,
        ),

    ),
    'relationships' => array(
        'position_salaryranges' => array(
            'lhs_module' => 'Positions',
            'lhs_table' => 'positions',
            'lhs_key' => 'id',
            'rhs_module' => 'SalaryRanges',
            'rhs_table' => 'salaryranges',
            'rhs_key' => 'position_id',
            'relationship_type' => 'one-to-many',
        ),
    ),
    'optimistic_locking' => true,
    'unified_search' => true,
    'full_text_search' => true,
);

if (!class_exists('VardefManager')) {
    require_once 'include/SugarObjects/VardefManager.php';
}
VardefManager::createVardef('SalaryRanges', 'SalaryRanges', array('basic', 'assignable', 'security_groups'));

$dictionary['SalaryRanges']['fields']['name']['vt_readonly'] = true;
$dictionary['SalaryRanges']['fields']['name']['audited'] = false;
$dictionary['SalaryRanges']['fields']['name']['related_fields'] = array(
    'position_name',
    'start_date',
    'end_date',
);
$dictionary['SalaryRanges']['fields']['name']['disable_num_format'] = 1;
$dictionary['SalaryRanges']['fields']['description']['audited'] = true;

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

/* * *******************************************************************************
 * SugarCRM is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2011 SugarCRM Inc.
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

$dictionary['Costs'] = array(
   'table' => 'costs',
   'audited' => true,
   'fields' => array(
      'type' => array(
         'required' => true,
         'name' => 'type',
         'vname' => 'LBL_TYPE',
         'type' => 'enum',
         'massupdate' => 0,
         'default' => 'transport',
         'comments' => '',
         'help' => '',
         'importable' => 'true',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'audited' => true,
         'reportable' => true,
         'len' => 100,
         'size' => '20',
         'options' => 'cost_type',
         'studio' => 'visible',
         'dependency' => false,
      ),
      'cost_amount' => array(
         'required' => false,
         'name' => 'cost_amount',
         'vname' => 'LBL_COST_AMOUNT',
         'type' => 'currency',
         'massupdate' => 0,
         'comments' => '',
         'help' => '',
         'importable' => 'true',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'audited' => true,
         'reportable' => true,
         'len' => 26,
         'size' => '20',
         'precision' => 2,
      ),
      'cost_amount_usdollars' => array(
         'required' => false,
         'name' => 'cost_amount_usdollars',
         'vname' => 'LBL_COST_AMOUNT_BASE',
         'type' => 'currency',
         'massupdate' => 0,
         'comments' => '',
         'help' => '',
         'importable' => 'true',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'audited' => false,
         'reportable' => true,
         'len' => 26,
         'size' => '20',
         'precision' => 2,
      ),
      'currency_id' => array(
         'required' => false,
         'name' => 'currency_id',
         'vname' => 'LBL_CURRENCY',
         'type' => 'id',
         'massupdate' => 0,
         'comments' => '',
         'help' => '',
         'importable' => 'true',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => 0,
         'audited' => false,
         'reportable' => true,
         'len' => 36,
         'size' => '20',
         'studio' => 'visible',
         'function' => array(
            'name' => 'getCurrencyDropDown',
            'returns' => 'html',
         ),
      ),
      'cost_date' => array(
         'required' => false,
         'name' => 'cost_date',
         'vname' => 'LBL_COST_DATE',
         'type' => 'date',
         'massupdate' => 0,
         'comments' => '',
         'help' => '',
         'importable' => 'true',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'audited' => true,
         'reportable' => true,
         'size' => '20',
         'enable_range_search' => true,
         'options' => 'date_range_search_dom',
      ),
      'cost_city' => array(
         'required' => true,
         'name' => 'cost_city',
         'vname' => 'LBL_COST_CITY',
         'type' => 'varchar',
         'massupdate' => 0,
         'comments' => '',
         'help' => '',
         'importable' => 'true',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'audited' => true,
         'reportable' => true,
         'len' => '255',
         'size' => '20',
      ),
      'accommodation_no' => array(
         'required' => false,
         'name' => 'accommodation_no',
         'vname' => 'LBL_ACCOMMODATION_NO',
         'type' => 'enum',
         'dbType' => 'int',
         'massupdate' => 0,
         'comments' => '',
         'default' => '',
         'help' => '',
         'importable' => 'true',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'audited' => true,
         'reportable' => true,
         'len' => '255',
         'size' => '20',
         'disable_num_format' => '',
         'options' => 'accommodation_no_list',
         'vt_dependency' => "equals(\$type,'accommodation')",
      ),
      'type_of_meal' => array(
         'required' => true,
         'name' => 'type_of_meal',
         'vname' => 'LBL_TYPE_OF_MEAL',
         'type' => 'enum',
         'massupdate' => 0,
         'default' => '',
         'comments' => '',
         'help' => '',
         'importable' => 'true',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'audited' => true,
         'reportable' => true,
         'len' => 100,
         'size' => '20',
         'options' => 'type_of_meal_list',
         'studio' => 'visible',
         'dependency' => false,
         'vt_dependency' => "equals(\$type,'restaurant')",
      ),
      "delegations" => array(
         'name' => 'delegations',
         'type' => 'link',
         'relationship' => 'costs_delegations',
         'source' => 'non-db',
         'vname' => 'LBL_DELEGATIONS',
      ),
      "delegation_name" => array(
         'name' => 'delegation_name',
         'type' => 'relate',
         'source' => 'non-db',
         'vname' => 'LBL_DELEGATION_NAME',
         'save' => true,
         'id_name' => 'delegation_id',
         'link' => 'delegations',
         'table' => 'delegations',
         'module' => 'Delegations',
         'rname' => 'name',
         'audited' => true,
         'vt_required' => true,
         'vt_dependency' => "not(equals(\$type,'transport'))",
      ),
      "delegation_id" => array(
         'name' => 'delegation_id',
         'type' => 'link',
         'relationship' => 'costs_delegations',
         'reportable' => false,
         'vname' => 'LBL_DELEGATION_ID',
         'rname' => 'id',
         'isnull' => 'true',
         'dbType' => 'id',
         'vt_calculated' => "ifElse(equals(\$type,'transport'),'',\$delegation_id)",
      ),
      "transportations" => array(
         'name' => 'transportations',
         'type' => 'link',
         'relationship' => 'costs_transportations',
         'source' => 'non-db',
         'vname' => 'LBL_TRANSPORTATIONS',
      ),
      "transportation_name" => array(
         'name' => 'transportation_name',
         'type' => 'relate',
         'source' => 'non-db',
         'vname' => 'LBL_TRANSPORTATION_NAME',
         'save' => true,
         'id_name' => 'transportation_id',
         'link' => 'transportations',
         'table' => 'transportations',
         'module' => 'Transportations',
         'rname' => 'name',
         'audited' => true,
         'vt_required' => 'equals(1,1)',
         'vt_dependency' => "equals(\$type,'transport')",
      ),
      "transportation_id" => array(
         'name' => 'transportation_id',
         'type' => 'link',
         'relationship' => 'costs_transportations',
         'reportable' => false,
         'vname' => 'LBL_TRANSPORTATION_ID',
         'rname' => 'id',
         'isnull' => 'true',
         'dbType' => 'id',
         'vt_calculated' => "ifElse(not(equals(\$type,'transport')),'',\$transportation_id)",
      ),
   ),
   'relationships' => array(
      'costs_transportations' => array(
         'lhs_module' => 'Transportations',
         'lhs_table' => 'transportations',
         'lhs_key' => 'id',
         'rhs_module' => 'Costs',
         'rhs_table' => 'costs',
         'rhs_key' => 'transportation_id',
         'relationship_type' => 'one-to-many',
      ),
      'costs_delegations' => array(
         'lhs_module' => 'Delegations',
         'lhs_table' => 'delegations',
         'lhs_key' => 'id',
         'rhs_module' => 'Costs',
         'rhs_table' => 'costs',
         'rhs_key' => 'delegation_id',
         'relationship_type' => 'one-to-many',
      ),
   ),
   'indices' => array(
      array(
         'name' => 'idx_delegation_id',
         'type' => 'index',
         'fields' => array(
            0 => 'delegation_id',
         ),
      ),
      array(
         'name' => 'idx_transportation_id',
         'type' => 'index',
         'fields' => array(
            0 => 'transportation_id',
         ),
      ),
   ),
   'optimistic_locking' => true,
);
if ( !class_exists('VardefManager') ) {
   require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('Costs', 'Costs', array( 'basic', 'assignable', 'security_groups' ));
$dictionary['Costs']['fields']['assigned_user_id']['required'] = true;
$dictionary['Costs']['fields']['assigned_user_id']['vt_calculated'] = "ifElse(equals(\$type,'transport'),related(@assigned_user_id,#transportations),related(@assigned_user_id,#delegations))";
$dictionary['Costs']['fields']['assigned_user_id']['massupdate'] = 0;

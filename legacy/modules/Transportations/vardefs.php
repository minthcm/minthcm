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

$dictionary['Transportations'] = array(
   'table' => 'transportations',
   'audited' => true,
   'fields' => array(
      'other_transportation' => array(
         'name' => 'other_transportation',
         'vname' => 'LBL_OTHER_TRANSPORTATION',
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
         'vt_dependency' => "inArray('other',\$type)",
      ),
      'from_city' => array(
         'required' => true,
         'name' => 'from_city',
         'vname' => 'LBL_FROM_CITY',
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
      'to_city' => array(
         'required' => true,
         'name' => 'to_city',
         'vname' => 'LBL_TO_CITY',
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
      'type' => array(
         'required' => true,
         'name' => 'type',
         'vname' => 'LBL_TYPE',
         'type' => 'enum',
         'massupdate' => 0,
         'default' => 'car',
         'comments' => '',
         'help' => '',
         'importable' => 'true',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'audited' => true,
         'reportable' => true,
         'len' => 100,
         'size' => '20',
         'options' => 'transport_type',
         'studio' => 'visible',
         'dependency' => false,
      ),
      'cost_total' => array(
         'required' => false,
         'name' => 'cost_total',
         'vname' => 'LBL_COST_TOTAL',
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
         'precision' => 2,
         'size' => '28',
      ),
      'eur_total' => array(
         'required' => false,
         'name' => 'eur_total',
         'vname' => 'LBL_EUR_TOTAL',
         'type' => 'currency',
         'massupdate' => 0,
         'comments' => '',
         'help' => '',
         'importable' => 'false',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'audited' => true,
         'reportable' => true,
         'len' => 26,
         'size' => '20',
         'source' => 'non-db',
      ),
      'pln_total' => array(
         'required' => false,
         'name' => 'pln_total',
         'vname' => 'LBL_PLN_TOTAL',
         'type' => 'currency',
         'massupdate' => 0,
         'comments' => '',
         'help' => '',
         'importable' => 'false',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'audited' => true,
         'reportable' => true,
         'len' => 26,
         'size' => '20',
         'source' => 'non-db',
      ),
      'usd_total' => array(
         'required' => false,
         'name' => 'usd_total',
         'vname' => 'LBL_USD_TOTAL',
         'type' => 'currency',
         'massupdate' => 0,
         'comments' => '',
         'help' => '',
         'importable' => 'false',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'audited' => true,
         'reportable' => true,
         'len' => 26,
         'size' => '20',
         'source' => 'non-db',
      ),
      'trans_date' => array(
         'required' => false,
         'name' => 'trans_date',
         'vname' => 'LBL_TRANS_DATE',
         'type' => 'datetimecombo',
         'massupdate' => 0,
         'comments' => '',
         'help' => '',
         'importable' => 'true',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'audited' => true,
         'reportable' => true,
         'size' => '20',
         'dbType' => 'datetime',
         'enable_range_search' => true,
         'options' => 'date_range_search_dom',
      ),
      "costs" => array(
         'name' => 'costs',
         'type' => 'link',
         'relationship' => 'costs_transportations',
         'source' => 'non-db',
         'side' => 'right',
         'vname' => 'LBL_COSTS',
      ),
      "delegations" => array(
         'name' => 'delegations',
         'type' => 'link',
         'relationship' => 'transportations_delegations',
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
      ),
      "delegation_id" => array(
         'name' => 'delegation_id',
         'type' => 'link',
         'relationship' => 'transportations_delegations',
         'reportable' => false,
         'vname' => 'LBL_DELEGATION_ID',
         'rname' => 'id',
         'isnull' => 'true',
         'dbType' => 'id'
      ),
   ),
   'relationships' => array(
      'transportations_delegations' => array(
         'lhs_module' => 'Delegations',
         'lhs_table' => 'delegations',
         'lhs_key' => 'id',
         'rhs_module' => 'Transportations',
         'rhs_table' => 'transportations',
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
   ),
   'optimistic_locking' => true,
);
if ( !class_exists('VardefManager') ) {
   require_once('include/SugarObjects/VardefManager.php');
}

VardefManager::createVardef('Transportations', 'Transportations', array( 'basic', 'assignable', 'security_groups' ));
$dictionary['Transportations']['fields']['assigned_user_id']['required'] = true;
$dictionary['Transportations']['fields']['assigned_user_id']['vt_calculated'] = 'related(@assigned_user_id,#delegations)';
$dictionary['Transportations']['fields']['assigned_user_id']['massupdate'] = 0;

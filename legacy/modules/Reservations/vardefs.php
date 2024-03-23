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

$dictionary['Reservations'] = array(
   'table' => 'reservations',
   'audited' => true,
   'inline_edit' => true,
   'duplicate_merge' => true,
   'fields' => array(
      'name' => array(
         'name' => 'name',
         'vname' => 'LBL_NAME',
         'type' => 'name',
         'link' => true,
         'dbType' => 'varchar',
         'len' => '255',
         'unified_search' => false,
         'full_text_search' => array(
            'boost' => 3,
         ),
         'required' => true,
         'importable' => 'required',
         'duplicate_merge' => 'disabled',
         'merge_filter' => 'disabled',
         'massupdate' => 0,
         'no_default' => false,
         'comments' => '',
         'help' => '',
         'duplicate_merge_dom_value' => '0',
         'audited' => true,
         'inline_edit' => true,
         'reportable' => true,
         'size' => '20',
      ),
      'starting_date' => array(
         'required' => true,
         'name' => 'starting_date',
         'vname' => 'LBL_STARTING_DATE',
         'type' => 'datetimecombo',
         'massupdate' => '1',
         'no_default' => false,
         'comments' => '',
         'help' => '',
         'importable' => 'true',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'audited' => true,
         'inline_edit' => true,
         'reportable' => true,
         'unified_search' => false,
         'merge_filter' => 'disabled',
         'size' => '20',
         'enable_range_search' => false,
         'dbType' => 'datetime',
         'validation' => array('type' => 'isbefore', 'compareto' => 'ending_date'),
      ),
      'ending_date' => array(
         'required' => true,
         'name' => 'ending_date',
         'vname' => 'LBL_ENDING_DATE',
         'type' => 'datetimecombo',
         'massupdate' => 0,
         'no_default' => false,
         'comments' => '',
         'help' => '',
         'importable' => 'true',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'audited' => true,
         'inline_edit' => true,
         'reportable' => true,
         'unified_search' => false,
         'merge_filter' => 'disabled',
         'size' => '20',
         'enable_range_search' => false,
         'dbType' => 'datetime',
      ),
      'resources' => array(
         'name' => 'resources',
         'type' => 'link',
         'relationship' => 'resources_reservations',
         'source' => 'non-db',
         'module' => 'Resources',
         'bean_name' => 'Resources',
         'vname' => 'LBL_RESOURCES',
         'id_name' => 'resource_id',
      ),
      'resource_name' => array(
         'name' => 'resource_name',
         'type' => 'relate',
         'source' => 'non-db',
         'vname' => 'LBL_RESOURCES',
         'save' => true,
         'id_name' => 'resource_id',
         'link' => 'resources',
         'table' => 'resources',
         'module' => 'Resources',
         'rname' => 'name',
         'required' => true,
         'vt_validation' => "AEM(callCustomApi('Resources','isReservable',{resource_id:\$resource_id}),'LBL_RESOURCE_NOT_FOR_RESERVATION_VALIDATION')",
      ),
      'resource_id' => array(
         'name' => 'resource_id',
         'type' => 'id',
         'relationship' => 'resources_reservations',
         'reportable' => false,
         'rname' => 'id',
         'isnull' => 'true',
         'dbType' => 'id',
         'vname' => 'LBL_RESOURCE_ID',
      ),
      'delegations' => array(
         'name' => 'delegations',
         'type' => 'link',
         'relationship' => 'delegations_reservations',
         'source' => 'non-db',
         'module' => 'Delegations',
         'bean_name' => 'Delegations',
         'vname' => 'LBL_DELEGATIONS',
         'id_name' => 'delegation_id',
      ),
      'delegation_name' => array(
         'name' => 'delegation_name',
         'type' => 'relate',
         'source' => 'non-db',
         'vname' => 'LBL_DELEGATIONS',
         'save' => true,
         'id_name' => 'delegation_id',
         'link' => 'delegations',
         'table' => 'delegations',
         'module' => 'Delegations',
         'rname' => 'name',
      ),
      'delegation_id' => array(
         'name' => 'delegation_id',
         'type' => 'id',
         'relationship' => 'delegations_reservations',
         'reportable' => false,
         'rname' => 'id',
         'isnull' => 'true',
         'dbType' => 'id',
         'vname' => 'LBL_DELEGATION_ID',
      ),
      'parent_type' => array(
         'name' => 'parent_type',
         'vname' => 'LBL_PARENT_TYPE',
         'type' => 'parent_type',
         'dbType' => 'varchar',
         'group' => 'parent_name',
         'options' => 'reservations_parent_type_list',
         'len' => 255,
      ),
      'parent_name' => array(
         'name' => 'parent_name',
         'parent_type' => 'record_type_display',
         'type_name' => 'parent_type',
         'id_name' => 'parent_id',
         'vname' => 'LBL_PARENT_NAME',
         'type' => 'parent',
         'group' => 'parent_name',
         'source' => 'non-db',
         'options' => 'reservations_parent_type_list',
         'required' => false,
      ),
      'parent_id' => array(
         'name' => 'parent_id',
         'vname' => 'LBL_PARENT_ID',
         'type' => 'id',
         'group' => 'parent_name',
         'reportable' => false,
      ),
   ),
   'relationships' => array(
      'reservations_meetings' => array(
         'lhs_module' => 'Meetings',
         'lhs_table' => 'meetings',
         'lhs_key' => 'id',
         'rhs_module' => 'Reservations',
         'rhs_table' => 'reservations',
         'rhs_key' => 'parent_id',
         'relationship_type' => 'one-to-many',
         'relationship_role_column' => 'parent_type',
         'relationship_role_column_value' => 'Meetings'
      ),
      'reservations_calls' => array(
         'lhs_module' => 'Calls',
         'lhs_table' => 'calls',
         'lhs_key' => 'id',
         'rhs_module' => 'Reservations',
         'rhs_table' => 'reservations',
         'rhs_key' => 'parent_id',
         'relationship_type' => 'one-to-many',
         'relationship_role_column' => 'parent_type',
         'relationship_role_column_value' => 'Calls'
      ),
      "resources_reservations" => array(
         'lhs_module' => 'Resources',
         'lhs_table' => 'resources',
         'lhs_key' => 'id',
         'rhs_module' => 'Reservations',
         'rhs_table' => 'reservations',
         'rhs_key' => 'resource_id',
         'relationship_type' => 'one-to-many',
      ),
      "delegations_reservations" => array(
         'lhs_module' => 'Delegations',
         'lhs_table' => 'delegations',
         'lhs_key' => 'id',
         'rhs_module' => 'Reservations',
         'rhs_table' => 'reservations',
         'rhs_key' => 'delegation_id',
         'relationship_type' => 'one-to-many',
      ),
   ),
   'optimistic_locking' => true,
   'unified_search' => true,
);
if ( !class_exists('VardefManager') ) {
   require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('Reservations', 'Reservations', array('basic', 'assignable', 'security_groups', 'employee_related'));

$dictionary['Reservations']['fields']['employee_name']['required'] = true;

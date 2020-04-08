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
$dictionary['OrganizationalUnits'] = array(
   'table' => 'organizationalunits',
   'audited' => true,
   'inline_edit' => true,
   'duplicate_merge' => true,
   'fields' => array(
      'name' =>
      array(
         'name' => 'name',
         'vname' => 'LBL_NAME',
         'type' => 'name',
         'link' => true,
         'dbType' => 'varchar',
         'len' => '255',
         'unified_search' => false,
         'full_text_search' =>
         array(
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
      'type' =>
      array(
         'required' => true,
         'name' => 'type',
         'vname' => 'LBL_TYPE',
         'type' => 'enum',
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
         'len' => 100,
         'size' => '20',
         'options' => 'unit_type_list',
         'studio' => 'visible',
         'dependency' => false,
      ),
      'positions_leader' => array(
         'name' => 'positions_leader',
         'type' => 'link',
         'relationship' => 'organizationalunits_positions_leader',
         'source' => 'non-db',
         'module' => 'Positions',
         'bean_name' => 'Positions',
         'vname' => 'LBL_POSITIONS_LEADER',
         'id_name' => 'position_leader_id',
      ),
      'position_leader_name' => array(
         'name' => 'position_leader_name',
         'type' => 'relate',
         'source' => 'non-db',
         'vname' => 'LBL_POSITION_LEADER_NAME',
         'save' => true,
         'id_name' => 'position_leader_id',
         'link' => 'positions_leader',
         'table' => 'positions',
         'module' => 'Positions',
         'rname' => 'name',
      ),
      'position_leader_id' => array(
         'name' => 'position_leader_id',
         'type' => 'link',
         'relationship' => 'organizationalunits_positions_leader',
         'source' => 'non-db',
         'reportable' => false,
         'side' => 'left',
         'vname' => 'LBL_POSITION_LEADER_ID', 
         'audited' => true,
         'mandatory_fetch' => true,
      ),
      'positions_membership' => array(
         'name' => 'positions_membership',
         'type' => 'link',
         'relationship' => 'organizationalunits_positions_membership',
         'source' => 'non-db',
         'module' => 'Positions',
         'bean_name' => 'Positions',
         'vname' => 'LBL_POSITIONS_MEMBERSHIP',
      ),
      'employees' => array( 
         'name' => 'employees',
         'type' => 'link',
         'relationship' => 'organizationalunits_employees',
         'source' => 'non-db',
         'module' => 'Employees',
         'bean_name' => 'Employee',
         'side' => 'right',
         'vname' => 'LBL_EMPLOYEES',
      ),
      'members' => array(
         'name' => 'members',
         'type' => 'link',
         'relationship' => 'member_organizationalunits',
         'module' => 'OrganizationalUnits',
         'bean_name' => 'OrganizationalUnits',
         'source' => 'non-db',
         'vname' => 'LBL_MEMBERS',
      ),
      'member_of' => array(
         'name' => 'member_of',
         'type' => 'link',
         'relationship' => 'member_organizationalunits',
         'module' => 'OrganizationalUnits',
         'bean_name' => 'OrganizationalUnits',
         'link_type' => 'one',
         'source' => 'non-db',
         'vname' => 'LBL_MEMBER_OF',
         'side' => 'right',
      ),
      'parent_id' => array(
         'name' => 'parent_id',
         'vname' => 'LBL_PARENT_ID',
         'type' => 'id',
         'required' => false,
         'reportable' => false,
         'audited' => true,
      ),
      'parent_name' => array(
         'name' => 'parent_name',
         'rname' => 'name',
         'id_name' => 'parent_id',
         'vname' => 'LBL_MEMBER_OF',
         'type' => 'relate',
         'isnull' => 'true',
         'module' => 'OrganizationalUnits',
         'table' => 'organizationalunits',
         'massupdate' => false,
         'source' => 'non-db',
         'len' => 36,
         'link' => 'member_of',
         'unified_search' => true,
         'importable' => 'true',
      ),
      'news' => array(
         'name' => 'news',
         'type' => 'link',
         'relationship' => 'organizationalunits_news',
         'source' => 'non-db',
         'module' => 'News',
         'bean_name' => 'News',
         'vname' => 'LBL_NEWS',
      ),
      "current_manager" => array( 
         'name' => 'current_manager',
         'type' => 'link',
         'relationship' => 'employees_organizationalunits',
         'source' => 'non-db',
         'module' => 'Employees',
         'bean_name' => 'Employee',
         'vname' => 'LBL_CURRENT_MANAGER',
         'id_name' => 'current_manager_id',
      ),
      "current_manager_name" => array(
         'name' => 'current_manager_name',
         'type' => 'relate',
         'source' => 'non-db',
         'vname' => 'LBL_CURRENT_MANAGER_NAME',
         'save' => true,
         'id_name' => 'current_manager_id',
         'link' => 'current_manager',
         'module' => 'Employees',
         'table' => 'users',
         'rname' => 'name',
         'audited' => true,
         'importable' => true,
         'reportable' => true,
         'massupdate' => false,
         'duplicate_merge' => 'enabled',
      ),
      "current_manager_id" => array(
         'name' => 'current_manager_id',
         'relationship' => 'employees_organizationalunits',
         'type' => 'id',
         'vname' => 'LBL_CURRENT_MANAGER_ID',
         'audited' => false,
         'reportable' => true,
      ),
      "onboardingoffboardingelements" => array(
         'name' => 'OnboardingOffboardingElements',
         'type' => 'link',
         'relationship' => 'organizationalunits_onboardingoffboardingelements',
         'source' => 'non-db',
         'module' => 'OnboardingOffboardingElements',
         'bean_name' => 'OnboardingOffboardingElements',
         'side' => 'left',
         'vname' => 'LBL_ONBOARDINGOFFBOARDINGELEMENTS',
      ),
   ),
   'relationships' => array(
      "employees_organizationalunits" => array(
         'lhs_module' => 'Employees',
         'lhs_table' => 'users',
         'lhs_key' => 'id',
         'rhs_module' => 'OrganizationalUnits',
         'rhs_table' => 'organizationalunits',
         'rhs_key' => 'current_manager_id',
         'relationship_type' => 'one-to-many',
      ),
      'member_organizationalunits' => array(
         'lhs_module' => 'OrganizationalUnits',
         'lhs_table' => 'organizationalunits',
         'lhs_key' => 'id',
         'rhs_module' => 'OrganizationalUnits',
         'rhs_table' => 'organizationalunits',
         'rhs_key' => 'parent_id',
         'relationship_type' => 'one-to-many'
      ),
   ),
   'optimistic_locking' => true,
   'unified_search' => true,
);
if ( !class_exists('VardefManager') ) {
   require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('OrganizationalUnits', 'OrganizationalUnits', array( 'basic', 'assignable', 'security_groups' ));

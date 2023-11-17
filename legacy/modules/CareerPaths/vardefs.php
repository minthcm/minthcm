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
$dictionary['CareerPaths'] = array(
   'table' => 'careerpaths',
   'audited' => true,
   'inline_edit' => true,
   'duplicate_merge' => true,
   'fields' => array(
      'positions_from' => array(
         'name' => 'positions_from',
         'type' => 'link',
         'relationship' => 'careerpaths_positions_from',
         'source' => 'non-db',
         'module' => 'Positions',
         'bean_name' => 'Positions',
         'vname' => 'LBL_POSITIONS_FROM',
         'id_name' => 'position_from_id',
      ),
      'position_from_name' => array(
         'name' => 'position_from_name',
         'type' => 'relate',
         'source' => 'non-db',
         'vname' => 'LBL_POSITION_FROM_NAME',
         'save' => true,
         'id_name' => 'position_from_id',
         'link' => 'positions_from',
         'table' => 'positions',
         'module' => 'Positions',
         'rname' => 'name',
         'required' => true,
      ),
      'position_from_id' => array(
         'name' => 'position_from_id',
         'type' => 'link',
         'relationship' => 'careerpaths_positions_from',
         'reportable' => false,
         'vname' => 'LBL_POSITION_FROM_ID',
         'dbType' => 'id',
      ),
      'positions_to' => array(
         'name' => 'positions_to',
         'type' => 'link',
         'relationship' => 'careerpaths_positions_to',
         'source' => 'non-db',
         'module' => 'Positions',
         'bean_name' => 'Positions',
         'vname' => 'LBL_POSITIONS_TO',
         'id_name' => 'position_to_id',
      ),
      'position_to_name' => array(
         'name' => 'position_to_name',
         'type' => 'relate',
         'source' => 'non-db',
         'vname' => 'LBL_POSITION_TO_NAME',
         'save' => true,
         'id_name' => 'position_to_id',
         'link' => 'positions_to',
         'table' => 'positions',
         'module' => 'Positions',
         'rname' => 'name',
         'required' => true,
      ),
      'position_to_id' => array(
         'name' => 'position_to_id',
         'type' => 'link',
         'relationship' => 'careerpaths_positions_to',
         'reportable' => false,
         'vname' => 'LBL_POSITION_TO_ID',
         'dbType' => 'id',
      ),
   ),
   'relationships' => array(
      "careerpaths_positions_from" => array(
         'lhs_module' => 'Positions',
         'lhs_table' => 'positions',
         'lhs_key' => 'id',
         'rhs_module' => 'CareerPaths',
         'rhs_table' => 'careerpaths',
         'rhs_key' => 'position_from_id',
         'relationship_type' => 'one-to-many',
      ),
      "careerpaths_positions_to" => array(
         'lhs_module' => 'Positions',
         'lhs_table' => 'positions',
         'lhs_key' => 'id',
         'rhs_module' => 'CareerPaths',
         'rhs_table' => 'careerpaths',
         'rhs_key' => 'position_to_id',
         'relationship_type' => 'one-to-many',
      ),
   ),
   'optimistic_locking' => true,
   'unified_search' => true,
);
if ( !class_exists('VardefManager') ) {
   require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('CareerPaths', 'CareerPaths', array( 'basic', 'assignable', 'security_groups' ));

$dictionary['CareerPaths']['fields']['name']['vt_calculated'] = 'concat($position_to_name)';

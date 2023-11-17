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
$dictionary['Ideas'] = array(
   'table' => 'ideas',
   'audited' => true,
   'inline_edit' => true,
   'duplicate_merge' => true,
   'fields' => array(
      'status' =>
      array(
         'required' => true,
         'name' => 'status',
         'vname' => 'LBL_STATUS',
         'type' => 'enum',
         'massupdate' => '1',
         'default' => 'new',
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
         'options' => 'idea_status_list',
         'studio' => 'visible',
         'dependency' => false,
      ),
      'explanation' =>
      array(
         'required' => false,
         'name' => 'explanation',
         'vname' => 'LBL_EXPLANATION',
         'type' => 'text',
         'massupdate' => 0,
         'no_default' => false,
         'comments' => '',
         'help' => '',
         'importable' => 'true',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'audited' => true,
         'inline_edit' => '',
         'reportable' => true,
         'unified_search' => false,
         'merge_filter' => 'disabled',
      ),
      "users" => array(
         'name' => 'users',
         'type' => 'link',
         'relationship' => 'users_ideas',
         'source' => 'non-db',
         'module' => 'Users',
         'bean_name' => 'User',
         'vname' => 'LBL_USERS',
         'id_name' => 'user_id',
      ),
      "user_name" => array(
         'name' => 'user_name',
         'type' => 'relate',
         'source' => 'non-db',
         'vname' => 'LBL_USER_NAME',
         'id_name' => 'user_id',
         'link' => 'users',
         'module' => 'Users',
         'table' => 'users',
         'rname' => 'name',
         'db_concat_fields' =>
         array(
            'first_name',
            'last_name',
         ),
         'required' => true,
         'audited' => true,
      ),
      "user_id" => array(
         'name' => 'user_id',
         'relationship' => 'users_ideas',
         'type' => 'id',
         'vname' => 'LBL_USER_ID',
      ),
      "notes" => array(
         'name' => 'notes',
         'type' => 'link',
         'relationship' => 'ideas_notes',
         'source' => 'non-db',
         'module' => 'Notes',
         'bean_name' => 'Note',
         'vname' => 'LBL_NOTES',
      ),
      'files' => array(
         'name' => 'files',
         'type' => 'link',
         'relationship' => 'ideas_files',
         'source' => 'non-db',
         'module' => 'Files',
         'bean_name' => 'Files',
         'vname' => 'LBL_FILES',
         'label' => 'LBL_FILES',
     ),
   ),
   'relationships' => array(
      "users_ideas" => array(
         'lhs_module' => 'Users',
         'lhs_table' => 'users',
         'lhs_key' => 'id',
         'rhs_module' => 'Ideas',
         'rhs_table' => 'ideas',
         'rhs_key' => 'user_id',
         'relationship_type' => 'one-to-many',
      ),
      'ideas_notes' =>
      array(
         'lhs_module' => 'Ideas',
         'lhs_table' => 'ideas',
         'lhs_key' => 'id',
         'rhs_module' => 'Notes',
         'rhs_table' => 'notes',
         'relationship_role_column_value' => 'ideas',
         'rhs_key' => 'parent_id',
         'relationship_type' => 'one-to-many',
         'relationship_role_column' => 'parent_type',
      ),
   ),
   'optimistic_locking' => true,
   'unified_search' => true,
);
if ( !class_exists('VardefManager') ) {
   require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('Ideas', 'Ideas', array( 'basic', 'assignable', 'security_groups' ));

$dictionary['Ideas']['fields']['description']['required'] = true;
$dictionary['Ideas']['fields']['description']['audited'] = true;
$dictionary['Ideas']['fields']['assigned_user_name']['required'] = true;
$dictionary['Ideas']['fields']['assigned_user_name']['audited'] = true;
$dictionary['Ideas']['fields']['assigned_user_id']['audited'] = false;
$dictionary['Ideas']['fields']['name']['audited'] = true;

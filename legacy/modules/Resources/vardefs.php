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

$dictionary['Resources'] = array(
   'table' => 'resources',
   'audited' => true,
   'inline_edit' => true,
   'duplicate_merge' => true,
   'fields' => array(
      'type' => array(
         'required' => true,
         'name' => 'type',
         'vname' => 'LBL_TYPE',
         'type' => 'enum',
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
         'len' => 100,
         'size' => '20',
         'options' => 'type_list',
         'studio' => 'visible',
         'dependency' => false,
      ),
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
         'duplicate_merge' => 'enabled',
         'merge_filter' => 'disabled',
         'massupdate' => 0,
         'no_default' => false,
         'comments' => '',
         'help' => '',
         'duplicate_merge_dom_value' => '1',
         'audited' => true,
         'inline_edit' => true,
         'reportable' => true,
         'size' => '20',
      ),
      'unavailable' => array(
         'required' => false,
         'name' => 'unavailable',
         'vname' => 'LBL_UNAVAILABLE',
         'type' => 'bool',
         'massupdate' => 0,
         'default' => '0',
         'no_default' => false,
         'comments' => '',
         'help' => '',
         'importable' => 'true',
         'duplicate_merge' => 'enabled',
         'duplicate_merge_dom_value' => '1',
         'audited' => true,
         'inline_edit' => true,
         'reportable' => true,
         'unified_search' => false,
         'merge_filter' => 'disabled',
         'len' => '255',
         'size' => '20',
      ),
      "reservations" => array(
         'name' => 'reservations',
         'type' => 'link',
         'relationship' => 'resources_reservations',
         'source' => 'non-db',
         'module' => 'Reservations',
         'bean_name' => 'Reservations',
         'side' => 'right',
         'vname' => 'LBL_RESERVATIONS',
      ),
      'meetings' => array(
         'name' => 'meetings',
         'type' => 'link',
         'relationship' => 'meetings_resources',
         'source' => 'non-db',
         'vname' => 'LBL_MEETINGS',
      ),
      'calls' => array(
         'name' => 'calls',
         'type' => 'link',
         'relationship' => 'calls_resources',
         'source' => 'non-db',
         'vname' => 'LBL_CALLS',
      ),
      "rooms_resources" => array (
         'name' => 'rooms_resources',
         'type' => 'link',
         'relationship' => 'rooms_resources',
         'source' => 'non-db',
         'module' => 'Rooms',
         'bean_name' => 'Rooms',
         'vname' => 'LBL_ROOMS_RESOURCES_TITLE',
         'id_name' => 'room_id',
      ),
       "room_name" => array (
         'name' => 'room_name',
         'type' => 'relate',
         'source' => 'non-db',
         'vname' => 'LBL_ROOM_NAME',
         'save' => true,
         'id_name' => 'room_id',
         'link' => 'rooms_resources',
         'table' => 'rooms',
         'module' => 'Rooms',
         'rname' => 'name',
       ),
       "room_id" => array (
         'name' => 'room_id',
         'type' => 'link',
         'relationship' => 'rooms_resources',
         'source' => 'non-db',
         'reportable' => false,
         'side' => 'left',
         'vname' => 'LBL_ROOM_ID',
       ),
       'files' => array(
         'name' => 'files',
         'type' => 'link',
         'relationship' => 'resources_files',
         'source' => 'non-db',
         'module' => 'Files',
         'bean_name' => 'Files',
         'vname' => 'LBL_FILES',
         'label' => 'LBL_FILES',
     ),
   ),
   'relationships' => array(),
   'optimistic_locking' => true,
   'unified_search' => true,
);
if ( !class_exists('VardefManager') ) {
   require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('Resources', 'Resources', array('basic', 'assignable', 'security_groups', 'employee_related'));

$dictionary["Resources"]["fields"]['description']['audited'] = true;

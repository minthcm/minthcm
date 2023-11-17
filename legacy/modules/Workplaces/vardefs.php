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
$dictionary['Workplaces'] = array(
    'table' => 'workplaces',
    'audited' => true,
    'inline_edit' => false,
    'duplicate_merge' => true,
    'fields' => array(
        'mode' =>
        array(
            'required' => true,
            'name' => 'mode',
            'vname' => 'LBL_MODE',
            'type' => 'enum',
            'massupdate' => '0',
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
            'options' => 'workplace_mode_list',
            'studio' => 'visible',
            'dependency' => false,
            'vt_validation' => "AEM(callCustomApi('Workplaces','canChangeMode',\$id,\$mode),'LBL_ERR_CANT_CHANGE_MODE')",
        ),
        'availability' => array(
            'required' => true,
            'name' => 'availability',
            'vname' => 'LBL_STATUS',
            'type' => 'enum',
            'massupdate' => '0',
            'default' => '',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => 100,
            'size' => '20',
            'options' => 'workplace_room_status',
            'studio' => 'visible',
        ),
        "rooms_workplaces" => array(
            'name' => 'rooms_workplaces',
            'type' => 'link',
            'relationship' => 'rooms_workplaces',
            'source' => 'non-db',
            'module' => 'Rooms',
            'bean_name' => 'Rooms',
            'vname' => 'LBL_RELATIONSHIP_ROOM_NAME',
            'id_name' => 'room_id',
         ),
         "room_name" => array(
            'required' => true,
            'name' => 'room_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_RELATIONSHIP_ROOM_NAME',
            'id_name' => 'room_id',
            'link' => 'rooms_workplaces',
            'module' => 'Rooms',
            'table' => 'rooms',
            'rname' => 'name',
            'vt_validation' => "AEM(callCustomApi('Workplaces','canSelectRoom',\$room_id),'LBL_ERR_CANT_SELECT_ROOM')",
         ),
         "room_id" => array(
            'name' => 'room_id',
            'relationship' => 'rooms_workplaces',
            'type' => 'id',
            'vname' => 'LBL_RELATIONSHIP_ROOM_ID',
         ),
         'files' => array(
            'name' => 'files',
            'type' => 'link',
            'relationship' => 'workplaces_files',
            'source' => 'non-db',
            'module' => 'Files',
            'bean_name' => 'Files',
            'vname' => 'LBL_FILES',
            'label' => 'LBL_FILES',
        ),
    ),
    'relationships' => array(
        "rooms_workplaces" => array(
           'name' => 'rooms_workplaces',
           'lhs_module' => 'Rooms',
           'lhs_table' => 'rooms',
           'lhs_key' => 'id',
           'rhs_module' => 'Workplaces',
           'rhs_table' => 'workplaces',
           'rhs_key' => 'room_id',
           'relationship_type' => 'one-to-many',
        ),
    ),
    'optimistic_locking' => true,
    'unified_search' => true,
);
if (!class_exists('VardefManager')) {
    require_once('include/SugarObjects/VardefManager.php');
}

VardefManager::createVardef('Workplaces', 'Workplaces',
    array(
    'basic',
    'assignable',
    'security_groups'
));

$dictionary['Workplaces']['fields']['assigned_user_name']['required'] = true;
$dictionary['Workplaces']['fields']['assigned_user_name']['audited'] = true;
$dictionary['Workplaces']['fields']['assigned_user_id']['audited'] = false;
$dictionary['Workplaces']['fields']['name']['audited'] = true;

$dictionary["Workplaces"]["fields"]["workplaces_allocations"] = array (
    'name' => 'workplaces_allocations',
    'type' => 'link',
    'relationship' => 'workplaces_allocations',    
    'source' => 'non-db',
    'module' => 'Allocations',                     
    'bean_name' => 'Allocations',                    
    'vname' => 'LBL_RELATIONSHIP_ALLOCATIONS',   
    'side' => 'right',
 );
 $dictionary["Workplaces"]["fields"]["workplaces_workschedules"] = array (
    'name' => 'workplaces_workschedules',
    'type' => 'link',
    'relationship' => 'workplaces_workschedules',        
    'source' => 'non-db',
    'module' => 'WorkSchedules',                        
    'bean_name' => 'WorkSchedules',                       
    'vname' => 'LBL_RELATIONSHIP_WORKSCHEDULES_NAME',   
    'side' => 'right',
 );

 $dictionary["Workplaces"]['indices'][] = array('name' => 'idx_del_room_id', 'type' => 'index', 'fields' => array( 'deleted','room_id'));

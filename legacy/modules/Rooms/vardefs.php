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
 * this program; if not, see http:
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
$dictionary['Rooms'] = array(
    'table' => 'rooms',
    'audited' => true,
    'inline_edit' => false,
    'duplicate_merge' => true,
    'fields' => array(
        'number_of_seats' => 
        array (
            'required' => false,
            'name' => 'number_of_seats',
            'vname' => 'LBL_NUMBER_OF_SEATS',
            'type' => 'int',
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
            'len' => '255',
            'size' => '20',
            'enable_range_search' => false,
            'disable_num_format' => '',
            'min' => false,
            'max' => false,
        ),
        'room_surface' => 
        array (
            'required' => false,
            'name' => 'room_surface',
            'vname' => 'LBL_ROOM_SURFACE',
            'type' => 'float',
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
            'len' => '16',
            'size' => '20',
            'enable_range_search' => false,
            'precision' => '2',
            'vt_validation' => "AEM(ifElse(or(empty(\$room_surface),equals(0,\$room_surface)),true,ifElse(greaterThan(\$room_surface, 0),true,false)),'LBL_NEGATIVE_SURFACE')",
        ),
        'room_plan' => 
        array (
            'required' => false,
            'name' => 'room_plan',
            'vname' => 'LBL_ROOM_PLAN',
            'type' => 'image',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => 0,
            'audited' => true,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => 255,
            'size' => '20',
            'studio' => 'visible',
            'dbType' => 'varchar',
            'border' => '',
            'width' => '120',
            'height' => '',
        ),
        'reservation_type' =>
        array(
            'required' => true,
            'name' => 'reservation_type',
            'vname' => 'LBL_RESERVATION_TYPE',
            'type' => 'enum',
            'massupdate' => '0',
            'default' => 'false',
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
            'options' => 'reservation_type_list',
            'studio' => 'visible',
            'dependency' => false,
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
        "securitygroups_rooms" => array(                  
            'name' => 'securitygroups_rooms',                 
            'type' => 'link',
            'relationship' => 'securitygroups_rooms',         
            'source' => 'non-db',
            'module' => 'SecurityGroups',                         
            'bean_name' => 'SecurityGroup',                       
            'vname' => 'LBL_RELATIONSHIP_SECURITY_GROUP_NAME',     
            'id_name' => 'security_group_id',                      
         ),
         "security_group_name" => array(                        
            'required' => true,
            'name' => 'security_group_name',                       
            'type' => 'relate',                              
            'source' => 'non-db',
            'vname' => 'LBL_RELATIONSHIP_SECURITY_GROUP_NAME',     
            'id_name' => 'security_group_id',                      
            'link' => 'securitygroups_rooms',                 
            'module' => 'SecurityGroups',                         
            'table' => 'securitygroups',                          
            'rname' => 'name',
            'vt_validation' => "AEM(callCustomApi('Rooms','canSelectSecurityGroup',\$security_group_id),'LBL_ERR_CANT_SELECT_SEC_GROUP')",
         ),
         "security_group_id" => array(
            'name' => 'security_group_id',                         
            'relationship' => 'securitygroups_rooms',         
            'type' => 'id',                                  
            'vname' => 'LBL_RELATIONSHIP_SECURITY_GROUP_ID',       
         ),
         "rooms_resources" => array ( 
            'name' => 'rooms_resources',                              
            'type' => 'link',
            'relationship' => 'rooms_resources',                     
            'source' => 'non-db',
            'module' => 'Resources',                                    
            'bean_name' => 'Resources',                                   
            'vname' => 'LBL_ROOMS_RESOURCES_TITLE',                  
            'id_name' => 'resource_id',                                 
         ),
          "resource_name" => array (  
            'name' => 'resource_name',                          
            'type' => 'relate',
            'source' => 'non-db',                         
            'vname' => 'LBL_RESOURCE_NAME',                    
            'save' => true,
            'id_name' => 'resource_id',                       
            'link' => 'rooms_resources',                     
            'table' => 'resources',                              
            'module' => 'Resources',                             
            'rname' => 'name',                              
          ),
          "resource_id" => array ( 
            'name' => 'resource_id',                                  
            'type' => 'link',
            'relationship' => 'rooms_resources',                   
            'source' => 'non-db',                                
            'reportable' => false,
            'side' => 'left',
            'vname' => 'LBL_RESOURCE_ID',                             
          ),
    ),
    'relationships' => array(
        "securitygroups_rooms" => array(                  
            'lhs_module' => 'SecurityGroups',                     
            'lhs_table' => 'securitygroups',                      
            'lhs_key' => 'id',                               
            'rhs_module' => 'Rooms',                     
            'rhs_table' => 'rooms',                      
            'rhs_key' => 'security_group_id',                      
            'relationship_type' => 'one-to-many',            
         ),
    ),
    'optimistic_locking' => true,
    'unified_search' => true,
);
if (!class_exists('VardefManager')) {
    require_once('include/SugarObjects/VardefManager.php');
}

VardefManager::createVardef('Rooms', 'Rooms',
    array(
    'basic',
    'assignable',
    'security_groups'
));

$dictionary['Rooms']['fields']['assigned_user_name']['required'] = true;
$dictionary['Rooms']['fields']['assigned_user_name']['audited'] = true;
$dictionary['Rooms']['fields']['assigned_user_id']['audited'] = false;
$dictionary['Rooms']['fields']['name']['audited'] = true;

$dictionary["Rooms"]["fields"]["rooms_workplaces"] = array (
    'name' => 'rooms_workplaces',
    'type' => 'link',
    'relationship' => 'rooms_workplaces',        
    'source' => 'non-db',
    'module' => 'Workplaces',                        
    'bean_name' => 'Workplaces',                       
    'vname' => 'LBL_RELATIONSHIP_WORKPLACES_NAME',   
    'side' => 'right',
 );
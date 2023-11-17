<?php

$module_name = 'SecurityGroups';
$searchdefs[$module_name] = array(
   'templateMeta' => array(
      'maxColumns' => '3',
      'widths' => array('label' => '10', 'field' => '30'),
   ),
   'layout' => array(
      'basic_search' => array(
         'name',
         array('name' => 'current_user_only', 'label' => 'LBL_CURRENT_USER_FILTER', 'type' => 'bool'),
      ),
      'advanced_search' => array(
         'name',
         'group_type',
         array(
            'name' => 'assigned_user_id',
            'label' => 'LBL_ASSIGNED_TO',
            'type' => 'enum',
            'function' => array( 'name' => 'get_user_array', 'params' => array( false ) )
         ),
         array(
            'name' => 'position_leader_name',
            'label' => 'LBL_POSITION_LEADER_NAME',
         ),
         array(
            'name' => 'parent_name',
            'label' => 'LBL_MEMBER_OF',
         ),
         'date_entered',
         'date_modified',
         'current_manager_name' => array(
            'type' => 'relate',
            'link' => true,
            'label' => 'LBL_CURRENT_MANAGER_NAME',
            'id' => 'CURRENT_MANAGER_ID',
            'width' => '10%',
            'default' => true,
            'name' => 'current_manager_name',
         ),
      ),
   ),
);

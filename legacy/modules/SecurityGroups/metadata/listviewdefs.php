<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}


$module_name = 'SecurityGroups';
$listViewDefs[$module_name] = array(
   'NAME' => array(
      'width' => '32',
      'label' => 'LBL_NAME',
      'default' => true,
      'link' => true
   ),
   'GROUP_TYPE' => array(
      'width' => '9',
      'label' => 'LBL_GROUP_TYPE',
      'default' => true,
   ),
   'PARENT_NAME' =>
   array(
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_MEMBER_OF',
      'id' => 'PARENT_ID',
      'width' => '10%',
      'default' => true,
   ),
   'POSITION_LEADER_NAME' =>
   array(
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_POSITION_LEADER_NAME',
      'id' => 'POSITION_LEADER_ID',
      'width' => '10%',
      'default' => true,
   ),
   'ASSIGNED_USER_NAME' => array(
      'width' => '9',
      'label' => 'LBL_ASSIGNED_TO_NAME',
      'default' => true
   ),
   'NONINHERITABLE' => array(
      'width' => '9',
      'label' => 'LBL_NONINHERITABLE',
      'default' => true
   ),
   'CURRENT_MANAGER_NAME' => array(
      'type' => 'relate',
      'link' => true,
      'label' => 'LBL_CURRENT_MANAGER_NAME',
      'id' => 'CURRENT_MANAGER_ID',
      'width' => '10%',
      'default' => true,
   ),
);

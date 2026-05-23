<?php

$module_name = 'SecurityGroups';
$viewdefs[$module_name]['EditView'] = array(

  'templateMeta' => array(
    'maxColumns' => '2',
    'widths' => array(
      array('label' => '10', 'field' => '30'),
      array('label' => '10', 'field' => '30')
    ),
  ),

  'panels' => array(
    'default' =>
    array(
      array(
        array('name' => 'name', 'displayParams' => array('required' => true)),
        'group_type',
      ),
      array(
        array(
           'name' => 'parent_name',
           'label' => 'LBL_MEMBER_OF'
        ),
        'assigned_user_name',
     ),
     array(
      array(
         'name' => 'current_manager_name',
         'label' => 'LBL_CURRENT_MANAGER_NAME',
      ),
      array(
         'name' => 'position_leader_name',
         'label' => 'LBL_POSITION_LEADER_NAME'
      ),
   ),
      array(
        'noninheritable',
      ),
      array(
        'description',
      ),
    ),


  ),



);

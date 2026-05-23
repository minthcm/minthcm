<?php
$module_name = 'Rooms';
$searchdefs [$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'current_user_only' => 
      array (
        'name' => 'current_user_only',
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
      'availability' => 
      array (
        'name' => 'availability',
        'label' => 'LBL_STATUS',
        'type' => 'enum',
        'massupdate' => 'false',
        'default' => true,
        'studio' => 'visible',
        'width' => '10%',
      ),
      'reservation_type' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_RESERVATION_TYPE',
        'width' => '10%',
        'default' => true,
        'name' => 'reservation_type',
      ),
      'security_group_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_RELATIONSHIP_SECURITY_GROUP_NAME',
        'id' => 'SECURITY_GROUP_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'security_group_name',
      ),
    ),
    'advanced_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'reservation_type' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_RESERVATION_TYPE',
        'width' => '10%',
        'default' => true,
        'name' => 'reservation_type',
      ),
      'availability' => 
      array (
        'name' => 'availability',
        'label' => 'LBL_STATUS',
        'type' => 'enum',
        'massupdate' => 'false',
        'default' => true,
        'studio' => 'visible',
        'width' => '10%',
      ),
      'assigned_user_id' => 
      array (
        'name' => 'assigned_user_id',
        'label' => 'LBL_ASSIGNED_TO',
        'type' => 'enum',
        'function' => 
        array (
          'name' => 'get_user_array',
          'params' => 
          array (
            0 => false,
          ),
        ),
        'default' => true,
        'width' => '10%',
      ),
      'security_group_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_RELATIONSHIP_SECURITY_GROUP_NAME',
        'width' => '10%',
        'default' => true,
        'id' => 'SECURITY_GROUP_ID',
        'name' => 'security_group_name',
      ),
    ),
  ),
  'templateMeta' => 
  array (
    'maxColumns' => '3',
    'maxColumnsBasic' => '4',
    'widths' => 
    array (
      'label' => '10',
      'field' => '30',
    ),
  ),
);
;
?>

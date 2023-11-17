<?php
$popupMeta = array (
    'moduleMain' => 'Rooms',
    'varName' => 'Rooms',
    'orderBy' => 'rooms.name',
    'whereClauses' => array (
  'name' => 'rooms.name',
  'reservation_type' => 'rooms.reservation_type',
  'assigned_user_id' => 'rooms.assigned_user_id',
  'security_group_name' => 'rooms.security_group_name',
  'availability' => 'rooms.availability',
),
    'searchInputs' => array (
  0 => 'name',
  1 => 'reservation_type',
  3 => 'security_group_name',
  4 => 'assigned_user_id',
  5 => 'availability',
),
    'searchdefs' => array (
  'name' => 
  array (
    'name' => 'name',
    'width' => '10%',
  ),
  'reservation_type' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_RESERVATION_TYPE',
    'width' => '10%',
    'name' => 'reservation_type',
  ),
  'availability' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_STATUS',
    'width' => '10%',
    'name' => 'availability',
  ),
  'security_group_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_RELATIONSHIP_SECURITY_GROUP_NAME',
    'id' => 'SECURITY_GROUP_ID',
    'width' => '10%',
    'name' => 'security_group_name',
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
    'width' => '10%',
  ),
),
    'listviewdefs' => array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
    'name' => 'name',
  ),
  'RESERVATION_TYPE' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_RESERVATION_TYPE',
    'width' => '10%',
    'default' => true,
    'name' => 'reservation_type',
  ),
  'NUMBER_OF_SEATS' => 
  array (
    'type' => 'int',
    'label' => 'LBL_NUMBER_OF_SEATS',
    'width' => '10%',
    'default' => true,
    'name' => 'number_of_seats',
  ),
  'SECURITY_GROUP_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_RELATIONSHIP_SECURITY_GROUP_NAME',
    'id' => 'SECURITY_GROUP_ID',
    'width' => '10%',
    'default' => true,
    'name' => 'security_group_name',
  ),
  'AVAILABILITY' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_STATUS',
    'width' => '10%',
    'name' => 'availability',
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'link' => true,
    'type' => 'relate',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'id' => 'ASSIGNED_USER_ID',
    'width' => '10%',
    'default' => true,
    'name' => 'assigned_user_name',
  ),
),
);

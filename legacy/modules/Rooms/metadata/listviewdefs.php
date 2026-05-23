<?php
$module_name = 'Rooms';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'RESERVATION_TYPE' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_RESERVATION_TYPE',
    'width' => '10%',
    'default' => true,
  ),
  'NUMBER_OF_SEATS' => 
  array (
    'type' => 'int',
    'label' => 'LBL_NUMBER_OF_SEATS',
    'width' => '10%',
    'default' => true,
  ),
  'SECURITY_GROUP_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_RELATIONSHIP_SECURITY_GROUP_NAME',
    'id' => 'SECURITY_GROUP_ID',
    'width' => '10%',
    'default' => true,
  ),
  'AVAILABILITY' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_STATUS',
    'width' => '10%',
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'link' => true,
    'type' => 'relate',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'id' => 'ASSIGNED_USER_ID',
    'width' => '10%',
    'default' => true,
  ),
  'CREATED_BY_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CREATED',
    'id' => 'CREATED_BY',
    'width' => '10%',
    'default' => false,
  ),
  'MODIFIED_BY_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_MODIFIED_NAME',
    'id' => 'MODIFIED_USER_ID',
    'width' => '10%',
    'default' => false,
  ),
  'ROOM_SURFACE' => 
  array (
    'type' => 'float',
    'label' => 'LBL_ROOM_SURFACE',
    'width' => '10%',
    'default' => false,
  ),
  'DATE_MODIFIED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_MODIFIED',
    'width' => '10%',
    'default' => false,
  ),
  'DATE_ENTERED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'default' => false,
  ),
);
;
?>

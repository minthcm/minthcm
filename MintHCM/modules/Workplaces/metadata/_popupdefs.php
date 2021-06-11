<?php
$popupMeta = array (
    'moduleMain' => 'Workplaces',
    'varName' => 'Workplaces',
    'orderBy' => 'workplaces.name',
    'whereClauses' => array (
  'name' => 'workplaces.name',
  'mode' => 'workplaces.mode',
  'assigned_user_id' => 'workplaces.assigned_user_id',
  'availability' => 'workplaces.availability',
),
    'searchInputs' => array (
  0 => 'name',
  1 => 'mode',
  2 => 'availability',
  3 => 'assigned_user_id',
),
    'searchdefs' => array (
  'name' => 
  array (
    'name' => 'name',
    'width' => '10%',
  ),
  'mode' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_MODE',
    'width' => '10%',
    'name' => 'mode',
  ),
  'availability' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_STATUS',
    'width' => '10%',
    'name' => 'availability',
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
);

<?php
$module_name = 'Allocations';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'MODE' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_MODE',
    'width' => '10%',
    'default' => true,
  ),
  'DATE_FROM' => 
  array (
    'type' => 'date',
    'default' => true,
    'label' => 'LBL_DATE_FROM',
    'width' => '10%',
  ),
  'DATE_TO' => 
  array (
    'type' => 'date',
    'default' => true,
    'label' => 'LBL_DATE_TO',
    'width' => '10%',
  ),
  'WORKPLACE_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_RELATIONSHIP_WORKPLACES',
    'id' => 'WORKPLACE_ID',
    'width' => '10%',
    'default' => true,
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

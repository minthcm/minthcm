<?php
$module_name = 'Rooms';
$viewdefs [$module_name] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'useTabs' => true,
      'tabDefs' => 
      array (
        'DEFAULT' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
      ),
      'syncDetailEditViews' => false,
    ),
    'panels' => 
    array (
      'default' => 
      array (
        0 => 
        array (
          0 => 'name',
          1 => 'assigned_user_name',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'security_group_name',
            'label' => 'LBL_RELATIONSHIP_SECURITY_GROUP_NAME',
            'displayParams' => 
            array (
              'class' => 'sqsDisabled',
              'initial_filter' => '&group_type=business_unit',
            ),
          ),
          1 => 
          array (
            'name' => 'availability',
            'studio' => 'visible',
            'label' => 'LBL_STATUS',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'room_surface',
            'label' => 'LBL_ROOM_SURFACE',
          ),
          1 => 
          array (
            'name' => 'room_plan',
            'studio' => 'visible',
            'label' => 'LBL_ROOM_PLAN',
          ),
        ),
        3 => 
        array (
          0 => 'description',
        ),
      ),
    ),
  ),
);
;
?>

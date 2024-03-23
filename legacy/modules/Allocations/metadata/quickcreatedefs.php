<?php
$module_name = 'Allocations';
$viewdefs [$module_name] = 
array (
  'QuickCreate' => 
  array (
    'templateMeta' => 
    array (
      'includes' => 
      array (
        0 => 
        array (
          'file' => 'modules/Allocations/js/edit.js',
        ),
      ),
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
      'useTabs' => false,
      'tabDefs' => 
      array (
        'DEFAULT' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
    ),
    'panels' => 
    array (
      'default' => 
      array (
        array (
          'assigned_user_name',
        ),
        array (
          'mode',
          array (
            'name' => 'workplace_name',
            'label' => 'LBL_RELATIONSHIP_WORKPLACES',
          ),
        ),
        array (
          array (
            'name' => 'date_from',
            'label' => 'LBL_DATE_FROM',
          ),
          array (
            'name' => 'date_to',
            'label' => 'LBL_DATE_TO',
          ),
        ),
      ),
    ),
  ),
);
;
?>

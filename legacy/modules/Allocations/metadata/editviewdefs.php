<?php
$module_name = 'Allocations';
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
    ),
    'panels' => 
    array (
      'default' => 
      array (
        0 => 
        array (
          1 => 'assigned_user_name',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'mode',
            'studio' => 'visible',
            'label' => 'LBL_MODE',
          ),
          1 => 
          array (
            'name' => 'workplace_name',
            'label' => 'LBL_RELATIONSHIP_WORKPLACES',
            'displayParams' => array (
              'initial_filter' => '" + (this.form.{$fields.mode.name}.value == "permanent" ?  "&mode_advanced[]=permanent" : ("&mode_advanced[]=hybrid&mode_advanced[]=rotational") ) + "&availability_advanced=active',
            ),
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'date_from',
            'label' => 'LBL_DATE_FROM',
          ),
          1 => 
          array (
            'name' => 'date_to',
            'label' => 'LBL_DATE_TO',
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

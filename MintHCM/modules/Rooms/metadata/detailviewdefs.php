<?php
$module_name = 'Rooms';
$viewdefs [$module_name] = 
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'buttons' => 
        array (
          0 => 'EDIT',
          1 => 'DUPLICATE',
          2 => 'DELETE',
          3 => 'FIND_DUPLICATES',
          4 => 
          array (
            'customCode' => '{include file="modules/Rooms/tpls/ReservationButton.tpl"}',
          ),
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
      'includes' => array(
        array('file' => "modules/Rooms/js/jquery.magnify.min.js"),
        array('file' => "modules/Rooms/js/view.detail.js"),
      ),
      'useTabs' => true,
      'tabDefs' => 
      array (
        'DEFAULT' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_PANEL_ASSIGNMENT' => 
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
          0 => 'name',
          1 => 'assigned_user_name',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'availability',
            'studio' => 'visible',
            'label' => 'LBL_STATUS',
          ),
          1 => 
          array (
            'name' => 'number_of_seats',
            'label' => 'LBL_NUMBER_OF_SEATS',
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
            'customCode' => (function() {
                $id = '{$fields.id.value}';
                $url = "index.php?entryPoint=download&type=Rooms&id={$id}_room_plan";
                return "<img data-magnify='gallery' data-src='{$url}' src='{$url}' style='max-width: 120px;'>";
            })(),
          ),
        ),
        3 => 
        array (
          0 => 'reservation_type',
          1 => 
          array (
            'name' => 'resource_name',
            'label' => 'LBL_RESOURCE_NAME',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'security_group_name',
            'label' => 'LBL_RELATIONSHIP_SECURITY_GROUP_NAME',
          ),
        ),
        5 => 
        array (
          0 => 'description',
        ),
      ),
      'LBL_PANEL_ASSIGNMENT' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'created_by_name',
            'label' => 'LBL_CREATED',
          ),
          1 => 
          array (
            'name' => 'modified_by_name',
            'label' => 'LBL_MODIFIED_NAME',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'date_entered',
            'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
          ),
          1 => 
          array (
            'name' => 'date_modified',
            'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
          ),
        ),
      ),
    ),
  ),
);
;
?>

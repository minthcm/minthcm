<?php
$module_name = 'AM_ProjectTemplates';
$viewdefs [$module_name] =
array(
  'DetailView' =>
  array(
    'templateMeta' =>
    array(
      'form' =>
      array(
        'buttons' =>
        array(
          0 => 'EDIT',
          1 => 'DUPLICATE',
          2 => 'DELETE',
          3 => 'FIND_DUPLICATES',
          4 =>
              array(
                    'customCode' => '<input title="{$APP.LBL_CREATE_PROJECT}" class="button" type="button" name="create_project" id="create_project" value="{$APP.LBL_CREATE_PROJECT}" onclick="confirmation(\'{$id}\')" />',
              ),
          5 =>
              array(
                'customCode' => '<input title="{$APP.LBL_VIEW_GANTT_TITLE}" class="button" type="button" name="view_gantt" id="view_gantt" value="{$APP.LBL_GANTT_BUTTON_LABEL}" onclick="javascript:window.location.href=\'index.php?module=AM_ProjectTemplates&action=view_GanttChart&record={$id}\'"/>',
              ),
          6 =>
          array(
            'customCode' => '<input title="{$APP.LBL_VIEW_DETAIL}" class="button" type="button" name="view_detail" id="view_detail" value="{$APP.LBL_DETAIL_BUTTON_LABEL}" onclick="javascript:window.location.href=\'index.php?module=AM_ProjectTemplates&action=DetailView&record={$id}\'"/>',
          ),
        ),
      ),
      'maxColumns' => '2',
      'widths' =>
      array(
        0 =>
        array(
          'label' => '10',
          'field' => '30',
        ),
        1 =>
        array(
          'label' => '10',
          'field' => '30',
        ),
      ),
      'includes' =>
      array(
          0 =>
          array(
           'file' => 'modules/AM_ProjectTemplates/create_project.js',
          ),
      ),
      'useTabs' => true,
      'tabDefs' =>
      array(
        'DEFAULT' =>
        array(
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_PANEL_ASSIGNMENT' =>
        array(
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
      ),
      'syncDetailEditViews' => true,
    ),
    'panels' =>
    array(
      'default' =>
      array(
        0 =>
        array(
          0 => 'name',
          1 =>
          array(
            'name' => 'status',
            'studio' => 'visible',
            'label' => 'LBL_STATUS',
          ),
        ),
        1 =>
        array(
          0 => 'override_business_hours',
          1 =>
          array(
            'name' => 'priority',
            'studio' => 'visible',
            'label' => 'LBL_PRIORITY',
          ),
        ),
      ),
      'LBL_PANEL_ASSIGNMENT' =>
      array (
        0 =>
        array (
          0 => 
          array (
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO_NAME',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'date_entered',
            'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
            'label' => 'LBL_DATE_ENTERED',
      ),
          1 => 
          array (
            'name' => 'date_modified',
            'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
            'label' => 'LBL_DATE_MODIFIED',
    ),
  ),
      )
    ),
  ),
);

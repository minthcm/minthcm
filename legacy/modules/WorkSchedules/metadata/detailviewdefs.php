<?php
$module_name = 'WorkSchedules';
$viewdefs[$module_name] =
array(
    'DetailView' => array(
        'templateMeta' => array(
            'form' => array(
                'buttons' => array(
                    0 => 'EDIT',
                    1 => 'DUPLICATE',
                    2 => 'DELETE',
                    3 => 'FIND_DUPLICATES',
                    4 => array(
                        'customCode' => '{include file="modules/WorkSchedules/tpls/CloseButton.tpl"}{include file="modules/WorkSchedules/tpls/AcceptWorkPlanButton.tpl"}{include file="modules/WorkSchedules/tpls/UndoAcceptanceButton.tpl"}',
                    ),
                ),
                'hidden' => array(
                    0 => '<input type="hidden" name="current_user_is_admin" id="current_user_is_admin" value="{$CURRENT_USER_IS_ADMIN}">',
                ),
            ),
            'maxColumns' => '2',
            'widths' => array(
                0 => array(
                    'label' => '10',
                    'field' => '30',
                ),
                1 => array(
                    'label' => '10',
                    'field' => '30',
                ),
            ),
            'useTabs' => true,
            'tabDefs' => array(
                'DEFAULT' => array(
                    'newTab' => true,
                    'panelDefault' => 'expanded',
                ),
                '' => array(
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'LBL_PANEL_ASSIGNMENT' => array(
                    'newTab' => true,
                    'panelDefault' => 'expanded',
                ),
            ),
            'includes' => array(
                0 => array(
                    'file' => 'include/javascript/moment.min.js',
                ),
                1 => array(
                    'file' => 'modules/WorkSchedules/js/detail.js',
                ),
                2 => array(
                    'file' => 'modules/WorkSchedules/tpls/TimeTrackingPane.js',
                ),
            ),
        ),
        'panels' => array(
            'default' => array(
                array(
                    0 => 'name',
                    1 => 'assigned_user_name',
                ),
                array(
                    0 => array(
                        'name' => 'type',
                        'studio' => 'visible',
                        'label' => 'LBL_TYPE',
                    ),
                    1 => array(
                        'name' => 'status',
                        'studio' => 'visible',
                        'label' => 'LBL_STATUS',
                    ),
                ),
                array(
                    0 => 'deputy_name',
                ),
                array(
                    0 => array(
                        'name' => 'comments',
                        'label' => 'LBL_COMMENTS',
                    ),
                    1 => array(
                        'name' => 'occasional_leave_type',
                        'label' => 'LBL_OCCASIONAL_LEAVE_TYPE',
                    ),
                ),
                array(
                    0 => array(
                        'name' => 'date_start',
                        'label' => 'LBL_DATE_START',
                    ),
                    1 => array(
                        'name' => 'date_end',
                        'label' => 'LBL_DATE_END',
                    ),
                ),
                array(
                    0 => 'spent_time',
                    1 => '',
                ),
                array(
                    0 => 'spent_time_settlement',
                    1 => 'delegation_name',
                ),
                array(
                    0 => array(
                        'name' => 'supervisor_acceptance',
                        'studio' => 'visible',
                        'label' => 'LBL_SUPERVISOR_ACCEPTANCE',
                    ),
                    1 => '',
                ),
                array(
                    0 => array(
                        'name' => 'workplace_name',
                        'label' => 'LBL_RELATIONSHIP_WORKPLACE_NAME',
                    ),
                    '',
                ),
                array(
                    0 => 'delegation_duration',
                ),
                array(
                    0 => 'description',
                ),
            ),
            '' => array(
                0 => array(
                    0 => array(
                        'name' => 'time_tracking_pane',
                        'customCode' => '{include file="modules/WorkSchedules/tpls/TimeTrackingPane.tpl"}',
                    ),
                ),
            ),
            'LBL_PANEL_ASSIGNMENT' => array(
                0 => array(
                    0 => array(
                        'name' => 'date_entered',
                        'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
                    ),
                    1 => array(
                        'name' => 'date_modified',
                        'label' => 'LBL_DATE_MODIFIED',
                        'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
                    ),
                ),
            ),
        ),
    ),
);

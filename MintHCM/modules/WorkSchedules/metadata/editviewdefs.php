<?php
$module_name = 'WorkSchedules';
$viewdefs[$module_name] =
array(
    'EditView' => array(
        'templateMeta' => array(
            'includes' => array(
                0 => array(
                    'file' => 'include/javascript/moment.min.js',
                ),
                1 => array(
                    'file' => 'modules/WorkSchedules/js/edit.js',
                ),
            ),
            'form' => array(
                'hidden' => array(
                    0 => '<input type="hidden" name="current_user_is_admin" id="current_user_is_admin" value="{$CURRENT_USER_IS_ADMIN}">',
                    1 => '<input type="hidden" name="redirected_from_calendar" id="redirected_from_calendar" value="{$REDIRECTED_FROM_CALENDAR}">',
                    2 => '<input type="hidden" name="previous_diff_minutes" id="previous_diff_minutes" value="{$PREVIOUS_DIFF_MINUTES}">',
                    3 => '<input type="hidden" name="return_module" id="return_module" value="{$RETURN_MODULE}">',
                ),
                'maxColumns' => '2',
                'useTabs' => false,
                'tabDefs' => array(
                    'DEFAULT' => array(
                        'newTab' => false,
                        'panelDefault' => 'expanded',
                    ),
                ),
            ),
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
            'useTabs' => false,
            'tabDefs' => array(
                'DEFAULT' => array(
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'LBL_REPEAT_TAB' => array(
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
            ),
        ),
        'panels' => array(
            'default' => array(
                0 => array(
                    0 => 'assigned_user_name',
                ),
                1 => array(
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
                2 => array(
                    0 => array(
                        'name' => 'comments',
                        'label' => 'LBL_COMMENTS',
                    ),
                    1 => array(
                        'name' => 'occasional_leave_type',
                        'label' => 'LBL_OCCASIONAL_LEAVE_TYPE',
                    ),
                ),
                3 => array(
                    0 => array(
                        'name' => 'date_start',
                        'label' => 'LBL_DATE_START',
                        'displayParams' => array(
                            'minutesStep' => 5,
                        ),
                    ),
                    1 => array(
                        'name' => 'date_end',
                        'label' => 'LBL_DATE_END',
                        'displayParams' => array(
                            'minutesStep' => 5,
                        ),
                    ),
                ),
                4 => array(
                    0 => array(
                        'name' => 'duration_hours',
                        'label' => 'LBL_DURATION',
                        'customCode' => '{include file="modules/WorkSchedules/tpls/DurationFieldEditView.tpl"}',
                    ),
                    1 => 'delegation_name',
                ),
                5 => array(
                    0 => array(
                        'name' => 'workplace_name',
                        'label' => 'LBL_RELATIONSHIP_WORKPLACE_NAME',
                        'displayParams' => array(
                            // 'class' => 'sqsDisabled',
                            'initial_filter' => '&for_employee_id="+this.form.{$fields.assigned_user_id.name}.value+"&availability_advanced=active',
                        ),
                    ),
                    '',
                ),
                6 => array(
                    0 => 'delegation_duration',
                ),
                7 => array(
                    0 => 'description',
                ),
            ),
            'LBL_REPEAT_TAB' => array(
                0 => array(
                    0 => array(
                        'name' => 'repeat_pane',
                        'hideLabel' => true,
                        'customCode' => '{include file="modules/WorkSchedules/tpls/RepeatPanelEditView.tpl"}',
                    ),
                ),
            ),
        ),
    ),
);

<?php
$module_name = 'WorkSchedules';
$viewdefs[$module_name] =
array(
    'EditView' => array(
        'templateMeta' => array(
            'includes' => array(
                array(
                    'file' => 'include/javascript/moment.min.js',
                ),
                array(
                    'file' => 'modules/WorkSchedules/js/edit.js',
                ),
            ),
            'form' => array(
                'hidden' => array(
                    '<input type="hidden" name="current_user_is_admin" id="current_user_is_admin" value="{$CURRENT_USER_IS_ADMIN}">',
                    '<input type="hidden" name="redirected_from_calendar" id="redirected_from_calendar" value="{$REDIRECTED_FROM_CALENDAR}">',
                    '<input type="hidden" name="previous_diff_minutes" id="previous_diff_minutes" value="{$PREVIOUS_DIFF_MINUTES}">',
                    '<input type="hidden" name="return_module" id="return_module" value="{$RETURN_MODULE}">',
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
                array(
                    'label' => '10',
                    'field' => '30',
                ),
                array(
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
                array(
                    'assigned_user_name',
                ),
                array(
                    array(
                        'name' => 'type',
                        'studio' => 'visible',
                        'label' => 'LBL_TYPE',
                    ),
                    array(
                        'name' => 'status',
                        'studio' => 'visible',
                        'label' => 'LBL_STATUS',
                    ),
                ),
                array(
                    'deputy_name',
                ),
                array(
                    array(
                        'name' => 'comments',
                        'label' => 'LBL_COMMENTS',
                    ),
                    array(
                        'name' => 'occasional_leave_type',
                        'label' => 'LBL_OCCASIONAL_LEAVE_TYPE',
                    ),
                ),
                array(
                    array(
                        'name' => 'date_start',
                        'label' => 'LBL_DATE_START',
                        'displayParams' => array(
                            'minutesStep' => 5,
                        ),
                    ),
                    array(
                        'name' => 'date_end',
                        'label' => 'LBL_DATE_END',
                        'displayParams' => array(
                            'minutesStep' => 5,
                        ),
                    ),
                ),
                array(
                    array(
                        'name' => 'duration_hours',
                        'label' => 'LBL_DURATION',
                        'customCode' => '{include file="modules/WorkSchedules/tpls/DurationFieldEditView.tpl"}',
                    ),
                    'delegation_name',
                ),
                array(
                    array(
                        'name' => 'workplace_name',
                        'label' => 'LBL_RELATIONSHIP_WORKPLACE_NAME',
                        'displayParams' => array(
                            // 'class' => 'sqsDisabled',
                            'initial_filter' => '&for_employee_id="+this.form.{$fields.assigned_user_id.name}.value+"&availability_advanced=active',
                        ),
                    ),
                    '',
                ),
                array(
                    'delegation_duration',
                ),
                array(
                    'description',
                ),
            ),
            'LBL_REPEAT_TAB' => array(
                array(
                    array(
                        'name' => 'repeat_pane',
                        'hideLabel' => true,
                        'customCode' => '{include file="modules/WorkSchedules/tpls/RepeatPanelEditView.tpl"}',
                    ),
                ),
            ),
        ),
    ),
);

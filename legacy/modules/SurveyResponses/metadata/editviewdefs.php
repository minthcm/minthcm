<?php
$module_name = 'SurveyResponses';
$viewdefs[$module_name] = array(
    'EditView' => array(
        'templateMeta' => array(
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
        ),
        'panels' => array(
            'default' => array(
                0 => array(
                    0 => 'name',
                    1 => 'assigned_user_name',
                ),
                1 => array(
                    0 => 'description',
                    1 => array(
                        'name' => 'survey_name',
                    ),
                ),
                2 => array(
                    0 => 'campaign_name',
                    1 => 'employee_name',
                ),
            ),
        ),
    ),
);

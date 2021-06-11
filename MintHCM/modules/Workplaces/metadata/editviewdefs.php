<?php
$module_name = 'Workplaces';
$viewdefs[$module_name] =
array(
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
            'useTabs' => true,
            'tabDefs' => array(
                'DEFAULT' => array(
                    'newTab' => true,
                    'panelDefault' => 'expanded',
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
                    0 => array(
                        'name' => 'mode',
                        'studio' => 'visible',
                        'label' => 'LBL_MODE',
                    ),
                    1 => array(
                        'name' => 'availability',
                        'studio' => 'visible',
                        'label' => 'LBL_STATUS',
                    ),
                ),
                2 => array(
                    0 => array(
                        'name' => 'room_name',
                        'label' => 'LBL_RELATIONSHIP_ROOM_NAME',
                        'displayParams' => array(
                            'class' => 'sqsDisabled',
                            'initial_filter' => '&availability_advanced=active',
                        ),
                    ),
                    '',
                ),
                3 => array(
                    0 => 'description',
                ),
            ),
        ),
    ),
);

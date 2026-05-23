<?php

// TODO - można uprościć
$viewdefs['EmployeeRoles'] = [
    'order' => [
        'mainPanel',
        'positionCard',
        'subpanels',
    ],
    'panels' => [
        'mainPanel' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
                        'fields' => [
                            [
                                [
                                    'name' => 'name',
                                    'label' => 'LBL_NAME',
                                    'type' => 'varchar',
                                ],
                                [
                                    'name' => 'status',
                                    'label' => 'LBL_STATUS',
                                    'type' => 'enum',
                                    'options' => 'role_status',
                                ],
                            ],
                            [
                                [
                                    'name' => 'description',
                                    'label' => 'LBL_DESCRIPTION',
                                    'type' => 'text',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        
        'positionCard' => [
            'component' => 'MintPanelPositionCard',
        ],

        'subpanels' => [
            'component' => 'MintPanelSubpanels',
        ],
    ],
];

<?php

$viewdefs['Ideas'] = [
    'clientDependencies' => [
        'modules/Ideas/js/edit.js',
    ],
    'order' => [
        'mainPanel',
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
                                    'options' => 'idea_status_list',
                                ],
                            ],
                            [
                                [
                                    'name' => 'user_name',
                                    'label' => 'LBL_USER_NAME',
                                    'type' => 'relate',
                                    'module' => 'Users',
                                    'id_name' => 'user_id',
                                ],
                                'explanation',
                            ],
                            [
                                'description',
                            ]
                        ],
                    ],
                ],
            ],
        ],
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
        ],
    ],
];

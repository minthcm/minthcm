<?php

$viewdefs['OnboardingTemplates'] = [
    'order' => [
        'mainPanel',
        'subpanels',
    ],
    'panels' => [
        'mainPanel' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'actions' => [
                    'Audit',
                    'Delete',
                    [
                        'title' => 'LBL_GENERATE_BUTTON',
                        'icon' => 'mdi-history',
                    ],
                    'Duplicate',
                ],
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
                        'fields' => [
                            [
                                [
                                    'name' => 'name',
                                    'label' => 'LBL_NAME',
                                ],
                            ],
                            [
                                [
                                    'name' => 'description',
                                    'label' => 'LBL_DESCRIPTION',
                                ],
                            ],
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

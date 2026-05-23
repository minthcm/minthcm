<?php

$viewdefs['OffboardingTemplates'] = [
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

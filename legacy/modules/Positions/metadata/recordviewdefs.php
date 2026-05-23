<?php

$viewdefs['Positions'] = [
    'order' => ['mainPanel', 'positionCard', 'subpanels'],
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
                                    'options' => 'position_status',
                                ],
                            ],
                            [
                                'securitygroup_leader_name',
                                'positions_supervision_name',
                            ],
                            [
                                'offboardingtemplate_name',
                                'onboardingtemplate_name',
                            ],
                            [
                                'description',
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

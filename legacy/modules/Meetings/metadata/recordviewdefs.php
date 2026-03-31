<?php

$viewdefs['Meetings'] = [
    'order' => ['basicInfo', 'scheduler', 'subpanels'],
    'panels' => [
        'basicInfo' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'actions' => [
                    'Audit',
                    'Delete',
                    [
                        'name' => 'Duplicate',
                        'skipFields' => ['repeat'],
                    ],
                ],
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
                        'fields' => [
                            ['name', 'status'],
                            ['type', 'parent_name'],
                            ['date_start', 'date_end'],
                            ['assigned_user_name', 'location'],
                            ['description', 'repeat']
                        ],
                    ],
                ],
            ],
        ],
        'scheduler' => [
            'component' => 'MintPanelScheduler',
        ],
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
        ],
    ],
];

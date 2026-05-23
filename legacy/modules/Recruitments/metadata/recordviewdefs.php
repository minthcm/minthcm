<?php

$viewdefs['Recruitments'] = [
    'order' => ['overview', 'subpanels'],
    'panels' => [
        'overview' => [
            'component' => 'MintPanelRecordDetails',
            'title' => 'LBL_DETAILS',
            'actions' => [
                'Audit',
                'Delete',
                'Duplicate',
            ],
            'data' => [
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
                        'fields' => [
                            [
                                'name',
                            ],
                            [
                                'start_date',
                                'end_date',
                            ],
                            [
                                'project_status',
                                'position_name',
                            ],
                            [
                                'currency_id',
                                '',
                            ],
                            [
                                'salary_from',
                                'salary_to',
                            ],
                            [
                                'description',
                            ],
                            [
                                'vacancy',
                                'start_work_date',
                            ],
                            [
                                'recruitment_channels',
                                'recruitment_type',
                            ],
                            [
                                'employees_number',
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

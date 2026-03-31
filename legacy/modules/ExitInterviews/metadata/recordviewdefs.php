<?php

$viewdefs['ExitInterviews'] = [
    'order' => ['details', 'subpanels'],
    'panels' => [
        'details' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
                        'fields' => [
                            [
                                'name',
                                'employee_name',
                            ],
                            [
                                'status',
                                'offboarding_name',
                            ],
                            [
                                'date_start',
                                'date_end',
                            ],
                            [
                                'description',
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

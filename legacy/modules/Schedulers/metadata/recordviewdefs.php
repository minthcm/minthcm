<?php

$viewdefs['Schedulers'] = [
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
                                'status',
                            ],
                            [
                                ['name' => 'job', 'type' => 'enum', 'function' => ['name' => ['Scheduler', 'getJobsList'], 'params' => []]],
                                ['name' => 'job_interval', 'type' => 'crontab'],
                            ],
                            [
                                'job_url',
                                '',
                            ],
                            [
                                'date_time_start',
                                'date_time_end',
                            ],
                            [
                                'last_run',
                                'catch_up',
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

<?php

$viewdefs['WorkSchedules'] = [
    'order' => ['basicInfo', /* 'recurrence', */ 'subpanels'],
    'panels' => [
        'basicInfo' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
                        'fields' => [
                            [
                                ['name' => 'status'],
                                ['name' => 'type'],
                            ],
                            [
                                ['name' => 'delegation_duration'],
                                ['name' => 'occasional_leave_type'],
                            ],
                            [
                                ['name' => 'date_start'],
                                ['name' => 'date_end'],
                            ],
                            [
                                ['name' => 'assigned_user_name'],
                            ],
                            [
                                ['name' => 'duration_hours'],
                                ['name' => 'duration_minutes'],
                            ],
                            [
                                ['name' => 'workplace_name'],
                                ['name' => 'delegation_name'],
                            ],
                            [
                                ['name' => 'description'],
                            ],
                            [
                                ['name' => 'supervisor_acceptance'],
                                ['name' => 'comments'],
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

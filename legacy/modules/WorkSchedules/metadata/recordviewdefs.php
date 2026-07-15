<?php

$viewdefs['WorkSchedules'] = [
    'order' => ['basicInfo', 'subpanels'],
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
                                ['name' => 'repeat'],
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

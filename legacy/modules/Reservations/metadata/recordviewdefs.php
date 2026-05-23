<?php

$viewdefs['Reservations'] = [
    'order' => ['basicInfo'],
    'panels' => [
        'basicInfo' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
                        'fields' => [
                            [
                                'name',
                                'starting_date',
                                'ending_date',
                            ],
                            [
                                'resource_name',
                                'delegation_name',

                            ],
                            [
                                'employee_name',

                            ],
                            [
                                'description',
                            ],
                        ],
                    ],
                    'd1' => [
                        'title' => 'LBL_RECORDVIEW_PANEL1',
                        'fields' => [
                            [
                                'parent_name',
                                '',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];

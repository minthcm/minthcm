<?php

$viewdefs['Rooms'] = [
    'order' => ['basicInfo', 'subpanels'],
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
                            ],
                            [
                                [
                                    'name' => 'security_group_name',
                                    'filters' => [
                                        [
                                            'field' => 'group_type',
                                            'operator' => 'equal',
                                            'value' => 'business_unit',
                                            'editable' => false,
                                        ],
                                    ],
                                ],
                                'room_surface',
                            ],
                            [
                                'room_plan',
                                'number_of_seats',
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

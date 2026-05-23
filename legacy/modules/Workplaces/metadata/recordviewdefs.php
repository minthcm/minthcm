<?php

$viewdefs['Workplaces'] = [
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
                                'mode',
                                'availability',
                            ],
                            [
                                [
                                    'name' => 'room_name',
                                    'filters' => [
                                        [
                                            'field' => 'availability',
                                            'operator' => 'equal',
                                            'value' => 'active',
                                            'editable' => false,
                                        ],
                                    ],
                                ],
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

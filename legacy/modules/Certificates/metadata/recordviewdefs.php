<?php

$viewdefs['Certificates'] = [
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
                                [
                                    'name' => 'name',
                                    'required' => true,
                                ],
                                'attempts_number',
                            ],
                            [
                                'pass_rate',
                                'duration',
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

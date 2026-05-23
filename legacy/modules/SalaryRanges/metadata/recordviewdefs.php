<?php

$viewdefs['SalaryRanges'] = [
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
                                ['name' => 'position_name'],
                            ],
                            [
                                ['name' => 'start_date'],
                                ['name' => 'end_date'],
                            ],
                            [
                                ['name' => 'currency_id'],
                            ],
                        ],
                    ],
                    'ranges' => [
                        'title' => 'LBL_SALARY_RANGES',
                        'fields' => [
                            [
                                ['name' => 'gross_value_from'],
                                ['name' => 'gross_value_to'],
                            ],
                            [
                                ['name' => 'net_value_from'],
                                ['name' => 'net_value_to'],
                            ],
                            [
                                ['name' => 'employer_costs_from'],
                                ['name' => 'employer_costs_to'],
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

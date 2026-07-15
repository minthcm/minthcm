<?php

$viewdefs['Contracts'] = [
    'order' => ['basicInfo', 'subpanels'],
    'panels' => [
        'basicInfo' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
                        'fields' => [
                            ['name', 'status'],
                            ['contract_starting_date', 'contract_ending_date'],
                            ['daily_working_time', 'contract_type'],
                            ['date_of_signing', 'employee_name'],
                            ['description'],
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

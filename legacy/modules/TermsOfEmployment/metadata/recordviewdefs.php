<?php

$viewdefs['TermsOfEmployment'] = [
    'order' => ['contract', 'subpanels'],
    'panels' => [
        'contract' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
                        'fields' => [
                            [
                                'name',
                                'contract_name',
                            ],
                            [
                                'date_of_signing',
                                'position_name',
                            ],
                            [
                                'term_starting_date',
                                'term_ending_date',
                            ],
                            [
                                'employee_name',
                            ],
                            [
                                'description',
                            ],
                        ],
                    ],
                    'salary' => [
                        'title' => 'LBL_PANEL_SALARY',
                        'fields' => [
                            [
                                'gross',
                                'net',
                            ],
                            [
                                'employer_cost',
                                [
                                    'name' => 'currency_id',
                                    'type' => 'relate',
                                    'module' => 'Currencies',
                                    'id_name' => 'currency_id',
                                    'rname' => 'name',
                                ],
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

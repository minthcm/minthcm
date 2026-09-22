<?php

$viewdefs['TermsOfEmployment'] = [
    'order' => ['header', 'contract', 'subpanels'],
    'panels' => [
        'header' => [
            'component' => 'MintPanelRecordHeader',
            'data' => [
                'fields' => [
                    [
                        'contract_name',
                        'term_starting_date',
                        'gross'
                    ],
                ],
                'actions' => [
                    'Audit',
                    'Delete',
                ],
            ],
        ],
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
                                'position_name',
                            ],
                            [
                                'date_of_signing',
                                'term_starting_date',
                                'term_ending_date',
                            ],
                            [
                                'employee_name',
                                'assigned_user_name',
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
                                ['name' => 'currency_id'],
                            ],
                        ],
                    ],
                    'other' => [
                        'title' => 'LBL_PANEL_ASSIGNMENT',
                        'collapsed' => true,
                        'fields' => [
                            [
                                'name' => 'date_entered',
                                'readonly' => true,
                            ],
                            [
                                'name' => 'date_modified',
                                'readonly' => true,
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

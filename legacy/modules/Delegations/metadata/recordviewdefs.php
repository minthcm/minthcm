<?php

$viewdefs['Delegations'] = [
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
                                'assigned_user_name',
                                'owner',
                            ],
                            [
                                'delegation_locale_name',
                                'currency_id',
                            ],
                            [
                                'exchange_rate',
                                'purpose',
                            ],
                            [
                                'start_date',
                                'end_date',
                            ],
                            [
                                'obtained_sum',
                                'assured_number_of_breakfasts',
                            ],
                            [
                                'assured_number_of_dinners',
                                'assured_number_of_suppers',
                            ],
                            [
                                'assured_number_of_accommodations',
                                'description',
                            ],
                        ],
                    ],
                    'another' => [
                        'title' => 'LBL_CALCULATIONS',
                        'fields' => [
                            [
                                'transport_cost_usdollar',
                                'regiments_usdollar',
                            ],
                            [
                                'accommodation_lump_sum_usdollar',
                                'total_accommodation_usdollar',
                            ],
                            [
                                'other_usdollar',
                                'total_expenses_usdollar',
                            ],
                            [
                                'obtained_sum_usdollars',
                                'return_sum_usdollar',
                            ],
                            [
                                'payoff_sum_usdollar',
                            ],

                        ],
                    ],
                ],
            ],
        ],
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
            'title' => 'LBL_SUBPANELS',
        ],
    ],
];

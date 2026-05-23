<?php

$viewdefs['Costs'] = [
    'order' => ['contactInfo', 'subpanels'],
    'panels' => [
        'contactInfo' => [
            'component' => 'MintPanelRecordDetails',
            'title' => 'LBL_CONTACT_INFORMATION',
            'data' => [
                'actions' => [
                    'Audit',
                    'Delete',
                ],
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
                        'fields' => [
                            ['type', 'delegation_name'],
                            [
                                'accommodation_no',
                            ],
                            [
                                'type_of_meal',
                            ],
                            [
                                'transportation_name',
                            ],
                            [
                                'cost_amount',
                                'currency_id',
                            ],
                            [
                                'cost_date',
                                'cost_city',
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

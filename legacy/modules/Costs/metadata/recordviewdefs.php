<?php

$viewdefs['Costs'] = [
    'order' => ['contactInfo', 'subpanels'],
    'panels' => [
        'contactInfo' => [
            'component' => 'MintPanelRecordDetails',
            'title' => 'LBL_CONTACT_INFORMATION',
            'data' => [
                // This panel lists its whole action menu explicitly; the default actions
                // (Audit / Delete / Duplicate) must not be merged in on top of it.
                'includeDefaultActions' => false,
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

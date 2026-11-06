<?php

$viewdefs['Kudos'] = [
    'order' => ['basicInfo', 'subpanels'],
    'panels' => [
        'basicInfo' => [
            'component' => 'MintPanelRecordDetails',
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
                            [
                                'employee_name',
                                'private',
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

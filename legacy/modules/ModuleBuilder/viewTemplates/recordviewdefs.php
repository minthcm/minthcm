<?php

$viewdefs[''] = [
    'order' => ['details', 'subpanels'],
    'panels' => [
        'details' => [
            'component' => 'MintPanelRecordDetails',
            'title' => 'LBL_DETAILS',
            'data' => [
                'actions' => [
                    'Audit',
                    'Delete',
                    'Duplicate',
                ],
                'sections' => [],
            ],
        ],
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
        ],
    ],
];

<?php

$viewdefs['MCPDocumentation'] = [
    'order' => ['basicInfo', 'subpanels'],
    'panels' => [
        'basicInfo' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'actions' => [
                    'Audit',
                    'Delete',
                    'Duplicate',
                ],
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
                        'fields' => [
                            ['name', 'category_name'],
                            ['content'],
                        ],
                    ],
                    'other' => [
                        'title' => 'LBL_OTHER',
                        'fields' => [
                            ['date_entered', 'date_modified'],
                            ['created_by_name', 'modified_by_name'],
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

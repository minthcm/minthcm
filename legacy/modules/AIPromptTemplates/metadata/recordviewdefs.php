<?php

$viewdefs['AIPromptTemplates'] = [
    'order' => ['basicInfo', 'subpanels'],
    'panels' => [
        'basicInfo' => [
            'component' => 'MintPanelRecordDetails',
            'actions' => [
                'Audit',
                'Delete',
                'Duplicate',
            ],
            'data' => [
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
                        'fields' => [
                            [
                                'name',
                                'root_module',
                            ],
                        ],
                    ],
                    'prompt' => [
                        'title' => 'LBL_PROMPT',
                        'fields' => [
                            [
                                'content',
                            ],
                        ],
                    ],
                    'adv_information' => [
                        'title' => 'LBL_ADV_INFORMATION',
                        'fields' => [
                            [
                                'description',
                            ],
                            [
                                'assigned_user_name',
                                '',
                            ],
                        ],
                    ],
                    'other' => [
                        'title' => 'LBL_OTHER',
                        'fields' => [
                            [
                                'date_entered',
                                'date_modified',
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

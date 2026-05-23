<?php

$viewdefs['Documents'] = [
    'order' => ['documentInfo', 'subpanels'],
    'panels' => [
        'documentInfo' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
                        'fields' => [
                            [
                                'document_name',
                                'filename',
                            ],
                            [
                                'status_id',
                                'active_date',
                            ],
                            [
                                'exp_date',
                                'revision',
                            ],
                            [
                                'template_type',
                                'is_template',
                            ],
                            [
                                'category_id',
                                'subcategory_id',
                            ],
                            [
                                'description',
                            ],
                            [
                                'related_doc_name',
                                'related_doc_rev_number',
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

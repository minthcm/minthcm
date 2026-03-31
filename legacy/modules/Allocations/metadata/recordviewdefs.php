<?php

$viewdefs['Allocations'] = [
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
                                'mode',
                            ],
                            [
                                [
                                    'name' => 'workplace_name',
                                    'label' => 'LBL_RELATIONSHIP_WORKPLACES',
                                    'filters' => [
                                        [
                                            'field' => 'availability',
                                            'operator' => 'equal',
                                            'value' => 'active',
                                            'editable' => false,
                                        ],
                                    ],
                                ],
                            ],
                            [
                                'date_from',
                                'date_to',
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

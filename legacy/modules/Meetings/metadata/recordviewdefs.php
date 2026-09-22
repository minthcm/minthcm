<?php

$viewdefs['Meetings'] = [
    'order' => ['basicInfo', 'scheduler', 'subpanels'],
    'panels' => [
        'basicInfo' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'actions' => [
                    'Audit',
                    'Delete',
                    [
                        'name' => 'DuplicateMeetings',
                        'skipFields' => ['repeat','status'],
                    ],
                    [
                        'key' => 'CloseMeeting',
                        'label' => 'LBL_CLOSE_BUTTON_TITLE',
                        'icon' => 'mdi-check',
                        'acl' => 'edit',
                        'type' => 'confirm',
                        'confirm' => [
                            'body' => 'LBL_CLOSE_MEETING_CONFIRM_BODY',
                        ],
                        'api_route' => 'Meetings/closeMeeting',
                        'customVisibility' => [
                            'operator' => 'AND',
                            'conditions' => [
                                ['field' => 'status', 'operator' => '!=', 'value' => 'Held'],
                            ],
                        ],
                        'onSuccess' => 'reload',
                    ],
                ],
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
                        'fields' => [
                            ['name', 'status'],
                            ['type', 'parent_name'],
                            ['date_start', 'date_end'],
                            ['assigned_user_name', 'location'],
                            ['description', 'repeat']
                        ],
                    ],
                ],
            ],
        ],
        'scheduler' => [
            'component' => 'MintPanelScheduler',
        ],
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
        ],
    ],
];

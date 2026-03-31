<?php

$viewdefs['Candidates'] = [
    'order' => ['contactInfo', 'subpanels'],
    'panels' => [
        'contactInfo' => [
            'component' => 'MintPanelRecordDetails',
            'title' => 'LBL_CONTACT_INFORMATION',
            'data' => [
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
                        'fields' => [
                            ['first_name', 'last_name'],
                            [
                                 'email1', 
                                [
                                    'name' => 'birthdate',
                                    'type' => 'age',
                                ],
                            ],
                            [
                                'phone_mobile',
                                'recr_contact_agree',
                            ],
                            [
                                [
                                    'name' => 'primary_address',
                                    'type' => 'fieldset',
                                    'label' => 'LBL_PRIMARY_ADDRESS',
                                    'properties' => [
                                        'fields' => [
                                            'primary_address_street',
                                            'primary_address_city',
                                            'primary_address_state',
                                            'primary_address_postalcode',
                                            'primary_address_country',
                                        ],
                                        'separator' => ', ',
                                    ],
                                ],
                                [
                                    'name' => 'alt_address',
                                    'type' => 'fieldset',
                                    'label' => 'LBL_ALT_ADDRESS',
                                    'properties' => [
                                        'fields' => [
                                            'alt_address_street',
                                            'alt_address_city',
                                            'alt_address_state',
                                            'alt_address_postalcode',
                                            'alt_address_country',
                                        ],
                                        'separator' => ', ',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'moreInfo' => [
                        'title' => 'LBL_SHOW_MORE_INFORMATION',
                        'fields' => [
                            ['potential', 'relocation'],
                            ['last_time_contact', 'date_planned_contact'],
                            ['description'],
                        ],
                    ],
                    'socials' => [
                        'title' => 'LBL_RECORDVIEW_PANEL1',
                        'fields' => [
                            ['linkedin', 'github'],
                            ['facebook', 'x_service'],
                        ],
                    ],
                    'other' => [
                        'title' => 'LBL_RECORDVIEW_PANEL2',
                        'collapsed' => true,
                        'fields' => [
                            ['assigned_user_name'],
                            ['date_entered', 'date_modified'],
                        ],
                    ],
                ],
            ],
        ],
        'files' => [
            'component' => 'MintPanelFiles',
        ],
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
        ],
    ],
];

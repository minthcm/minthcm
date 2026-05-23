<?php

$viewdefs['Employees'] = [
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
                                'first_name',
                                'last_name',
                            ],
                            [
                                [
                                    'name' => 'email1',
                                    'type' => 'email',
                                ],
                                'phone_mobile',
                            ],
                            [
                                'phone_work',
                                'phone_other',
                            ],
                            [
                                'employee_status',
                                [
                                    'name' => 'birthdate',
                                    'type' => 'age',
                                ],
                            ],
                            [
                                'position_name',
                                [
                                    'name' => 'photo',
                                    'type' => 'file',
                                ],
                            ],
                            [
                                'securitygroup_name',
                                'reports_to_name',
                            ],
                            [
                                'messenger_type',
                                'messenger_id',
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
                                '',
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

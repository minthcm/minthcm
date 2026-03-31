<?php

$viewdefs['EmployeeCertificates'] = [
    'order' => ['basicInfo', 'subpanels'],
    'panels' => [
        'basicInfo' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
                        'fields' => [
                            ['name'],
                            ['status', 'attempts_number'],
                            ['start_date', 'end_date'],
                            ['points_scored',
                                [
                                    'name' => 'certificate_name',
                                    'label' => 'LBL_RELATIONSHIP_CERTIFICATE_NAME',
                                ],
                            ],
                            [
                                [
                                    'name' => 'candidate_name',
                                    'label' => 'LBL_RELATIONSHIP_CANDIDATE_NAME',
                                ],
                                'employee_name',
                            ],
                            ['description'],
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

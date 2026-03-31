<?php

$viewdefs['Candidatures'] = [
    'order' => ['basicInfo', 'files', 'subpanels'],
    'panels' => [
        'basicInfo' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'actions' => [
                    'Audit',
                    'Delete',
                    'ConvertToEmployee',
                    'Duplicate',
                    'RejectAndMoveToAnotherRecruitment',
                ],
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
                        'fields' => [
                            [
                                'entry_interview',
                            ],
                            [
                                'status',
                                'status_information',
                            ],
                            [
                                'start_date',
                                'reason_for_rejection',
                            ],
                            [
                                'work_start',
                                'training_date',
                            ],
                            [
                                'to_decision',
                                'route_of_acquisition',
                            ],
                            [
                                [
                                    'name' => 'recruitment_name',
                                    'filters' => [
                                        [
                                            'field' => 'project_status',
                                            'operator' => 'equal',
                                            'value' => 'open',
                                            'editable' => true,
                                        ],
                                    ],
                                ],
                                'original_candidature_name',
                            ],
                            [
                                'parent_name',
                                'linkedin',
                            ],
                            [
                                'source',
                                'task_grade',
                            ],
                            [
                                'scoring',
                                'description',
                            ],
                        ],
                    ],
                    'd1' => [
                        'title' => 'LBL_RECORDVIEW_PANEL5',
                        'fields' => [
                            [
                                'employment_form',
                                'currency_id',
                            ],
                            [
                                'net_amount',
                                'gross_amount',
                            ],
                            [
                                'dg_amount',
                                'notice',
                            ],
                        ],
                    ],
                    'd2' => [
                        'title' => 'LBL_RECORDVIEW_PANEL4',
                        'fields' => [
                            [
                                'final_employment_form',
                                'salary_net',
                            ],
                            [
                                'notice_final_expectations',
                            ],
                        ],
                    ],
                    'other' => [
                        'title' => 'LBL_PANEL_ASSIGNMENT',
                        'collapsed' => true,
                        'fields' => [

                            [
                                'assigned_user_name',
                                'employee_name',
                            ],
                            [
                                [
                                    'name' => 'date_entered',
                                    'readonly' => true,
                                ],
                                [
                                    'name' => 'date_modified',
                                    'readonly' => true,
                                ],
                            ],
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

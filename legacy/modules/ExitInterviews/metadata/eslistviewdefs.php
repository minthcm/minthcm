<?php

$module_name = 'ExitInterviews';
$ESListViewDefs['ExitInterviews'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'status' => [
            'default' => true,
        ],
        'date_start' => [
            'default' => true,
        ],
        'date_end' => [
            'default' => true,
        ],
        'offboarding_name' => [
            'link' => true,
            'default' => true,
        ],
        'employee_name' => [
            'link' => true,
            'default' => true,
        ],
        'date_modified' => [
        ],
        'date_entered' => [
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
    ],
    'search' => [
        'name' => [],
        'date_start' => [],
        'date_end' => [],
        'status' => [],
        'offboarding_name' => [],
        'employee_name' => [],
        'assigned_user_name' => [],
        'date_entered' => [],
        'date_modified' => [],
        'created_by_name' => [],
        'modified_by_name' => [],
    ],
];

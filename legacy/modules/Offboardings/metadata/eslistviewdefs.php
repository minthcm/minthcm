<?php

$module_name = 'Offboardings';
$ESListViewDefs['Offboardings'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'date_start' => [
            'default' => true,
        ],
        'status' => [
            'default' => true,
        ],
        'offboardingtemplate_name' => [
            'link' => true,
            'default' => true,
        ],
        'employee_name' => [
            'link' => true,
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
    ],
    'search' => [
        'name' => [],
        'date_start' => [],
        'status' => [],
        'offboardingtemplate_name' => [],
        'employee_name' => [],
        'assigned_user_name' => [],
        'date_entered' => [],
        'date_modified' => [],
        'created_by_name' => [],
        'modified_by_name' => [],
    ],
];

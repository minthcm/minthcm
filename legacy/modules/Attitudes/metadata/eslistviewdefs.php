<?php

$module_name = 'Attitudes';
$ESListViewDefs['Attitudes'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'employee_name' => [
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'description' => [
        ],
        'date_modified' => [
            'default' => true,
        ],
        'date_entered' => [
            'default' => true,
        ],
    ],
    'search' => [
        'name' => [],
        'employee_name' => [],
        'assigned_user_name' => [],
        'date_entered' => [],
        'date_modified' => [],
        'created_by_name' => [],
        'modified_by_name' => [],
    ],
];

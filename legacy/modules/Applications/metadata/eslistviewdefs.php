<?php

$module_name = 'Applications';
$ESListViewDefs['Applications'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'status' => [
            'default' => true,
        ],
        'type' => [
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'employee_name' => [
            'default' => true,
        ],
        'date_modified' => [
            'default' => true,
        ],
        'date_entered' => [
            'default' => true,
        ],
        'created_by_name' => [
            'link' => true,
        ],
        'modified_by_name' => [
            'link' => true,
        ],
    ],
    'search' => [
        'name' => [],
        'status' => [],
        'type' => [],
        'employee_name' => [],
        'assigned_user_name' => [],
        'date_entered' => [],
        'date_modified' => [],
        'created_by_name' => [],
        'modified_by_name' => [],
    ],
];

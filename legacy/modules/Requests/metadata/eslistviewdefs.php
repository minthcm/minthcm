<?php

$module_name = 'Requests';
$ESListViewDefs['Requests'] = [
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
        'employee_name' => [
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'date_modified' => [
            'default' => true,
        ],
        'date_entered' => [
            'default' => true,
        ],
        'created_by_name' => [
            'link' => false,
        ],
        'modified_by_name' => [
            'link' => false,
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

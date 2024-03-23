<?php

$module_name = 'Resources';
$ESListViewDefs['Resources'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'unavailable' => [
            'default' => true,
        ],
        'type' => [
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'date_entered' => [
            'default' => true,
        ],
        'date_modified' => [
            'default' => true,
        ],
        'employee_name' => [
            'default' => true,
        ],
    ],
    'search' => [
        'name' => [],
        'employee_name' => [],
        'type' => [],
        'unavailable' => [],
        'assigned_user_name' => [],
        'date_entered' => [],
        'date_modified' => [],
        'created_by_name' => [],
        'modified_by_name' => [],
    ],
];

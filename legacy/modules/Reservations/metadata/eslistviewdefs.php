<?php

$module_name = 'Reservations';
$ESListViewDefs['Reservations'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'starting_date' => [
            'default' => true,
        ],
        'ending_date' => [
            'default' => true,
        ],
        'resource_name' => [
            'link' => true,
            'default' => true,
        ],
        'parent_name' => [
            'link' => true,
            'default' => true,
        ],
        'delegation_name' => [
            'link' => true,
            'default' => true,
        ],
        'employee_name' => [
            'default' => true,
        ],
    ],
    'search' => [
        'name' => [],
        'employee_name' => [],
        'parent_name' => [],
        'resource_name' => [],
        'delegation_name' => [],    
        'assigned_user_name' => [],
        'date_entered' => [],
        'date_modified' => [],
        'created_by_name' => [],
        'modified_by_name' => [],
    ],
];

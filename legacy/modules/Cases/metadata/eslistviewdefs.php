<?php

$module_name = 'Cases';
$ESListViewDefs['Cases'] = [
    'columns' => [
        'case_number' => [
            'default' => true,
        ],
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'account_name' => [
            'link' => true,
            'default' => true,
        ],
        'priority' => [
            'default' => true,
        ],
        'status' => [
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'date_entered' => [
            'default' => true,
        ],
    ],
    'search' => [
        'case_number' => [
        ],
        'name' => [
        ],
        'account_name' => [
        ],
        'type' => [
        ],
        'state' => [
        ],
        'status' => [
        ],
        'assigned_user_id' => [
        ],
        'priority' => [
        ],
    ],
];

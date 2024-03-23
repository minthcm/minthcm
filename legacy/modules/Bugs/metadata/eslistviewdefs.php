<?php

$module_name = 'Bugs';
$ESListViewDefs['Bugs'] = [
    'columns' => [
        'bug_number' => [
            'link' => true,
            'default' => true,
        ],
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
        'priority' => [
            'default' => true,
        ],
        'release_name' => [
        ],
        'fixed_in_release_name' => [
            'default' => true,
        ],
        'resolution' => [
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
    ],
    'search' => [
        'bug_number' => [
        ],
        'name' => [
        ],
        'resolution' => [
        ],
        'found_in_release' => [
        ],
        'fixed_in_release' => [
        ],
        'type' => [
        ],
        'status' => [
        ],
        'assigned_user_id' => [
        ],
        'priority' => [
        ],
    ],
];

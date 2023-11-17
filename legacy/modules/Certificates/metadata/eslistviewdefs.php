<?php

$module_name = 'Certificates';
$ESListViewDefs['Certificates'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'attempts_number' => [
            'default' => true,
        ],
        'duration' => [
            'default' => true,
        ],
        'pass_rate' => [
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'date_entered' => [
            'default' => true,
        ],
        'date_modified' => [
            'default' => false,
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
        'assigned_user_name' => [],
        'date_entered' => [],
        'date_modified' => [],
        'created_by_name' => [],
        'modified_by_name' => [],
        'attempts_number' => [],
        'duration' => [],
        'pass_rate' => [],       
    ],
];

<?php

$module_name = 'DashboardManager';
$ESListViewDefs['DashboardManager'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'is_loaded' => [
            'default' => true,
        ],
        'business_role' => [
        ],
        'description' => [
            'default' => true,
        ],
        'assigned_user_name' => [
        ],
        'date_entered' => [
            'default' => true,
        ],
        'created_by_name' => [
            'link' => true,
            'default' => true,
        ],
        'date_modified' => [
        ],
        'modified_by_name' => [
            'link' => true,
        ],
    ],
    'search' => [
        'name' => [
        ],
        'assigned_user_id' => [
        ],
        'is_loaded' => [
        ],
        'business_role' => [
        ],
        'date_entered' => [
        ],
    ],
];

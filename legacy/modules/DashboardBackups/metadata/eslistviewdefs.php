<?php

$module_name = 'DashboardBackups';
$ESListViewDefs['DashboardBackups'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'dashboardmanager_name' => [
            'default' => true,
        ],
        'dashboardhistory_name' => [
            'default' => true,
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
        'dashboardmanager_name' => [
        ],
        'dashboardhistory_name' => [
        ],
        'date_entered' => [
        ],
    ],
];

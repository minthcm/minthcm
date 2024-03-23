<?php

$module_name = 'DashboardHistory';
$ESListViewDefs['DashboardHistory'] = [
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
    ],
];

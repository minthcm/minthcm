<?php

$module_name = 'KReports';
$ESListViewDefs['KReports'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'report_module' => [
            'default' => true,
        ],
        'report_status' => [],
        'listtype' => [
            'default' => true,
        ],
        'chart_layout' => [
            'default' => true,
        ],
        'description' => [
            'default' => true,
        ],
        'date_entered' => [
            'default' => true,
        ],
        'date_modified' => [
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
    ],
    'search' => [
        'name' => [],
        'current_user_only' => [],
        'report_module' => [],
        'report_status' => [],
        'listtype' => [],
        'assigned_user_name' => [],
        'date_entered' => [],
        'date_modified' => [],
        'created_by_name' => [],
        'modified_by_name' => [],
    ],
];

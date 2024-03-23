<?php

$module_name = 'AOR_Scheduled_Reports';
$ESListViewDefs['AOR_Scheduled_Reports'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'aor_report_name' => [
            'link' => true,
            'default' => true,
        ],
        'status' => [
            'default' => true,
        ],
        'date_modified' => [
        ],
        'assigned_user_name' => [
            'link' => true,
        ],
        'date_entered' => [
        ],
    ],
    'search' => [
        'name' => [
        ],
        'current_user_only' => [
        ],
        'email' => [
        ],
    ],
];

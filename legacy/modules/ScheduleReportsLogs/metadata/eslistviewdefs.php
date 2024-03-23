<?php

$module_name = 'ScheduleReportsLogs';
$ESListViewDefs['ScheduleReportsLogs'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'execute_data' => [
            'default' => true,
        ],
        'status' => [
            'default' => true,
        ],
        'schedule_report_name' => [
            'link' => true,
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
    ],
    'search' => [
        'name' => [
        ],
        'status' => [
        ],
        'execute_data' => [
        ],
        'schedule_report_name' => [
        ],
        'assigned_user_id' => [
        ],
    ],
];

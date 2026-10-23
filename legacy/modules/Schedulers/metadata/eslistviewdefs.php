<?php

$module_name = 'Schedulers';
$ESListViewDefs['Schedulers'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'job_interval' => [
            'type' => 'crontab',
            'default' => true,
        ],
        'date_time_start' => [
            'default' => true,
        ],
        'status' => [
            'default' => true,
        ],
    ],
    'search' => [
        'name' => [],
        'status' => [],
    ],
    'panels' => [
        'Schedulers',
    ],
];

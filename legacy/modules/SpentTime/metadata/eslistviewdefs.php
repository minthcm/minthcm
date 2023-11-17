<?php

$module_name = 'SpentTime';
$ESListViewDefs['SpentTime'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'spend_time' => [
            'default' => true,
        ],
        'work_date' => [
            'default' => true,
        ],
        'type' => [
        ],
        'category' => [
        ],
        'description' => [
            'default' => true,
        ],
    ],
    'search' => [
        'name' => [
        ],
        'spent_time' => [
        ],
        'date_start' => [
        ],
        'date_end' => [
        ],
        'type' => [
        ],
        'category' => [
        ],
        'assigned_user_id' => [
        ],
    ],
];

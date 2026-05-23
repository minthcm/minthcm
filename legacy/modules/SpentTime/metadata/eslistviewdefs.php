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
        'organizational_unit' => [
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
        'organizational_unit' => [
        ],
        'assigned_user_id' => [
        ],
    ],
];

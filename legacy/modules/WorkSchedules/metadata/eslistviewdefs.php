<?php

$module_name = 'WorkSchedules';
$ESListViewDefs['WorkSchedules'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'type' => [
            'default' => true,
        ],
        'status' => [
            'default' => true,
        ],
        'supervisor_acceptance' => [
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'deputy_name' => [
        ],
        'date_start' => [
        ],
        'date_end' => [
        ],
        'spent_time' => [
        ],
        'spent_time_settlement' => [
        ],
        'delegation_duration' => [
        ],
        'workplace_name' => [
            'link' => true,
            'default' => true,
        ],
    ],
    'search' => [
        'name' => [],
        'type' => [],
        'status' => [],
        'supervisor_acceptance' => [],
        'date_start' => [],
        'date_end' => [],
        'delegation_duration' => [],
        'spent_time' => [],
        'spent_time_settlement' => [],
        'workplace_name' => [],
        'assigned_user_name' => [],
        'deputy_name' => [],
        'date_entered' => [],
        'date_modified' => [],
        'created_by_name' => [],
        'modified_by_name' => [],
    ],
];

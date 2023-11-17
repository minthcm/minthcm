<?php

$module_name = 'ProjectTask';
$ESListViewDefs['ProjectTask'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'project_name' => [
            'link' => true,
            'default' => true,
        ],
        'date_start' => [
            'default' => true,
        ],
        'date_finish' => [
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'priority' => [
            'default' => true,
        ],
        'percent_complete' => [
            'default' => true,
        ],
    ],
    'search' => [
        'name' => [],
        'project_name' => [],
        'date_start' => [],
        'date_finish' => [],
        'priority' => [],
        'percent_complete' => [],
        'assigned_user_name' => [],
        'date_entered' => [],
        'date_modified' => [],
        'created_by_name' => [],
        'modified_by_name' => [],
    ],
];

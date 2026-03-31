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
        'status' => [
            'default' => false,
        ],
        'task_number' => [
            'default' => false,
        ],
        'estimated_effort' => [
            'default' => false,
        ],
        'actual_effort' => [
            'default' => false,
        ],
        'predecessors' => [
            'default' => false,
        ],
        'relationship_type' => [
            'default' => false,
        ],
        'order_number' => [
            'default' => false,
        ],
        'milestone_flag' => [
            'default' => false,
        ],
        'utilization' => [
            'default' => false,
        ],
        'duration' => [
            'default' => false,
        ],
        'duration_unit' => [
            'default' => false,
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
        'status' => [],
        'task_number' => [],
        'estimated_effort' => [],
        'actual_effort' => [],
        'predecessors' => [],
        'relationship_type' => [],
        'order_number' => [],
        'milestone_flag' => [],
        'utilization' => [],
        'duration' => [],
        'duration_unit' => [],
    ],
];

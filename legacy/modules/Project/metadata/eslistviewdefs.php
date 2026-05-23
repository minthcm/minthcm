<?php

$module_name = 'Project';
$ESListViewDefs['Project'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'priority' => [
            'default' => true,
        ],
        'status' => [
            'type' => 'enum',
            'default' => true,
        ],
        'estimated_start_date' => [
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'estimated_end_date' => [
            'default' => true,
        ],
        'am_projecttemplates_project_1_name' => [
            'default' => false,
        ],
        'override_business_hours' => [
            'default' => false,
        ],
    ],
    'search' => [
        'name' => [],
        'estimated_start_date' => [],
        'estimated_end_date' => [],
        'status' => [],
        'priority' => [],
        'assigned_user_name' => [],
        'date_entered' => [],
        'date_modified' => [],
        'created_by_name' => [],
        'modified_by_name' => [],
        'am_projecttemplates_project_1_name' => [],
        'override_business_hours' => [],
    ],
];

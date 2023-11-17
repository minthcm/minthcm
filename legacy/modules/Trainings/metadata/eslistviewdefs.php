<?php

$module_name = 'Trainings';
$ESListViewDefs['Trainings'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'status' => [
            'default' => true,
        ],
        'date_start' => [
            'default' => true,
        ],
        'date_end' => [
            'default' => true,
        ],
        'training_type' => [
            'default' => true,
        ],
        'date_modified' => [
        ],
        'date_entered' => [
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'parent_name' => [
            'link' => true,
            'default' => true,
        ],
    ],
    'search' => [
        'name' => [],
        'date_start' => [],
        'date_end' => [],
        'status' => [],
        'training_type' => [],
        'parent_name' => [],
        'assigned_user_name' => [],
        'date_entered' => [],
        'date_modified' => [],
        'created_by_name' => [],
        'modified_by_name' => [],
    ],
];

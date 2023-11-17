<?php

$module_name = 'Appraisals';
$ESListViewDefs['Appraisals'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'status' => [
            'default' => true,
        ],
        'type' => [
            'default' => true,
        ],
        'date' => [
            'default' => true,
        ],
        'employee_name' => [
            'link' => true,
            'default' => true,
        ],
        'evaluator_name' => [
            'link' => true,
            'default' => true,
        ],
        'date_modified' => [
        ],
        'date_entered' => [
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'candidature_name' => [
            'default' => true,
        ],
        'position_name' => [
            'default' => true,
        ],
        'created_by_name' => [
            'link' => true,
        ],
        'modified_by_name' => [
            'link' => true,
        ],
    ],
    'search' => [
        'name' => [],
        'date' => [],
        'type' => [],
        'status' => [],
        'candidature_name' => [],
        'position_name' => [],
        'employee_name' => [],
        'evaluator_name' => [],
        'assigned_user_name' => [],
        'date_entered' => [],
        'date_modified' => [],
        'created_by_name' => [],
        'modified_by_name' => [],
    ],
];

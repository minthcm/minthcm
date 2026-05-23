<?php

$module_name = 'CompetencyRatings';
$ESListViewDefs['CompetencyRatings'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'rating' => [
            'default' => true,
        ],
        'competency_name' => [
            'link' => true,
            'default' => true,
        ],
        'parent_name' => [
            'link' => true,
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'employee_name' => [
            'default' => true,
        ],
        'date_entered' => [
        ],
        'date_modified' => [
        ],
        'modified_by_name' => [
            'link' => true,
        ],
        'created_by_name' => [
            'link' => true,
        ],
    ],
    'search' => [
        'name' => [
        ],
        'rating' => [
        ],
        'competency_name' => [
        ],
        'parent_name' => [
        ],
        'assigned_user_id' => [
        ],
        'employee_id' => [
        ],
    ],
];

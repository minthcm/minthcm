<?php

$module_name = 'PeriodsOfEmployment';
$ESListViewDefs['PeriodsOfEmployment'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'period_starting_date' => [
            'default' => true,
        ],
        'period_ending_date' => [
            'default' => true,
        ],
        'date_modified' => [
            'default' => true,
        ],
        'date_entered' => [
        ],
        'created_by_name' => [
            'link' => true,
        ],
        'modified_by_name' => [
            'link' => true,
        ],
        'employee_name' => [
            'default' => true,
        ],
    ],
    'search' => [
        'name' => [],
        'period_starting_date' => [],
        'period_ending_date' => [],
        'employee_name' => [],
        'assigned_user_name' => [],
        'date_entered' => [],
        'date_modified' => [],
        'created_by_name' => [],
        'modified_by_name' => [],
    ],
];

<?php

$module_name = 'Kudos';
$ESListViewDefs[$module_name] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
            'label' => 'LBL_AUTHOR'
        ],
        'employee_name' => [
            'default' => true,
        ],
        'date_entered' => [
            'default' => true,
        ],
        'private' => [],
        'date_modified' => [],
    ],
    'search' => [
        'name' => [],
        'employee_name' => [],
        'assigned_user_name' => [],
        'date_entered' => [],
        'date_modified' => [],
    ],
];

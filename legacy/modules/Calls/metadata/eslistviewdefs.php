<?php

if (!defined('sugarEntry') || !sugarEntry) {
    exit('Not A Valid Entry Point');
}

$module_name = 'Calls';
$ESListViewDefs[$module_name] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'status' => [],
        'direction' => [
            'default' => true,
        ],
        'date_start' => [
            'default' => true,
        ],
        'duration_minutes' => [
            'default' => true,
        ],
        'parent_name' => [
            'link' => true,
            'default' => true,
        ],
        'assigned_user_name' => [
            'link' => true,
            'default' => true,
        ],
        'date_entered' => [
            'default' => true,
        ],
        'date_modified' => [],
    ],
    'search' => [
        'name' => [],
        'status' => [],
        'date_entered' => [],
        'date_modified' => [],
        'date_start' => [],
        'direction' => [],
        'duration_hours' => [],
        'duration_minutes' => [],
        'parent_name' => [],
        'assigned_user_name' => [],
    ],
];


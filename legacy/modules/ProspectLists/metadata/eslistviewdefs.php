<?php

$module_name = 'ProspectLists';
$ESListViewDefs['ProspectLists'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'list_type' => [
            'default' => true,
        ],
        'description' => [
            'default' => true,
        ],
        'report_name' => [
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'date_entered' => [
            'default' => true,
        ],
        'entry_count' => [
            'default' => false,
        ],
        'automatic_update' => [
            'default' => false,
        ],
    ],
    'search' => [
        'name' => [],
        'list_type' => [],
        'report_name' => [],
        'assigned_user_name' => [],
        'date_entered' => [],
        'entry_count' => [],
        'automatic_update' => [],
    ],
];

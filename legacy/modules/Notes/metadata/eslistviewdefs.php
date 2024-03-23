<?php

$module_name = 'Notes';
$ESListViewDefs['Notes'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'parent_name' => [
            'link' => true,
            'default' => true,
        ],
        'filename' => [
            'default' => true,
        ],
        'created_by_name' => [
            'default' => true,
        ],
        'date_modified' => [
        ],
        'date_entered' => [
            'default' => true,
        ],
    ],
    'search' => [
        'name' => [],
        'parent_name' => [],
        'filename' => [],
        'assigned_user_name' => [],
        'date_entered' => [],
        'date_modified' => [],
        'created_by_name' => [],
        'modified_by_name' => [],
    ],
];

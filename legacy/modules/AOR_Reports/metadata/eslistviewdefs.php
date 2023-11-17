<?php

$module_name = 'AOR_Reports';
$ESListViewDefs['AOR_Reports'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'report_module' => [
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'date_entered' => [
            'default' => true,
        ],
        'date_modified' => [
            'default' => true,
        ],
    ],
    'search' => [
        'name' => [],
        'report_module' => [],
        'assigned_user_name' => [],
        'date_entered' => [],
        'date_modified' => [],
        'created_by_name' => [],
        'modified_by_name' => [],
    ],
];

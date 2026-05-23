<?php

$module_name = 'AOW_Processed';
$ESListViewDefs['AOW_Processed'] = [
    'columns' => [
        'aow_workflow' => [
            'link' => true,
            'default' => true,
        ],
        'parent_type' => [
            'default' => true,
        ],
        'parent_name' => [
            'link' => true,
            'default' => true,
        ],
        'status' => [
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
        'aow_workflow' => [
        ],
        'parent_name' => [
        ],
        'status' => [
        ],
    ],
];

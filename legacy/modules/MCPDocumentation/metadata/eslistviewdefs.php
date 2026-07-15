<?php

$module_name = 'MCPDocumentation';
$ESListViewDefs['MCPDocumentation'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'category_name' => [
            'default' => true,
        ],
        'date_modified' => [
            'default' => true,
        ],
        'date_entered' => [
            'default' => false,
        ],
        'created_by_name' => [
            'default' => false,
        ],
        'modified_by_name' => [
            'default' => false,
        ],
    ],
    'search' => [
        'name' => [],
        'category_name' => [],
        'date_entered' => [],
        'date_modified' => [],
        'created_by_name' => [],
        'modified_by_name' => [],
    ],
];

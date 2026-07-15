<?php

$module_name = 'MCPDocCategories';
$ESListViewDefs['MCPDocCategories'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'description' => [ // FIXME: Nie dodawaj do list view opisu kategorii, bo może być długi
            'default' => true,
        ],
        'date_modified' => [
            'default' => false,
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
        'date_entered' => [],
        'date_modified' => [],
        'created_by_name' => [],
        'modified_by_name' => [],
    ],
];

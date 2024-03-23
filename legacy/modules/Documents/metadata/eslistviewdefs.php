<?php

$module_name = 'Documents';
$ESListViewDefs['Documents'] = [
    'columns' => [
        'document_name' => [
            'link' => true,
            'default' => true,
        ],
        'filename' => [
            'link' => true,
            'default' => true,
        ],
        'category_id' => [
            'default' => true,
        ],
        'subcategory_id' => [
            'default' => true,
        ],
        'last_rev_create_date' => [
            'default' => true,
        ],
        'exp_date' => [
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'modified_by_name' => [
        ],
        'date_entered' => [
            'default' => true,
        ],
    ],
    'search' => [
        'document_name' => [],
        'status' => [],
        'template_type' => [],
        'category_name' => [],
        'subcategory_name' => [],
        'active_date' => [],
        'exp_date' => [],
        'assigned_user_name' => [],
        'date_entered' => [],
        'date_modified' => [],
        'created_by_name' => [],
        'modified_by_name' => [],
    ],
];

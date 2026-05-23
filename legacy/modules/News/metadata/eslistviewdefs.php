<?php

$module_name = 'News';
$ESListViewDefs['News'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'news_type' => [
            'default' => true,
        ],
        'news_status' => [
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'publication_date' => [
            'default' => true,
        ],
        'date_modified' => [
        ],
        'date_entered' => [
        ],
        'created_by_name' => [
        ],
        'modified_by_name' => [
            'link' => true,
        ],
    ],
    'search' => [
        'name' => [],
        'news_type' => [],
        'news_status' => [],
        'publication_date' => [],
        'assigned_user_name' => [],
        'date_entered' => [],
        'date_modified' => [],
        'created_by_name' => [],
        'modified_by_name' => [],
    ],
];

<?php

$module_name = 'AOK_KnowledgeBase';
$ESListViewDefs['AOK_KnowledgeBase'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'status' => [
            'default' => true,
        ],
        'author' => [
            'link' => true,
            'default' => true,
        ],
        'approver' => [
            'link' => true,
            'default' => true,
        ],
        'revision' => [
            'default' => true,
        ],
        'created_by_name' => [
            'link' => true,
            'default' => true,
        ],
        'modified_by_name' => [
            'link' => true,
            'default' => true,
        ],
        'date_entered' => [
            'default' => true,
        ],
        'date_modified' => [
            'default' => true,
        ],
        'assigned_user_name' => [
            'link' => true,
        ],
    ],
    'search' => [
        'name' => [],
        'status' => [],
        'author' => [],
        'approver' => [],
        'revision' => [],
        'date_entered' => [],
        'date_modified' => [],
        'assigned_user_name' => [],
        'created_by_name' => [],
        'modified_by_name' => [],
    ],
];


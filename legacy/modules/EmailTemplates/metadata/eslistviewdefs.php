<?php

$module_name = 'EmailTemplates';
$ESListViewDefs['EmailTemplates'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'type' => [
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'date_modified' => [
            'default' => true,
        ],
        'date_entered' => [
            'default' => true,
        ],
    ],
    'search' => [
        'name' => [],
        'type' => [],
        'subject' => [],
        'assigned_user_name' => [],
        'date_entered' => [],
        'date_modified' => [],
        'created_by_name' => [],
        'modified_by_name' => [],
    ],
];

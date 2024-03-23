<?php

$module_name = 'OffboardingTemplates';
$ESListViewDefs['OffboardingTemplates'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'position_name' => [
            'link' => true,
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'date_modified' => [
        ],
        'date_entered' => [
        ],
        'created_by_name' => [
            'link' => true,
        ],
        'modified_by_name' => [
            'link' => true,
        ],
    ],
    'search' => [
        'name' => [],
        'position_name' => [],
        'assigned_user_name' => [],
        'date_entered' => [],
        'date_modified' => [],
        'created_by_name' => [],
        'modified_by_name' => [],
    ],
];

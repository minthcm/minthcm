<?php

$module_name = 'AIPromptTemplates';
$ESListViewDefs[$module_name] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
            'label' => 'LBL_ASSIGNED_USER'
        ],
        'root_module' => [
            'default' => true,
        ],
        'date_entered' => [
            'default' => true,
        ],
        'date_modified' => [],
    ],
    'search' => [
        'name' => [],
        'assigned_user_name' => [],
        'date_entered' => [],
        'date_modified' => [],
        'root_module' => [],
    ],
];

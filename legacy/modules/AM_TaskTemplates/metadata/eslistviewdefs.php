<?php

$module_name = 'AM_TaskTemplates';
$ESListViewDefs['AM_TaskTemplates'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'status' => [
            'default' => true,
        ],
        'priority' => [
            'default' => true,
        ],
        'milestone_flag' => [
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'am_tasktemplates_am_projecttemplates_name' => [
            'link' => true,
            'default' => true,
        ],
    ],
    'search' => [
        'name' => [
        ],
        'status' => [
        ],
        'priority' => [
        ],
        'am_tasktemplates_am_projecttemplates_name' => [
        ],
        'assigned_user_id' => [
        ],
    ],
];

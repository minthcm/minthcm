<?php

$module_name = 'OAuth2Clients';
$ESListViewDefs['OAuth2Clients'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'allowed_grant_type' => [
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
    ],
    'search' => [
        'name' => [
        ],
        'allowed_grant_type' => [
        ],
    ],
];

<?php

$module_name = 'OAuth2Tokens';
$ESListViewDefs['OAuth2Tokens'] = [
    'columns' => [
        'id' => [
            'link' => true,
            'default' => true,
        ],
        'oauth2client_name' => [
            'link' => true,
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'token_is_revoked' => [
            'default' => true,
        ],
        'date_entered' => [
            'default' => true,
        ],
        'access_token_expires' => [
            'default' => true,
        ],
        'refresh_token_expires' => [
            'default' => true,
        ],
    ],
    'search' => [
        'id' => [
        ],
        'oauth2client_name' => [
        ],
        'assigned_user_name' => [
        ],
        'token_is_revoked' => [
        ],
        'active_only' => [
        ],
        'grant_type' => [
        ],
    ],
];

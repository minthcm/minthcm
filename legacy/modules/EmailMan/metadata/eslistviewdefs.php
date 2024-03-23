<?php

$module_name = 'EmailMan';
$ESListViewDefs['EmailMan'] = [
    'columns' => [
        'campaign_name' => [
            'link' => true,
            'default' => true,
        ],
        'recipient_name' => [
            'default' => true,
        ],
        'recipient_email' => [
            'default' => true,
        ],
        'message_name' => [
            'default' => true,
        ],
        'send_date_time' => [
            'default' => true,
        ],
        'send_attempts' => [
            'default' => true,
        ],
        'in_queue' => [
            'default' => true,
        ],
    ],
    'search' => [
        'campaign_name' => [
        ],
        'to_name' => [
        ],
        'to_email' => [
        ],
        'current_user_only' => [
        ],
    ],
];

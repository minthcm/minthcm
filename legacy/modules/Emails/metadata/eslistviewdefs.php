<?php

$module_name = 'Emails';
$ESListViewDefs['Emails'] = [
    'columns' => [
        'from_addr_name' => [
            'default' => true,
        ],
        'indicator' => [
            'default' => true,
        ],
        'subject' => [
            'default' => true,
        ],
        'has_attachment' => [
        ],
        'assigned_user_name' => [
        ],
        'date_entered' => [
            'default' => true,
        ],
        'date_sent_received' => [
            'default' => true,
        ],
        'to_addrs_names' => [
        ],
        'category_id' => [
            'default' => true,
        ],
    ],
    'search' => [
        'name' => [],
        'imap_keywords' => [],
        'from_addr_name' => [],
        'to_addrs_names' => [],
        'category_name' => [],
        'parent_name' => [],
        'assigned_user_name' => [],
        'date_entered' => [],
        'date_modified' => [],
        'created_by_name' => [],
        'modified_by_name' => [],
    ],
];

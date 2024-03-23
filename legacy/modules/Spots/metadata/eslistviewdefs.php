<?php

$module_name = 'Spots';
$ESListViewDefs['Spots'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'type' => [
            'default' => true,
        ],
        'date_entered' => [
            'default' => true,
        ],
        'created_by_name' => [
            'default' => true,
        ],
    ],
    'search' => [
        'assigned_user_id' => [
        ],
    ],
];

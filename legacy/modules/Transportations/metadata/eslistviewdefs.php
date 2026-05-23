<?php

$module_name = 'Transportations';
$ESListViewDefs['Transportations'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'delegation_name' => [
            'default' => true,
        ],
        'from_city' => [
            'default' => true,
        ],
        'to_city' => [
            'default' => true,
        ],
        'trans_date' => [
            'default' => true,
        ],
        'type' => [
            'default' => true,
        ],
        'date_entered' => [
        ],
        'other_transportation' => [
        ],
        'date_modified' => [
        ],
    ],
    'search' => [
        'from_city' => [
        ],
        'to_city' => [
        ],
        'trans_date' => [
        ],
        'type' => [
        ],
        'other_transportation' => [
        ],
        'assigned_user_id' => [
        ],
    ],
];

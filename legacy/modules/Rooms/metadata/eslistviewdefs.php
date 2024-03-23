<?php

$module_name = 'Rooms';
$ESListViewDefs['Rooms'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'reservation_type' => [
            'default' => true,
        ],
        'number_of_seats' => [
            'default' => true,
        ],
        'security_group_name' => [
            'link' => true,
            'default' => true,
        ],
        'availability' => [
            'default' => true,
        ],
        'assigned_user_name' => [
            'link' => true,
            'default' => true,
        ],
        'resource_name' => [
            'link' => true,
        ],
        'created_by_name' => [
            'link' => true,
        ],
        'modified_by_name' => [
            'link' => true,
        ],
        'room_surface' => [
        ],
        'date_modified' => [
        ],
        'date_entered' => [
        ],
    ],
    'search' => [
        'name' => [],
        'reservation_type' => [],
        'number_of_seats',
        'availability' => [],
        'room_surface' => [],
        'resource_name' => [],
        'assigned_user_name' => [],
        'security_group_name' => [],
        'date_modified' => [],
        'date_entered' => [],
        'created_by_name' => [],
        'modified_by_name' => [],
    ],
];

<?php

$module_name = 'Campaigns';
$ESListViewDefs['Campaigns'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'status' => [
            'default' => true,
        ],
        'campaign_type' => [
            'default' => true,
        ],
        'end_date' => [
            'default' => true,
        ],
        'date_entered' => [
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'track_campaign' => [
            'link' => true,
            'default' => true,
        ],
    ],
    'search' => [
        'name' => [],
        'start_date' => [],
        'end_date' => [],
        'status' => [],
        'campaign_type' => [],
        'assigned_user_name' => [],
        'date_entered' => [],
        'date_modified' => [],
        'created_by_name' => [],
        'modified_by_name' => [],
    ],
];

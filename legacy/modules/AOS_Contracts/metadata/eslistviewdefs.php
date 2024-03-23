<?php

$module_name = 'AOS_Contracts';
$ESListViewDefs['AOS_Contracts'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'status' => [
            'default' => true,
        ],
        'assigned_user_name' => [
            'link' => true,
            'default' => true,
        ],
        'contract_account' => [
            'link' => true,
            'default' => true,
        ],
        'total_contract_value' => [
            'default' => true,
        ],
        'start_date' => [
            'default' => true,
        ],
        'end_date' => [
            'default' => true,
        ],
        'date_entered' => [
        ],
    ],
    'search' => [
        'name' => [
        ],
        'contract_account' => [
        ],
        'opportunity' => [
        ],
        'start_date' => [
        ],
        'end_date' => [
        ],
        'total_contract_value' => [
        ],
        'status' => [
        ],
        'contract_type' => [
        ],
        'assigned_user_id' => [
        ],
    ],
];

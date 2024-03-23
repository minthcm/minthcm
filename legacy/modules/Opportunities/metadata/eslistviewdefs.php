<?php

$module_name = 'Opportunities';
$ESListViewDefs['Opportunities'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'account_name' => [
            'link' => true,
            'default' => true,
        ],
        'sales_stage' => [
            'default' => true,
        ],
        'amount_usdollar' => [
            'default' => true,
        ],
        'opportunity_type' => [
        ],
        'lead_source' => [
        ],
        'next_step' => [
        ],
        'probability' => [
        ],
        'date_closed' => [
            'default' => true,
        ],
        'created_by_name' => [
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'modified_by_name' => [
        ],
        'date_entered' => [
            'default' => true,
        ],
    ],
    'search' => [
        'name' => [
        ],
        'account_name' => [
        ],
        'amount' => [
        ],
        'assigned_user_id' => [
        ],
        'sales_stage' => [
        ],
        'lead_source' => [
        ],
        'date_closed' => [
        ],
        'next_step' => [
        ],
    ],
];

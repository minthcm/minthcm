<?php

$module_name = 'AOS_Invoices';
$ESListViewDefs['AOS_Invoices'] = [
    'columns' => [
        'number' => [
            'default' => true,
        ],
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'status' => [
            'default' => true,
        ],
        'billing_contact' => [
            'link' => true,
            'default' => true,
        ],
        'billing_account' => [
            'link' => true,
            'default' => true,
        ],
        'total_amount' => [
            'default' => true,
        ],
        'due_date' => [
            'default' => true,
        ],
        'assigned_user_name' => [
            'link' => true,
            'default' => true,
        ],
        'annual_revenue' => [
        ],
        'billing_address_street' => [
        ],
        'billing_address_city' => [
        ],
        'billing_address_state' => [
        ],
        'billing_address_postalcode' => [
        ],
        'billing_address_country' => [
        ],
        'shipping_address_street' => [
        ],
        'shipping_address_city' => [
        ],
        'shipping_address_state' => [
        ],
        'shipping_address_postalcode' => [
        ],
        'shipping_address_country' => [
        ],
        'phone_alternate' => [
        ],
        'website' => [
        ],
        'ownership' => [
        ],
        'employees' => [
        ],
        'ticker_symbol' => [
        ],
        'date_entered' => [
        ],
    ],
    'search' => [
        'name' => [
        ],
        'billing_contact' => [
        ],
        'billing_account' => [
        ],
        'number' => [
        ],
        'total_amount' => [
        ],
        'due_date' => [
        ],
        'status' => [
        ],
        'assigned_user_id' => [
        ],
    ],
];

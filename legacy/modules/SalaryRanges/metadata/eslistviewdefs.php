<?php

$module_name = 'SalaryRanges';
$ESListViewDefs['SalaryRanges'] = [
    'columns' => [
        'name' => [
            'default' => true,
        ],
        'position_name' => [
            'default' => true,
        ],
        'start_date' => [
            'default' => true,
        ],
        'end_date' => [
            'default' => true,
        ],
        'gross_value_from' => [
            'default' => true,
            'related_fields' => [
                'currency_id',
            ],
            'currency_format' => true,
        ],
        'gross_value_to' => [
            'default' => true,
            'related_fields' => [
                'currency_id',
            ],
            'currency_format' => true,
        ],
        'net_value_from' => [
            'related_fields' => [
                'currency_id',
            ],
            'currency_format' => true,
        ],
        'net_value_to' => [
            'related_fields' => [
                'currency_id',
            ],
            'currency_format' => true,
        ],
        'employer_costs_from' => [
            'related_fields' => [
                'currency_id',
            ],
            'currency_format' => true,
        ],
        'employer_costs_to' => [
            'related_fields' => [
                'currency_id',
            ],
            'currency_format' => true,
        ],
        'date_modified' => [
        ],
        'modified_by_name' => [
        ],
        'created_by_name' => [
        ],
        'date_entered' => [
        ],
    ],
    'search' => [
        'name' => [],
        'position_name' => [],
        'start_date' => [],
        'end_date' => [],
        'gross_value_from' => [],
        'gross_value_to' => [],
        'net_value_from' => [],
        'net_value_to' => [],
        'employer_costs_from' => [],
        'employer_costs_to' => [],
        'assigned_user_name' => [],
        'date_entered' => [],
        'date_modified' => [],
        'created_by_name' => [],
        'modified_by_name' => [],
    ],
];

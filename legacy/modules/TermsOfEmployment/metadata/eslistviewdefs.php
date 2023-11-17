<?php

$module_name = 'TermsOfEmployment';
$ESListViewDefs['TermsOfEmployment'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'contract_name' => [
            'default' => true,
        ],
        'position_name' => [
        ],
        'term_starting_date' => [
            'default' => true,
        ],
        'term_ending_date' => [
            'default' => true,
        ],
        'date_of_signing' => [
            'default' => true,
        ],
        'gross' => [
            'default' => true,
        ],
        'net' => [
            'default' => true,
        ],
        'employer_cost' => [
            'default' => true,
        ],
        'employee_name' => [
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'created_by_name' => [
            'link' => true,
        ],
        'modified_by_name' => [
            'link' => true,
        ],
        'date_modified' => [
        ],
        'date_entered' => [
        ],
    ],
    'search' => [
        'name' => [],
        'contract_name' => [],
        'date_of_signing' => [],
        'term_starting_date' => [],
        'term_ending_date' => [],
        'gross' => [],
        'net' => [],
        'employer_cost' => [],
        'contracted_employee' => [],
        'employee_name' => [],
        'assigned_user_name' => [],
        'date_entered' => [],
        'date_modified' => [],
        'created_by_name' => [],
        'modified_by_name' => [],
    ],
];

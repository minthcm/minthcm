<?php

$module_name = 'Employees';
$acl_module_name = 'Users';
$ESListViewDefs['Employees'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        'position_name' => [
            'link' => true,
            'default' => true,
        ],
        'securitygroup_name' => [
            'link' => true,
            'default' => true,
        ],
        'reports_to_name' => [
            'link' => true,
            'default' => true,
        ],
        'email1' => [
            'link' => true,
            'default' => true,
        ],
        'phone_work' => [
            'link' => true,
            'default' => true,
        ],
        'employee_status' => [
            'default' => true,
        ],
        'primary_address_street' => [
        ],
        'primary_address_city' => [
        ],
        'primary_address_state' => [
        ],
        'primary_address_country' => [
        ],
        'birthdate' => [
        ],
        'date_entered' => [
        ],
        'birthdate' => [
        ],
    ],
    'search' => [
        'first_name' => [
        ],
        'last_name' => [
        ],
        'employee_status' => [
        ],
        'position_name' => [
        ],
        'securitygroup_name' => [
        ],
        'email' => [
        ],
        'birthdate' => [
        ],
        'primary_address_street' => [
        ],
        'primary_address_city' => [
        ],
        'primary_address_state' => [
        ],
        'primary_address_postalcode' => [
        ],
        'primary_address_country' => [
        ],
        'birthdate' => [
        ],
    ],
];

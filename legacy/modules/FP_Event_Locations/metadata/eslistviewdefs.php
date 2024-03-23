<?php

$module_name = 'FP_Event_Locations';
$ESListViewDefs['FP_Event_Locations'] = [
    'columns' => [
        'name' => [
            'link' => true,
            'default' => true,
        ],
        /*
         TODO: rozwiązać problem z legacy/lib/Utility/ArrayMapper.php
        'address' => [],
        'address_city' => [],
        'address_country' => [],
        'address_postalcode' => [],
        'address_state' => [],
        */
        'capacity' => [
            'default' => true,
        ],
        'assigned_user_name' => [
            'default' => true,
        ],
        'date_entered' => [
            'default' => true,
        ],
        'date_modified' => [],
        'created_by_name' => [],
        'modified_by_name' => [],
    ],
    'search' => [
        'name' => [],
        'capacity' => [],
        /*
         TODO: rozwiązać problem z legacy/lib/Utility/ArrayMapper.php
        'address' => [],
        'address_city' => [],
        'address_country' => [],
        'address_postalcode' => [],
        'address_state' => [],
        */
        'assigned_user_name' => [],
        'date_entered' => [],
        'date_modified' => [],
        'created_by_name' => [],
        'modified_by_name' => [],
    ],
];

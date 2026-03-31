<?php

if (!defined('sugarEntry') || !sugarEntry) {
    exit('Not A Valid Entry Point');
}

$module_name = 'Meetings';
$ESListViewDefs[$module_name] = [
    'columns' => [
        'NAME' => [
            'width' => '25%',
            'label' => 'LBL_LIST_SUBJECT',
            'link' => true,
            'default' => true,
        ],
        'STATUS' => [
            'type' => 'enum',
            'width' => '10%',
            'label' => 'LBL_LIST_STATUS',
            'link' => false,
            'default' => false,
        ],
        'TYPE' => [
            'width' => '10%',
            'label' => 'LBL_TYPE',
            'link' => false,
            'default' => false,
        ],
        'CONTACT_NAME' => [
            'width' => '15%',
            'label' => 'LBL_LIST_CONTACT',
            'link' => true,
            'id' => 'CONTACT_ID',
            'module' => 'Contacts',
            'default' => true,
            'ACLTag' => 'CONTACT',
        ],
        'parent_name' => [
            'link' => true,
            'default' => true,
            'ACLTag' => 'PARENT',
        ],
        'DATE_START' => [
            'width' => '10%',
            'label' => 'LBL_LIST_DATE',
            'link' => false,
            'default' => true,
            'related_fields' => [
                'time_start',
            ],
        ],
        'DATE_END' => [
            'default' => false,
        ],
        'DURATION' => [
            'width' => '15%',
            'label' => 'LBL_DURATION',
            'default' => false,
            'type' => 'varchar',
        ],
        'ASSIGNED_USER_NAME' => [
            'width' => '10%',
            'label' => 'LBL_LIST_ASSIGNED_TO_NAME',
            'module' => 'Employees',
            'id' => 'ASSIGNED_USER_ID',
            'default' => true,
            'link' => true,
        ],
        'DATE_ENTERED' => [
            'width' => '10%',
            'label' => 'LBL_DATE_ENTERED',
            'default' => true,
        ],
        'date_modified' => [],
        'location' => [
            'default' => false
        ]
    ],
    'search' => [
        'name' => [],
        'status' => [],
        'type' => [],
        'date_entered' => [],
        'date_modified' => [],
        'date_start' => [],
        'date_end' => [],
        'duration' => [],
        'parent_name' => [],
        'assigned_user_name' => [],
        'location' => [],
    ],
    'defaultSort' => [
        'field' => 'date_start',
        'order' => 'DESC'
    ],
];

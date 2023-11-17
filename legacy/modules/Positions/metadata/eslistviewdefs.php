<?php

if (!defined('sugarEntry') || !sugarEntry) {
    exit('Not A Valid Entry Point');
}

$module_name = 'Positions';
$ESListViewDefs[$module_name] = [
    'columns' => [
        'NAME' => [
            'name' => 'name',
            'label' => 'LBL_NAME',
            'default' => true,
            'enabled' => true,
            'link' => true,
        ],
        'STATUS' => [
            'label' => 'LBL_STATUS',
            'default' => true,
        ],
        'SECURITYGROUP_LEADER_NAME' => [
            'name' => 'securitygroup_leader_name',
            'label' => 'LBL_SECURITYGROUPS_LEADER_NAME',
            'id' => 'SECURITYGROUPS_LEADER_ID',
            'enabled' => true,
            'default' => true,
            'link' => true,
        ],
        'OFFBOARDINGTEMPLATE_NAME' => [
            'name' => 'offboardingtemplate_name',
            'label' => 'LBL_OFFBOARDINGTEMPLATE_NAME',
            'enabled' => true,
            'default' => false,
        ],
        'ONBOARDINGTEMPLATE_NAME' => [
            'name' => 'onboardingtemplate_name',
            'label' => 'LBL_ONBOARDINGTEMPLATE_NAME',
            'enabled' => true,
            'default' => false,
        ],
        'POSITIONS_SUPERVISION_NAME' => [
            'name' => 'positions_supervision_name',
            'label' => 'LBL_POSITIONS_SUPERVISION_NAME',
            'enabled' => true,
            'default' => true,
            'link' => true,
        ],
        'ASSIGNED_USER_NAME' => [
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO_NAME',
            'default' => true,
            'enabled' => true,
            'link' => true,
        ],
        'DATE_MODIFIED' => [
            'label' => 'LBL_DATE_MODIFIED',
            'enabled' => true,
            'default' => true,
            'name' => 'date_modified',
            'readonly' => true,
        ],
    ],
    'search' => [
        'name' => [],
        'status' => [],
        'securitygroup_leader_name' => [],
        'offboardingtemplate_name' => [],
        'onboardingtemplate_name' => [],
        'positions_supervision_name' => [],
        'assigned_user_name' => [],
        'date_entered' => [],
        'date_modified' => [],
        'created_by_name' => [],
        'modified_by_name' => [],
    ]
];

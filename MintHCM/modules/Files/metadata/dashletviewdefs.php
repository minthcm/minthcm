<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $current_user;

$dashletData['FilesDashlet']['searchFields'] = array(
    'date_entered' => array(
        'default' => ''
    ),
    'date_modified' => array(
        'default' => ''
    ),
    'assigned_user_id' => array(
        'type' => 'assigned_user_name',
        'default' => $current_user->name
    ),
);
$dashletData['FilesDashlet']['columns'] = array(
    'document_name' => array(
        'width' => '40',
        'label' => 'LBL_NAME',
        'link' => true,
        'default' => true,
    ),
    'date_entered' => array(
        'width' => '15',
        'label' => 'LBL_DATE_ENTERED',
        'default' => false,
    ),
    'date_modified' => array(
        'width' => '15',
        'label' => 'LBL_DATE_MODIFIED',
        'default' => false,
    ),
    'created_by_name' => array(
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_CREATED',
        'id' => 'CREATED_BY',
        'width' => '10%',
        'default' => false,
    ),
    'parent_name' => array(
        'width' => '25',
        'label' => 'LBL_PARENT_NAME',
        'sortable' => false,
        'dynamic_module' => 'PARENT_TYPE',
        'id' => 'PARENT_ID',
        'link' => true,
        'ACLTag' => 'PARENT',
        'related_fields' => array( 'parent_id', 'parent_type' ),
        'default' => true,
     ), 
    'modified_by_name' => array(
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_MODIFIED_NAME',
        'id' => 'MODIFIED_USER_ID',
        'width' => '10%',
        'default' => false,
    ),
    'assigned_user_name' => array(
        'width' => '8',
        'label' => 'LBL_LIST_ASSIGNED_USER',
        'default' => true,
    ),
);

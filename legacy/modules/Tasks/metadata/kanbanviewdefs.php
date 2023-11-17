<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $app_list_strings;

$module_name = 'Tasks';
$kanbanViewDefs[$module_name] = array(
    'columns_field' => 'status',
    'order_field' => '',
    'columns' => $app_list_strings['task_status_dom'],
    'black_list' => array(
        'Completed'
    ),
    'expired_excluded_columns' => array(),
    'allow_create' => ACLController::checkAccess('Tasks', 'edit', true),
    //In case you want to block the dragging of item from one column or another, make change in 'actions'
    'actions' => array(
        // 'Not Started' => array(
        //     'In Progress',
        //     'Pending Input',
        //     'Deferred',
        // ),
        // 'In Progress' => array(
        //     'Not Started',
        //     'Pending Input',
        //     'Deferred',
        // ),
        // 'Pending Input' => array(
        //     'Not Started',
        //     'In Progress',
        //     'Deferred',
        // ),
        // 'Deferred' => array(
        //     'Not Started',
        //     'In Progress',
        //     'Pending Input',
        // ),
    ),
    'required_fields' => array(
        'name'
    ),
);

<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $app_list_strings;

function extractSublist($listDef, $sublistIdentifiers) {
    $result = [];
    foreach ($sublistIdentifiers as $identifier) {
        $result[$identifier] = $listDef[$identifier];
    }
    return $result;
}

$module_name = 'Candidatures';
$kanbanViewDefs[$module_name] = array(
    'columns_field' => 'status',
    'order_field' => '',
    'columns' => extractSublist($app_list_strings['status_list'], [
        // Displayed Kanban columns
        'New',
        'InProgress',
        'EntryInterview',
        'AfterEntryInterview',
        'PracticalTask',
        'Scored',
        'MeetingPrimary',
        'Scored2',
        'MeetingAdditional',
        'Offer',
    ]),
    'black_list' => array(
        'CandidateResignation',
        'Hired',
        'Rejected',
        'Acceptance',
    ),
    'expired_excluded_columns' => array(),
    'allow_create' => ACLController::checkAccess('Candidatures', 'edit', true),
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
        'name',
    ),
);

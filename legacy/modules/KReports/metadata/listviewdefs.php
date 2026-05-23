<?php
/* * *******************************************************************************
* This file is part of KReporter. KReporter is an enhancement developed
* by aac services k.s.. All rights are (c) 2016 by aac services k.s.
*
* This Version of the KReporter is licensed software and may only be used in
* alignment with the License Agreement received with this Software.
* This Software is copyrighted and may not be further distributed without
* witten consent of aac services k.s.
*
* You can contact us at info@kreporter.org
******************************************************************************* */




if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$listViewDefs['KReports'] = array(
    'NAME' => array(
        'width' => '40',
        'label' => 'LBL_LIST_SUBJECT',
        'link' => true,
        'default' => true),
    'REPORT_MODULE' => array(
        'width' => '10',
        'label' => 'LBL_LIST_MODULE',
        'link' => false,
        'default' => true),
    'LISTTYPE' => array(
        'width' => '10',
        'label' => 'LBL_LIST_LISTTYPE',
        'link' => false,
        'default' => true),
    'CHART_LAYOUT' => array(
        'width' => '10',
        'label' => 'LBL_LIST_CHART_LAYOUT',
        'link' => false,
        'default' => true),
    'DESCRIPTION' => array(
        'width' => '30',
        'label' => 'LBL_DESCRIPTION',
        'link' => false,
        'default' => true),    
    'DATE_ENTERED' => array(
        'width' => '10',
        'label' => 'LBL_LIST_DATEENTERED',
        'link' => false,
        'default' => true),
    'DATE_MODIFIED' => array(
        'width' => '10',
        'label' => 'LBL_LIST_DATEMODIFIED',
        'link' => false,
        'default' => true),
    'ASSIGNED_USER_NAME' => array(
        'width' => '10',
        'label' => 'LBL_LIST_ASSIGNED_USER_NAME',
        'link' => false,
        'default' => true),
);


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

$subpanel_layout = array(
    'top_buttons' => array(
        array(
            'widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'KReports'
        ),
    ),
    'list_fields' => array(
        'name' => array(
            'vname' => 'LBL_NAME',
            'width' => '30%',
        ),
        'report_module' => array(
            'vname' => 'LBL_MODULE',
            'width' => '15%',
        ),
        'report_status' => array(
            'vname' => 'LBL_REPORT_STATUS',
            'width' => '15%',
        ),
        'remove_button' => array(
            'vname' => 'LBL_REMOVE',
            'widget_class' => 'SubPanelRemoveButton',
            'width' => '2%',
        ),
    ),
);


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

$pluginmetadata = array(
    'id' => 'googlecharts',
    'displayname' => 'LBL_GOOGLECHARTS',
    'type' => 'visualization',
    'visualization' => array(
        'include' => 'kGoogleCharts.php',
        'class' => 'kGoogleChart'
    ),
    'pluginpanel' => 'SpiceCRM.KReporter.Designer.visualizationplugins.googlechartspanel',
    'viewpanel' => 'SpiceCRM.KReporter.Viewer.visualizationplugins.googlechartsviz',
    'includes' => array(
        'edit' => 'googlechartspanel.js',
        'view' => 'googlechartsviz.js'
    )
);

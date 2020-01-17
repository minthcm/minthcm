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

$customFunctionInclude = 'modules/KReports/KReportCustomFunctions.php';

$kreportEmailTemplate = 'dd457b9e-ec56-70c7-5b74-4d9c6d0938b6';
$kreportEmailSubject = 'K Reporter';
$kreportEmailBody = 'This is your scheduled Report';

// exculde Modules from Selection in Reports
$excludedModules = array('Schedulers', 'Administration');


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

function additionalDetailsKReport($fields) {
    static $mod_strings;
    global $app_list_strings;
    if (empty($mod_strings)) {
        global $current_language;
        $mod_strings = return_module_language($current_language, 'KReports');
    }

    $overlib_string = '';

    if (!empty($fields['DESCRIPTION'])) {
        $overlib_string .= html_entity_decode($fields['DESCRIPTION']);
        if (strlen($fields['DESCRIPTION']) > 300)
            $overlib_string .= '...';
    }
    else
        $overlib_string .= 'no Description maintained';

    $editLink = "index.php?action=EditView&module=KReports&record={$fields['ID']}";
    $viewLink = "index.php?action=DetailView&module=Reports&record={$fields['ID']}";

    $return_module = empty($_REQUEST['module']) ? 'KReports' : $_REQUEST['module'];
    $return_action = empty($_REQUEST['action']) ? 'ListView' : $_REQUEST['action'];

    $editLink .= "&return_module=$return_module&return_action=$return_action";
    $viewLink .= "&return_module=$return_module&return_action=$return_action";

    return array('fieldToAddTo' => 'NAME',
        'string' => $overlib_string,
        'editLink' => $editLink,
        'viewLink' => $viewLink);
}



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


class pluginktargetlistexportcontroller {

    public   function action_export_to_targetlist($requestdata) {

        $thisReport = BeanFactory::getBean('KReports', $requestdata['record']);

        // check if we have set dynamic Options
        if (isset($_REQUEST['whereConditions']))
            $thisReport->whereOverride = json_decode(html_entity_decode($requestdata['whereConditions']), true);

        $thisReport->createTargeList($requestdata['targetlist_name']);

        return true;
    }
    
}
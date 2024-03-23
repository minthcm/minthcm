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




if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

require_once('modules/KReports/KReport.php');

class KReportPresentationManager {

    // centrally keep the pluginmanager
    var $pluginManager;

    public function __construct() {

        $this->pluginManager = new KReportPluginManager();
    }
    
    // function to get the Plugin Object
    public function getPresentationPlugin($thisReport){
       $listOptions = json_decode(html_entity_decode($thisReport->presentation_params), true);
        if(!empty($listOptions['plugin']))
            $pluginObject = $this->pluginManager->getPresentationObject($listOptions['plugin']);
        else
            $pluginObject = $this->pluginManager->getPresentationObject($thisReport->listtype);
        return $pluginObject;
    }
    
    public function renderPresentation($thisReport) {
        $listOptions = json_decode(html_entity_decode($thisReport->presentation_params), true);
        if(!empty($listOptions['plugin']))
            $pluginObject = $this->pluginManager->getPresentationObject($listOptions['plugin']);
        else
            $pluginObject = $this->pluginManager->getPresentationObject($thisReport->listtype);
        return $pluginObject->display($thisReport);
    }
    
    public function getPresentationExport($thisReport, $dynamicols, $renderFields = true){
        $listOptions = json_decode(html_entity_decode($thisReport->presentation_params), true);
        if(!empty($listOptions['plugin']))
            $pluginObject = $this->pluginManager->getPresentationObject($listOptions['plugin']);
        else
            $pluginObject = $this->pluginManager->getPresentationObject($thisReport->listtype);
        return $pluginObject->getExportData($thisReport, $dynamicols, $renderFields);
    }
    
    public function getPresentationMetadata($thisReport) {
        $listOptions = json_decode(html_entity_decode($thisReport->presentation_params), true);
        if(!empty($listOptions['plugin']))
            $pluginObject = $this->pluginManager->getPresentationObject($listOptions['plugin']);
        else
            $pluginObject = $this->pluginManager->getPresentationObject($thisReport->listtype);
        return $pluginObject->getPresentationMetaData($thisReport);
    }
}
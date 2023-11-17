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


/*
 * prototype class for a visualization item 
 * all visualization items should be based on this class and then extend the methods herein. 
 * KReporter expects these functions to be available
 */

class kreportvisualizationplugin {
    /*
     * generic function to return any additonal heade rinfos or JS files that shouldbe included
     * if a vis objkect of this type is rendered. This is only processed once per this type of plugin
     * and should be used to pass back files that are generic for this type of plugin (e.g. google js 
     * libraries for the Goolge Chart
     */
    public function getHeader() {
        return '';
    }
    
    /*
     * generic function to return any additonal heade rinfos or JS files that shouldbe included
     * if a vis objkect of this type is rendered. This is only processed once per this type of plugin
     * and should be used to pass back files that are generic for this type of plugin (e.g. google js 
     * libraries for the Goolge Chart
     */
    public function getAddVizDiv($thisDivId) {
        return '';
    }
     /*
     * return data that your visulaization object can parse the visualizationmanager on the frontend will 
     * call the update function on your vis obejct and then pass along the data you pass along here. You can 
     * return whatever you want ... you are responsible to parse it afterwards. Data is passed between here 
     * and the Frontend base64 encoded so all kind of data can be transferred
     * 
     * Paramaters: 
     * $thisReport: the report object
     * $thisParams: the Vis Obnject params extracted from the vis manager
     * $snapshotid: the id of the snapshot that has been selected (required to be passed to the report if you 
     *              want to select this properly in your visualization
     * $addReportParams: Additional Report Params passed to the Query
     */
    public function getItemUpdate($thisReport, $thisParams, $snaphotid = 0, $addReportParams = array()) {
        return '';
    }
    /*
     * return any data that will be put into the container rendered by the vis manager. This is processed
     * for each vis item and thus should contain the data for each item. also make sure that this declares
     * a proper instance since the plugin might be rendere two times (e.g. two charts)
     * 
     * Paramaters: 
     * $thisDivId: the id of the dic in the container this gets rendered into
     * $thisReport: the report object
     * $thisParams: the Vis Obnject params extracted from the vis manager
     * $addReportParams: Additional Report Params passed to the Query
     */
    public function getItem($thisDivId, $thisReport, $thisParams, $addReportParams = array()) {
        return '';
    }
    
    /*
     * public function that gets handed the data the item returned from the export function (if it has one)
     * the processes this to be ina  regular format (SVG, IMG) and return either false if no processing was possible or the 
     * plugin does not support expüorting or an aray with two items: 'data' with the data and 'type' with the type
     * 
     * Types are 'SVG', 'IMG'
     */
    public function parseExportData($exportData){
        return false;
    }
}


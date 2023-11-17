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



require_once('modules/KReports/KReport.php');
require_once('modules/KReports/KReportChartData.php');
require_once('modules/KReports/Plugins/prototypes/kreportvisualizationplugin.php');

class kGoogleChart extends kreportvisualizationplugin {

    //2013-03-05 ... required for the rrenderer of numbers
    var $report = null;

    public function __construct() {
        
    }



    /*
     * get the Chart Object to render into the visualization
     */

    public function getItem($thisDivId, $thisReport, $thisParams, $addReportParams = array(), $snapshotid = 0) {
        $this->report = $thisReport;
        $googleData = $this->getChartData($thisReport, $thisParams, $snapshotid, $addReportParams);
        $chartData = $this->wrapGoogleData($googleData, $thisDivId, $thisParams);

        return $chartData;
    }

    public function getChartData($thisReport, $thisParams, $snaphotid = 0, $addReportParams = array()) {
                
        $chartDataObj = new KReportChartData();
        $fields = json_decode(html_entity_decode($thisReport->listfields, ENT_QUOTES, 'UTF-8'), true);

        // check for all the fieldids we have
        $fieldMap = array();
        foreach ($fields as $thisFieldIndex => $thisFieldData) {
            $fieldMap[$thisFieldData['fieldid']] = $thisFieldIndex;
        }

        //$dimensions = array(array('fieldid' => $fields[0]['fieldid']));
        $dimensions = array();
        foreach ($thisParams['dimensions'] as $thisDimension => $thisDimensionData) {
            if ($thisDimensionData != null)
                $dimensions[] = array('fieldid' => $thisDimensionData);
        }

        //$dataseries = array($fields[1]['fieldid'], $fields[2]['fieldid']);
        $dataseries = array();
        foreach ($thisParams['dataseries'] as $thisDataSeries => $thisDataSeriesData) {
            $dataseries[$thisDataSeriesData['fieldid']] = array(
                'fieldid' => $thisDataSeriesData['fieldid'],
                'name' => $fields[$fieldMap[$thisDataSeriesData['fieldid']]]['name'],
                // 2013-03-19 handle Chart Function properly Bug #448
                // also added axis and renderer
                'axis' => $thisDataSeriesData['axis'],
                'chartfunction' => $thisDataSeriesData['chartfunction'],
                'renderer' => $thisDataSeriesData['renderer']
            );
        }

        // set Chart Params
        $chartParams = array();
        $chartParams['type'] = $thisParams['type']; //needed in KReportChartData for unset dimension1
        $chartParams['showEmptyValues'] = ($thisParams['options']['emptyvalues'] == 'on' ? true : false);
        if ($thisParams['context'] != '')
            $chartParams['context'] = $thisParams['context'];

        //get data
        $rawData = $chartDataObj->getChartData($thisReport, $snaphotid, $chartParams, $dimensions, $dataseries, $addReportParams);

        //convert for display
        $convertFunction = $this->getConvertFunctionName($thisParams['type']);
        return $this->$convertFunction($rawData['chartData'], $rawData['dimensions'], $rawData['dataseries']);
    }
    
    /**
     * retrieve name of function transforming data for chartWrapper
     * @param unknown_type $charttype
     * @return Ambigous <string, unknown>
     */
    public function getConvertFunctionName($charttype){
        $fn = "convertRawToGoogleData";
        return $fn;
    }

    /*
     * helper function to mingle the data and prepare for a google represenatation
     */

    public function convertRawToGoogleData($chartData, $dimensions, $dataseries) {
        
        $googleData = array();
        $googleData['cols'] = array();
        $googleData['rows'] = array();

        foreach ($dimensions as $thisDimension) {
            $googleData['cols'][] = array('id' => $thisDimension['fieldid'], 'type' => 'string', 'label' => $thisDimension['fieldid']);
        }

        foreach ($dataseries as $thisDataseries) {
            $googleData['cols'][] = array('id' => $thisDataseries['fieldid'], 'type' => 'number', 'label' => ($thisDataseries['name'] != '' ? $thisDataseries['name'] : $thisDataseries['fieldid']));

            // 2013-03-05 check if we have a renderer
            $dataseries[$thisDataseries['fieldid']]['renderer'] = $this->report->getXtypeRenderer($this->report->fieldNameMap[$thisDataseries['fieldid']]['type'], $thisDataseries['fieldid']);
        }

        //2013-03-05 instantiate a renderer
        $kreportRenderer = new KReportRenderer();

        
        foreach ($chartData as $thisDimensionId => $thisData) {
            $rowArray = array();
            
            $rowArray[] = array('v' => $dimensions[0]['values'][$thisDimensionId]);
            
            foreach ($dataseries as $thisDataseries) {
                //2013-03-05 check if we should render
                if (!empty($thisDataseries['renderer']))
                    $rowArray[] = array('x' => 'guidValue', 'v' => $thisData[$thisDataseries['fieldid']], 'f' => $kreportRenderer->{$thisDataseries['renderer']}($thisDataseries['fieldid'], $thisData));
                else
                    $rowArray[] = array('v' => $thisData[$thisDataseries['fieldid']]);
            }
            $googleData['rows'][] = array('c' => $rowArray);
        }
        
        return $googleData;
    }
    
    
    

    /*
     * function to wrap the code with the google visualization API options etc.
     */

    public function wrapGoogleData($googleData, $divId, $thisParams) {

        $gvizclass = $thisParams['type'] . 'Chart';
        
        
        // else continue processing ..
        $googleChart = array(
            'chartType' => $gvizclass,
            'containerId' => $divId,
            'options' => array(
                'legend' => none,
                'fontSize' => 11
            ),
            'dataTable' => $googleData
        );

        // switch for specific types
        switch($thisParams['type']){
            case 'Donut':
                $googleChart['chartType'] = 'PieChart';
                $googleChart['options']['pieHole'] =  '0.4';
                break;
            case 'Column':
                if($thisParams['options']['material'] == 'on')
                    $googleChart['chartType'] = 'Bar';
                break;
        }

        // handle options
        foreach ($thisParams['options'] as $thisOption => $thisOptionCount) {
            switch ($thisOption) {
                case 'is3D':
                    $googleChart['options']['is3D'] = true;
                    break;
                case 'legend':
                    $googleChart['options']['legend'] = array(
                        'position' => 'right'
                    );
                    break;
            }
        }

        // set the title if we have one
        if ($thisParams['title'] != '') {
            $googleChart['options']['title'] = $thisParams['title'];
            $googleChart['options'][titleTextStyle] = array(
                'fontSize' => 14
            );
        }

        //set the legend
        if ($thisParams['legend'] != '' && $thisParams['legend'] != '') {
            $googleChart['options']['legend'] = array(
                'position' => $thisParams['legend']
            );
        }

        // set axis max/min values
        if ($thisParams['minmax']['vmin'] != '') {
            $googleChart['options']['min'] = $thisParams['minmax']['vmin'];
        }
        if ($thisParams['minmax']['vmax'] != '') {
            $googleChart['options']['max'] = $thisParams['minmax']['vmax'];
        }
        if ($thisParams['minmax']['hmin'] != '')
            $googleChart['options']['hAxis']['minValue'] = $thisParams['minmax']['hmin'];
        if ($thisParams['minmax']['hmax'] != '')
            $googleChart['options']['hAxis']['maxValue'] = $thisParams['minmax']['hmax'];


        // handle the colors
        include('modules/KReports/config/KReportColors.php');
        if ($thisParams['colors'] != '' && isset($kreportColors[$thisParams['colors']])) {
            $googleChart['options']['colors'] = $kreportColors[$thisParams['colors']]['colors'];
        }

        // see if we have a special color for a series
        foreach ($thisParams['dataseries'] as $seriesId => $seriesData) {
            if ($seriesData['color'] != '')
                $googleChart['options']['colors'][$seriesId] = '#' . $seriesData['color'];
        }

        // send back the Chart as Array
        return $googleChart;
    }

    /*
     * google chart provides proper svg code .. so nothing to do but to base64 decode
     */

    function parseExportData($exportedData) {
        return array(
            'type' => 'SVG',
            'data' => urldecode(base64_decode($exportedData))
        );
    }

}
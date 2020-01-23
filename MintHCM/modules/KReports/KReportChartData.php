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

class KReportChartData {

   function __construct() {
      
   }

   /*
    * function getChartData
    * thisReport: report object
    * chartParams:
    *  - context: context for Selection
    *  - parentbean: if called from a parent bean ... as subpanel
    *  - showEmptyValues: also create records for empty value (e.g. enums)
    *  - emptyValuesFunction: function to generate empty values .. will be used for date charts ... e.g. month, days, ... to be defined
    * dimensions: array with fieldids for the dimension (max 3)
    * dataSeries: array with fieldids for which we produce data series
    */

   public function getChartData($thisReport, $snapshotid = '0', $chartParams = array(), $dimensions = array(), $dataSeries = array(), $addReportParams = array()) {

      // initialize the return Array
      $chartData = array();

      // set the basic Report Params
      $reportParams = $addReportParams;
      $reportParams['noFormat'] = true;
      $reportParams['noLinks'] = true;

      // set selection parameters
      if (isset($chartParams['context']) && $chartParams['context'] != '')
         $reportParams['context'] = $chartParams['context'];

      if (isset($chartParams['parentBean']) && $chartParams['parentBean'] != '')
         $reportParams['parentbean'] = $chartParams['parentBean'];

      // get the chart data
      $reportResults = $thisReport->getSelectionResults($reportParams, $snapshotid, false, '', array());

      // build the results array
      $dimCount = count($dimensions);

      // if we have no dimensions we have a set of dataseries and need to make dimensions values , one per dataseries
      // flipping the array around more or less
      if ($dimCount == 0) {
         // build an array with one dimensions where the values are the ids of the dataseries
         foreach ($dataSeries as $thisDataSeries)
            $dimensions[0]['values'][$thisDataSeries['fieldid']] = $thisReport->listFieldArrayById[$thisDataSeries['fieldid']]['name'];

         // set am ambigous fieldid
         $dimensions[0]['fieldid'] = 'multivalues';
      } else {
         $dim0Values = $thisReport->getEnumValues($dimensions[0]['fieldid']);

         if (is_array($dim0Values) && count($dim0Values) > 0 && $chartParams['showEmptyValues']) {
            $dimensions[0]['values'] = array();
            foreach ($dim0Values as $thisDimensionkey => $thisDimensionValue) {
               $chartData[$thisDimensionkey] = array();
               $dimensions[0]['values'][$thisDimensionkey] = $thisDimensionValue;
            }
         }
      }

      // 2013-03-19 handle Chart Function properly Bug #448
      // count the records per diemnsion
      $dimCountArray = array();

      // loop over the results
      foreach ($reportResults as $thisResultRecord) {
         // if we do not have the value
         if ($dimCount > 0) {
            if (!isset($dimensions[0]['values'][$thisResultRecord[$dimensions[0]['fieldid']]]))
               $dimensions[0]['values'][$thisResultRecord[$dimensions[0]['fieldid']]] = $thisResultRecord[$dimensions[0]['fieldid']];
         }

         switch ($dimCount) {
            case 0:
               //build an array where we match the dataseries as dimensions and then sum up on these records
               foreach ($dataSeries as $thisDataSeries) {
                  if (empty($chartData[$thisDataSeries['fieldid']]['value']))
                     $chartData[$thisDataSeries['fieldid']]['value'] = 0;
                  //2012-11-27 ... if the value is not numeric count .. otherwiese sum
                  if (is_numeric($thisResultRecord[$thisDataSeries['fieldid']]))
                  // 2013-03-19 handle Chart Function properly Bug #448
                     switch ($thisDataSeries['chartfunction']) {
                        case 'MAX':
                           if ($thisResultRecord[$thisDataSeries['fieldid']] > $chartData[$thisDataSeries['fieldid']]['value'])
                              $chartData[$thisDataSeries['fieldid']]['value'] = (float)$thisResultRecord[$thisDataSeries['fieldid']];
                           break;
                        case 'MIN':
                           if ($thisResultRecord[$thisDataSeries['fieldid']] < $chartData[$thisDataSeries['fieldid']]['value'] || $chartData[$thisDataSeries['fieldid']]['value'] == 0)
                              $chartData[$thisDataSeries['fieldid']]['value'] = (float)$thisResultRecord[$thisDataSeries['fieldid']];
                           break;
                        case 'COUNT':
                           $chartData[$thisDataSeries['fieldid']]['value']++;
                           break;
                        default:
                           $chartData[$thisDataSeries['fieldid']]['value'] += $thisResultRecord[$thisDataSeries['fieldid']];
                           break;
                     }

                  else
                     $chartData[$thisDataSeries['fieldid']]['value']++;
               }
               break;
            case 1:
               // 2013-03-19 handle Chart Function properly Bug #448
               // set the dimension Counter
               $dimCountArray[$thisResultRecord[$dimensions[0]['fieldid']]]++;

               // this is straight forwards
               foreach ($dataSeries as $thisDataSeries) {
                  if (empty($chartData[$thisResultRecord[$dimensions[0]['fieldid']]][$thisDataSeries['fieldid']]))
                     $chartData[$thisResultRecord[$dimensions[0]['fieldid']]][$thisDataSeries['fieldid']] = 0;
                  //2012-11-27 ... if the value is not numeric count .. otherwiese sum
                  if (is_numeric($thisResultRecord[$thisDataSeries['fieldid']]))
                  // 2013-03-19 handle Chart Function properly Bug #448
                     switch ($thisDataSeries['chartfunction']) {
                        case 'MAX':
                           if ($chartData[$thisResultRecord[$dimensions[0]['fieldid']]][$thisDataSeries['fieldid']] < $thisResultRecord[$thisDataSeries['fieldid']])
                              $chartData[$thisResultRecord[$dimensions[0]['fieldid']]][$thisDataSeries['fieldid']] = (float)$thisResultRecord[$thisDataSeries['fieldid']];
                           break;
                        case 'MIN':
                           if ($chartData[$thisResultRecord[$dimensions[0]['fieldid']]][$thisDataSeries['fieldid']] == 0 || $chartData[$thisResultRecord[$dimensions[0]['fieldid']]][$thisDataSeries['fieldid']] > $thisResultRecord[$thisDataSeries['fieldid']])
                              $chartData[$thisResultRecord[$dimensions[0]['fieldid']]][$thisDataSeries['fieldid']] = (float)$thisResultRecord[$thisDataSeries['fieldid']];
                           break;
                        case 'COUNT':
                           $chartData[$thisResultRecord[$dimensions[0]['fieldid']]][$thisDataSeries['fieldid']]++;
                           break;
                        default:
                           $chartData[$thisResultRecord[$dimensions[0]['fieldid']]][$thisDataSeries['fieldid']] += $thisResultRecord[$thisDataSeries['fieldid']];
                           break;
                     }

                  else
                     $chartData[$thisResultRecord[$dimensions[0]['fieldid']]][$thisDataSeries['fieldid']]++;
               }
               break;
            case 2:
               // 2013-03-19 handle Chart Function properly Bug #448
               // set the dimension Counter
               $dimCountArray[$thisResultRecord[$dimensions[0]['fieldid']]][$thisResultRecord[$dimensions[1]['fieldid']]]++;

               // see if we have the value for dimension 2
               $value = $thisResultRecord[$dimensions[1]['fieldid']];
               if (!isset($dimensions[1]['values'][$thisResultRecord[$dimensions[1]['fieldid']]]))
                  $dimensions[1]['values'][$thisResultRecord[$dimensions[1]['fieldid']]] = $dimensions[1]['values'][$thisResultRecord[$dimensions[1]['fieldid']]];

              //this is a scatter chart
              if(empty($dataSeries)){
                  //handle it like a COUNT
                  $isScatter = true;
                  $chartData[$thisResultRecord[$dimensions[0]['fieldid']]][$thisResultRecord[$dimensions[1]['fieldid']]]++;
              }
              else{ //end
                  // set the value
                   foreach ($dataSeries as $thisDataSeries)
                      if (is_numeric($thisResultRecord[$thisDataSeries['fieldid']]))
                      // 2013-03-19 handle Chart Function properly Bug #448
                         switch ($thisDataSeries['chartfunction']) {
                            case 'MAX':
                               if ($chartData[$thisResultRecord[$dimensions[0]['fieldid']]][$thisResultRecord[$dimensions[1]['fieldid']]] < $thisResultRecord[$thisDataSeries['fieldid']])
                                  $chartData[$thisResultRecord[$dimensions[0]['fieldid']]][$thisResultRecord[$dimensions[1]['fieldid']]] = (float)$thisResultRecord[$thisDataSeries['fieldid']];
                               break;
                            case 'MIN':
                               if ($chartData[$thisResultRecord[$dimensions[0]['fieldid']]][$thisResultRecord[$dimensions[1]['fieldid']]] == 0 || $chartData[$thisResultRecord[$dimensions[0]['fieldid']]][$thisResultRecord[$dimensions[1]['fieldid']]] < $thisResultRecord[$thisDataSeries['fieldid']])
                                  $chartData[$thisResultRecord[$dimensions[0]['fieldid']]][$thisResultRecord[$dimensions[1]['fieldid']]] = (float)$thisResultRecord[$thisDataSeries['fieldid']];
                               break;
                            case 'COUNT':
                               $chartData[$thisResultRecord[$dimensions[0]['fieldid']]][$thisResultRecord[$dimensions[1]['fieldid']]]++;
                               break;
                            default:
                               $chartData[$thisResultRecord[$dimensions[0]['fieldid']]][$thisResultRecord[$dimensions[1]['fieldid']]] += $thisResultRecord[$thisDataSeries['fieldid']];
                               break;
                         }
                      else
                         $chartData[$thisResultRecord[$dimensions[0]['fieldid']]][$thisResultRecord[$dimensions[1]['fieldid']]]++;
               }
                  
               break;
            case 3:
               // see if we have the value for dimension 2
               if (!isset($dimensions[1]['values'][$thisResultRecord[$dimensions[1]['fieldid']]]))
                  $dimensions[1]['values'][$thisResultRecord[$dimensions[1]['fieldid']]] = $dimensions[1]['values'][$thisResultRecord[$dimensions[1]['fieldid']]];
               // see if we have the value for dimension 3
               if (!isset($dimensions[2]['values'][$thisResultRecord[$dimensions[2]['fieldid']]]))
                  $dimensions[2]['values'][$thisResultRecord[$dimensions[2]['fieldid']]] = $dimensions[1]['values'][$thisResultRecord[$dimensions[2]['fieldid']]];
               
               // set the value
               foreach ($dataSeries as $thisDataSeries){
                  if (is_numeric($thisResultRecord[$thisDataSeries['fieldid']]))
                  // 2013-03-19 handle Chart Function properly Bug #448
                     switch ($thisDataSeries['chartfunction']) {
                        case 'MAX':
                           if ($chartData[$thisResultRecord[$dimensions[0]['fieldid']]][$thisResultRecord[$dimensions[1]['fieldid']]][$thisResultRecord[$dimensions[2]['fieldid']]] < $thisResultRecord[$thisDataSeries['fieldid']])
                              $chartData[$thisResultRecord[$dimensions[0]['fieldid']]][$thisResultRecord[$dimensions[1]['fieldid']]][$thisResultRecord[$dimensions[2]['fieldid']]] = (float)$thisResultRecord[$thisDataSeries['fieldid']];
                           break;
                        case 'MIN':
                           if ($chartData[$thisResultRecord[$dimensions[0]['fieldid']]][$thisResultRecord[$dimensions[1]['fieldid']]][$thisResultRecord[$dimensions[2]['fieldid']]] == 0 || $chartData[$thisResultRecord[$dimensions[0]['fieldid']]][$thisResultRecord[$dimensions[1]['fieldid']]][$thisResultRecord[$dimensions[2]['fieldid']]] < $thisResultRecord[$thisDataSeries['fieldid']])
                              $chartData[$thisResultRecord[$dimensions[0]['fieldid']]][$thisResultRecord[$dimensions[1]['fieldid']]][$thisResultRecord[$dimensions[2]['fieldid']]] = (float)$thisResultRecord[$thisDataSeries['fieldid']];
                           break;
                        case 'COUNT':
                           $chartData[$thisResultRecord[$dimensions[0]['fieldid']]][$thisResultRecord[$dimensions[1]['fieldid']]][$thisResultRecord[$dimensions[2]['fieldid']]]++;
                           break;
                        default:
                           $chartData[$thisResultRecord[$dimensions[0]['fieldid']]][$thisResultRecord[$dimensions[1]['fieldid']]][$thisResultRecord[$dimensions[2]['fieldid']]] += $thisResultRecord[$thisDataSeries['fieldid']];
                           break;
                     }
                  else
                     $chartData[$thisResultRecord[$dimensions[0]['fieldid']]][$thisResultRecord[$dimensions[1]['fieldid']]][$thisResultRecord[$dimensions[2]['fieldid']]]++;

               }
               break;
          }
      }
         
      // 2013-03-19 handle Chart Function properly Bug #448
      // an array for the cumulated sum
      $dimCumsumArray = array();
      $valueAxes = array();

      // Fill all missing values with 0
      switch ($dimCount) {
         // 2013-03-19 handle Chart Function properly Bug #448
         case 0:
            foreach ($dataSeries as $thisDataSeries) {
               // 2013-03-19 handle Chart Function properly Bug #448
               switch ($thisDataSeries['chartfunction']) {
                  case 'AVG':
                     $chartData[$thisDataSeries['fieldid']]['value'] = $chartData[$thisDataSeries['fieldid']]['value'] / count($reportResults);
                     break;
               }
            }
            break;
         case 1:
            foreach ($dimensions[0]['values'] as $thisKey => $thisValue) {
               foreach ($dataSeries as $thisDataSeries) {
                  if (!isset($chartData[$thisKey][$thisDataSeries['fieldid']]))
                     $chartData[$thisKey][$thisDataSeries['fieldid']] = 0;

                //catch valueAxes
//              if(!empty($thisDataSeries['axis'])){
                    //va will be common axis on the left
                    $valueAxisId = (!empty($thisDataSeries['axis']) ? $thisDataSeries['axis'] : "va"); 
                    if(!in_array($valueAxisId, array_keys($valueAxes))){
                        $valueAxes[$valueAxisId] = array(
                            'id' => $valueAxisId,
                            'axisThickness'=> 2,
                            'vaxisAlpha'=> 1,
                            'position'=> (count($valueAxes) <= 0 ? "left" : "right"),
                            'offset' => (count($valueAxes) <= 1 ? 0 : 30),
                            'title' => $thisDataSeries['axis']
                        );
                        if(!empty($thisDataSeries['color'])){
                             $valueAxes[$valueAxisId]['axisColor'] = $thisDataSeries['color'];
                        }

                    }
//              }
                  // 2013-03-19 handle Chart Function properly Bug #448
                  switch ($thisDataSeries['chartfunction']) {
                     case 'AVG':
                        $chartData[$thisKey][$thisDataSeries['fieldid']] = $chartData[$thisKey][$thisDataSeries['fieldid']] / $dimCountArray[$thisKey];
                        break;
                     case 'CUMSUM':
                        $dimCumsumArray[$thisDataSeries['fieldid']] += $chartData[$thisKey][$thisDataSeries['fieldid']];
                        $chartData[$thisKey][$thisDataSeries['fieldid']] = $dimCumsumArray[$thisDataSeries['fieldid']];
                        break;
                  }
               }
            }
            break;
         case 2:
            foreach ($dimensions[0]['values'] as $thisKey => $thisValue) {
               foreach ($dimensions[1]['values'] as $this1Key => $this1Value) {
                  if (!isset($chartData[$thisKey][$this1Key]))
                     $chartData[$thisKey][$this1Key] = 0;

                  // 2013-03-19 handle Chart Function properly Bug #448
                  switch ($thisDataSeries['chartfunction']) {
                     case 'AVG':
                        $chartData[$thisKey][$this1Key] = $chartData[$thisKey][$this1Key] / $dimCountArray[$thisKey][$this1Key];
                        break;
                     case 'CUMSUM':
                        $dimCumsumArray[$this1Key] += $chartData[$thisKey][$this1Key];
                        $chartData[$thisKey][$this1Key] = $dimCumsumArray[$this1Key];
                        break;
                  }
               }
            }
            break;
         case 3:
            foreach ($dimensions[0]['values'] as $thisKey => $thisValue) {
               foreach ($dimensions[1]['values'] as $this1Key => $this1Value) {
                  foreach ($dimensions[2]['values'] as $this2Key => $this2Value) {
                     foreach ($dataSeries as $thisDataSeries)
                        if (!isset($chartData[$thisKey][$this1Key][$this2Key]))
                           $chartData[$thisKey][$this1Key][$this2Key] = 0;
                  }
               }
            }
            break;
      }
      
      // if dimcount has changed we shift the dataseries
      if ($dimCount == 0) {
         // reset the Array
         $dataSeries = array();
         // add one DataSeries for the values
         $dataSeries['value'] = array(
             'fieldid' => 'value',
             'name' => 'value'
         );
      } elseif ($dimCount >= 2) {
         // 2014-03-31 change that proper fieldid is returned in n dimensional case
         // reset the Array
         $dataseriesFieldArray = reset($dataSeries);
         $dataSeriesFieldId = $dataseriesFieldArray['fieldid'];
         $dataSeries = array();
         // add one DataSeries for the values
         foreach ($dimensions[1]['values'] as $thisKey => $thisValue) {
            $dataSeries[$thisKey] = array(
                'fieldid' => $thisKey,
                'dimfieldid' => $dataSeriesFieldId,
                'name' => $thisValue
            );
         };
         // remove the dimension for the charts
         //if(!$isScatter) unset($dimensions[1]);
         $keepDimensionsForTypes = array('Scatter', 'Bubble', 'Sankey');
         if(!in_array($chartParams['type'], $keepDimensionsForTypes)) unset($dimensions[1]);
      }
      
      return array('dimensions' => $dimensions, 'dataseries' => $dataSeries, 'chartData' => $chartData, 'valueAxes' => $valueAxes);
   }

}
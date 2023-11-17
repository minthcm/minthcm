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

/* 
 * Upgrade from kreporter 3.1 to kreporter 4.x means the end of fusion charts support 
 * Following script will convert all fusions charts into highcharts
 * This script may be run before oder after the update
 * 
 * Call this script over URL
 * Sugar7.x: yourSitePath.com/#bwc/index.php?module=KReports&action=convertFusionchart2Highchart
 * Sugar6.x: yourSitePath.com/index.php?module=KReports&action=convertFusionchart2Highchart
 */

class KReporterFusionChartSupport {
    
    public $mapFusion2HighchartType = array(
        'Area2D' => 'area',
        'MSArea' => 'area',
        'MSColumn2D' => 'column',
        'MSColumn3D' => 'column',
        'Column2D' => 'column',
        'Column3D' => 'column',
        'MSLine' => 'line',
        'MSBar2D' => 'bar',
        'MSBar3D' => 'bar',
        'StackedArea2D' => 'area_stacked',
        'StackedBar2D' => 'bar_stacked',
        'StackedBar3D' => 'bar_stacked',
        'StackedColumn2D' => 'column_stacked',
        'StackedColumn3D' => 'column_stacked',
        'MSCombiDY2D' => 'column',
        'Marimekko' => 'area',
        'Bar2D' => 'bar',
        'Line' => 'line',
        'Pie2D' => 'pie',
        'Pie3D' => 'pie',
        'Doughnut2D' => 'pie_donut',
        'Doughnut3D' => 'pie_donut',
        'Pareto2D' => '', //No type found
        'Pareto3D' => '' //No type found
     
    );
            
    
    public function convertFusionCharts2HighCharts(){      
        //backup original kreports table
        $this->backupKReports();
        
        //get reports
        $res = $this->getKReportsWithFusionCharts();
        
        //create sql update
        $uQ = array();
        while($row = $GLOBALS['db']->fetchByAssoc($res)){
            $uQ[$row['id']] = "UPDATE kreports SET visualization_params = '".$this->mapFusion2Highchart($row['visualization_params'], $row['id'])."' WHERE id='".$row['id']."'";
        }
        
        //process updates
        $this->processSqls($uQ);
        
    }
    
    public function backupKReports(){
        $q = "SHOW TABLES LIKE 'kreports_backup'";
        if(!$res = $GLOBALS['db']->query($q))
            die('Could not check existence of kreports_backup table. DB error: '.$GLOBALS['db']->last_error);
        if($GLOBALS['db']->getRowCount($res) <= 0){
            if(!$GLOBALS['db']->query("CREATE TABLE kreports_backup LIKE kreports;"))
                die('Could not create kreports_backup table. DB error: '.$GLOBALS['db']->last_error);                
            if(!$GLOBALS['db']->query("INSERT kreports_backup SELECT * FROM kreports;"))
                die('Could not popoulate kreports_backup table. DB error: '.$GLOBALS['db']->last_error);
        }
    }
    
    
    public function getKReportsWithFusionCharts(){
        $uQ = array();
        $q = "SELECT id, visualization_params FROM kreports "
                . "WHERE visualization_params LIKE '%fusionchart%' AND "
                . "deleted=0";
        if(!$res = $GLOBALS['db']->query($q))
            die('DB error: '.$GLOBALS['db']->last_error);
       
        return $res;
    }
    
    public function mapChartTypeFusion2Highchart($fusiontype){
        
        return $this->mapFusion2HighchartType[$fusiontype];
        
    }
    public function mapFusion2Highchart($visualization_params, $kreport_id){
        ini_set('max_execution_time', 3600);
        $visObj = json_decode(html_entity_decode($visualization_params, ENT_QUOTES, 'UTF-8'));
//echo '<pre>'.print_r($visObj, true);
//echo '<pre>---------------------';        
        foreach($visObj as $layout => $charts){       
            if($visObj->plugin == 'fusioncharts') $visObj->plugin = 'highcharts';
            
//            echo ("<br>Layout: ".$layout. " = ".gettype($layout));
            if(is_object($charts->fusioncharts) && !empty($charts->fusioncharts->uid)){
// echo '<pre>F '.print_r($charts->fusioncharts, true);
				
				$originalFusionChartType = $charts->fusioncharts->type;
                $visObj->$layout->highcharts = $charts->fusioncharts;
				
// echo '<pre>fill H '.print_r($visObj->$layout->highcharts, true);
				
                $visObj->$layout->highcharts->type = $this->mapChartTypeFusion2Highchart($charts->fusioncharts->type);
                if($visObj->$layout->highcharts->type == "" && !empty($charts->fusioncharts->type)){
                    echo "<br />Could not map chart type ".json_encode($charts->fusioncharts). " in report with ID ".$kreport_id;
                }
                if(isset($visObj->$layout->plugin) && $visObj->$layout->plugin == 'fusioncharts') $visObj->$layout->plugin = 'highcharts';
                
				//clean up dataseries config
				if(isset($visObj->$layout->highcharts->dataseries)){
					foreach($visObj->$layout->highcharts->dataseries as $idx => $dataserie){						
						if(isset($dataserie->axis))
							unset($charts->fusioncharts->dataseries[$idx]->axis);
						
						if(isset($dataserie->renderer))
							unset($charts->fusioncharts->dataseries[$idx]->renderer);
						
					}
				}
				
				
				//convert options from charttype
				if(preg_match("/3D$/", $originalFusionChartType)){
					$visObj->$layout->highcharts->options->{'3d'} = "on";
				}
				
				//convert legend param from charttype
				if($visObj->$layout->highcharts->options->legend == "on"){
					$visObj->$layout->highcharts->legend = "bottom";
					unset($visObj->$layout->highcharts->options->legend);
				}
				
// echo '<pre>'.print_r($vis->$layout->highcharts, true);
// die();
				
                unset($visObj->$layout->fusioncharts);
                
            }elseif(is_object($charts->fusioncharts) && empty($charts->fusioncharts->uid)){
                unset($visObj->$layout); //clean up   //sometimes something like "{"0": "fusioncharts": {}} found in visualization_params             
            }
        }
//         echo '<pre>'.print_r($visObj, true);die();
        return str_replace("'", "\'", str_replace("\\", "\\\\", json_encode($visObj)));
    }
    
    
    public function processSqls($sqls){
        if(count($sqls) <= 0){
            die("No reports found to be updated.");
        }
        
        foreach($sqls as $id => $q){
            if(!$GLOBALS['db']->query($q))
                die('DB error: '.$GLOBALS['db']->last_error);
            else{
                echo "<div>Report with ID ".$id." was updated.<br />".$q."<br /></div>";
            }
        }        
    }
}


$cf = new KReporterFusionChartSupport();
$cf->convertFusionCharts2HighCharts();
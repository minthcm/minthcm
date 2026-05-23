<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class SugarWidgetSubPanelAcceptWorkScheduleButton extends SugarWidgetField
{
    public function displayList(&$layout_def)
    {
        global $app_strings;
        global $subpanel_item_count;
        $return_module = $_REQUEST['module'];
        $return_id = $_REQUEST['record'];
        $module_name = $layout_def['module'];
        $record_id = $layout_def['fields']['ID'];
        $unique_id = $layout_def['subpanel_id']."_accept_".$subpanel_item_count; //bug 51512

        $workSchedule = BeanFactory::getBean("WorkSchedules",$record_id);
        if(empty($workSchedule->id)){
            return '';
        }

        if(!$workSchedule->canBeAccepted()) {
            return '';
        }
        
        if ($layout_def['EditView']) {
            $html = "<a id=\"$unique_id\" onclick=\"acceptWorkSchedule('$record_id')\" >".$app_strings['LNK_ACCEPT']."</a>";
            return $html;
        } else {
            return '';
        }
    }
}

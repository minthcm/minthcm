<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/MVC/View/views/view.list.php';

class SecurityGroupsViewList extends ViewList
{

    public function preDisplay()
    {
        $squery = new StoreQuery();
        $squery->query = array('group_type' => array('standard', 'business_unit', 'department', 'team', 'other'), 'module' => 'SecurityGroups', 'searchFormTab' => 'advanced_search', 'query' => 'true', 'action' => 'index');
        $squery->SaveQuery('SecurityGroups');
        parent::preDisplay();
    }
}
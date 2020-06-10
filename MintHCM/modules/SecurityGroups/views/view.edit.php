<?php

if (!defined('sugarEntry') || !sugarEntry)
	die('Not A Valid Entry Point');

class SecurityGroupsViewEdit extends ViewEdit {

	public function __construct() {
		parent::__construct();
		$this->useForSubpanel = true;
		$this->useModuleQuickCreateTemplate = true;
	}


	function display() {

        global $app_list_strings;

        $list = $app_list_strings['group_type_list'];

        if($this->bean->group_type == 'private'){
            $list = ['private' => $list['private']];
        } else {
            $list = array_diff($list, ['private' => $list['private']]);
        }
        $app_list_strings['group_type_list'] =  $list;
        

		parent::display();

	}

}

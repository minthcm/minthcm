<?php

 if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $mod_strings, $app_strings, $sugar_config;
 
if(ACLController::checkAccess('Comments', 'edit', true)){
    $module_menu[]=array('index.php?module=Comments&action=EditView&return_module=Comments&return_action=DetailView', $mod_strings['LNK_NEW_RECORD'], 'Add', 'Comments');
}
if(ACLController::checkAccess('Comments', 'list', true)){
    $module_menu[]=array('index.php?module=Comments&action=index&return_module=Comments&return_action=DetailView', $mod_strings['LNK_LIST'],'View', 'Comments');
}

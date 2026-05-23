<?php

 if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $mod_strings, $app_strings, $sugar_config;
 
if(ACLController::checkAccess('Reactions', 'edit', true)){
    $module_menu[]=array('index.php?module=Reactions&action=EditView&return_module=Reactions&return_action=DetailView', $mod_strings['LNK_NEW_RECORD'], 'Add', 'Reactions');
}
if(ACLController::checkAccess('Reactions', 'list', true)){
    $module_menu[]=array('index.php?module=Reactions&action=index&return_module=Reactions&return_action=DetailView', $mod_strings['LNK_LIST'],'View', 'Reactions');
}

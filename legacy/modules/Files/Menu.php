<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $mod_strings, $app_strings, $sugar_config;

if (ACLController::checkAccess('Files', 'edit', true)) {
    $module_menu[] = array('index.php?module=Files&action=EditView&return_module=Files&return_action=DetailView', $mod_strings['LNK_NEW_RECORD'], 'Add', 'Files');
}
if (ACLController::checkAccess('Files', 'list', true)) {
    $module_menu[] = array('index.php?module=Files&action=index&return_module=Files&return_action=DetailView', $mod_strings['LNK_LIST'], 'View', 'Files');
}
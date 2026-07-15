<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $mod_strings, $app_strings;
if (ACLController::checkAccess('MCPDocCategories', 'edit', true)) {
    $module_menu[] = [
        'index.php?module=MCPDocCategories&action=EditView&return_module=MCPDocCategories&return_action=DetailView',
        $mod_strings['LNK_NEW_RECORD'],
        'Create',
    ];
}

if (ACLController::checkAccess('MCPDocCategories', 'list', true)) {
    $module_menu[] = [
        'index.php?module=MCPDocCategories&action=index&return_module=MCPDocCategories&return_action=DetailView',
        $mod_strings['LNK_LIST'],
        'List',
    ];
}

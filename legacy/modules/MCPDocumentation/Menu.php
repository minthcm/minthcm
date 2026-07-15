<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $mod_strings, $app_strings;
if (ACLController::checkAccess('MCPDocumentation', 'edit', true)) {
    $module_menu[] = [
        'index.php?module=MCPDocumentation&action=EditView&return_module=MCPDocumentation&return_action=DetailView',
        $mod_strings['LNK_NEW_RECORD'],
        'Create',
    ];
}

if (ACLController::checkAccess('MCPDocumentation', 'list', true)) {
    $module_menu[] = [
        'index.php?module=MCPDocumentation&action=index&return_module=MCPDocumentation&return_action=DetailView',
        $mod_strings['LNK_LIST'],
        'List',
    ];
}

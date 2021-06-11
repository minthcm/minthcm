<?php

require_once 'include/LastNextContacts/LastNextContacts.php';
require_once 'include/LastNextContacts/LastNextContactsPanelsEditor.php';
require_once 'modules/ModuleBuilder/parsers/ParserFactory.php';
require_once 'include/LastNextContacts/LastNextContactsConfig.php';

global $current_language;
$mod_strings = return_module_language($current_language, "Administration");


$conf_path = LastNextContactsConfig::getConfigPath();
include $conf_path;

$new_data_type = (isset($_REQUEST['type'])) ? $_REQUEST['type'] : 2;

$panel_editor = new LastNextContactsPanelsEditor();
foreach ($last_next_modules_panels as $module) {
    $panel_editor->addLastNextContactPanelForModule($module, $new_data_type);
}

if ($new_data_type == 1) {
    echo !empty($mod_strings['LBL_ADD_LAST_NEXT_CONTACT_TABS_SUCCESS']) ? $mod_strings['LBL_ADD_LAST_NEXT_CONTACT_TABS_SUCCESS'] : 'LBL_ADD_LAST_NEXT_CONTACT_TABS_SUCCESS';
} else {
    echo !empty($mod_strings['LBL_ADD_LAST_NEXT_CONTACT_PANEL_SUCCESS']) ? $mod_strings['LBL_ADD_LAST_NEXT_CONTACT_PANEL_SUCCESS'] : 'LBL_ADD_LAST_NEXT_CONTACT_PANEL_SUCCESS';
}


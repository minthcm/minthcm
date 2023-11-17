<?php
global $current_user;
if (!is_admin($current_user)) {
    echo "Error: Access denied.";
    return;
}

global $current_language;
$smarty = new Sugar_Smarty();
$smarty->assign('lang', return_module_language($current_language, 'Administration'));
echo $smarty->fetch('include/LastNextContacts/entrypoints/CalculateDLNC.tpl');

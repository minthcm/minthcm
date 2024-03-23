<?php

function smarty_function_get_module_icon_class($params, &$smarty)
{
    $lower_module_name = str_replace('_', '-', strtolower($params['module_name']));
    $theme             = SugarThemeRegistry::get('SuiteP');
    if (isset($theme->fa_module_icons[$lower_module_name])) {
        $class = "fas {$theme->fa_module_icons[$lower_module_name]}";
    } else {
        $class = "suitepicon suitepicon-module-{$lower_module_name}";
    }
    return $class;
}

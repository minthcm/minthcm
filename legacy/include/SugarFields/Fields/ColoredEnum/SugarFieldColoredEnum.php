<?php

require_once 'include/SugarFields/Fields/Enum/SugarFieldEnum.php';

class SugarFieldColoredEnum extends SugarFieldEnum
{
    public function getListViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex)
    {
        require 'include/ColoredEnum/ColoredEnumVariables.php';
        $style = 'white-space: nowrap;';
        if (
            !empty($vardef['options_colors'])
            && !empty($vardef['options_colors'][$parentFieldArray[strtoupper($vardef['name'])]])
        ) {
            $style .= $colored_enum_variables[$vardef['options_colors'][$parentFieldArray[strtoupper($vardef['name'])]]];
        } else {
            $style .= $colored_enum_variables['-default-'];
        }
        $this->ss->assign('style', $style);
        return parent::getListViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex);
    }
}

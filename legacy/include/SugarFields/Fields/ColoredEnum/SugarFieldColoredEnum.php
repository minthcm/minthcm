<?php

require_once 'include/SugarFields/Fields/Enum/SugarFieldEnum.php';

class SugarFieldColoredEnum extends SugarFieldEnum
{
    public function getListViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex)
    {
        global $app_list_strings;
        $style = '';
        if (
            !empty($vardef['options_colors'])
            && !empty($app_list_strings[$vardef['options_colors']][$parentFieldArray[strtoupper($vardef['name'])]])
        ) {
            $style = $app_list_strings[$vardef['options_colors']][$parentFieldArray[strtoupper($vardef['name'])]];
        }
        $this->ss->assign('style', $style);
        return parent::getListViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex);
    }
}

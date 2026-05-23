<?php


require_once('include/SugarFields/Fields/Base/SugarFieldBase.php');

class SugarFieldDLNCdatetimecombo extends SugarFieldBase
{
    function getListViewSmarty($parentFieldArray, $vardef, $displayParams, $col)
    {
        global $app_strings;
        $tabindex = 1;
        $parentFieldArray = $this->setupFieldArray($parentFieldArray, $vardef);
        $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex, false);

        $this->ss->left_delimiter = '{';
        $this->ss->right_delimiter = '}';
        $this->ss->assign('col', $vardef['name']);
        $this->ss->assign('APP', $app_strings);
        $this->ss->assign("has_access", $this->get_access());
        return $this->fetch($this->findTemplate('ListView'));
    }
    protected function get_access(){
        return  1;
    }
}

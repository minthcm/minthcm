<?php

class EmailMergeViewProcessor
{
    protected $bean;

    public function __construct(SugarBean $bean)
    {
        $this->bean = $bean;
    }

    public function getProcessedView()
    {
        require_once 'include/SugarEmailAddress/SugarEmailAddress.php';
        $sea = new SugarEmailAddress();
        return $sea->getEmailAddressWidgetEditView($this->bean->id, $this->bean->module_name, false, 'modules/MergeRecords/EmailAddressMergeWidget/EmailAddressMergeWidget.tpl', '0');
    }
}
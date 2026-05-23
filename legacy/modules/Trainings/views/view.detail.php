<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

#[\AllowDynamicProperties]
class TrainingsViewDetail extends ViewDetail
{
    public function preDisplay()
    {
        $this->setupButtonsVisibility();
        parent::preDisplay();
    }
    protected function setupButtonsVisibility()
    {
        $this->canShowCloseTrainingButton();
    }
    protected function isCorrectStatus(array $status_list)
    {
        return in_array($this->bean->status, $status_list);
    }
    protected function canShowCloseTrainingButton()
    {
        $this->ss->assign('CLOSE_TRAINING_SHOW', (!$this->isCorrectStatus(['held'])));
    }
}

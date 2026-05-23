<?php

class IdeasAvailableActionsManager
{
    public $available_actions;
    public $idea;
    public $current_user;

    const DISPLAY_ACTION_BUTTONS_STATUSES = ['new', 'in_progress'];

    public function __construct($idea)
    {
        global $current_user;
        $this->current_user = $current_user;
        $this->idea = $idea;
        $this->available_actions = [];
    }

    public function getAvailableActions()
    {
        $this->available_actions['accept'] = $this->getAcceptActionAvailability();
        $this->available_actions['reject'] = $this->getRejectActionAvailability();
        $this->available_actions['forward'] = $this->getForwardActionAvailability();
        return $this->available_actions;
    }

    protected function getAcceptActionAvailability()
    {
        return (
            in_array($this->idea->status, self::DISPLAY_ACTION_BUTTONS_STATUSES)
            && ($this->isSuperior() || $this->isDecisionMaker())
        );
    }

    protected function getRejectActionAvailability()
    {
        return (
            in_array($this->idea->status, self::DISPLAY_ACTION_BUTTONS_STATUSES)
            && ($this->isSuperior() || $this->isDecisionMaker())
        );
    }

    protected function getForwardActionAvailability()
    {
        return (
            (
                in_array($this->idea->status, self::DISPLAY_ACTION_BUTTONS_STATUSES)
                && ($this->isSuperior() || $this->isDecisionMaker())
            )
            || empty($this->idea->user_id)
        );
    }

    protected function isSuperior()
    {
        global $current_user;
        return in_array($this->current_user->id, $current_user->getSuperiors($current_user));
    }

    protected function isDecisionMaker()
    {
        return $this->current_user->id == $this->idea->user_id;
    }
}

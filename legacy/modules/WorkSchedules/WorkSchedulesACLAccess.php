<?php

class WorkSchedulesACLAccess
{
    protected $bean;
    protected $result;

    public function __construct($bean, $parent_result)
    {
        $this->bean = $bean;
        $this->result = $parent_result;
    }

    public function ACLAccess($view, $is_owner = 'not_set', $in_group = 'not_set')
    {

        switch (strtolower($view)) {
            case 'edit':
            case 'editview':
            case 'delete':
                if (!$this->canOpenClosed()) {
                    $result = false;
                    if ($this->canEditBecauseOfSupervisor()) {
                        $result = true;
                    }
                    $this->result = $result;
                }
                break;
            default:

                break;
        }
        return $this->result;
    }

    protected function canOpenClosed()
    {
        global $current_user;
        if ($this->bean->status == 'closed' && !$current_user->is_admin) {
            return false;
        } else {
            return true;
        }
    }

    protected function canEditBecauseOfSupervisor()
    {
        global $current_user;
        $user_has_access = $this->result;
        $user_controller = ControllerFactory::getController('Users');
        $subordinates = $user_controller::getIDOfSubordinates([$current_user->id]);
        $current_user_is_supervisor_of_the_record_owner = in_array($this->bean->assigned_user_id, $subordinates);

        $schedule_date = getDateTimeObject($this->bean->schedule_date);
        $schedule_date->setTime(0, 0, 0);
        $now = new DateTime();
        $now->setTime(0, 0, 0);
        $m = $now->format('m');
        $y = $now->format('Y');
        $now->setDate($y, $m, 1);
        if ($user_has_access && $current_user_is_supervisor_of_the_record_owner && $schedule_date
            >= $now) {
            return true;
        } else {
            return false;
        }
    }
}
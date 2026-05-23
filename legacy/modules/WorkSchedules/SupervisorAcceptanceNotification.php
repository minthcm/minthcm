<?php

SugarAutoLoader::requireWithCustom('include/Notifications/Notification.php');
use WorkSchedules;

class SupervisorAcceptanceNotification {
    public $user_id;
    public $work_schedule_bean;

    public function __construct(WorkSchedules $work_schedule_bean) {
        $this->work_schedule_bean = $work_schedule_bean;
        $this->user_id = $work_schedule_bean->assigned_user_id;
    }

    public function send() {
        $notification = new Notification();
        $notification->setRelatedBeanFromBean($this->work_schedule_bean);
        $notification->setAssignedUserId($this->user_id);
        $notification->disableUniqueValidation();
        $notification->setType("Workschedule accept");
        $notification->setDescription(sprintf(translate('LBL_WORKSCHEDULE_ACCEPTED_NOTIFICATION', 'WorkSchedules'), $this->work_schedule_bean->schedule_date));
        $notification->simpleAlert(true);
        $notification->WebPush(false, true);
    }
}
<?php
require_once("modules/WorkSchedules/SupervisorAcceptanceNotification.php");

    class LogicHooks
    {
        function after_save($bean, $event, $arguments)
        {
            if ($bean->fetched_row['supervisor_acceptance'] === 'wait' && $bean->supervisor_acceptance === 'accepted') {
                $notification = new SupervisorAcceptanceNotification($bean);
                $notification->send(); 
            }
        }
    }
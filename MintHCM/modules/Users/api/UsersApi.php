<?php

class UsersApi
{
    public function isUserAllowedToCreateCalendarEvents()
    {
        return ACLController::checkAccess('WorkSchedules', 'edit')
        || ACLController::checkAccess('Meetings', 'edit')
        || ACLController::checkAccess('Calls', 'edit');
    }
}

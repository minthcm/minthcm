<?php

class UsersApi
{
    public function isUserAllowedToCreateCalendarEvents()
    {
        return ACLController::checkAccess('WorkSchedules', 'edit')
        || ACLController::checkAccess('Meetings', 'edit')
        || ACLController::checkAccess('Calls', 'edit');
    }

    public function passwordValidationCheck($args)
    {
        $userBean = BeanFactory::getBean('Users');
        if (empty($args['password'])) {
            return false;
        }
        return [ 'message' => $userBean->passwordValidationCheck($args['password']) ];
    }

}
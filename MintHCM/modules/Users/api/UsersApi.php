<?php

class UsersApi
{
    public function isUserAllowedToCreateCalendarEvents()
    {
        return ACLController::checkAccess('WorkSchedules', 'edit')
        || ACLController::checkAccess('Meetings', 'edit')
        || ACLController::checkAccess('Calls', 'edit');
    }

    public function checkUserDuplicate($args)
    {
        if (empty($args['login'])) {
            return false;
        }
        $db = DBManagerFactory::getInstance();
        $login = $db->quote($args['login']);

        $userBean = BeanFactory::getBean('Users');
        $userDuplicate = $userBean->retrieve_by_string_fields(
            array(
                'user_name' => "$login",
            )
        );

        if (!empty($userDuplicate)) {
            return true;
        }
        return false;
    }

}

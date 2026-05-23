<?php

/**
 * Checks if current_user or typed user by ID is admin.
 * EOU:
 * "isUserAdmin( '' )" will give us "true" if current_user is admin
 * "isUserAdmin( 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxx' )" will give us "true" if user with ID: 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxx' is admin
 */
class VTExpression_isUserAdmin extends VTExpression
{

    public $availability = array('vt_calculated', 'vt_dependency', 'vt_required', 'vt_readonly', 'vt_duplicate', 'vt_validation', 'related');
    public $serversideFrontend = true;
    public $sqlBackendFormula = false;
    public $inputParams = array('user_id');

    public function backend($arguments = array())
    {
        global $current_user;
        $result = false;
        if (empty($arguments['user_id'])) {
            $result = $current_user->isAdmin();
        } else {
            $user = BeanFactory::getBean('Users', $arguments['user_id']);
            if ($user) {
                $result = $user->isAdmin();
            }
        }
        return $result;
    }

}

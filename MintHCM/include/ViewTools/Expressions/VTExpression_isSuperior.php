<?php

/**
 * Checks if current user is supperior for user from argument
 * EOU:
 * "isSuperior(\$assigned_user_id)"
 */
class VTExpression_isSuperior extends VTExpression
{

    public $availability = array('vt_calculated', 'vt_dependency', 'vt_validation');
    public $serversideFrontend = true;
    public $sqlBackendFormula = false;

    public function backend($arguments = array())
    {
        global $current_user;
        if ($current_user->isAdmin()) {
            return true;
        }
        $result = false;
        $sugar_controller = ControllerFactory::getController('Users');
        $user_ids = $sugar_controller::getIDOfSubordinates(array($current_user->id));
        $user_ids[] = $current_user->id;
        if (in_array($arguments[0], $user_ids)) {
            $result = true;
        }
        return $result;
    }

}

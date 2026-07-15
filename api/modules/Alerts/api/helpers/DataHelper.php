<?php

namespace MintHCM\Modules\Alerts\api\helpers;

use Alert;

class DataHelper
{
    public static function isAssignedUserCurrentUser(Alert $alert)
    {
        global $current_user;
        return $alert->assigned_user_id === $current_user->id;
    }
}

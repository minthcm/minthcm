<?php

class DashboardManagerApi
{

    public function validateUniqueRole($args)
    {
        global $db;
        $sql = "SELECT id FROM dashboardmanager WHERE deleted = 0 AND business_role = '{$args['business_role']}' AND id NOT LIKE '{$args['id']}' LIMIT 1";
        $duplicated_id = $db->getOne($sql);

        if ($duplicated_id) {
            return false;
        } else {
            return true;
        }
    }

}

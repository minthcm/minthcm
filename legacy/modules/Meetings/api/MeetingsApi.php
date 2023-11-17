<?php

class MeetingsApi
{

    public function getUsers($args)
    {
        global $db;
        $users = [];
        if (!empty($args['id'])) {
            $query = "SELECT DISTINCT u.id, CONCAT(u.first_name, ' ', u.last_name) full_name, u.phone_work, u.phone_mobile, ea.email_address email1 FROM securitygroups_users sg_u
        LEFT JOIN users u ON u.status='Active' AND u.deleted=0 AND u.id=sg_u.user_id
        LEFT JOIN email_addr_bean_rel eabr ON eabr.deleted=0 AND eabr.bean_id=u.id AND eabr.bean_module='Users'
        LEFT JOIN email_addresses ea ON ea.deleted=0 AND ea.id=eabr.email_address_id
        WHERE sg_u.deleted=0 AND sg_u.securitygroup_id = " . $db->quoted($args['id']) . " ORDER BY u.last_name";
            $result = $db->query($query);
            while ($row = $db->fetchByAssoc($result)) {
                $users[] = $row;
            }
        }
        return json_encode($users);
    }

}

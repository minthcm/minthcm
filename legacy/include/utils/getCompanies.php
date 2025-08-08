<?php
if (!function_exists('getCompanies')) {
    function getCompanies() {
        global $db, $current_user;

        $user_id = $current_user->id;

        $sql = "SELECT sg.id, sg.name 
                FROM securitygroups sg
                JOIN securitygroups_users sgu ON sg.id = sgu.securitygroup_id
                WHERE sgu.user_id = '{$user_id}' 
                AND sg.deleted = 0 
                AND sgu.deleted = 0 
                AND sg.group_type = 'company' 
                ORDER BY sg.name";

        $companies = [];
        $result = $db->query($sql);
        
        while ($row = $db->fetchByAssoc($result)) {
            $companies[$row['id']] = $row['name'];
        }
        if(count($companies)!=1){
            $companies = array('' => '') + $companies;
        }

        return $companies;
    }
}

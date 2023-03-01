<?php

class SecurityGroupsApi {
   
   public function checkSubordinatedGroups($params) {
        if (
            (in_array($params['parent_id'], $this->getSubordinatedGroups(Array($params['id']))) ||
            $params['id'] == $params['parent_id']) &&
            !empty($params['parent_id'])
        ) {
            return false;
        }
        else
        {
            return true;
        }
   }

   protected function getSubordinatedGroups(Array $group_ids)
   {
        global $db;
        $return = array();
        $sql = "SELECT id FROM securitygroups WHERE parent_id IN('" . implode("','", $group_ids) . "') AND deleted = 0";
        $r = $db->query($sql);
        while ( $a = $db->fetchByAssoc($r) ) {
        $return[] = $a['id'];
        }
        if ( !empty($return) ) {
        $return = array_merge($return, $this->getSubordinatedGroups($return));
        }
        return array_unique($return);
   }
   
}
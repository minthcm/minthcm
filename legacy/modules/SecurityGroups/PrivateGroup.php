<?php


/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * MintHCM is a Human Capital Management software based on SuiteCRM developed by MintHCM, 
 * Copyright (C) 2018-2023 MintHCM
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by SugarCRM" 
 * logo and "Supercharged by SuiteCRM" logo and "Reinvented by MintHCM" logo. 
 * If the display of the logos is not reasonably feasible for technical reasons, the 
 * Appropriate Legal Notices must display the words "Powered by SugarCRM" and 
 * "Supercharged by SuiteCRM" and "Reinvented by MintHCM".
 */

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once('modules/SecurityGroups/SecurityGroup.php');
require_once('modules/SecurityGroups/SecurityGroupUserRelationship.php');

class PrivateGroup extends SecurityGroup {

   protected $user;
   protected $security_group;
   protected $user_assigned_private_groups;

   const SECURITY_GROUPS_MODULE_NAME = "SecurityGroups";

   public function __construct($user = null) {
      $this->user = $user;
   }

   public function create() {
      $this->security_group = BeanFactory::newBean(self::SECURITY_GROUPS_MODULE_NAME);
      $this->security_group->name = $this->user->first_name . ' ' . $this->user->last_name . ' - Private Group';
      $this->security_group->group_type = 'private';
      $this->security_group->assigned_user_id = $this->user->id;
      $this->security_group->save();
      $this->addSupervisorToPrivateGroup($this->security_group->id, $this->user->id);
   }

   public function delete() {
      if ( $this->getUserPrivateGroup($this->user->id) ) {
         $this->security_group->mark_deleted($this->security_group->id);
      }
   }

   public function update($new_reports_to_id, $old_reports_to_id) {
      if ( $this->getUserPrivateGroup($this->user->id) ) {
         $this->getUserAssignedPrivateGroups($this->user->id);
         if ( !empty($old_reports_to_id) ) {
            $this->delSupervisorFromPrivateGroup($this->security_group->id, $old_reports_to_id);
            $this->delSupervisorFromUserAssignedPrivateGroups($old_reports_to_id);
         }
         if ( !empty($new_reports_to_id) ) {
            $this->addSupervisorToPrivateGroup($this->security_group->id, $new_reports_to_id);
            $this->addSupervisorToUserAssignedPrivateGroups($new_reports_to_id);
         }
      }
   }

   protected function addSupervisorToPrivateGroup($pg_id, $user_id) {
      $sgur = new SecurityGroupUserRelationship();
      $sgur->user_id = $user_id;
      $sgur->securitygroup_id = $pg_id;
      $sgur->save();
      $reports_to_id = User::getUserSupervisiorID($user_id);
      if ( $reports_to_id ) {
         $this->addSupervisorToPrivateGroup($pg_id, $reports_to_id);
      }
   }

   protected function delSupervisorFromPrivateGroup($pg_id, $user_id) {
      $sgur = new SecurityGroupUserRelationship();
      if ( $sgur->retrieve_by_string_fields(array( 'securitygroup_id' => $pg_id, 'user_id' => $user_id )) ) {
         $sgur->mark_deleted($sgur->id);
      }
      $reports_to_id = User::getUserSupervisiorID($user_id);
      if ( $reports_to_id ) {
         $this->delSupervisorFromPrivateGroup($pg_id, $reports_to_id);
      }
   }

   protected function getUserPrivateGroup($user_id) {
      global $db;
      $sql = "SELECT id FROM securitygroups WHERE group_type = 'private' AND assigned_user_id = '{$user_id}' AND deleted = 0";
      $result = $db->getOne($sql);
      if ( $result ) {
         $group = BeanFactory::getBean('SecurityGroups', $result);
         if ( $group && $group->id ) {
            $this->security_group = $group;
            return $group;
         }
      }
      return null;
   }

   protected function getUserAssignedPrivateGroups($id) {
      global $db;
      $this->user_assigned_private_groups = array();
      $sql = "SELECT sg.id FROM securitygroups_users sgu LEFT JOIN securitygroups sg ON (sg.id = sgu.securitygroup_id) ";
      $sql .= " WHERE sgu.user_id = '{$id}' ";
      $sql .= " AND sg.group_type='private' AND sg.assigned_user_id <> sgu.user_id AND sg.deleted = 0 AND sgu.deleted = 0 ";
      $result = $db->query($sql);
      while ( ($row = $db->fetchByAssoc($result)) != null ) {
         $this->user_assigned_private_groups[] = $row['id'];
      }
   }

   protected function delSupervisorFromUserAssignedPrivateGroups($user_id) {
      if ( $this->user_assigned_private_groups ) {
         foreach ( $this->user_assigned_private_groups as $pg_id ) {
            $this->delSupervisorFromPrivateGroup($pg_id, $user_id);
         }
      }
   }

   protected function addSupervisorToUserAssignedPrivateGroups($user_id) {
      if ( $this->user_assigned_private_groups ) {
         foreach ( $this->user_assigned_private_groups as $pg_id ) {
            $this->addSupervisorToPrivateGroup($pg_id, $user_id);
         }
      }
   }

}

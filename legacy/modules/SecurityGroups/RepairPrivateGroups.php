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

require_once('include/utils.php');
require_once('modules/SecurityGroups/PrivateGroup.php');
require_once('modules/SecurityGroups/SecurityGroupUserRelationship.php');
SugarAutoLoader::requireWithCustom('include/Notifications/Notification.php');

class RepairPrivateGroups {

   protected $groups_ids;
   protected $checked_users_ids;

   public function __construct() {
      $this->checked_users_ids = array();
   }

   public function repair() {
      $this->getAllPrivateGroupsIDs();
      $this->delUsellesPrivateGroups();
      foreach ( $this->groups_ids as $group_id ) {
         $this->repairSinglePrivateGroup($group_id);
      }
      $this->createMissingPrivateGroups();
      $this->addNotification();
   }

   protected function repairSinglePrivateGroup($group_id) {
      $old_members_ids = $this->getGroupMembersIDs($group_id);
      $new_members_ids = array();
      $owner_id = $this->getGroupOwnerID($group_id);
      array_push($this->checked_users_ids, $owner_id);
      $this->getGroupNewMembersIDs($new_members_ids, $owner_id);
      $this->delMembersFromGroup($group_id, array_diff($old_members_ids, $new_members_ids));
      $this->addMembersToGroup($group_id, array_diff($new_members_ids, $old_members_ids));
   }

   protected function getAllPrivateGroupsIDs() {
      global $db;
      $this->groups_ids = array();
      $sql = "SELECT id FROM securitygroups WHERE group_type = 'private' AND deleted = 0";
      $result = $db->query($sql);
      while ( ($row = $db->fetchByAssoc($result)) != null ) {
         $this->groups_ids[] = $row['id'];
      }
   }

   protected function getGroupOwnerID($id) {
      global $db;
      $sql = "SELECT assigned_user_id FROM securitygroups WHERE id = '{$id}' AND group_type = 'private' AND deleted = 0";
      return $db->getOne($sql);
   }

   protected function getGroupMembersIDs($id) {
      global $db;
      $old_members_ids = array();
      $sql = "SELECT user_id FROM securitygroups_users WHERE securitygroup_id = '{$id}' AND deleted = 0";
      $result = $db->query($sql);
      while ( ($row = $db->fetchByAssoc($result)) != null ) {
         $old_members_ids[] = $row['user_id'];
      }
      return $old_members_ids;
   }

   protected function getGroupNewMembersIDs(&$new_members_ids, $id) {
      array_push($new_members_ids, $id);
      $reports_to_id = User::getUserSupervisiorID($id);
      if ( $reports_to_id ) {
         $this->getGroupNewMembersIDs($new_members_ids, $reports_to_id);
      }
   }

   protected function delMembersFromGroup($group_id, $members_ids) {
      foreach ( $members_ids as $member_id ) {
         $sgur = new SecurityGroupUserRelationship();
         if ( $sgur->retrieve_by_string_fields(array('securitygroup_id' => $group_id, 'user_id' => $member_id)) ) {
            $sgur->mark_deleted($sgur->id);
         }
      }
   }

   protected function addMembersToGroup($group_id, $members_ids) {
      foreach ( $members_ids as $member_id ) {
         $sgur = new SecurityGroupUserRelationship();
         $sgur->user_id = $member_id;
         $sgur->securitygroup_id = $group_id;
         $sgur->save();
      }
   }

   protected function delUsellesPrivateGroups() {
      for ( $i = 0; $i < count($this->groups_ids); $i++ ) {
         $user = BeanFactory::getBean('Users');
         $owner_id = $this->getGroupOwnerID($this->groups_ids[$i]);
         if ( $owner_id && !$user->retrieve($owner_id) ) {
            $this->delPrivateGroup($this->groups_ids[$i]);
            unset($this->groups_ids[$i]);
         }
      }
   }

   protected function delPrivateGroup($group_id) {
      $group = BeanFactory::getBean('SecurityGroups', $group_id);
      if ( $group && $group->id ) {
         $group->mark_deleted($group->id);
      }
   }

   protected function createMissingPrivateGroups() {
      foreach ( array_diff($this->getAllUsersIDs(), $this->checked_users_ids) as $user_id ) {
         $user = BeanFactory::getBean('Users');
         if ( $user->retrieve($user_id) ) {
            $private_group = new PrivateGroup($user);
            $private_group->create();
         }
      }
   }

   protected function getAllUsersIDs() {
      global $db;
      $users_ids = array();
      $sql = "SELECT id FROM users WHERE id <> '1' AND deleted = 0";
      $result = $db->query($sql);
      while ( ($row = $db->fetchByAssoc($result)) != null ) {
         $users_ids[] = $row['id'];
      }
      return $users_ids;
   }

   protected function addNotification() {
      global $app_strings, $current_user;
      $notification = new Notification();
      $notification->setAssignedUserId($current_user->id);
      $notification->setDescription(translate($app_strings['LBL_REPAIR_PRIVATE_GROUPS_ALERT']));
      $notification->disableUniqueValidation();
      $notification->saveAsAlert();
   }

}

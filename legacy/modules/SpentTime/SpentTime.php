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

require_once 'include/DateFunctions/DateFormatter.php';
include_once 'include/ViewTools/Expressions/VTFormulaParser.php';
require_once 'modules/WorkSchedules/RelHooks.php';

class SpentTime extends Basic {

   public $new_schema = true;
   public $module_dir = 'SpentTime';
   public $object_name = 'SpentTime';
   public $table_name = 'spenttime';
   public $importable = false;
   public $disable_row_level_security = true;
   public $id;
   public $name;
   public $date_entered;
   public $date_modified;
   public $modified_user_id;
   public $modified_by_name;
   public $created_by;
   public $created_by_name;
   public $description;
   public $deleted;
   public $created_by_link;
   public $modified_user_link;
   public $assigned_user_id;
   public $assigned_user_name;
   public $assigned_user_link;
   public $employee_id;

   public function bean_implements($interface) {
      $result = false;
      if ( $interface === 'ACL' ) {
         $result = true;
      }
      return $result;
   }

   public function save($check_notify = false) {
      global $mod_strings;

      $other_worktime_count = $this->getCountOfSpendTimeRecordsInGivenFrame();

      if ( !$other_worktime_count ) {
         $update_task_on_rel_del = null;
         $is_manual_save = false;

         if ( isset($_REQUEST['action']) ) {
            if ( $_REQUEST['action'] == 'DeleteRelationship' ) {
               if ( !$this->spendtime_projecttask_id ) {
                  $update_task_on_rel_del = $this->fetched_row['spendtime_projecttask_id'];
                  $this->spendtime_project_id = null;
               }
            } else if ( $_REQUEST['action'] == 'Save' ) {
               $is_manual_save = true;
            }
         }
         $this->employee_id = $this->assigned_user_id;
         $this->setNameAsUsernameAndWorkDate();

         $expected_spent_time = $this->getValidSpendTime();
         if ( !$this->isSpendTimeValid($expected_spent_time) ) {
            $this->spent_time = $expected_spent_time;
         }

         $parent_result = parent::save($check_notify);
         return $parent_result;
      } else {
         $formula_parser = new VTFormulaParser();
         $formula_parser->appendErrorMessage($mod_strings['LBL_SPENT_TIME_RECORD_FOR_THIS_PERIOD_ALREADY_EXISTS'], $this, 'date_end');
      }
      return false;
   }

   protected function setNameAsUsernameAndWorkDate() {
      $outputString = "";
      $dateDbFormat = "/^\d{4}-\d{2}-\d{2}$/";
      $inputString = $this->work_date;
      if ( preg_match($dateDbFormat, $inputString) ) {
         $outputString = $inputString;
      } else {
         $timedate = new TimeDate();
         $outputString = $timedate->to_db_date($inputString, false);
      }
      $this->name = $outputString . ' - ' . $this->assigned_user_name;
   }

   public function ACLAccess($view, $is_owner = 'not_set', $in_group = 'not_set') {

      if ( !$this->checkAccessForParentWorkScheduleIfExists($view) ) {
         return false;
      }
      $result = null;
      global $current_user, $db, $timedate;
      if ( is_admin($current_user) || is_admin_for_module($current_user, $this->getACLCategory()) ) {
         $result = true;
      } else {
         $_view = strtolower($view);
         if ( in_array($_view, array('edit', 'editview', 'save', 'massupdate', 'delete')) && $result !== false && !empty($this->workschedule_id) ) {
            SugarAutoLoader::requireWithCustom('modules/SpentTime/SpentTimeActionAccess.php');
            $action_access = new SpentTimeActionAccess();
            $action_access->setBean(BeanFactory::getBean('WorkSchedules', $this->workschedule_id));
            $result = $action_access->checkAccess('add_past_time')['result'];
         }
         if ( $result !== false ) {
            $result = parent::ACLAccess($view, $is_owner, $in_group);
            if ( in_array($view, array('EditView', 'edit')) ) {
               $result = ($this->assigned_user_id == $current_user->id) || ($this->employee_id == $current_user->id) || (empty($this->assigned_user_id));
            }
         }
      }
      return $result;
   }

   //to remove
   public function isOwner($user_id) {
      $is_owner = parent::isOwner($user_id);
      if ( !$is_owner ) {
         global $current_user, $db;
         $sql = "SELECT 1 FROM users WHERE id='{$this->assigned_user_id}' AND reports_to_id='{$current_user->id}'";
         $is_superior = (bool) $db->getOne($sql);
      }
      return ($is_owner || $is_superior);
   }

   //to remove
   public function getOwnerWhere($user_id, $table_alias = null) {
      $result = parent::getOwnerWhere($user_id);
      $result .= " OR  {$this->table_name}.assigned_user_id IN (SELECT id FROM users WHERE reports_to_id = '{$user_id}') ";
      return " ( " . $result . " ) ";
   }

   protected function getCountOfSpendTimeRecordsInGivenFrame() {
      if ( !class_exists('SpentTimeApi') ) {
         include 'modules/SpentTime/api/SpentTimeApi.php';
      }
      $api = new SpentTimeApi();
      $count = $api->getCountOfSpendTimeRecordsInGivenFrame($this->id, $this->workschedule_id, $this->date_start, $this->date_end);
      return $count;
   }

   protected function isSpendTimeValid($expected_spent_time) {
      $valid = true;

      if ( $this->spent_time != $expected_spent_time ) {
         $GLOBALS['log']->fatal("Incorrect spent time! Actual: " . $this->spent_time . ", excepted: " . $expected_spent_time . ". Record ID: " . $this->id);
         $valid = false;
      }

      return $valid;
   }

   protected function getValidSpendTime() {
      $date_start = new DateTime($this->date_start);
      $date_end = new DateTime($this->date_end);

      $interval = $date_end->diff($date_start);

      $hour = $interval->format("%h");
      $min = round($interval->format("%i") / 60, 2);

      $expected_spent_time = $hour + $min;

      return $expected_spent_time;
   }

   protected function checkAccessForParentWorkScheduleIfExists($view) {
      global $current_user;
      $result = true;

      switch ( $view ) {
         case 'edit':
         case 'EditView':
         case 'delete':
            if ( !$current_user->is_admin && $this->load_relationship('workschedules') ) {
               $beans = $this->workschedules->getBeans();
               if ( count($beans) && array_shift(array_values($beans))->status === 'closed' ) {
                  $result = false;
               }
            }
      }
      return $result;
   }

   protected function postSave() {
      $this->updateRelatedWorkSchedule();
   }

   protected function updateRelatedWorkSchedule() {
      $obj = new WorkSchedulesRelHooks();
      $obj->afterSpentTimeEdit($this);
   }

}

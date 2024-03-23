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

class SpentTimeActionAccess {
    const ALLOWED_TIME = '11';
   protected $bean;
   protected $errors = array();
   protected $actions = array(
      'add_past_time' => array(
         'acl' => 'edit',
         'methods' => array(
            'isAdmin',
            'isSuperiorOfUserAssignedToWorkSchedule',
            'isMyWorkScheduleInCurrentMonth',
            'amISuperiorOfAnyOneAndItIsMyWorkSchedule',
            'isValidDatetimeLastDayOfMonth'
         ),
         'conditions' => 'OR'
      ),
   );

   public function setBean(WorkSchedules $bean) {
      $this->bean = $bean;
   }

   public function checkAccess($action_name) {
      $this->errors = array();
      $action_name = strtolower($action_name);
      if ( isset($this->actions[$action_name]) ) {
         $acl = $this->bean->ACLAccess($this->actions[$action_name]['acl']);
         if ( !$acl ) {
            $errors[] = 'ERR_ACLACCESS_DENIED';
         }
         if ( is_array($this->actions[$action_name]['methods']) ) {
            if ( $this->actions[$action_name]['conditions'] == "OR" ) {
               $result = $this->conditionsOR($this->actions[$action_name]['methods']);
               $return = $result && $acl;
            } else if ( $this->actions[$action_name]['conditions'] == "AND" ) {
               $result = $this->conditionsAND($this->actions[$action_name]['methods']);
               $return = (count($this->errors) > 0 || !$result) ? false : true;
            }
         }
      }
      return array( 'result' => $return, 'errors' => $this->getErrors() );
   }

   protected function conditionsOR($methods) {
      $result = false;
      foreach ( $methods as $method ) {
         if ( method_exists($this, $method) && !$result ) {
            $method_result = $this->{$method}();
            if ( !$method_result ) {
               $this->errors[] = 'ERR_METHOD_ERROR_' . strtoupper($method);
            }
            $result = $result || $method_result;
         }
      }
      return $result;
   }

   protected function conditionsAND($methods) {
      foreach ( $methods as $method ) {
         if ( method_exists($this, $method) ) {
            if ( !$this->{$method}() ) {
               $this->errors[] = 'ERR_METHOD_ERROR_' . strtoupper($method);
            }
         } else {
            $this->errors[] = 'ERR_METHOD_DOES_NOT_EXISTS_' . strtoupper($method);
         }
      }
      return (count($this->errors) > 0) ? false : true;
   }

   public function getErrors() {
      return $this->errors;
   }

   protected function isAdmin() {
      global $current_user;
      return $current_user->isAdmin();
   }

   protected function isMyWorkScheduleInCurrentMonth() {
      global $current_user, $timedate;
      $now = new SugarDateTime();
      $date = SugarDateTime::createFromFormat($timedate->get_date_format(), $this->bean->schedule_date);
      return ($this->bean->assigned_user_id == $current_user->id && $date->format('m') == $now->format('m'));
   }

   protected function isValidDatetimeLastDayOfMonth(){
    global $timedate;
    $now = new SugarDateTime();
    $date = SugarDateTime::createFromFormat($timedate->get_date_format(), $this->bean->schedule_date);
    return  !$this->isMyWorkScheduleInCurrentMonth() 
            && $now->format('G') <= static::ALLOWED_TIME
            && $now->format('j') != '1'
            && abs($now->format('n') - $date->format('n')) == '1';
   }

   protected function isSuperiorOfUserAssignedToWorkSchedule() {
      global $current_user;
      $assigned_user = BeanFactory::getBean('Users', $this->bean->assigned_user_id);
      return ($current_user->id == $assigned_user->reports_to_id);
   }

   protected function amISuperiorOfAnyOneAndItIsMyWorkSchedule() {
      global $current_user, $db;
      $sql = "SELECT id FROM users WHERE reports_to_id='{$current_user->id}' AND deleted=0";
      $superior = $db->getOne($sql);
      return ($this->bean->assigned_user_id == $current_user->id && $superior);
   }

}

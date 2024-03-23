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

class PeriodsOfEmployment extends Basic {

   public $new_schema = true;
   public $module_dir = 'PeriodsOfEmployment';
   public $object_name = 'PeriodsOfEmployment';
   public $table_name = 'periodsofemployment';
   public $importable = false;
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
   public $SecurityGroups;

   public function bean_implements($interface) {
      if ( $interface === 'ACL' ) {
         return true;
      } else {
         return false;
      }
   }

   public function ACLAccess($view, $is_owner = 'not_set', $in_group = 'not_set') {

      $limited_view_types = array( 'delete', 'editview', 'edit' );

      if ( in_array(strtolower($view), $limited_view_types) ) {
         $result = false;
      } else {
         $result = parent::ACLAccess($view, $is_owner, $in_group);
      }

      return $result;
   }

   public function save($check_notify = false) {
      $this->name = $this->getName();
      return parent::save($check_notify);
   }

   protected function getName() {
      $employee_bean = BeanFactory::getBean('Employees', $this->employee_id);
      global $sugar_config;
      $start_date = getDateTimeObject($this->period_starting_date);
      $end_date = getDateTimeObject($this->period_ending_date);
      return $employee_bean->first_name . ' '
              . $employee_bean->last_name . ' '
              . $start_date->format($sugar_config['default_date_format']) . ' - '
              . (!empty($end_date) ? $end_date->format($sugar_config['default_date_format']) : '...' );
   }

}

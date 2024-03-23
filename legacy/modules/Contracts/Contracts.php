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

class Contracts extends Basic {

   public $new_schema = true;
   public $module_dir = 'Contracts';
   public $object_name = 'Contracts';
   public $table_name = 'contracts';
   public $importable = true;
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
   public $date_of_signing;
   public $contract_starting_date;
   public $contract_ending_date;
   public $status;
   public $contract_type;
   public $daily_working_time;
   public $periodofemployment_id;
   public $employee_id;

   public function bean_implements($interface) {
      if ( $interface === "ACL" ) {
         return true;
      } else {
         return false;
      }
   }

   public function updateDates() {
      global $db;
      $query = "SELECT MIN(term_starting_date) AS date_start FROM termsofemployment WHERE contract_id = '{$this->id}'"
              . " AND deleted=0";
      $result = $db->query($query);
      if ( $row = $db->fetchByAssoc($result) ) {
         if ( is_null($row['date_start']) ) {
            $this->contract_starting_date = '';
         } else {
            $this->contract_starting_date = $row['date_start'];
         }
         $query = "SELECT term_starting_date AS date_start, term_ending_date AS date_finish "
                 . "FROM termsofemployment WHERE contract_id = '{$this->id}' AND deleted=0 "
                 . "ORDER BY date_start DESC LIMIT 1";
         $result = $db->query($query);
         if ( $row = $db->fetchByAssoc($result) ) {
            if ( $row['date_finish'] == null ) {
               $this->contract_ending_date = '';
            } else {
               $this->contract_ending_date = $row['date_finish'];
            }
         } else {
            $this->contract_ending_date = '';
         }
      }

      if ( ($this->contract_starting_date != $this->fetched_row['contract_starting_date'] || $this->contract_ending_date != $this->fetched_row['contract_ending_date'] ) && $this->fetched_row != 0 ) {
         $this->save();
      }
   }

   public function save($check_notify = false) {

      $update_periods = false;
      if ( ($this->contract_starting_date != $this->fetched_row['contract_starting_date'] || $this->contract_ending_date != $this->fetched_row['contract_ending_date'] || $this->employee_id != $this->fetched_row['employee_id'] ) && !empty($this->contract_starting_date) ) {
         $update_periods = true;
         $old_employee = $this->fetched_row['employee_id'];
      }
      $return_value = parent::save($check_notify);
      if ( $update_periods ) {
         SugarAutoLoader::requireWithCustom('modules/PeriodsOfEmployment/PeriodsOfEmploymentUpdater.php');
         $PoEU = new PeriodsOfEmploymentUpdater($this->employee_id);
         $PoEU->update();
         if ( isset($old_employee) && !empty($old_employee) ) {
            $PoEU = new PeriodsOfEmploymentUpdater($old_employee);
            $PoEU->update();
         }
      }

      return $return_value;
   }

   public function mark_deleted($id) {
      $contract = BeanFactory::getBean($this->object_name, $id);
      $employee_id_needed_after_delete = $contract->employee_id;
      $this->deleteRelatedTermsOfEmployment();
      parent::mark_deleted($id);
      SugarAutoLoader::requireWithCustom('modules/PeriodsOfEmployment/PeriodsOfEmploymentUpdater.php');
      $PoEU = new PeriodsOfEmploymentUpdater($employee_id_needed_after_delete);
      $PoEU->update();
   }

   protected function getRelatedTermsOfEmployment() {
      global $db;
      $terms_of_employment_ids = array();
      $sql = "SELECT id FROM termsofemployment WHERE contract_id = '{$this->id}' AND deleted = 0";
      $result = $db->query($sql);
      while ( $row = $db->fetchByAssoc($result) ) {
         $terms_of_employment_ids[] = $row['id'];
      }
      return $terms_of_employment_ids;
   }

   protected function deleteRelatedTermsOfEmployment() {
      foreach ( $this->getRelatedTermsOfEmployment() as $related_term_of_employment_id ) {
         $related_term_of_employment = BeanFactory::getBean('TermsOfEmployment');
         if ( $related_term_of_employment->retrieve($related_term_of_employment_id) ) {
            $related_term_of_employment->mark_deleted($related_term_of_employment_id);
         }
      }
   }

}

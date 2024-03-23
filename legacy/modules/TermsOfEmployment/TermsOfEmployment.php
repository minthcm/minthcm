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

class TermsOfEmployment extends Basic {

   public $new_schema = true;
   public $module_dir = 'TermsOfEmployment';
   public $object_name = 'TermsOfEmployment';
   public $table_name = 'termsofemployment';
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
   public $term_starting_date;
   public $term_ending_date;
   public $date_of_signing;
   public $gross;
   public $currency_id;
   public $net;
   public $employer_cost;

   public function bean_implements($interface) {
      if ( $interface === 'ACL' ) {
         return true;
      } else {
         return false;
      }
   }

   public function save($check_notify = false) {
      $old_starting_date = $this->fetched_row['term_starting_date'];
      $old_ending_date = $this->fetched_row['term_ending_date'];
      $old_contract_id = $this->fetched_row['contract_id'];
      $this->convertCurrencyFields();
      parent::save($check_notify);

      if ( $this->term_starting_date != $old_starting_date || $this->term_ending_date != $old_ending_date || $this->contract_id != $old_contract_id ) {
         $contract = BeanFactory::getBean('Contracts', $this->contract_id);
         if ( $contract && $contract->id ) {
            $contract->updateDates();
         }
      }
      if ( $this->contract_id != $old_contract_id ) {
         $old_contract = BeanFactory::getBean('Contracts', $old_contract_id);
         if ( $old_contract && $old_contract->id ) {
            $old_contract->updateDates();
         }
      }
   }

   public function mark_deleted($id) {
      $contract_id = $this->contract_id;
      parent::mark_deleted($id);
      $contract = BeanFactory::getBean('Contracts', $contract_id);
      if ( $contract && $contract->id ) {
         $contract->updateDates();
      }
   }

   public function ACLAccess($view, $is_owner = 'not_set', $in_group = 'not_set') {
      $view = strtolower($view);
      if ( in_array($view, array( 'delete' )) ) {
         global $db;
         $query = "SELECT t1.id, t2.id "
                 . "FROM termsofemployment t1 JOIN termsofemployment t2 ON t1.contract_id = t2.contract_id "
                 . "WHERE t1.deleted = 0 AND t2.deleted = 0 AND t1.contract_id = '{$this->contract_id}'"
                 . "AND t1.term_ending_date < '{$this->term_starting_date}' AND t2.term_starting_date > IF "
                 . "('{$this->term_ending_date}' != '', '{$this->term_ending_date}', '2099-12-31')";
         $result = $db->query($query);
         if ( $row = $db->fetchByAssoc($result) ) {
            return false;
         }
      }
      return parent::ACLAccess($view, $is_owner, $in_group);
   }

   protected function convertCurrencyFields() {
      $currency = new Currency();
      $currency->retrieve($this->currency_id);

      if ( isset($this->gross) ) {
         $this->gross = !number_empty($this->gross) ? $this->gross : 0.0;
         $this->gross_usdollar = $currency->convertToDollar(unformat_number($this->gross));
      }
      if ( isset($this->net) ) {
         $this->net = !number_empty($this->net) ? $this->net : 0.0;
         $this->net_usdollar = $currency->convertToDollar(unformat_number($this->net));
      }
      if ( isset($this->employer_cost) ) {
         $this->employer_cost = !number_empty($this->employer_cost) ? $this->employer_cost : 0.0;
         $this->employer_cost_usdollar = $currency->convertToDollar(unformat_number($this->employer_cost));
      }
   }

}

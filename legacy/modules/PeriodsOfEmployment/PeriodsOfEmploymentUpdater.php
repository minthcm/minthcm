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

class PeriodsOfEmploymentUpdater {

   protected $employee_id;
   protected $db;
   protected $new_periods = [];
   protected $periods_in_db = [];

   const PERIOD_MODULE_NAME = 'PeriodsOfEmployment';

   public function __construct($employee_id) {
      $this->employee_id = $employee_id;
      global $db;
      $this->db = $db;
   }

   public function update() {
      $this->loadNewPeriods();
      $this->loadPeriodsFromDataBase();
      foreach ( $this->new_periods as $new_period ) {
         $this->handlePeriod($new_period);
      }
      $this->removeNotUsedPeriods();
   }

   protected function handlePeriod($new_period) {
      global $timedate;
      $id_of_first_period_in_first_contract = $new_period->contracts_row_objects[0]['periodofemployment_id'];
      if ( !empty($id_of_first_period_in_first_contract) && !isset($this->periods_in_db[$id_of_first_period_in_first_contract]['is_used']) ) {
         $this->periods_in_db[$id_of_first_period_in_first_contract]['is_used'] = true;
         $new_period_of_employment = BeanFactory::getBean(static::PERIOD_MODULE_NAME, $id_of_first_period_in_first_contract);
      } else {
         $new_period_of_employment = BeanFactory::newBean(static::PERIOD_MODULE_NAME);
      }
      $new_period_of_employment->employee_id = $this->employee_id;
      $new_period_of_employment->period_starting_date = $new_period->start_date->format($timedate->get_date_format());
      if ( $new_period->end_date ) {
         $new_period_of_employment->period_ending_date = $new_period->end_date->format($timedate->get_date_format());
      } else {
         $new_period_of_employment->period_ending_date = null;
      }
      $new_period_of_employment->save(false);
      $id_of_first_period_in_first_contract = $new_period_of_employment->id;

      foreach ( $new_period->contracts_row_objects as $contract ) {
         if ( $contract['periodofemployment_id'] != $id_of_first_period_in_first_contract ) {
            $contract_bean = BeanFactory::getBean('Contracts', $contract['id']);
            $contract_bean->periodofemployment_id = $id_of_first_period_in_first_contract;
            $contract_bean->save(false);
         }
      }
   }

   protected function removeNotUsedPeriods() {
      $period_focus = BeanFactory::newBean(static::PERIOD_MODULE_NAME);
      foreach ( $this->periods_in_db as $period_in_db ) {
         if ( !isset($period_in_db['is_used']) ) {
            $period_focus->mark_deleted($period_in_db['id']);
         }
      }
   }

   protected function loadPeriodsFromDataBase() {
      $sql = "SELECT id, period_starting_date, period_ending_date FROM periodsofemployment"
              . " WHERE deleted=0 AND employee_id='{$this->employee_id}' ORDER BY period_starting_date";
      $sql_result = $this->db->query($sql);
      while ( $row = $this->db->fetchByAssoc($sql_result) ) {
         $this->periods_in_db[$row['id']] = $row;
      }
   }

   protected function loadNewPeriods() {
      $sql = "SELECT id, contract_starting_date, contract_ending_date, periodofemployment_id FROM contracts"
              . " WHERE employee_id = '{$this->employee_id}' AND deleted=0 ORDER BY contract_starting_date";
      $sql_result = $this->db->query($sql);
      while ( $row = $this->db->fetchByAssoc($sql_result) ) {
         $contract = $this->setDateFieldToDateTimeForRow($row);
         $period = $this->getPeriodForContract($contract);
         if ( !$period ) {
            $period = new ContractPeriod();
            $this->new_periods[] = $period;
         }
         $period->contracts_row_objects[] = $contract;
         if ( is_null($period->start_date) || $period->start_date > $contract['contract_starting_date'] ) {
            $period->start_date = $contract['contract_starting_date'];
         }
         if ( (!is_null($period->end_date) && !is_null($contract['contract_ending_date'])) && $period->end_date < $contract['contract_ending_date'] ) {
            $period->end_date = $contract['contract_ending_date'];
         }
         if ( is_null($contract['contract_ending_date']) ) {
            $period->end_date = null;
         }
      }
   }

   protected function getPeriodForContract($contract) {
      $start_date_minus_one_day = clone $contract['contract_starting_date'];
      $start_date_minus_one_day->modify('-1 day');
      if ( $contract['contract_ending_date'] ) {
         $end_date_plus_day = clone $contract['contract_ending_date'];
         $end_date_plus_day->modify('+1 day');
      }
      foreach ( $this->new_periods as $period ) {
         if ( !empty($end_date_plus_day) && $this->conditionsIfEndDateIsNotEmpty($period, $start_date_minus_one_day, $end_date_plus_day) ) {
            return $period;
         } else if ( $this->conditionsIfEndDateIsEmpty($period, $start_date_minus_one_day) ) {
            return $period;
         }
      }
      return false;
   }

   protected function conditionsIfEndDateIsNotEmpty(ContractPeriod $period, $start_date, $end_date) {
      if ( !empty($period->end_date) ) {
         if (
                 ($start_date <= $period->start_date && $end_date >= $period->start_date) || ($end_date >= $period->end_date && $start_date <= $period->end_date) || ($start_date >= $period->start_date && $end_date <= $period->end_date ) || ($start_date <= $period->start_date && $end_date >= $period->end_date)
         ) {
            return $period;
         }
      } else {
         if (
                 ($start_date <= $period->start_date && $end_date >= $period->start_date) || $start_date >= $period->start_date
         ) {
            return $period;
         }
      }
      return false;
   }

   protected function conditionsIfEndDateIsEmpty(ContractPeriod $period, $start_date) {
      if ( !empty($period->end_date) ) {
         if ( ($start_date >= $period->start_date && $start_date <= $period->end_date) || ($start_date <= $period->start_date) ) {
            return $period;
         }
      } else {
         if ( $start_date <= $period->start_date || $start_date >= $period->start_date ) {
            return $period;
         }
      }
      return false;
   }

   protected function setDateFieldToDateTimeForRow($row) {
      global $timedate;
      $row['contract_starting_date'] = DateTime::createFromFormat(
                      $timedate->get_db_date_format(), $row['contract_starting_date']
              )->setTime(0, 0, 0);
      if ( !empty($row['contract_ending_date']) ) {
         $row['contract_ending_date'] = DateTime::createFromFormat(
                         $timedate->get_db_date_format(), $row['contract_ending_date']
                 )->setTime(0, 0, 0);
      }
      return $row;
   }

}

class ContractPeriod {

   public $start_date;
   public $end_date = false;
   public $id;
   public $contracts_row_objects = [];

}

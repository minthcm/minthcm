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

class TermsOfEmploymentApi {

   /**
    * QA: Refactor this function to reduce its Cognitive Complexity from 26 to the 15 allowed.
    * QA: Reduce the number of returns of this function 6, down to the maximum allowed 3.
    */
   public function validateTermDates($args) {
      if ( !empty($args['id']) ) {
         $focus = BeanFactory::getBean('TermsOfEmployment', $args['id']);
         if ( $focus->term_starting_date == $args['date_start'] && $focus->term_ending_date == $args['date_end'] ) {
            return true;
         }
      }
      global $db;
      $query = "SELECT t1.term_ending_date AS hole_start, t2.term_starting_date AS hole_end "
              . "FROM termsofemployment t1 JOIN termsofemployment t2 ON t1.contract_id = t2.contract_id AND t1.term_ending_date < t2.term_starting_date "
              . "WHERE t1.deleted=0 AND t2.deleted=0 AND t1.contract_id = '{$args['contract_id']}' "
              . "AND t1.id != '{$args['id']}' AND t2.id != '{$args['id']}' "
              . "AND t1.term_ending_date < '{$args['date_start']}' AND t2.term_starting_date > '{$args['date_end']}' "
              . "ORDER BY DATEDIFF('{$args['date_start']}', hole_start) ASC";
      $sql_result = $db->query($query);
      if ( $row = $db->fetchByAssoc($sql_result) ) {
         $hole_start = new DateTime($row['hole_start']);
         $hole_end = new DateTime($row['hole_end']);
         $term_start = new DateTime($args['date_start']);
         $term_end = new DateTime($args['date_end']);
         $start_diff = date_diff($hole_start, $term_start);
         $end_diff = date_diff($term_end, $hole_end);
         if ( ($hole_start < $term_start && $start_diff->d == 1) && ($term_end < $hole_end && $end_diff->d == 1) ) {
            return true;
         }
      }

      $query = "SELECT MIN(term_starting_date) AS date_start, IF(MAX(term_ending_date IS NULL) = 0, MAX(term_ending_date), NULL) AS date_end "
              . "FROM termsofemployment "
              . "WHERE deleted=0 AND id != '{$args['id']}' AND contract_id = '{$args['contract_id']}'";
      $sql_result = $db->query($query);
      if ( $row = $db->fetchByAssoc($sql_result) ) {
         if ( $row['date_start'] == null && $row['date_end'] == null ) {
            return true;
         }
         $contract_start = new DateTime($row['date_start']);
         $term_end = $args['date_end'] == '' ? '' : new DateTime($args['date_end']);
         if ( $row['date_end'] == null && ($term_end == '' || $contract_start < $term_end) ) {
            return false;
         }
         $contract_end = new DateTime($row['date_end']);
         $term_start = new DateTime($args['date_start']);
         $start_diff = date_diff($contract_end, $term_start);
         if ( $term_end ) {
            $end_diff = date_diff($term_end, $contract_start);
            $end_diff_d = $end_diff->d;
         } else {
            $end_diff_d = 1;
         }

         if ( ($contract_end < $term_start && $start_diff->d == 1) || ($term_end < $contract_start && $end_diff_d == 1) ) {
            return true;
         }
      }
      return false;
   }

   public function checkIfTermInBetween($args) {
      global $db;
      $query = "SELECT term_starting_date, term_ending_date, contract_id "
              . "FROM termsofemployment "
              . "WHERE id='{$args['id']}'";
      $result = $db->query($query);
      $row = $db->fetchByAssoc($result);
      $query = "SELECT t1.id, t2.id "
              . "FROM termsofemployment t1 JOIN termsofemployment t2 ON t1.contract_id = t2.contract_id "
              . "WHERE t1.deleted = 0 AND t2.deleted = 0 AND t1.contract_id = '{$row['contract_id']}'"
              . "AND t1.term_ending_date < '{$row['term_starting_date']}' AND t2.term_starting_date > "
              . "IF ('{$row['term_ending_date']}' != '', '{$row['term_ending_date']}', '2099-12-31')";
      $result = $db->query($query);
      $row = $db->fetchByAssoc($result);
      return !empty($row) ? true : false;
   }

}

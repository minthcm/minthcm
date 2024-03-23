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

class ResourcesApi {

   public function isReservable($args) {

      global $db;
      $fetched_rows = '';

      $sql = "SELECT type FROM resources WHERE id='{$args['resource_id']}' AND deleted=0";
      $result = $db->query($sql);

      while ( $row = $db->fetchByAssoc($result) ) {
         $fetched_rows = $row['type'];
      }

      return ($fetched_rows == 'not_for_reservation' ? false : true);
   }

   public function getBusyTimeSlots($args) {
      $busy_timeslots = array();
      if ( isset($args['resource_id']) && !empty($args['timeslots']) ) {
         $start_date = $this->getDateFromHash(current($args['timeslots'])['hash']);
         $end_date = $this->getDateFromHash(end($args['timeslots'])['hash']);
         $reservations = $this->getReservations($args['resource_id'], $start_date, $end_date);
         $reservations_timeslots = $this->getReservationsTimeSlots($reservations);
         foreach ( $args['timeslots'] as $timeslot ) {
            $this->getBusyTimeSlotsPerSlot($timeslot, $reservations_timeslots, $busy_timeslots);
         }
      }
      return $busy_timeslots;
   }

   public function getBusyTimeSlotsPerSlot($timeslot, $reservations_timeslots, &$busy_timeslots) {
      foreach ( $reservations_timeslots as $reservation_id => $reservation_timeslots ) {
         if ( in_array($this->getDateFromHash($timeslot['hash']), $reservation_timeslots) ) {
            if ( !isset($busy_timeslots[$timeslot['hash']]) ) {
               $busy_timeslots[$timeslot['hash']] = ['records' => [$reservation_id => 'Reservations']];
            } else {
               $busy_timeslots[$timeslot['hash']]['records'][$reservation_id] = 'Reservations';
            }
         }
      }
   }

   private function getDateFromHash($hash) {
      $date_array = str_split($hash, 2);
      $date = $date_array[0] .
         $date_array[1] . '-' .
         $date_array[2] . '-' .
         $date_array[3] . ' ' .
         $date_array[4] . ':' .
         $date_array[5];
      return date('Y-m-d H:i', strtotime($date . " +1 month"));
   }

   private function getReservationsTimeSlots($reservations) {
      $reservations_timeslots = array();
      foreach ( $reservations as $reservation ) {
         $startTime = new DateTime($reservation['starting_date']);
         $endTime = new DateTime($reservation['ending_date']);
         $timeArray = array();
         while ( $startTime < $endTime ) {
            $timeArray[] = $startTime->format('Y-m-d H:i');
            $startTime->modify('+15 minutes');
         }
         $reservations_timeslots[$reservation['id']] = $timeArray;
      }
      return $reservations_timeslots;
   }

   private function getReservations($resource_id, $start_date, $end_date) {
      global $db;
      $resevations = array();
      $sql = "SELECT id, starting_date, ending_date "
         . "FROM reservations "
         . "WHERE resource_id='{$resource_id}' "
         . "AND starting_date < '{$end_date}' "
         . "AND ending_date > '{$start_date}' "
         . "AND deleted=0";
      $result = $db->query($sql);
      while ( $row = $db->fetchByAssoc($result) ) {
         $resevations[] = $row;
      }
      return $resevations;
   }

}

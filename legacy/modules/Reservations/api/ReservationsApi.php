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

class ReservationsApi {

   CONST RESERVATION_BEAN = 'Reservations';
   CONST RESERVATION_KEY = 'reservation_id';

   public function updateReservation($args) {
      global $timedate;
      $reservation = BeanFactory::newBean(static::RESERVATION_BEAN);
      if ( !empty($args[static::RESERVATION_KEY]) && $reservation->retrieve($args[static::RESERVATION_KEY]) ) {
         $reservation->starting_date = $timedate->to_db($args['starting_date']);
         $reservation->ending_date = $timedate->to_db($args['ending_date']);
         $reservation->save();
         return true;
      }
      return false;
   }

   public function deleteReservation($args) {
      $reservation = BeanFactory::newBean(static::RESERVATION_BEAN);
      if ( !empty($args[static::RESERVATION_KEY]) && $reservation->retrieve($args[static::RESERVATION_KEY]) ) {
         $reservation->mark_deleted($args[static::RESERVATION_KEY]);
         return true;
      }
      return false;
   }

   public function getReservations($args) {
      if ( !empty($args['reservations_ids']) ) {
         $result = array();
         foreach ( $args['reservations_ids'] as $reservation_id ) {
            $reservation = BeanFactory::getBean(static::RESERVATION_BEAN, $reservation_id);
            if ( $reservation && !empty($reservation->id) ) {
               $result[] = new ReservationInfo($reservation);
            }
         }
         return $result;
      }
      return false;
   }

}

class ReservationInfo {

   public $id;
   public $name;
   public $starting_date;
   public $employee;

   public function __construct($bean) {
      $this->id = $bean->id;
      $this->name = $bean->name;
      $this->starting_date = $bean->starting_date;
      $this->employee = $bean->employee_name;
   }

}

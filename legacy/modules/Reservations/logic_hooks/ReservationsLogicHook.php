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

class ReservationsLogicHook {

   protected $supported_modules = [ 'Calls', 'Meetings' ];
   protected $resources_rel = "resources";
   protected $reservations_rel = "reservations";

   public function beforeActivityDelete($bean) {
      if ( in_array($bean->module_name, array_keys($this->supported_modules)) ) {
         $rel = $this->reservations_rel;
         if ( $bean->load_relationship($rel) ) {
            foreach ( $bean->$rel->get() as $reservation_id ) {
               $reservation = BeanFactory::newBean('Reservations');
               $reservation->mark_deleted($reservation_id);
            }
         }
      }
   }

   public function afterActivitySave($bean) {
      if ( in_array($bean->module_name, array_keys($this->supported_modules)) ) {
         $rel = $this->resources_rel;
         if ( $bean->load_relationship($rel) ) {
            $resources_ids = $bean->$rel->get();
            $this->createReservations($bean, array_diff($bean->resources_arr, $resources_ids));
            if ( $bean->date_start != $bean->fetched_row['date_start'] ||
                    $bean->date_end != $bean->fetched_row['date_end'] ||
                    $bean->duration_hours != $bean->fetched_row['duration_hours'] ||
                    $bean->duration_minutes != $bean->fetched_row['duration_minutes']
            ) {
               $this->updateReservations($bean);
            }
         }
      }
   }

   private function createReservations($bean, $resources_ids) {
      global $current_user;
      foreach ( $resources_ids as $resource_id ) {
         $reservation = BeanFactory::newBean('Reservations');
         $reservation->name = $bean->name;
         $reservation->resource_id = $resource_id;
         $reservation->starting_date = $bean->date_start;
         if ( $bean->module_name == "Calls" ) {
            $date_end = new DateTime($bean->date_start);
            $date_end->modify("+" . $bean->duration_hours . " hours +" . $bean->duration_minutes . " minutes");
            $reservation->ending_date = $date_end->format('Y-m-d H:i:s');
         } else {
            $reservation->ending_date = $bean->date_end;
         }
         $reservation->parent_type = $bean->module_name;
         $reservation->parent_id = $bean->id;
         $reservation->assigned_user_id = $current_user->id;
         $reservation->employee_id = $current_user->id;
         $reservation->save();
      }
   }

   private function updateReservations($bean) {
      $rel = $this->reservations_rel;
      if ( $bean->load_relationship($rel) ) {
         foreach ( $bean->$rel->get() as $reservation_id ) {
            $reservation = BeanFactory::getBean('Reservations', $reservation_id);
            if ( $reservation && !empty($reservation->id) ) {
               $reservation->starting_date = $bean->date_start;
               if ( $bean->module_name == "Calls" ) {
                  $date_end = new DateTime($bean->date_start);
                  $date_end->modify("+" . $bean->duration_hours . " hours +" . $bean->duration_minutes . " minutes");
                  $reservation->ending_date = $date_end->format('Y-m-d H:i:s');
               } else {
                  $reservation->ending_date = $bean->date_end;
               }
               $reservation->save();
            }
         }
      }
   }

}

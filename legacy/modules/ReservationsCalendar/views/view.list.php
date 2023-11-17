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

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once('include/MVC/View/views/view.list.php');

class ReservationsCalendarViewList extends ViewList {

   public function display() {
      $resources = $this->getResources();
      $this->ss->assign('resources', $resources);
      $calendars = $this->getCalendarList($resources);
      $this->ss->assign('calendars', json_encode($calendars));
      $reservations = $this->getReservations($calendars);
      $this->ss->assign('reservations', json_encode($reservations));
      $resource_id = filter_input(INPUT_GET, 'resource_id', FILTER_SANITIZE_STRING);
      if ( !empty($resource_id) ) {
         $this->ss->assign('default_resource', $resource_id);
      }
      $this->ss->assign('CALENDAR_FDOW', $GLOBALS['current_user']->get_first_day_of_week());
      echo $this->ss->fetch('modules/ReservationsCalendar/tpl/view.list.tpl');
   }

   protected function getResources() {
      $resource = BeanFactory::getBean('Resources');
      $resources = $resource->get_full_list("name", "type='for_reservation'");
      return (!empty($resources)) ? $resources : array();
   }

   protected function getCalendarList($resources) {
      $calendars = array();
      foreach ( $resources as $resource ) {
         $calendar = array();
         $calendar['id'] = $resource->id;
         $calendar['name'] = $resource->name;
         $calendar['checked'] = false;
         $calendar['color'] = '#000000';
         $calendar['bgColor'] = '#009976';
         $calendar['borderColor'] = '#3A87AD';
         $calendars[] = $calendar;
      }
      return $calendars;
   }

   protected function getReservations($calendars) {
      $reservations = array();
      $reservation = BeanFactory::getBean('Reservations');
      foreach ( $calendars as $calendar ) {
         $reservation_beans = $reservation->get_full_list("name", "resource_id='{$calendar['id']}' AND ending_date > (NOW() - INTERVAL 3 MONTH)");
         if ( !empty($reservation_beans) ) {
            foreach ( $reservation_beans as $bean ) {
               $row = array();
               $row['id'] = $bean->id;
               $row['calendarId'] = $bean->resource_id;
               $row['title'] = $bean->name;
               $row['category'] = "time";
               $row['start'] = $this->getDate($bean->starting_date);
               $row['end'] = $this->getDate($bean->ending_date);
               $row['attendees'] = [ 'anyone' ];
               $row['raw'] = [ 'creator' => [ 'name' => $bean->employee_name ] ];
               $row['isReadOnly'] = !($bean->ACLAccess('edit')); 
               $row['isNotDeletable'] = !($bean->ACLAccess('delete'));
               $row['detailViewAccess'] = $bean->ACLAccess('detail');
               $reservations[] = $row;
            }
         }
      }
      return $reservations;
   }

   protected function getDate($date) {
      global $current_user, $timedate;
      $tz = $current_user->getPreference('timezone');
      $datef = $current_user->getPreference('datef');
      $timef = $current_user->getPreference('timef');
      $format = $datef . " " . $timef;
      if ( empty($tz) ) {
         $tz = 'UTC';
      }
      $tzo = new DateTimeZone($tz);
      $result_date = $timedate->asUser($timedate->fromDb($date), $current_user);
      $result_date = DateTime::createFromFormat($format, $result_date, $tzo);
      return $result_date->format('c');
   }

}

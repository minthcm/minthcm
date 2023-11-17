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

class CalendarActivities {

   private static $initialized = false;
   private static $activities = array();

   private static function init() {
      if ( !self::$initialized ) {
         $conf_file_path = __DIR__ . '/config.php';
         $activities = array();
         if ( file_exists($conf_file_path) ) {
            include $conf_file_path;
            self::$activities = $activities;
            self::$initialized = true;
         }
      }
      return self::$initialized;
   }

   private static function userHasAccess($user_id, $module) {
      global $current_user;
      $hasAccess = false;
      $acl = $_SESSION['ACL'][$current_user->id][$module]['module']['view']['aclaccess'];

      if ( $acl == ACL_ALLOW_ALL || $current_user->is_admin ||
              ($acl == ACL_ALLOW_OWNER && $current_user->id === $user_id) ) {
         $hasAccess = true;
      }

      return $hasAccess;
   }

   private static function getActivityBean($module, $id = null) {
      return BeanFactory::getBean($module, $id);
   }

   private static function createCalendarActivity(SugarBean $bean) {
      global $timedate;

      $options = static::$activities[$bean->module_name];
      $start_field = $options['start_time_field'];
      $end_field = $options['end_time_field'];
      $ca = new CalendarActivity($bean);

      if ( !empty($ca) ) {
         $ca->sugar_bean->originalId = $ca->sugar_bean->id;
         $ca->start_time = $bean->$start_field;
         $ca->end_time = $bean->$end_field;

         if ( !(isset($bean->duration_hours) && isset($bean->duration_minutes)) ) {

            $d = static::getDuration($ca->start_time, $ca->end_time);
            $bean->duration_hours = $d->hours;
            $bean->duration_minutes = $d->minutes;
         }
      }
      if ( !is_object($ca->start_time) ) {
         $ca->start_time = $timedate->fromUser($ca->start_time);
         if ( !is_object($ca->start_time) ) {
            $ca->start_time = $timedate->fromUser($bean->$start_field . ' 06:00am');
         }
      }
      if ( !is_object($ca->end_time) ) {
         $ca->end_time = $timedate->fromUser($ca->end_time);
         if ( !is_object($ca->end_time) ) {
            $ca->start_time = $timedate->fromUser($bean->$end_field . ' 06:00am');
         }
      }

      return $ca;
   }

   private static function buildSelectQuery(SugarBean $bean, $cal_view_start_time, $cal_view_end_time, $user_id = null) {

      $table_name = $bean->table_name;
      $assignable = isset($bean->field_defs['assigned_user_id']);
      $options = static::$activities[$bean->module_name];
      $start_field = $options['start_time_field'];
      $end_field = $options['end_time_field'];
      $view_start_time = $cal_view_start_time->format('Y-m-d H:i:s');
      $view_end_time = $cal_view_end_time->format('Y-m-d H:i:s');
      $fields = array( 'id', $start_field, $end_field, 'status' );
      $where = "deleted=0 AND "
              . "((DATE($start_field) BETWEEN DATE('$view_start_time') AND DATE('$view_end_time')) OR "
              . "(DATE($end_field) BETWEEN DATE('$view_start_time') AND DATE('$view_end_time')))";

      if ( $assignable ) {
         $fields[] = 'assigned_user_id';
         if ( $user_id ) {
            $where .= " AND assigned_user_id='$user_id' ";
         }
      }

      $query = "SELECT " . implode(',', $fields) . " FROM $table_name " . "WHERE $where";

      return $query;
   }

   private static function fetchActivities(SugarBean $bean, $view_start_time, $view_end_time, $user_id = null) {
      $data = array();
      $query = self::buildSelectQuery($bean, $view_start_time, $view_end_time, $user_id);
      $result = $bean->db->query($query);
      while ( $row = $bean->db->fetchByAssoc($result) ) {
         array_push($data, $row);
      }
      return $data;
   }

   /**
    * Convert sugar datetime format to DB format or keep it if is well formatted.
    * @param string $inputString Sugar datetime format or "Y-m-d H:i:s" format
    * @return string Datetime in format "Y-m-d H:i:s" or empty string
    * if $inputString is invalid
    */
   public static function toDbDatetime($inputString) {

      $outputString = "";
      $dateDbFormat = "/^\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}$/";

      if ( preg_match($dateDbFormat, $inputString) ) {
         $outputString = $inputString;
      } else {
         $timedate = new TimeDate();
         $start_arr = explode(' ', $inputString);
         $start_time = $start_arr[1];
         $start_date = $timedate->to_db_date($inputString, false);
         if ( $start_date ) {
            $outputString = $start_date . ' ' . $start_time;
         }
      }

      return $outputString;
   }

   /**
    * Find duration between dates.
    * @param string $start_sugar_timedate Sugar datetime format of "Y-m-d H:i:s" format
    * @param string $end_sugar_timedate Sugar datetime format of "Y-m-d H:i:s" format
    * @return stdClass stdClass having hours and minutes properties
    */
   public static function getDuration($start_sugar_timedate, $end_sugar_timedate) {

      $start = static::toDbDatetime($start_sugar_timedate);
      $end = static::toDbDatetime($end_sugar_timedate);

      $diff = @strtotime($end) - @strtotime($start);

      $minutes = $diff / 60;
      $hours = 0;

      if ( $minutes > 59 ) {
         $hours = floor($minutes / 60);
         $minutes = $minutes % 60;
      }

      $duration = new stdClass();
      $duration->hours = $hours;
      $duration->minutes = $minutes;

      return $duration;
   }

   /**
    * Get all definitions stored into config file or definitions for 
    * specified module if param is given.
    * @param string $module [Optional] Specified module name
    * @return array
    */
   public static function getDefs($module = false) {
      self::init();
      $defs = static::$activities;
      if ( $module ) {
         $defs = isset(static::$activities[$module]) ?
                 static::$activities[$module] : false;
      }
      return $defs;
   }

   /**
    * This method should be called only for hard coded injection activities
    * into $act_list in CalendarActivity->get_activities just before
    * return statement. All params must be passed.
    * @param type $act_list
    * @param type $user_id
    * @param type $view_start_time
    * @param type $view_end_time
    * @param type $view
    */
   public static function pushCalendarActivityList(&$act_list, $user_id, $view_start_time, $view_end_time, $view) {

      // Calendar activities are sometimes used by vCals module
      // on Call saving and this method is being invoked as well
      // then causing problems - excluding freebusy view seems
      // to let avoid it.
      if ( $view != "freebusy" ) {

         self::init();

         foreach ( array_keys(self::$activities) as $module ) {

            if ( !self::userHasAccess($user_id, $module) ) {
               continue;
            }

            $bean = self::getActivityBean($module);
            $fetchedData = self::fetchActivities($bean, $view_start_time, $view_end_time, $user_id);
            foreach ( $fetchedData as $data ) {
               $bean = self::getActivityBean($module, $data['id']);
               if ( $bean ) {
                  $ca = self::createCalendarActivity($bean);
                  $act_list[] = $ca;
               }
            }
         }
      }
   }

}

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

require_once 'include/Notifications/Notification.php';
require_once 'include/Notifications/NotificationPlugin.php';

class NotificationManager {

   const PLUGINS_DIRECTORY = "./include/Notifications/plugins";

   protected $plugins_collection = array();

   public function __construct() {
      $files = scandir(self::PLUGINS_DIRECTORY);

      foreach ( $files as $file ) {
         if ( $file == '.' || $file == '..' ) {
            continue;
         }

         include self::PLUGINS_DIRECTORY . '/' . $file;
         $class_name = self::getClassFromFile($file);
         $plugin = new $class_name;
         if ( $plugin instanceof NotificationPlugin ) {
            $this->plugins_collection[] = $class_name;
         }
      }
   }

   public function run() {
      $this->clearOldWebPush();
      foreach ( $this->plugins_collection as $plugin_class ) {
         $plugin = new $plugin_class();
         $plugin->run();
      }
   }



   protected static function getClassFromFile($file) {
      $r = substr($file, 0, -4);
      return $r;
   }

   public static function isValidUser($user_id) {
      $user_bean = BeanFactory::getBean('Users', $user_id);
      return (!empty($user_bean->id));
   }

   public static function getUserFullName($user_id) {
      $user_bean = BeanFactory::getBean('Users', $user_id);
      if ( !empty($user_bean->id) ) {
         return $user_bean->full_name;
      }
      return false;
   }

   public static function toDbDatetime($input_string) {
      global $timedate;
      $output_string = "";
      $date_db_format = "/^\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}$/";

      if ( preg_match($date_db_format, $input_string) ) {
         $output_string = $input_string;
      } else {
         $start_arr = explode(' ', $input_string);
         $start_time = $start_arr[1];
         $start_date = $timedate->to_db_date($input_string, false);
         if ( $start_date ) {
            $output_string = $start_date . ' ' . $start_time;
         }
      }

      return $output_string;
   }

   public static function NotificationForBeanExists($bean,$type) {
     return self::NotificationExists($bean->parent_type,$bean->id,$type);
   }

   public static function notificationForRecordWithId($module,$record_id,$type){
      return self::NotificationExists($module,$record_id,$type);
   }

   public static function NotificationExists($module,$record_id,$type){
      global $db;
      $sql = "SELECT id FROM alerts WHERE parent_type='{$module}' AND parent_id='{$record_id}' AND alert_type='{$type}' AND is_read=0 AND (type != 'webpush' OR type IS NULL)";
      $sql_result = $db->getOne($sql);
      return !empty($sql_result);
   }


   public function clearOldWebPush(){
      global $db;
      $db->query("DELETE FROM alerts WHERE type='webpush' AND is_read=1 AND DATE(date_entered) = DATE( DATE_SUB( NOW() , INTERVAL 30 DAY ) )" );
   }
   

}

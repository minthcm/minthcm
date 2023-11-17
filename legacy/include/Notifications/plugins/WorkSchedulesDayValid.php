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
require_once 'include/Notifications/NotificationPlugin.php';

class WorkSchedulesDayValid extends NotificationPlugin {

   public function run() {
      global $app_strings;
      $work_schedules = $this->getNotClosedWorkSchedules();
      foreach ( $work_schedules as $work_schedule ) {
         if ( !$work_schedule ) {
            continue;
         }
         if(empty($work_schedule['assigned_user_id'])){
            $GLOBALS['log']->fatal("WorkSchedulesDayValid: There is Work Schedule without assigned user id (WS id: ".$work_schedule['id'].")");
            continue;
         }
         if(NotificationManager::notificationForRecordWithId('WorkSchedules',$work_schedule['id'],'WorkSchedulesDayValid')){
            continue;
         }

         $this->getNewNotification()
                 ->setDescription(sprintf(translate('LBL_APPROVED_ALERT', 'WorkSchedules'), $this->getWorkScheduleStartDate($work_schedule['id'])))
                 ->setAssignedUserId($work_schedule['assigned_user_id'])
                 ->setRelatedBean($work_schedule['id'], 'WorkSchedules')
                 ->setType('WorkSchedulesDayValid')
                 ->saveAsAlert()->WebPush();
      }

      $this->removeIncorrectNotifications();

   }
   public function isWebPushableNotification(){
      return true;
   }

   protected function removeIncorrectNotifications(){
      $wrong_notifications = $this->getWrongNotifications();
      foreach ( $wrong_notifications as $wrong_notification ) {
         $notification = BeanFactory::getBean('Alerts', $wrong_notification['id']);
         if ( !empty($notification->id) ) {
            $notification->mark_deleted($notification->id);
         }
      }
   }

   protected function getWorkScheduleStartDate($work_schedule_id) {
      $bean = BeanFactory::getBean('WorkSchedules', $work_schedule_id);
      $datetime = explode(' ', NotificationManager::toDbDatetime($bean->date_start));
      return $datetime[0];
   }

   protected function getNotClosedWorkSchedules() {
      global $db;
      $sql_result = $db->query("SELECT id, assigned_user_id FROM workschedules WHERE status!='Closed' AND deleted=0 AND date_end < CURDATE();");
      $result = array();
      while ( $result[] = $db->fetchByAssoc($sql_result) );
      return $result;
   }

   public function getWrongNotifications() {
      global $db;
      $sql = "SELECT alerts.id,alerts.name,alerts.type,workschedules.id,workschedules.name,workschedules.deleted,alerts.deleted,alerts.url_redirect,alerts.parent_id
      FROM alerts 
      LEFT JOIN workschedules ON alerts.parent_type='WorkSchedules'
            AND alerts.parent_id=workschedules.id AND workschedules.deleted=0 
                 WHERE alerts.deleted=0 AND ( workschedules.id IS NULL OR alerts.assigned_user_id <> workschedules.assigned_user_id)  AND alerts.alert_type='WorkSchedulesDayValid' AND alerts.type IS NULL  ";
      $sql_result = $db->query($sql);
      $result = array();
      while ( $result[] = $db->fetchByAssoc($sql_result) );
      return $result;
   }

}

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

class WorkSchedulesRelHooks {

   private static function sumSpentTime($workschedule_id) {
      $q = "SELECT SUM(t.spent_time) FROM spenttime t "
              . "INNER JOIN workschedules_spenttime wr "
              . "ON wr.spenttime_id=t.id AND wr.deleted=0 "
              . "WHERE wr.workschedule_id='$workschedule_id' AND t.deleted=0";
      return $GLOBALS['db']->getOne($q);
   }

   private static function updateSpentTimeAndStatus(SugarBean $bean, $arguments, $m = true) {
      $extra_time = 0;
      // there is no new rel record in db yet - need to add manualy
      $cur_rel_bean = $arguments['related_bean'];
      if ( $m && $cur_rel_bean instanceof SpentTime ) {
         $extra_time = $cur_rel_bean->spent_time;
      }
      $bean->spent_time = self::sumSpentTime($bean->id) + $extra_time;
      $bean->status = $bean->spent_time > 0 ? 'worked' : 'planned';
      $bean->save();
   }

   public function afterSpentTimeEdit($bean) {
      $bean->retrieve($bean->id);
      if ( !empty($bean->workschedule_id) ) {
         $ws = BeanFactory::getBean('WorkSchedules', $bean->workschedule_id);
		 if($bean->workschedule_id==$ws->id){
           $ws->spent_time = self::sumSpentTime($bean->workschedule_id);
           $ws->save();
        }
      }
   }

   public function after_relationship_add($bean, $event, $arguments) {
      if ( $arguments['link'] == 'spenttimes' ) {
         self::updateSpentTimeAndStatus($bean, $arguments);
         self::updateTaskRel($bean, $event, $arguments);
      }
   }

   public function after_relationship_delete($bean, $event, $arguments) {
      if ( $arguments['link'] == 'spenttimes' ) {
         self::updateSpentTimeAndStatus($bean, $arguments, false);
         self::updateTaskRel($bean, $event, $arguments);
      }
   }

   protected function updateTaskRel($bean, $event, $arguments) {
      static ::updateSpendTime(($arguments['module'] != 'WorkSchedules' ?
                      $arguments['id'] : $arguments['related_id']));
   }

   protected static function updateSpendTime($old) {
      global $db;
      $new = static ::updateIdFromAudit($old);
      if ( $new != '' ) {
         $db->query("UPDATE workschedules_spenttime SET spenttime_id = '$new' WHERE spenttime_id = '{$old}'");
      }
   }

   protected static function updateIdFromAudit($id) {
      global $db;
      return $db->getOne("SELECT parent_id FROM spenttime_audit WHERE before_value_string = '$id'");
   }

}

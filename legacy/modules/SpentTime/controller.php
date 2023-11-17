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

class SpentTimeController extends SugarController {

   protected function getLastDate(SugarBean $bean) {
      global $db, $timedate;

      $query = "SELECT
                  date_end date
               FROM
                  workschedules_spenttime ws
               INNER JOIN 
                  spenttime st 
               ON
                  st.id = ws.spenttime_id
                  AND st.deleted=0
                  AND ws.deleted=0 
               WHERE
                  ws.workschedule_id = '{$bean->id}'
                  AND ws.workschedule_id != ''
                  AND st.deleted = 0
                  AND ws.deleted = 0
               ORDER BY
                  st.date_end DESC";

      $res = $db->fetchOne($query);

      if ( !$res ) {
         $query = "SELECT 
                     date_start date
                  FROM
                  workschedules
                  WHERE
                     id = '{$bean->id}'
                     AND deleted = 0
                  ORDER BY 
                     date_start DESC";
         $res = $db->fetchOne($query);
      }
      return $timedate->fromDb($res['date']);
   }

   public function action_getDate() {
      global $timedate;

      ob_clean();
      $workschedule = BeanFactory::getBean("WorkSchedules");
      if ( $workschedule && $workschedule->retrieve($_REQUEST['record']) ) {
         $date_start = $this->getLastDate($workschedule);
         $time_date_start = $timedate->splitTime($timedate->asUser($date_start), $timedate->get_date_time_format());

         $date_end = $timedate->fromUser($workschedule->date_end);
         if ( $date_end && $date_end->format('Y-m-d') == date('Y-m-d') ) {
            $date_end = $timedate->fromUser(date($timedate->get_date_time_format()));
         }
         $time_date_end = $timedate->splitTime($timedate->asUser($date_end), $timedate->get_date_time_format());

         $return_array = array(
            'scheduleDateStart' => $timedate->asUserDate($date_start),
            'scheduleDateEnd' => $timedate->asUserDate($date_end),
            'scheduleDateLastMin' => array('H' => $time_date_start['h'], 'M' => $time_date_start['m']),
            'scheduleDateEndMin' => array('H' => $time_date_end['h'], 'M' => $time_date_end['m']),
         );
         exit(json_encode($return_array));
      }
   }

   public function action_isUniqueSpentTime() {
      global $timedate;
      $result = true;
      $record_id = isset($_REQUEST['record_id']) ? $_REQUEST['record_id'] : '';
      $assigned_user_id = isset($_REQUEST['assigned_user_id']) ? $_REQUEST['assigned_user_id'] : '';
      $date_start = isset($_REQUEST['date_start']) ? $_REQUEST['date_start'] : '';
      $date_end = isset($_REQUEST['date_end']) ? $_REQUEST['date_end'] : '';
      if ( $assigned_user_id != '' && $date_start != '' && $date_end != '' ) {
         $db_date_start = $timedate->to_db($date_start);
         $db_date_end = $timedate->to_db($date_end);
         $query = "
            SELECT
               COUNT(id) as counter
            FROM
               spenttime
            WHERE
               assigned_user_id = '$assigned_user_id' AND
               ((
                  (date_start BETWEEN '$db_date_start' AND '$db_date_end') AND
                  date_start != '$db_date_start' AND 
                  date_start != '$db_date_end'
               ) OR (
                  (date_end BETWEEN '$db_date_start' AND '$db_date_end') AND
                  date_end != '$db_date_start' AND
                  date_end != '$db_date_end'
               )) AND
               id != '$record_id' AND
               deleted = 0
         ";
         if ( $GLOBALS['db']->getOne($query) > 0 ) {
            $result = false;
         }
      }
      ob_clean();
      exit(json_encode(array('result' => $result)));
   }

}

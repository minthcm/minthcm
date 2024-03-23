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

global $db, $current_user;
$tz = $current_user->getPreference('timezone');
if ( empty($tz) ) {
   $tz = 'Europe/Warsaw';
}
$tzo = new DateTimeZone($tz);
$now_date = DateTime::createFromFormat($timedate->get_date_time_format(), $timedate->now(true), $tzo);
$mysql_offset = ($tzo->getOffset($now_date) / 60 / 60);
$sql = "SELECT
   A.id,
   B.id as user_id,
   CONCAT(IFNULL(B.first_name,''),' ',IFNULL(B.last_name,'')) AS name,
   A.type,
   date(DATE_ADD(A.date_start, INTERVAL {$mysql_offset} HOUR)) as start_date,
   DATE_FORMAT(DATE_ADD(A.date_start, INTERVAL {$mysql_offset} HOUR),'%H:%i') as start_time,
   DATE(DATE_ADD(A.date_end, INTERVAL {$mysql_offset} HOUR)) as end_date,
   DATE_FORMAT(DATE_ADD(A.date_end, INTERVAL {$mysql_offset} HOUR),'%H:%i') as end_time

FROM 
   workschedules A 
   INNER JOIN users  B ON A.assigned_user_id = B.id
WHERE (
         (
               A.type = 'holiday'     
            OR A.type='sick'
            OR A.type='sick_care'
            OR A.type='delegation'
            OR A.type='occasional_leave'
            OR A.type='leave_at_request'
         )
         OR 
         (
            A.type='home'
            AND 11 BETWEEN HOUR(A.date_start) AND HOUR(A.date_end)
         )
         OR ( A.type = 'overtime'        AND duration_hours >= 4 )
         OR ( A.type = 'excused_absence' AND duration_hours >= 4 )
      )
      AND A.deleted = 0
      AND B.deleted = 0
   AND A.date_start > DATE(SUBDATE(NOW(), INTERVAL 30 DAY))
   ORDER BY
         B.id,
         start_date asc
      ";
$sql_result = $db->query($sql);

$typeToColor = array(
   'holiday' => '#ffff99', //'#ffb128',
   'sick' => '#f08475', // '#e94b35',
   'delegation' => '#e1b7c6', // '#733047',
   'occasional_leave' => '#ffcf99', // '#ed7f00',
   'home' => '#a7d3f1', // '#2c97de',
   'overtime' => '#adebad', // '#2c97de',
   'leave_at_request' => '#ffb128',
   'excused_absence' => '#adff2f',
);
$result = array();
global $app_list_strings;
$users_events = array();
$i = 0;
while ( $event = $db->fetchByAssoc($sql_result) ) {

   $start_timstamp = strtotime($event['start_date']);
   $today_key = $event['user_id'] . date("Y-m-d", $start_timstamp) . $event['type'];
   $yesterday_key = $event['user_id'] . date("Y-m-d", strtotime("- 1 day", $start_timstamp)) . $event['type'];

   if ( !isset($users_events[$yesterday_key]) ) {
      $users_events[$today_key] = $i;
      $result[$i] = array(
         "title" => $event['name'] . ' [' . $app_list_strings['workschedule_type_list'][$event['type']] . ']',
         "start" => $event['start_date'],
         "color" => $typeToColor[$event['type']],
         "allDay" => true,
         "editable" => false,
         "eventTextColor" => '#000',
      );
   } else {
      $pdId = $users_events[$yesterday_key];
      $users_events[$today_key] = $users_events[$yesterday_key];
      $result[$pdId]["end"] = date("Y-m-d", strtotime("+ 1 day", strtotime($event['start_date'])));
   }
   $i++;
}
echo json_encode(array_values($result));
return;

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

class WorkSchedulesNotPlandForTwoWeeks extends NotificationPlugin
{

    const PLAN_FOR_DAYS = 10;

    public function run()
    {
        $work_schedules = $this->getNotPlannedWorkSchedules();
        foreach ($work_schedules as $work_schedule) {
            if ($work_schedule == false) {
                continue;
            }

            if(NotificationManager::notificationForRecordWithId('Users',$work_schedule['id'],'WorkSchedulesNotPlandForTwoWeeks')){
                continue;
             }

            $options = ['url_redirect' => 'index.php?module=WorkSchedules' ];
            $this->getNewNotification()
                ->setAssignedUserId($work_schedule['id'])->setRelatedBean($work_schedule['id'], 'Users')
                ->setDescription(translate('LBL_TWO_WEEKS_ALERT', 'WorkSchedules'))->setType('WorkSchedulesNotPlandForTwoWeeks')
                ->saveAsAlert(true,$options)->WebPush(true,true,$options);
        }
    }

    public function isWebPushableNotification(){
        return true;
    }
    public function getWebPushDescriptionConfig(){
        return true;
    }
    public function getWebPushLinkConfig(){
        return true;
     }
     public function getWebPushOverrideConfig(){
        return $options = ['url_redirect' => 'index.php?module=WorkSchedules' ];
     }
    protected function getNotPlannedWorkSchedules()
    {
        global $db;
        $work_days = $this->getWorkingDaysArray();
        $days_where = "workschedules.schedule_date IN('" . implode("','", $work_days) . "')";

        $query = "SELECT COUNT(users.id) as 'days',users.id FROM users LEFT JOIN workschedules ON workschedules.assigned_user_id=users.id  AND " . $days_where . " WHERE users.status='Active' GROUP BY users.id HAVING days<" . self::PLAN_FOR_DAYS;
        $sql_result = $db->query($query);
        $return_data = array();
        while ($return_data[] = $db->fetchByAssoc($sql_result));
        return $return_data;
    }

    protected function getWorkingDaysArray()
    {
        $non_working_days = $this->getNonWorkingDays();
        $work_days = array();
        $shift = 0;
        while (count($work_days) < self::PLAN_FOR_DAYS) {
            $date = date("Y-m-d", strtotime("+ $shift days"));
            if (date('w', strtotime($date)) == 0 || date('w', strtotime($date)) == 6 || in_array($date, $non_working_days)) {
                $shift++;
                continue;
            }
            $work_days[] = $date;
            $shift++;
        }
        return $work_days;
    }

    protected function getNonWorkingDays()
    {
        global $db;
        $non_working_days = array();
        $result = $db->query("SELECT date FROM `nonworkingdays` WHERE `deleted`=0;");
        while ($row = $db->fetchByAssoc($result)) {
            $non_working_days[] = $row['date'];
        }
        return $non_working_days;
    }

}

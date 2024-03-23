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

class SpentTimeApi
{

    public function getCountOfSpendTimeRecordsInGivenFrame($id, $workschedule_id, $date_start, $date_end, $frontend = false)
    {
        $frontend = !!$frontend;
        if (!empty($workschedule_id) && !empty($date_start) && !empty($date_end)) {
            if ($frontend) {
                global $timedate;
                $date_start = $timedate->to_db($date_start);
                $date_end = $timedate->to_db($date_end);
            }
            if (empty($date_start) || empty($date_end)) {
                $GLOBALS['log']->fatal('Empty date_start or date_end while validating spent time ' . $frontend ? 'FRONTEND' : 'BACKEND');
                $GLOBALS['log']->fatal('Arguments: ' . print_r(func_get_args(), true));
            } else {
                $db = DBManagerFactory::getInstance();
                $id_where = '';
                if (isset($id)) {
                    $id_where = "st.id != '{$id}' AND ";
                }
                $sql = "SELECT COUNT(st.id) AS count FROM workschedules_spenttime ws "
                    . "LEFT JOIN spenttime st ON "
                    . "ws.spenttime_id=st.id "
                    . "AND ws.workschedule_id = '{$workschedule_id}' WHERE "
                    . "st.deleted = 0 AND "
                    . $id_where
                    . "((st.date_start <= '{$date_start}' AND st.date_end > '{$date_start}') OR "
                    . "(st.date_start < '{$date_end}' AND st.date_end >= '{$date_end}') OR "
                    . "(st.date_start < '{$date_start}' AND st.date_end > '{$date_start}') OR "
                    . "(st.date_start > '{$date_start}' AND st.date_end < '{$date_end}'))";
                return intval($db->getOne($sql));
            }
        }
        return 0;
    }

    public function canLogToWorkOffSchedule($workschedule_id)
    {
        global $db;
        $result = true;
        $work_off_types = array(
            'holiday',
            'sick',
            'sick_care',
            'occasional_leave',
            'leave_at_request',
            'overtime',
            'excused_absence',
        );
        if (!empty($workschedule_id)) {
            $sql = "
            SELECT
               type
            FROM
               workschedules
            WHERE
               id = '{$workschedule_id}' AND
               deleted = 0";
            $get_one = $db->getOne($sql);
            if (!empty($get_one) && in_array($get_one, $work_off_types)) {
                $result = false;
            }
        }
        return $result;
    }

    public function canLogTimeToPast($args)
    {
        SugarAutoLoader::requireWithCustom('modules/SpentTime/SpentTimeActionAccess.php');
        $checker = new SpentTimeActionAccess();
        $checker->setBean(BeanFactory::getBean('WorkSchedules', $args['workschedule_id']));
        return $checker->checkAccess('add_past_time');
    }

    public function getCurrentUserId($args)
    {
        global $current_user;
        if (!empty($current_user->id)) {
            return $current_user->id;
        }
        return false;
    }

}

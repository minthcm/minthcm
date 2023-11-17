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

class WorkSchedulesController extends SugarController
{

    public function process()
    {
        global $timedate;
        if (isset($_GET['redirected_from_calendar'])) {
            if (!ACLController::checkAccess('WorkSchedules', 'edit', true)) {
                if (ACLController::checkAccess('Meetings', 'edit', true)) {
                    $date_end = getDateTimeObject($_GET['date_end']);
                    $date_diff = $date_end->diff(getDateTimeObject($_GET['date_start']));
                    if ($date_diff->i == 30 && $date_diff->h == 0) {
                        $date_end->modify("+30 minutes");
                        SugarApplication::redirect('index.php?module=Meetings&action=EditView&return_module=Home&date_start=' . $_GET['date_start'] . '&date_end=' . $date_end->format($timedate->get_date_time_format()) . '&assigned_user_id=' . $_GET['assigned_user_id']);
                    } else {
                        SugarApplication::redirect('index.php?module=Meetings&action=EditView&return_module=Home&date_start=' . $_GET['date_start'] . '&date_end=' . $_GET['date_end'] . '&assigned_user_id=' . $_GET['assigned_user_id']);
                    }
                } else if (ACLController::checkAccess('Calls', 'edit', true)) {
                    SugarApplication::redirect('index.php?module=Calls&action=EditView&return_module=Home&date_start=' . $_GET['date_start'] . '&assigned_user_id=' . $_GET['assigned_user_id']);
                }
            }
        }
        parent::process();
    }

    public function action_checkIfCanBeClosed()
    {
        $this->bean->retrieve($_REQUEST['id']);
        echo json_encode($this->bean->canBeConfirmed());
    }

    public function action_checkScheduleName()
    {
        global $db;
        $workschedule_id = $_REQUEST['id']; // DZ TODO (security)
        $sql = "SELECT name FROM workschedules WHERE id = '{$workschedule_id}'";
        $res = $db->getOne($sql);
        echo ($res ? $res : '');
    }

    protected function action_resetPeriodicity()
    {
        if ($this->bean->id) {
            require_once 'modules/Calendar/CalendarUtils.php';
            CalendarUtils::markRepeatDeleted($this->bean);

            $fields = ['repeat_parent_id', 'repeat_type', 'repeat_interval', 'repeat_dow', 'repeat_until', 'repeat_count'];
            $update_fields = [];
            foreach ($fields as $field) {
                if ($field === 'repeat_interval') {
                    $update_fields[] = $field . '=1';
                } else {
                    $update_fields[] = $field . '=NULL';
                }
            }
            $update_fields = implode(',', $update_fields);
            $GLOBALS['db']->query("UPDATE workschedules SET {$update_fields} WHERE id='{$this->bean->id}'");

            $url = 'index.php?action=EditView&module=WorkSchedules&record=';
            SugarApplication::redirect($url . $this->bean->id);
        }
    }

    protected function action_getPlansListForDashlet()
    {
        global $db, $current_user;
        $user_id = $current_user->id;
        $date = isset($_REQUEST['date']) ? $_REQUEST['date'] : date('Y-m-d');
        $plans = array(
            'items' => array(),
        );

        if ($_REQUEST['date'] != date('Y-m-d')) {
            $_SESSION['dashlet_loaded_before'] = true;
        } else {
            unset($_SESSION['dashlet_loaded_before']);
        }

        $q = "SELECT * FROM workschedules " . "WHERE assigned_user_id='$user_id' AND schedule_date='$date' AND deleted=0";
        $r = $db->query($q);
        while ($row = $db->fetchByAssoc($r)) {
            array_push($plans['items'], $row);
        }
        ob_clean();
        echo json_encode($plans);
    }

    protected function action_getWorkSchedulesDates()
    {
        global $db;
        global $timedate;
        if (empty($timedate)) {
            $timedate = TimeDate::getInstance();
        }
        $workschedule_id = $_REQUEST['record'];
        $work_schedule_date_start = false;
        $work_schedule_date_end = false;
        $work_schedule_dates_sql = "SELECT date_start, date_end FROM workschedules WHERE id = '{$workschedule_id}' AND deleted='0' LIMIT 1";
        $work_schedule_dates_query = $db->query($work_schedule_dates_sql);
        while ($row = $db->fetchByAssoc($work_schedule_dates_query)) {
            $work_schedule_date_start = $row['date_start'];
            $work_schedule_date_end = $row['date_end'];
        }

        // DZ TODO wyjąć zapytania
        $spent_times_last_datetime = $db->getOne("SELECT max(date_end) as max_date_end FROM spenttime A INNER JOIN workschedules_spenttime B ON A.id = B.spenttime_id AND B.workschedule_id = '{$workschedule_id}' AND A.deleted='0' AND B.deleted=0");
        if ($spent_times_last_datetime == '') {
            $spent_times_last_datetime = $work_schedule_date_start;
        }
        ob_clean();
        echo json_encode(array(
            'work_schedule_date_start' => $timedate->to_display_date($work_schedule_date_start),
            'work_schedule_datetime_start' => $timedate->to_display_date_time($work_schedule_date_start),
            'work_schedule_date_end' => $timedate->to_display_date($work_schedule_date_end),
            'work_schedule_datetime_end' => $timedate->to_display_date_time($work_schedule_date_end),
            'spent_times_last_date' => $timedate->to_display_date($spent_times_last_datetime),
            'spent_times_last_datetime' => $timedate->to_display_date_time($spent_times_last_datetime),
        ));
    }

    protected function retrieveByCurDate()
    {
        global $db;
        $result = true;
        if (!$this->bean->id) {
            $q = "SELECT id FROM workschedules WHERE deleted=0 AND schedule_date=CURDATE() ORDER BY date_start";
            $id = $db->getOne($q);
            if (!$this->bean->retrieve($id)) {
                $result = false;
            }
        }
        return $result;
    }

    protected function action_isOwner()
    {
        global $db;
        ob_clean();
        $id = $_REQUEST['record'];
        $user = $_REQUEST['user'];
        $qi = "SELECT assigned_user_id='$user' FROM workschedules " . "WHERE id='$id'";
        echo $db->getOne($qi) ? '1' : '0';
        echo ob_get_clean();
    }

    protected function action_getRelatedTimes()
    {
        global $db;
        $times = array(
            'items' => array(),
        );
        global $timedate;
        if (empty($timedate)) {
            $timedate = TimeDate::getInstance();
        }

        $q = "SELECT t.* FROM spenttime AS t
            INNER JOIN workschedules_spenttime AS w
            ON t.id=w.spenttime_id AND w.deleted=0
            WHERE t.deleted=0 AND w.workschedule_id='{$this->bean->id}'
            ORDER BY date_start";
        $r = $db->query($q);
        while ($row = $db->fetchByAssoc($r)) {
            array_push($times['items'], $row);
        }
        ob_clean();
        echo json_encode($times);
    }

    protected function post_save()
    {
        if (isset($_REQUEST['return_module']) && ($_REQUEST['return_module'] == 'Calendar' || $_REQUEST['return_module'] == 'Home')) {
            $module = $_REQUEST['return_module'];
            $action = 'index';
            $url = "index.php?module=" . $module . "&action=" . $action;
        } else {
            $module = $this->module;
            $action = (!empty($this->return_action) ? $this->return_action : 'DetailView');
            $id = (!empty($this->return_id) ? $this->return_id : $this->bean->id);
            $url = "index.php?module=" . $module . "&action=" . $action . "&record=" . $id;
        }

        $this->set_redirect($url);
    }

    public function action_isUniqueWorkSchedule()
    {
        global $timedate;
        $result = true;
        $record_id = isset($_REQUEST['record_id']) ? $_REQUEST['record_id'] : '';
        $assigned_user_id = isset($_REQUEST['assigned_user_id']) ? $_REQUEST['assigned_user_id'] : '';
        $date_start = isset($_REQUEST['date_start']) ? $_REQUEST['date_start'] : '';
        $date_end = isset($_REQUEST['date_end']) ? $_REQUEST['date_end'] : '';
        if ($assigned_user_id != '' && $date_start != '' && $date_end != '') {
            $db_date_start = $timedate->to_db($date_start);
            $db_date_end = $timedate->to_db($date_end);
            $query = "
            SELECT
             COUNT(id) as counter
            FROM
              workschedules
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
            if ($GLOBALS['db']->getOne($query) > 0) {
                $result = false;
            }
        }
        ob_clean();
        exit(json_encode(array('result' => $result)));
    }

    public function action_WSMassConfirmation()
    {
        SugarAutoLoader::requireWithCustom('modules/WorkSchedules/MassConfirmation.php');
        if (!empty($_REQUEST['ids']) || (isset($_REQUEST['entire_list']) && !empty($_REQUEST['encoded_query']))) {
            MassConfirmation::schedule($_REQUEST['ids'], $_REQUEST['encoded_query'], $_REQUEST['entire_list']);
        }
    }

    public function action_wasDasheltLoadedBefore()
    {
        echo json_encode(['status' => $_SESSION['dashlet_loaded_before']]);
    }

}

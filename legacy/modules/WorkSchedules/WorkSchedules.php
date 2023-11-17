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

require_once 'include/CalendarActivities/CalendarActivities.php';
require_once 'include/DateFunctions/DateFormatter.php';
require_once 'modules/WorkSchedules/AcceptWorkScheduleValidator.php';

class WorkSchedules extends Basic
{

    public $new_schema = true;
    public $module_dir = 'WorkSchedules';
    public $object_name = 'WorkSchedules';
    public $table_name = 'workschedules';
    public $importable = true;
    public $disable_row_level_security = true;
    public $id;
    public $name;
    public $date_entered;
    public $date_modified;
    public $modified_user_id;
    public $modified_by_name;
    public $created_by;
    public $created_by_name;
    public $description;
    public $deleted;
    public $created_by_link;
    public $modified_user_link;
    public $assigned_user_id;
    public $assigned_user_name;
    public $assigned_user_link;
    public $lp;
    public $type;
    public $status;
    public $schedule_date;
    public $date_start;
    public $date_end;
    public $duration_minutes;
    public $duration_hours;
    public $spent_time;
    public $comments;
    public $occasional_leave_type;
    public $repeat_type;
    public $supervisor_acceptance;
    private static $repeatSaveRoudTripCounter = 0;
    private $_date_start = null;

    public function bean_implements($interface)
    {
        switch ($interface) {
            case 'ACL':return true;
            default:return false;
        }
    }

    private static function setDurationBaseOnDateEnd(WorkSchedules $bean)
    {
        $duration = CalendarActivities::getDuration($bean->date_start, $bean->date_end);
        $bean->duration_hours = $duration->hours;
        $bean->duration_minutes = $duration->minutes;
        return $duration;
    }

    protected function clearFieldsAfterTypeChange()
    {
        if ($this->type != 'delegation' && $this->fetched_row['delegation_duration'] > 0) {
            $this->delegation_duration = 0;
        }

        if ($this->type != 'occasional_leave' && strlen($this->occasional_leave_type) > 0) {
            $this->occasional_leave_type = null;
        }

        if (!in_array($this->type, array('occasional_leave', 'excused_absence')) && strlen($this->comments) > 0) {
            $this->comments = null;
        }
    }

    public function save($check_notify = false)
    {
        global $timedate, $current_user;

        $this->fixUpFormatting();
        $this->setSupervisorAcceptance();

        if ($this->isDataBaseOnDuration()) {
            if (isset($this->date_start) && isset($this->duration_hours) && isset($this->duration_minutes)) {
                $td = $timedate->fromDb($this->date_start);
                if (!$td) {
                    $td = $timedate->fromUser($this->date_start);
                }
                if ($td) {
                    $this->date_end = $td->modify("+{$this->duration_hours} hours {$this->duration_minutes} mins")->asDb();
                }
            }
        } elseif ($this->isDataBaseOnDateEnd()) {
            self::setDurationBaseOnDateEnd($this);
        }

        $this->clearFieldsAfterTypeChange();

        $this->_date_start = $timedate->to_display_date_time($this->date_start, true, true, $current_user);

        switch ($this->type) {
            case "occasional_leave":
            case "sick":
            case "sick_care":
            case "holiday":
            case "delegation":
            case 'office':
            case 'home':
            case 'overtime':
            case 'excused_absence':
            default:
                $this->spent_time_settlement = (strtotime($this->date_end) - strtotime($this->date_start)) / 3600;
                if ($this->type == "sick") {
                    if ($this->spent_time_settlement > 8) {
                        $this->spent_time_settlement = 8;
                        $td = $timedate->fromDb($this->date_start);
                        if (!$td) {
                            $td = $timedate->fromUser($this->date_start);
                        }
                        if ($td) {
                            $this->date_end = $td->modify("+{$this->spent_time_settlement} hours")->asDb();
                        }
                    }
                    $this->duration_hours = (int) $this->spent_time = $this->spent_time_settlement;
                    $this->duration_minutes = (int) (60 * ($this->spent_time_settlement - $this->duration_hours));
                }
                break;
        }

        if ($this->isInCycle()) {
            $this->UnpinCycle();
        }

        // MintHCM #76236 START
        $this->disableAlertForConfirmedWorkSchedule();
        // MintHCM #76236 END

        $this->beforeSave();
        $new_record = empty($this->fetched_row);

        if (empty($this->date_end) || empty($this->date_start)) {
            $GLOBALS['log']->fatal("Date start or date end is empty. Cannot save.Date start: {$this->date_start} date end: {$this->date_end}");
        } else {
            
                        // prevent a mass mailing for recurring meetings created in Calendar module
                        if ( empty($this->id) && !empty($_REQUEST['repeat_type']) && !empty($this->repeat_parent_id) ) {
                            $check_notify = false;
                        }

            $parent_result = parent::save($check_notify);
            $this->saveRepeatly();
            //prevents work schedule from being saved with spent_time_settlement equals to 0
            if ($this->spent_time_settlement == 0 || $this->spent_time_settlement == null) {
                $this->spent_time_settlement = (strtotime($this->date_end) - strtotime($this->date_start)) / 3600;
                $GLOBALS['log']->fatal("Spent time settlement is equal to 0 after save! Date start: {$this->date_start} date end: {$this->date_end} calculated spent time: {$this->spent_time_settlement}");

                $parent_result = parent::save($check_notify);
            }
        }

        $this->addNotification($new_record);
        if ($parent_result) {
            if (isset($_REQUEST['return_module']) && ($_REQUEST['return_module'] == 'Calendar' || $_REQUEST['return_module'] == 'Home')) {
                header("Location: index.php?module={$_REQUEST['return_module']}&action=index");
            } else if ($redirect) {
                handleRedirect($return_id, 'Calls');
            }
        }
        return $parent_result;
    }
    protected function addNotification($new_record)
    {
        if ($new_record && in_array($this->type , ['holiday', 'sick', 'sick_care', 'occasional_leave', 'leave_at_request', 'overtime', 'excused_absence'])) {
            require_once 'include/Notifications/plugins/WorkScheduleLeaveCreated.php';
            (new WorkScheduleLeaveCreated($this))->run();
        }
    }
    protected function checkUniqueTime()
    {
        global $db, $current_user;

        $sql_result = $db->query("
         SELECT
            id
         FROM
            {$this->table_name}
         WHERE
            id != '{$this->id}' AND
            deleted = 0 AND
            assigned_user_id  = '{$current_user->id}' AND
            assigned_user_id != '' AND
            (
               '{$this->date_start}' BETWEEN date_start             AND date_end OR
               '{$this->date_end}'   BETWEEN date_start             AND date_end OR
               date_start            BETWEEN '{$this->date_start}'  AND '{$this->date_end}' OR
               date_end              BETWEEN '{$this->date_start}'  AND '{$this->date_end}'
            )
      ");
        if ($result = $db->fetchByAssoc($sql_result)) {
            return $result['id'];
        }
        return false;
    }

    protected function isInCycle()
    {
        $result = false;
        if ($this->spent_time > 0) {
            $result = true;
        }
        if ($this->repeat_parent_id != '' && $this->id != '') {
            if (!empty($this->fetched_row)) {
                $result = true;
            }
        }
        return $result;
    }

    public function UnpinCycle()
    {
        global $db;
        $db->query("UPDATE {$this->table_name} SET repeat_parent_id=NULL, repeat_count=NULL, repeat_until=NULL, repeat_dow=NULL, repeat_interval=1, repeat_type=NULL WHERE repeat_parent_id='{$this->repeat_parent_id}' OR repeat_parent_id='{$this->id}' ");

        $this->repeat_parent_id = '';
        $this->repeat_count = '';
        $this->repeat_until = '';
        $this->repeat_dow = '';
        $this->repeat_interval = '';
        $this->repeat_type = '';
        return false;
    }

    private function isDataBaseOnDuration()
    {
        return $_REQUEST['action'] == 'Reschedule' || ($_REQUEST['action'] == 'Resize' && isset($_REQUEST['duration_hours']) && isset($_REQUEST['duration_minutes']));
    }

    private function isDataBaseOnDateEnd()
    {
        return $_REQUEST['action'] == 'Save' && isset($_REQUEST['date_start']) && isset($_REQUEST['date_end']);
    }

    private function setNameBeforeSave()
    {
        global $app_list_strings;

        $type = $app_list_strings['workschedule_type_list'][$this->type];
        $db_formatted_date = DateFormatter::getDateInDatabaseFormat($this->schedule_date);
        $this->name = "{$db_formatted_date} {$type} {$this->lp}";
    }

    private function setLpBeforeSave()
    {
        if (!$this->lp) {
            $db_formatted_date = DateFormatter::getDateInDatabaseFormat($this->schedule_date);

            $q = "SELECT COUNT(id) FROM workschedules WHERE assigned_user_id='{$this->assigned_user_id}' AND schedule_date='$db_formatted_date' AND deleted=0 AND id!='{$this->id}'";
            $lp = +$GLOBALS['db']->getOne($q) + 1;
            $this->lp = $lp;
        }
    }

    private function setScheduleDateBeforeSave()
    {
        if ($this->_date_start) {
            $this->schedule_date = array_shift(explode(' ', $this->_date_start));
        }
    }

    public function beforeSave($check_notify = false)
    {
        $this->setScheduleDateBeforeSave();
        $this->setLpBeforeSave();
        $this->setNameBeforeSave();
    }

    private function shouldBeProcessed()
    {
        return !($_REQUEST['module'] != $this->module_name || self::$repeatSaveRoudTripCounter || empty($_REQUEST['repeat_type']) || empty($_REQUEST['date_start']));
    }

    private function saveRepeatly()
    {
        if ($this->shouldBeProcessed()) {

            self::$repeatSaveRoudTripCounter++;
            require_once 'modules/Calendar/CalendarUtils.php';

            $params = array(
                'type' => $_REQUEST['repeat_type'],
                'interval' => $_REQUEST['repeat_interval'],
                'count' => $_REQUEST['repeat_count'],
                'until' => isset($_REQUEST['repeat_until']) ? $_REQUEST['repeat_until'] : null,
                'dow' => $_REQUEST['repeat_dow'],
            );

            $repeatArr = CalendarUtils::build_repeat_sequence($_REQUEST['date_start'], $params);
            $limit = SugarConfig::getInstance()->get('calendar.max_repeat_count', 1000);

            if (!empty($_REQUEST['edit_all_recurrences'])) {
                CalendarUtils::markRepeatDeleted($this);
            }

            if (count($repeatArr) < $limit && isset($repeatArr) && is_array($repeatArr) && count($repeatArr) > 0) {
                CalendarUtils::save_repeat_activities($this, $repeatArr);
            }
        }
    }

    public function ACLAccess($view, $is_owner = 'not_set', $in_group = 'not_set')
    {
        require_once 'modules/WorkSchedules/WorkSchedulesACLAccess.php';
        $acl = new WorkSchedulesACLAccess($this, parent::ACLAccess($view, $is_owner));
        return $acl->ACLAccess($view, $is_owner, $in_group);
        
    }

    protected function setSupervisorAcceptance()
    {
        if (!in_array($this->type, array('holiday', 'overtime', 'home'))) {
            $this->supervisor_acceptance = 'not_applicable';
        } elseif ((in_array($this->type, array('holiday', 'overtime', 'home')) && !in_array($this->fetched_row['type'], array('holiday', 'overtime', 'home'))) || ($this->date_start != $this->fetched_row['date_start'] || $this->date_end != $this->fetched_row['date_end'])) {
            $this->supervisor_acceptance = 'wait';
        }
    }

    public function mark_deleted($id)
    {
        if ($this->haveRelatedSpentTimes($id)) {
            SugarApplication::appendErrorMessage(translate("LBL_NOT_EMPTY_WORK_SCHEDULES"));
            return;
        }
        parent::mark_deleted($id);
    }

    protected function haveRelatedSpentTimes($id)
    {
        $db = DBManagerFactory::getInstance();
        $sql = "SELECT
         count(WR.id)
         FROM
            workschedules_spenttime WR
         INNER JOIN spenttime RUST ON RUST.id=WR.spenttime_id AND RUST.deleted = 0
         WHERE
            WR.deleted = 0
            AND WR.workschedule_id = '$id'  ";
        $result = $db->getOne($sql);
        return $result != 0;
    }

    public function canBeConfirmed()
    {
        return (new AcceptWorkScheduleValidator($this))->validate();
    }

    public function confirm() {
        if ($this->canBeConfirmed() == 1) {
            $this->status = 'closed';
            return $this->save();
        } else {
            return false;
        }
    }

    public function checkOwner() {
        global $current_user;
        return $this->assigned_user_id == $current_user->id;
    }

    // MintHCM #76236 START
    protected function disableAlertForConfirmedWorkSchedule()
    {
        if (!empty($this->id)) {
            $sql = "SELECT id FROM alerts WHERE parent_id ='{$this->id}' AND alert_type = 'WorkSchedulesDayValid' AND parent_type = 'WorkSchedules' AND is_read = '0' AND deleted = '0'";
            $result = $this->db->query($sql);
            while ($row = $this->db->fetchByAssoc($result)) {
                $alert = BeanFactory::getBean('Alerts', $row['id']);
                $alert->is_read = "1";
                $alert->save();
            }
        }
    }
    // MintHCM #76236 END

}

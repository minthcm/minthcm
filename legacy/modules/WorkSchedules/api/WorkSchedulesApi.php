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

class WorkSchedulesApi
{

    public function canChangeTypeToWorkOff($args)
    {
      global $db;
      $id = $args['id'];
      $type = $args['type'];
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
      if ( !empty($id) && !empty($type) && in_array($type, $work_off_types) ) {
         $sql = "
            SELECT
               id
            FROM
               workschedules_spenttime
            WHERE
               workschedule_id = '{$id}' AND
               deleted = 0";
         if ( !empty($db->getOne($sql)) ) {
            $result = false;
         }
      }
      return $result;
   }

    public function checkWorkScheduleCreatedByPeriodicity($data)
    {
      require_once 'modules/Calendar/CalendarUtils.php';
      global $db, $timedate;
      if ( !empty($data['data']) && !empty($data['data']['date_start']) ) {
         $repeatArr = CalendarUtils::build_repeat_sequence($data['data']['date_start'], $data['data']);
         $date_interval = sprintf('+%d hour +%d minutes', $data['data']['duration_hours'], $data['data']['duration_minutes']);
         foreach ( $repeatArr as $repeat ) {
            $db_date_start = $timedate->to_db($repeat);
            $db_date_end = date('Y-m-d H:i', strtotime($date_interval, strtotime($db_date_start)));
            $query = "
               SELECT COUNT(id)
               FROM workschedules
               WHERE assigned_user_id = '{$data['data']['assigned_user_id']}'
                 AND date_start < '{$db_date_end}'    
                 AND date_end > '{$db_date_start}'
                 AND deleted = 0
               ";
            if ( !empty($data['data']['record_id']) ) {
               $query .= "AND id != '{$data['data']['record_id']}'";
            }
            if ( $db->getOne($query) > 0 ) {
               return substr($repeat, 0, 10);
            }
         }
      }
      return null;
   }

    public function validateWorkplaceStatus($args)
    {
        $workplace_id = $args['id'];
        $workplace = BeanFactory::getBean('Workplaces', $workplace_id);
        return !$workplace || empty($workplace->id) || 'active' == $workplace->availability;
    }

    public function validateWorkplaceAllocationPeriods($args)
    {
        global $timedate;
        $workplace_id = $args['workplace_id'];
        $date_start = $args['date_start'];
        $date_end = $args['ate_end'];
        $return = true;
        $workplace = BeanFactory::getBean('Workplaces', $workplace_id);
        if ($workplace) {
            $workplace->load_relationship('workplaces_allocations');
            $allocations = $workplace->workplaces_allocations->getBeans();
            if(is_array($allocations)){
                foreach($allocations as $allocation_id => $allocation){
                    $start_date = strtotime($date_start);
                    $end_date = strtotime($date_end);
                    $from_date = strtotime($allocation->date_from);
                    $to_date = strtotime($allocation->date_to);
                    if (empty($to_date)) {
                        if ($start_date >= $from_date) {
                            return true;
                        }

                    } else if ($start_date >= $from_date && $end_date <= $to_date) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        }
        return $return;
    }

    public function canChangeWorkScheduleStatus($args)
    {
        $id = $args['id'];
        $status = $args['status'];
        $return = true;
        $work_schedule = BeanFactory::getBean('WorkSchedules', $id);
        if ($status == 'closed' && $work_schedule->canBeConfirmed() != "1") {
            $return = false;
        }

        return $return;

    }

    public function validateDelegationDurationValue($args)
    {
        if(is_array($args)){
            $delegation_duration = unformat_number($args['delegation_duration']);
            if (!empty($delegation_duration)) {
                if (is_numeric($delegation_duration) && $delegation_duration >= 0) {
                    return true;
                } else {
                    return false;
                }
            }
        }
        return true;
    }

    public function getActiveWorkplaces($args) {
        global $timedate;
        $employee = BeanFactory::getBean('Employees', $args['assigned_user_id']);
        $date_start = $timedate->to_db_date($args['date_start']);
        $date_end = $timedate->to_db_date($args['date_end']);
        if (empty($employee->id)) {
            return;
        }   
        return $employee->getActiveWorkplaces(null, $date_start, $date_end);
    }
}


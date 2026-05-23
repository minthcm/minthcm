<?php

use MintHCM\Lib\MintLogic\Exceptions\ValidationException;
use MintHCM\Lib\MintLogic\Hook;
use MintHCM\Lib\MintLogic\Validators\IsInRange;

return [
    'rules' => [
        [
            'hooks' => [Hook::ALL],
            'triggerFields' => ['date_start', 'date_end'],
            'trigger' => true,
            'logic' => [
                'validation' => [
                    'date_start' => [
                        function ($bean) {
                            $date_start = empty($bean->date_start) ? null : new DateTime($bean->date_start);
                            $date_end = empty($bean->date_end) ? null : new DateTime($bean->date_end);
                            if ($date_end && $date_start > $date_end) {
                                throw new ValidationException('LBL_START_DATE_AFTER_END_DATE');
                            }
                        },
                    ],
                ],
            ],
        ],
        [
            'hooks' => [Hook::ALL],
            'triggerFields' => ['spent_time'],
            'trigger' => true,
            'logic' => [
                'validation' => [
                    'spent_time' => [
                        function ($bean) {
                            IsInRange::validate($bean, 'spent_time', 0, 1000);
                        },
                    ],
                ],
            ],
        ],
        [
            'hooks' => [Hook::ALL],
            'triggerFields' => ['remaining_hours'],
            'trigger' => true,
            'logic' => [
                'validation' => [
                    'remaining_hours' => [
                        function ($bean) {
                            IsInRange::validate($bean, 'remaining_hours', 0, 1000);
                        },
                    ],
                ],
            ],
        ],
        'getCountOfSpendTimeRecordsInGivenFrame' => [
            'hooks' => [Hook::ALL],
            'triggerFields' => ['date_start'],
            'trigger' => true,
            'logic' => [
                'validation' => [
                    'date_start' => [
                        function ($bean) {
                            global $timedate;
                            if (empty($bean->workschedule_id) || empty($bean->date_start) || empty($bean->date_end)) {
                                return;
                            }
                            $date_start = $timedate->to_db($bean->date_start);
                            $date_end = $timedate->to_db($bean->date_end);
                            if (empty($date_start) || empty($date_end)) {
                                return;
                            }
                            $db = DBManagerFactory::getInstance();
                            $id_where = '';
                            if (isset($bean->id)) {
                                $id_where = "st.id != '{$bean->id}' AND ";
                            }
                            $sql = "SELECT COUNT(st.id) AS count FROM workschedules_spenttime ws "
                                . "LEFT JOIN spenttime st ON "
                                . "ws.spenttime_id=st.id "
                                . "AND ws.workschedule_id = '{$bean->workschedule_id}' WHERE "
                                . "st.deleted = 0 AND "
                                . $id_where
                                . "((st.date_start <= '{$date_start}' AND st.date_end > '{$date_start}') OR "
                                . "(st.date_start < '{$date_end}' AND st.date_end >= '{$date_end}') OR "
                                . "(st.date_start < '{$date_start}' AND st.date_end > '{$date_start}') OR "
                                . "(st.date_start > '{$date_start}' AND st.date_end < '{$date_end}'))";
                            $count = intval($db->getOne($sql));
                            if ($count != 0) {
                                throw new ValidationException('LBL_SPENT_TIME_RECORD_FOR_THIS_PERIOD_ALREADY_EXISTS');
                            }
                        },
                    ],
                ],
            ],
        ],
        'canLogToWorkOffSchedule' => [
            'hooks' => [Hook::ALL],
            'triggerFields' => ['workschedule_name'],
            'trigger' => true,
            'logic' => [
                'validation' => [
                    'date_start' => [
                        function ($bean) {
                            $db = DBManagerFactory::getInstance();
                            $work_off_types = array(
                                'holiday',
                                'sick',
                                'sick_care',
                                'occasional_leave',
                                'leave_at_request',
                                'overtime',
                                'excused_absence',
                            );
                            if (!empty($bean->workschedule_id)) {
                                $sql = "SELECT type
                                        FROM workschedules
                                        WHERE
                                            deleted = 0 AND
                                            id = {$db->quoted($bean->workschedule_id)}
                                ";
                                $get_one = $db->getOne($sql);
                                if (!empty($get_one) && in_array($get_one, $work_off_types)) {
                                    throw new ValidationException('LBL_ERR_CANT_LOG_TO_WORK_OFF_SCHEDULE');
                                }
                            }
                        },
                    ],
                ],
            ],
        ],
    ],
];

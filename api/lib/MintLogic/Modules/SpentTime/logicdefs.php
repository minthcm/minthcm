<?php

use MintHCM\Lib\MintLogic\Exceptions\ValidationException;
use MintHCM\Lib\MintLogic\Hook;
use MintHCM\Lib\MintLogic\Modules\SpentTime\Updaters\SetDatesFromWorkscheduleUpdater;
use MintHCM\Lib\MintLogic\Modules\SpentTime\Validators\SpentTimeOverlapValidator;
use MintHCM\Lib\MintLogic\Validators\IsInRange;

return [
    'rules' => [
        'setDatesFromWorkschedule' => [
            'hooks' => [Hook::ALL, Hook::CHANGE],
            'triggerFields' => ['workschedule_name', 'workschedule_id'],
            'trigger' => true,
            'logic' => [
                'update' => new SetDatesFromWorkscheduleUpdater(),
            ],
        ],
        'calculateSpentTime' => [
            'hooks' => [Hook::ALL, Hook::CHANGE],
            'triggerFields' => ['date_start', 'date_end'],
            'trigger' => true,
            'logic' => [
                'update' => function ($bean) {
                    if (empty($bean->date_start) || empty($bean->date_end)) {
                        return [];
                    }
                    $date_start = new DateTime($bean->date_start);
                    $date_end = new DateTime($bean->date_end);
                    if ($date_start >= $date_end) {
                        return [];
                    }
                    $diff_seconds = $date_end->getTimestamp() - $date_start->getTimestamp();
                    $hours = round($diff_seconds / 3600, 2);
                    return [
                        'spent_time' => $hours,
                    ];
                },
            ],
        ],
        'spentTimeReadonly' => [
            'hooks' => [Hook::ALL],
            'logic' => [
                'readonly' => [
                    'spent_time' => true,
                ],
            ],
        ],
        [
            'hooks' => [Hook::ALL, Hook::CHANGE],
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
                    'date_start' => SpentTimeOverlapValidator::class,
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

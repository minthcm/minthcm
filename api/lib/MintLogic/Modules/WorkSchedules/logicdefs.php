<?php

use MintHCM\Data\BeanFactory;
use MintHCM\Lib\MintLogic\Exceptions\ValidationException;
use MintHCM\Lib\MintLogic\Formula;
use MintHCM\Lib\MintLogic\Hook;
use MintHCM\Lib\MintLogic\Modules\WorkSchedules\Validators\WorkSchedulesDateEndValidator;
use MintHCM\Lib\MintLogic\Modules\WorkSchedules\Validators\WorkSchedulesDurationHoursValidator;
use MintHCM\Lib\MintLogic\Modules\WorkSchedules\Validators\WorkSchedulesTypeExistsValidator;
use MintHCM\Lib\MintLogic\Modules\WorkSchedules\Validators\WorkSchedulesTypeWorkOffValidator;
use MintHCM\Lib\MintLogic\Modules\WorkSchedules\Validators\WorkSchedulesWorkplaceValidator;

return [
    'rules' => [
        'init' => [
            'hooks' => [Hook::ALL],
            'logic' => [
                'visible' => [
                    'occasional_leave_type' => false,
                    'comments' => false,
                    'delegation_name' => false,
                    'workplace_name' => false,
                ],
                'required' => [
                    'occasional_leave_type' => false,
                    'comments' => false,
                    'delegation_name' => false,
                ],
                'update' => function ($bean) {
                    $return = [];
                    if (empty($bean->date_start) && empty($bean->date_end)) {
                        $return['date_start'] = ((new SugarDateTime('today 08:00'))->asDb(true));
                        $return['date_end'] = (new SugarDateTime('today 16:00'))->asDb(true);
                    }
                    return $return;
                },
            ],
        ],
        [
            'hooks' => [Hook::ALL],
            'triggerFields' => ['type', 'date_start', 'date_end', 'duration_hours', 'duration_minutes'],
            'trigger' => Formula::notEmpty('$type'),
            'logic' => [
                'validation' => [
                    'type' => WorkSchedulesTypeExistsValidator::class,
                    'date_end' => WorkSchedulesDateEndValidator::class,
                    'duration_hours' => WorkSchedulesDurationHoursValidator::class,
                    //TODO validateWorkScheduleCreatedByPeriodicity - validate repeat_panel
                ],
            ],
        ],
        [
            'hooks' => [Hook::INIT],
            'triggerFields' => ['type'],
            'trigger' => Formula::notInArray('$type', ['holiday', 'overtime', 'home']),
            'logic' => [
                'visible' => [
                    'supervisor_acceptance' => false,
                ],
            ],
        ],
        [
            'hooks' => [Hook::INIT, Hook::CHANGE],
            'triggerFields' => ['status'],
            'trigger' => Formula::equals('$status', 'closed'),
            'logic' => [
                'validation' => [
                    'status' => [
                        function ($bean) {
                            if ('closed' === $bean->status && $bean->canBeConfirmed() != '1') {
                                throw new ValidationException('LBL_ERR_CANNOT_CHANGE_WORK_SCHEDULE_STATUS');
                            }
                        },
                    ],
                ],
            ],
        ],
        [
            'hooks' => [Hook::INIT, Hook::CHANGE],
            'triggerFields' => ['type'],
            'trigger' => Formula::notEmpty('$type'),
            'logic' => [
                'validation' => [
                    'type' => WorkSchedulesTypeWorkOffValidator::class,
                ],
            ],
        ],
        [
            'hooks' => [Hook::INIT, Hook::CHANGE],
            'triggerFields' => ['type'],
            'trigger' => Formula::equals('$type', 'home'),
            'logic' => [
                'visible' => [
                    'delegation_name' => true,
                ],
            ],
        ],
        [
            'hooks' => [Hook::INIT, Hook::CHANGE],
            'triggerFields' => ['type', 'workplace_name'],
            'trigger' => Formula::equals('$type', 'office'),
            'logic' => [
                'visible' => [
                    'workplace_name' => true,
                ],
                'validation' => [
                    'workplace_name' => WorkSchedulesWorkplaceValidator::class,
                ],
                'update' => function ($bean) {
                    if (empty($bean->workplace_id) || empty($bean->workplace_name)) {
                        global $timedate;
                        $employee = BeanFactory::getBean('Employees', $bean->assigned_user_id);
                        $date_start = $timedate->to_db_date($bean->date_start);
                        $date_end = $timedate->to_db_date($bean->date_end);
                        if (empty($employee->id)) {
                            return [];
                        }
                        $activeWorkplace = $employee->getActiveWorkplaces(null, $date_start, $date_end);
                        if (!empty($activeWorkplace)) {
                            return [];
                        }
                        return [
                            'workplace_id' => $activeWorkplace['id'],
                            'workplace_name' => $activeWorkplace['name'],
                        ];
                    }
                },
            ],
        ],
        [
            'hooks' => [Hook::INIT],
            'triggerFields' => ['type'],
            'trigger' => Formula::notEquals('$type', 'delegation'),
            'logic' => [
                'visible' => [
                    'delegation_duration' => false,
                ],
                'required' => [
                    'delegation_duration' => false,
                ],
            ],
        ],
        [
            'hooks' => [Hook::INIT, Hook::CHANGE],
            'triggerFields' => ['type', 'delegation_duration'],
            'trigger' => Formula::equals('$type', 'delegation'),
            'logic' => [
                'visible' => [
                    'delegation_duration' => true,
                ],
                'required' => [
                    'delegation_duration' => true,
                ],
                'validation' => [
                    'delegation_duration' => [
                        function ($bean) {
                            $delegation_duration = unformat_number($bean->delegation_duration);
                            if (
                                !empty($delegation_duration)
                                && (
                                    !is_numeric($delegation_duration)
                                    || $delegation_duration < 0
                                )
                            ) {
                                throw new ValidationException('LBL_ERR_DELEGATION_DURATION_NOT_VALID');
                            }
                        },
                    ],
                ],
                'update' => function ($bean) {
                    if (!empty($bean->delegation_duration)) {
                        $precision = 2;
                        $delegation_duration = unformat_number($bean->delegation_duration);
                        return [
                            'delegation_duration' => format_number($delegation_duration, $precision, $precision),
                        ];
                    }
                },
            ],
        ],
        [
            'hooks' => [Hook::INIT, Hook::CHANGE],
            'triggerFields' => ['type'],
            'trigger' => Formula::equals('$type', 'occasional_leave'),
            'logic' => [
                'visible' => [
                    'occasional_leave_type' => true,
                ],
                'required' => [
                    'occasional_leave_type' => true,
                ],
            ],
        ],
        [
            'hooks' => [Hook::INIT, Hook::CHANGE],
            'triggerFields' => ['type'],
            'trigger' => Formula::or(
                Formula::equals('$type', 'occasional_leave'),
                Formula::equals('$type', 'excused_absence')
            ),
            'logic' => [
                'visible' => [
                    'comments' => true,
                ],
                'required' => [
                    'comments' => true,
                ],
            ],
        ],
        [
            'hooks' => [Hook::INIT, Hook::CHANGE],
            'triggerFields' => ['date_start', 'date_end'],
            'trigger' => Formula::and(
                Formula::notEmpty('$date_start'),
                Formula::notEmpty('$date_end')
            ),
            'logic' => [
                'update' => function ($bean) {
                    $start = new SugarDateTime($bean->date_start);
                    $end = new SugarDateTime($bean->date_end);
                    $startTimestamp = $start->getTimestamp();
                    $endTimestamp = $end->getTimestamp();

                    $diffInSeconds = $endTimestamp - $startTimestamp;

                    $hours = floor($diffInSeconds / 3600);
                    $minutes = floor(($diffInSeconds % 3600) / 60);
                    return [
                        'duration_hours' => $hours,
                        'duration_minutes' => $minutes,
                    ];
                },
            ],
        ],
        [
            'hooks' => [Hook::INIT, Hook::CHANGE],
            'triggerFields' => ['duration_hours', 'duration_minutes'],
            'trigger' => true,
            'logic' => [
                'update' => function ($bean) {
                    if (empty($bean->date_start)) {
                        return [];
                    }
                    if (
                        ($bean->duration_hours < 0 || $bean->duration_minutes < 0)
                        || (0 == $bean->duration_hours && 0 == $bean->duration_minutes)
                    ) {
                        return [
                            'duration_hours' => 8,
                            'duration_minutes' => 0,
                        ];
                    }
                    $start = new SugarDateTime($bean->date_start);
                    $interval = new DateInterval("PT{$bean->duration_hours}H{$bean->duration_minutes}M");
                    $start->add($interval);
                    return [
                        'date_end' => $start->asDb(false),
                    ];
                },
            ],
        ],
        [
            'hooks' => [Hook::INIT, Hook::CHANGE],
            'triggerFields' => ['assigned_user_id'],
            'trigger' => true,
            'logic' => [
                'update' => function ($bean) {
                    global $current_user; /** @var User $current_user */
                    if(!empty($bean->assigned_user_id)){
                        $user = BeanFactory::getBean('Users', $bean->assigned_user_id);
                        if(empty($user->id) || $user->id !== $bean->assigned_user_id){
                            return [];
                        }
                        return [
                            'assigned_user_id' => $user->id,
                            'assigned_user_name' => $user->name
                        ];
                    }
                    return [
                        'assigned_user_id' => $current_user->id,
                        'assigned_user_name' => $current_user->name
                    ];
                },
            ],
        ],
    ],
];

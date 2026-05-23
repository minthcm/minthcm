<?php

use MintHCM\Lib\MintLogic\Exceptions\ValidationException;
use MintHCM\Lib\MintLogic\Hook;

return [
    'rules' => [
        [
            'hooks' => [Hook::ALL],
            'triggerFields' => ['workplace_id', 'mode'],
            'trigger' => true,
            'logic' => [
                'validation' => [
                    'workplace_id' => [
                        function ($bean) {
                            /** @var Workplaces $workplace */
                            $workplace = BeanFactory::getBean('Workplaces', $bean->workplace_id);
                            if ('active' !== $workplace->availability) {
                                throw new ValidationException('LBL_ERR_WORKPLACE_STATUS');
                            }
                            if ($workplace->mode === 'permanent' && $bean->mode !== 'permanent') {
                                throw new ValidationException('LBL_ERR_WORKPLACE_STATUS');
                            }
                            if ($bean->mode === 'rotational' && $workplace->mode === 'permanent') {
                                throw new ValidationException('LBL_ERR_WORKPLACE_STATUS');
                            }
                        },
                    ],
                ],
            ],
        ],
        [
            'hooks' => [Hook::ALL],
            'triggerFields' => ['id', 'workplace_id', 'mode', 'date_from', 'date_to'],
            'trigger' => true,
            'logic' => [
                'validation' => [
                    'date_from' => [
                        function ($bean) {
                            if ($bean->mode !== 'permanent') {
                                return;
                            }
                            $from_date = empty($bean->date_from) ? null : new DateTime($bean->date_from);
                            $to_date = empty($bean->date_to) ? null : new DateTime($bean->date_to);
                            if ($to_date && $from_date > $to_date) {
                                throw new ValidationException(translate('LBL_DATE_FROM', 'Allocations') . ' ' . translate('MSG_IS_NOT_BEFORE') . ' ' . translate('LBL_DATE_TO', 'Allocations'));
                            }

                            $workplace = BeanFactory::getBean('Workplaces', $bean->workplace_id);
                            if (empty($workplace->id)) {
                                return;
                            }
                            $workplace->load_relationship('workplaces_allocations');
                            $allocations = $workplace->workplaces_allocations->getBeans();

                            foreach ($allocations as $allocation) {
                                if ($allocation->id === $bean->id) {
                                    continue;
                                }
                                $start_date = empty($allocation->date_from) ? null : new DateTime($allocation->date_from);
                                $end_date = empty($allocation->date_to) ? null : new DateTime($allocation->date_to);

                                if (empty($end_date) && empty($to_date)) {
                                    throw new ValidationException('LBL_ERR_WORKPLACE_PERIODS');
                                }
                                if (empty($end_date) && !empty($to_date) && ($from_date > $start_date || $to_date > $start_date)) {
                                    throw new ValidationException('LBL_ERR_WORKPLACE_PERIODS');
                                }
                                if (!empty($end_date) && empty($to_date) && $from_date <= $end_date) {
                                    throw new ValidationException('LBL_ERR_WORKPLACE_PERIODS');
                                }
                                if (
                                    $end_date
                                    && $to_date
                                    && (
                                        ($end_date >= $from_date && $start_date <= $from_date)
                                        || ($end_date <= $from_date && $start_date >= $from_date)
                                    )
                                ) {
                                    throw new ValidationException('LBL_ERR_WORKPLACE_PERIODS');
                                }
                            }
                        },
                    ],
                ],
            ],
        ],
        [
            'hooks' => [Hook::ALL],
            'triggerFields' => ['date_from', 'date_to'],
            'trigger' => true,
            'logic' => [
                'validation' => [
                    'date_to' => [
                        function ($bean) {
                            $from_date = empty($bean->date_from) ? null : new DateTime($bean->date_from);
                            $to_date = empty($bean->date_to) ? null : new DateTime($bean->date_to);
                            if ($from_date && $to_date && $from_date > $to_date) {
                                throw new ValidationException(translate('LBL_DATE_FROM', 'Allocations') . ' ' . translate('MSG_IS_NOT_BEFORE') . ' ' . translate('LBL_DATE_TO', 'Allocations'));
                            }
                        },
                    ],
                ],
            ],
        ],
    ],
];

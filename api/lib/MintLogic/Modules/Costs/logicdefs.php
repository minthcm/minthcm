<?php

use MintHCM\Lib\MintLogic\Formula;
use MintHCM\Lib\MintLogic\Hook;
use MintHCM\Lib\MintLogic\Exceptions\ValidationException;

return [
    'rules' => [
        'init' => [
            'hooks' => [Hook::INIT],
            'logic' => [
                'readonly' => [
                    'assigned_user_name' => true,
                ],
            ],
        ],
        'assigned' => [
            'hooks' => [Hook::INIT, Hook::CHANGE],
            'triggerFields' => ['delegation_name', 'transportation_name'],
            'logic' => [
                'update' => function ($bean) {
                    global $current_user; /** @var User $current_user */
                    if ('transport' == $bean->type && !empty($bean->transportation_id)) {
                        $transportation = BeanFactory::getBean('Transportations', $bean->transportation_id);
                        if ($transportation && $transportation->id) {
                            return [
                                'assigned_user_id' => $transportation->assigned_user_id,
                                'assigned_user_name' => $transportation->assigned_user_name,
                            ];
                        }
                    } else {
                        $delegation = BeanFactory::getBean('Delegations', $bean->delegation_id);
                        if ($delegation && $delegation->id) {
                            return [
                                'assigned_user_id' => $delegation->assigned_user_id,
                                'assigned_user_name' => $delegation->assigned_user_name,
                            ];
                        }
                    }
                    return [];
                },
            ],
        ],
        'accommodation' => [
            'hooks' => [Hook::ALL, Hook::CHANGE],
            'triggerFields' => ['type'],
            'trigger' => Formula::notInArray('$type', ['accommodation']),
            'logic' => [
                'visible' => [
                    'accommodation_no' => false,
                ],
            ],
        ],
        'type_of_meal' => [
            'hooks' => [Hook::ALL, Hook::CHANGE],
            'triggerFields' => ['type'],
            'trigger' => Formula::notInArray('$type', ['restaurant']),
            'logic' => [
                'visible' => [
                    'type_of_meal' => false,
                ],
            ],
        ],
        'delegation' => [
            'hooks' => [Hook::ALL, Hook::CHANGE],
            'triggerFields' => ['type'],
            'trigger' => Formula::inArray('$type', ['transport']),
            'logic' => [
                'visible' => [
                    'delegation_name' => false,
                ],
            ],
        ],
        'transportation' => [
            'hooks' => [Hook::ALL, Hook::CHANGE],
            'triggerFields' => ['type'],
            'trigger' => Formula::notInArray('$type', ['transport']),
            'logic' => [
                'visible' => [
                    'transportation_name' => false,
                ],
            ],
        ],
        'currency_validate' => [
            'hooks' => [Hook::ALL],
            'triggerFields' => ['currency_id'],
            'trigger' => Formula::notEmpty('$currency_id'),
            'logic' => [
                'validation' => [
                    'currency_id' => [
                        function ($bean) {
                            if ('-99' == $bean->currency_id) {
                                return true;
                            }
                            if (!empty($bean->delegation_id)) {
                                $delegation_id = $bean->delegation_id;
                            } else if (!empty($bean->transportation_id)) {
                                $transport = BeanFactory::getBean('Transportations', $bean->transportation_id);
                                $delegation_id = $transport->delegation_id;
                            } else {
                                return true;
                            }

                            $delegation = BeanFactory::getBean('Delegations', $delegation_id);
                            $delegation_locale = BeanFactory::getBean('DelegationsLocale', $delegation->delegation_locale_id);
                            $locale_currency = $delegation_locale->currency_id;
                            if ($bean->currency_id != $locale_currency) {
                                throw new ValidationException('LBL_CURRENCY_LOCALE_NOT_MATCHING');
                            }
                        },
                    ],
                ],
            ],
        ],
    ],
];

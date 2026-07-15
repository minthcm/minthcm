<?php
use MintHCM\Lib\MintLogic\Hook;
use MintHCM\Lib\MintLogic\Formula;
use MintHCM\Lib\MintLogic\Modules\Delegations\Validators\DelegationValidator;
use MintHCM\Lib\MintLogic\Exceptions\ValidationException;
use MintHCM\Data\BeanFactory;
use MintHCM\Utils\LegacyConnector;

return [
    'bean' => [
        'validation' => [
            DelegationValidator::class,
        ],
    ],
    'rules' => [
        'init' => [
            'hooks' => [Hook::INIT],
            'logic' => [
                'readonly' => [
                    'transport_cost_usdollar' => true,
                    'regiments_usdollar' => true,
                    'accommodation_lump_sum_usdollar' => true,
                    'total_accommodation_usdollar' => true,
                    'other_usdollar' => true,
                    'total_expenses_usdollar' => true,
                    'obtained_sum_usdollar' => true,
                    'return_sum_usdollar' => true,
                    'payoff_sum_usdollar' => true,
                    'currency_id' => true,
                ],
            ],
        ],
        'currency' => [
            'hooks' => [Hook::ALL, Hook::CHANGE],
            'triggerFields' => ['delegation_locale_name'],
            'logic' => [
                'update' => function ($bean) {
                    $locale = BeanFactory::getBean('DelegationsLocale', $bean->delegation_locale_id);
                    return [
                        'currency_id' => $locale->currency_id,
                    ];
                },
            ],
        ],
        'exchange_rate' => [
            'hooks' => [Hook::ALL, Hook::CHANGE],
            'triggerFields' => ['currency_id'],
            'trigger' => Formula::inArray('$currency_id', ['', '-99']),
            'logic' => [
                'visible' => [
                    'exchange_rate' => false,
                ],
                // 'update' => function ($bean) {
                //     if(empty($bean->exchange_rate)){
                //         return [
                //             'exchange_rate' => 1,
                //         ];
                //     }
                //     return [];
                // },
            ],
        ],
        'date_order' => [
            'hooks' => [Hook::ALL, Hook::CHANGE],
            'triggerFields' => ['start_date', 'end_date'],
            'trigger' => true,
            'logic' => [
                'validation' => [
                    'start_date' => [
                        function ($bean) {
                            $start_date = empty($bean->start_date) ? null : new DateTimeImmutable($bean->start_date, new DateTimeZone('UTC'));
                            $end_date = empty($bean->end_date) ? null : new DateTimeImmutable($bean->end_date, new DateTimeZone('UTC'));
                            if ($start_date && $end_date && $start_date > $end_date) {
                                throw new ValidationException(LegacyConnector::callFunction('translate', 'LBL_START_DATE', 'Delegations') . ' ' . LegacyConnector::callFunction('translate', 'MSG_IS_NOT_BEFORE') . ' ' . LegacyConnector::callFunction('translate', 'LBL_END_DATE', 'Delegations'));
                            }
                        },
                    ],
                    'end_date' => [
                        function ($bean) {
                            $start_date = empty($bean->start_date) ? null : new DateTimeImmutable($bean->start_date, new DateTimeZone('UTC'));
                            $end_date = empty($bean->end_date) ? null : new DateTimeImmutable($bean->end_date, new DateTimeZone('UTC'));
                            if ($start_date && $end_date && $start_date > $end_date) {
                                throw new ValidationException(LegacyConnector::callFunction('translate', 'LBL_START_DATE', 'Delegations') . ' ' . LegacyConnector::callFunction('translate', 'MSG_IS_NOT_BEFORE') . ' ' . LegacyConnector::callFunction('translate', 'LBL_END_DATE', 'Delegations'));
                            }
                        },
                    ],
                ],
            ],
        ],
    ],
];

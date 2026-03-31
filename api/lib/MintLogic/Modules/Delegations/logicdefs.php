<?php
use MintHCM\Lib\MintLogic\Hook;
use MintHCM\Lib\MintLogic\Formula;
use MintHCM\Lib\MintLogic\Modules\Delegations\Validators\DelegationValidator;

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
                    'obtained_sum_usdollars' => true,
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
    ],
];

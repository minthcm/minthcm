<?php

use MintHCM\Lib\MintLogic\Formula;
use MintHCM\Lib\MintLogic\Hook;
use MintHCM\Lib\MintLogic\Modules\TermsOfEmployment\Validators\ContractTermDatesReadonlyValidator;
use MintHCM\Lib\MintLogic\Modules\TermsOfEmployment\Validators\LastTermClosedValidator;
use MintHCM\Lib\MintLogic\Modules\TermsOfEmployment\Validators\TermDatesValidator;

return [
    'bean' => [
        'validation' => [
            LastTermClosedValidator::class,
            TermDatesValidator::class,
        ],
    ],
    'rules' => [
        'init' => [
            'hooks' => [Hook::INIT],
            'logic' => [
                'update' => function ($bean) {
                    global $current_user; /** @var User $current_user */
                    if (empty($bean->assigned_user_id)) {
                        return [
                            'assigned_user_id' => $current_user->id,
                            'assigned_user_name' => $current_user->name,
                        ];
                    }
                    return [];
                },
            ],
        ],
        [
            'hooks' => [Hook::ALL],
            'triggerFields' => ['contract_name', 'term_starting_date', 'term_ending_date'],
            'trigger' => true,
            'logic' => [
                'readonly' => ContractTermDatesReadonlyValidator::class,
            ],
        ],
        [
            'hooks' => [Hook::ALL],
            'triggerFields' => ['term_ending_date', 'term_starting_date', 'contract_name'],
            'trigger' => Formula::or(
                Formula::notEmpty('$term_ending_date'),
                Formula::notEmpty('$term_starting_date'),
                Formula::notEmpty('$contract_name')
            ),
            'logic' => [
                'validation' => [
                    'term_ending_date' => TermDatesValidator::class,
                ],
            ],
        ],
    ],
];

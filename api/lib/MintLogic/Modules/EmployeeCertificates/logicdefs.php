<?php

use MintHCM\Lib\MintLogic\Formula;
use MintHCM\Lib\MintLogic\Hook;
use MintHCM\Lib\MintLogic\Validators\IsUnique;

return [
    'bean' => [
        'validation' => [
            Formula::validate(
                Formula::and(
                    Formula::empty('$employee_id'),
                    Formula::empty('$candidate_id')
                ),
                'LBL_CANDIDATE_OR_EMPLOYEE_HAVE_TO_BE_SET'
            ),
        ],
    ],
    'rules' => [
        [
            'hooks' => [Hook::ALL, Hook::CHANGE],
            'triggerFields' => ['employee_id', 'candidate_id', 'certificate_id'],
            'trigger' => true,
            'logic' => [
                'update' => function ($bean) {
                    $personName = !empty($bean->employee_id) ? $bean->employee_name : $bean->candidate_name;
                    return [
                        'name' => trim($bean->certificate_name . ' - ' . $personName),
                    ];
                },
            ],
        ],
    ],
];

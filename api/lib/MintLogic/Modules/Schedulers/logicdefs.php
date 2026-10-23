<?php

use MintHCM\Data\MintDateTime;
use MintHCM\Lib\MintLogic\Formula;
use MintHCM\Lib\MintLogic\Hook;

return [
    'rules' => [
        [
            'hooks' => [Hook::ALL],
            'logic' => [
                'readonly' => [
                    'last_run' => true,
                ],
            ],
        ],
        [
            'hooks' => [Hook::INIT],
            'trigger' => Formula::empty('$id'),
            'logic' => [
                'update' => function ($bean) {
                    if (!empty($bean->date_time_start)) {
                        return [];
                    }
                    return [
                        'date_time_start' => (new MintDateTime())->format('Y-m-d H:i:s'),
                    ];
                },
            ],
        ],
        [
            'hooks' => [Hook::INIT, Hook::CHANGE],
            'triggerFields' => ['job'],
            'trigger' => Formula::equals('$job', 'url::'),
            'logic' => [
                'visible' => [
                    'job_url' => true,
                ],
            ],
        ],
        [
            'hooks' => [Hook::INIT, Hook::CHANGE],
            'triggerFields' => ['job'],
            'trigger' => Formula::notEquals('$job', 'url::'),
            'logic' => [
                'visible' => [
                    'job_url' => false,
                ],
            ],
        ],
    ],
];

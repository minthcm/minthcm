<?php

use MintHCM\Lib\MintLogic\Formula;
use MintHCM\Lib\MintLogic\Hook;

return [
    'rules' => [
        'automaticUpdateOn' => [
            'hooks' => [Hook::ALL],
            'triggerFields' => ['automatic_update'],
            'trigger' => Formula::equals('$automatic_update', true),
            'logic' => [
                'visible' => [
                    'kreport_name' => true,
                ],
                'required' => [
                    'kreport_name' => true,
                ],
            ],
        ],
        'automaticUpdateOff' => [
            'hooks' => [Hook::ALL],
            'triggerFields' => ['automatic_update'],
            'trigger' => Formula::equals('$automatic_update', false),
            'logic' => [
                'visible' => [
                    'kreport_name' => false,
                ],
                'required' => [
                    'kreport_name' => false,
                ],
            ],
        ],
        'showDomain' => [
            'hooks' => [Hook::ALL],
            'triggerFields' => ['list_type'],
            'trigger' => Formula::equals('$list_type', 'exempt_domain'),
            'logic' => [
                'visible' => [
                    'domain_name' => true,
                ],
            ],
        ],
        'hideDomain' => [
            'hooks' => [Hook::ALL],
            'triggerFields' => ['list_type'],
            'trigger' => Formula::notEquals('$list_type', 'exempt_domain'),
            'logic' => [
                'visible' => [
                    'domain_name' => false,
                ],
            ],
        ],
    ],
];

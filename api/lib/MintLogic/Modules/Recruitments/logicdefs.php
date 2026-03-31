<?php

use MintHCM\Lib\MintLogic\Hook;
use MintHCM\Lib\MintLogic\Formula;

return [
    'rules' => [
        [
            'hooks' => [
                Hook::INIT,
                Hook::CHANGE,
            ],
            'triggerFields' => ['recruitment_type'],
            'trigger' => Formula::equals('$recruitment_type', 'continuous'),
            'logic' => [
                'visible' => [
                    'vacancy' => false,
                ]
            ],
        ]
    ],
];
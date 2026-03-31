<?php

use MintHCM\Data\BeanFactory;
use MintHCM\Lib\MintLogic\Formula;
use MintHCM\Lib\MintLogic\Hook;

return [
    'rules' => [
        'init' => [
            'hooks' => [Hook::INIT],
            'logic' => [
                'readonly' => [
                    'linkedin' => true,
                ],
            ],
        ],
        'notHired' => [
            'hooks' => [Hook::ALL, Hook::CHANGE],
            'triggerFields' => ['status'],
            'trigger' => Formula::notInArray('$status', ['Acceptance', 'Hired']),
            'logic' => [
                'visible' => [
                    'work_start' => false,
                    'training_date' => false,
                ],
            ],
        ],
        'rejection' => [
            'hooks' => [Hook::INIT, Hook::CHANGE],
            'triggerFields' => ['status'],
            'trigger' => Formula::notInArray('$status', ['Rejected']),
            'logic' => [
                'visible' => [
                    'reason_for_rejection' => false,
                ],
            ],
        ],
        'originalCandidature' => [
            'hooks' => [Hook::INIT, Hook::CHANGE],
            'triggerFields' => ['route_of_acquisition'],
            'trigger' => Formula::inArray('$route_of_acquisition', ['', 'original_candidature']),
            'logic' => [
                'visible' => [
                    'original_candidature_name' => false,
                ],
            ],
        ],
        'linkedin' => [
            'hooks' => [Hook::INIT, Hook::CHANGE],
            'triggerFields' => ['parent_id', 'parent_type'],
            'trigger' => Formula::and(Formula::equals('$parent_type', 'Candidates')),
            'logic' => [
                'update' => function ($bean) {
                    $candidate = BeanFactory::getBean('Candidates', $bean->parent_id);
                    return ['linkedin' => $candidate->linkedin ?? ''];
                },
            ],
        ],
    ],
];

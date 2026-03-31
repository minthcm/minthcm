<?php
use MintHCM\Lib\MintLogic\Hook;
use MintHCM\Lib\MintLogic\Formula;

return [
    'rules' => [
        'assigned_user_readonly' => [
            'hooks' => [Hook::ALL],
            'logic' => [
                'readonly' => [
                    'assigned_user_name' => true,
                ],
            ],
        ],
        'other_transportation_show' => [
            'hooks' => [Hook::ALL, Hook::CHANGE],
            'triggerFields' => ['type'],
            'trigger' => Formula::equals('$type', 'other'),
            'logic' => [
                'visible' => [
                    'other_transportation' => true,
                ],
            ],
        ],
        'other_transportation_hide' => [
            'hooks' => [Hook::ALL, Hook::CHANGE],
            'triggerFields' => ['type'],
            'trigger' => Formula::notEquals('$type', 'other'),
            'logic' => [
                'visible' => [
                    'other_transportation' => false,
                ],
            ],
        ],
        'assigned_user_calculated' => [
            'hooks' => [Hook::INIT, Hook::CHANGE],
            'triggerFields' => ['delegation_id'],
            'logic' => [
                'update' => function ($bean) {
                    $delegation_id = $bean->delegation_id;
                    if (!empty($delegation_id)) {
                        $delegation = MintHCM\Data\BeanFactory::getBean('Delegations', $delegation_id);
                        if ($delegation && !empty($delegation->assigned_user_id)) {
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
    ],
];

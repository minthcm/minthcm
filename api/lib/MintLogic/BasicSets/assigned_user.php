<?php

use MintHCM\Lib\MintLogic\Formula;
use MintHCM\Lib\MintLogic\Hook;

return [
    'rules' => [
        'assigned_user_init' => [
            'hooks' => [Hook::INIT],
            'trigger' => Formula::empty('$id'),
            'logic' => [
                'update' => function ($bean) {
                    global $current_user;
                    if (!empty($bean->field_defs['assigned_user_name']) && empty($bean->assigned_user_id)) {
                        return [
                            'assigned_user_id' => $current_user->id,
                            'assigned_user_name' => $current_user->name,
                        ];
                    }
                    return [];
                }
            ]
        ],
    ],
];

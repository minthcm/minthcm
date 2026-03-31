<?php

use MintHCM\Lib\MintLogic\Hook;

return [
    'rules' => [
        'readonly_from_vardefs' => [
            'hooks' => [Hook::INIT],
            'trigger' => true,
            'logic' => [
                'readonly' => function ($bean) {
                    $readonly = [];
                    foreach ($bean->field_defs as $field => $def) {
                        if (isset($def['readonly']) && true === $def['readonly']) {
                            $readonly[$field] = true;
                        }
                    }
                    return $readonly;
                }
            ]
        ],
    ],
];

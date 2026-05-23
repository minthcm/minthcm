<?php

use MintHCM\Lib\MintLogic\Hook;

return [
    'rules' => [
        'required_from_vardefs' => [
            'hooks' => [Hook::INIT],
            'trigger' => true,
            'logic' => [
                'required' => function ($bean) {
                    $required = [];
                    foreach ($bean->field_defs as $field => $def) {
                        if (!empty($def['required'])) {
                            $required[$field] = true;
                        }
                    }
                    return $required;
                }
            ]
        ],
    ],
];

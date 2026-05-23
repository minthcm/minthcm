<?php

use MintHCM\Lib\MintLogic\Hook;

return [
    'rules' => [
        'check_relate_module_access' => [
            'hooks' => [Hook::INIT],
            'trigger' => true,
            'logic' => [
                'readonly' => function ($bean) {
                    $readonly = [];
                    foreach ($bean->field_defs as $field => $def) {
                        if (
                            isset($def['type'])
                            && $def['type'] === 'relate'
                            && isset($def['module'])
                            && !ACLController::checkAccess($def['module'], 'list', true)
                        ) {
                            $readonly[$field] = true;
                        }
                    }
                    return $readonly;
                }
            ]
        ],
    ],
];

<?php

use MintHCM\Lib\MintLogic\Hook;

return [
    'rules' => [
        'init_parent_fields' => [
            'hooks' => [Hook::INIT],
            'trigger' => true,
            'logic' => [
                'readonly' => function ($bean) {
                    $readonly = [];
                    foreach ($bean->field_defs as $field => $def) {
                        if (
                            isset($def['type'])
                            && $def['type'] === 'parent'
                            && !empty($bean->{$def['id_name']})
                            && !empty($bean->{$def['type_name']})
                            && !ACLController::checkAccess($bean->{$def['type_name']}, 'list', true)
                        ) {
                            $readonly[$field] = true;
                        }
                    }
                    return $readonly;
                },
                'options' => function ($bean) {
                    global $app_list_strings;
                    $parentOptions = [];
                    foreach ($bean->field_defs as $field => $def) {
                        if (
                            isset($def['type'])
                            && $def['type'] === 'parent'
                            && !empty($def['options'])
                            && !empty($app_list_strings[$def['options']])
                        ) {
                            $options = $app_list_strings[$def['options']];
                            foreach ($options as $module => $label) {
                                if (
                                    !ACLController::checkAccess($module, 'list', true)
                                    && $bean->{$def['type_name']} !== $module
                                ) {
                                    unset($options[$module]);
                                }
                            }
                            $parentOptions[$field] = $options;
                        }
                    }
                    return $parentOptions;
                }
            ]
        ],
    ],
];
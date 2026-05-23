<?php

use MintHCM\Lib\MintLogic\Hook;

return [
    'rules' => [
        'init_function_options' => [
            'hooks' => [Hook::INIT],
            'trigger' => true,
            'logic' => [
                'options' => function ($bean) {
                    $functionOptionsFields = [];
                    foreach ($bean->field_defs as $field => $vardef) {
                        if (isset($vardef['type']) && in_array($vardef['type'], ['enum', 'multienum']) && isset($vardef['function'])) {
                            if (!empty($vardef['function']['include'])) {
                                require_once $vardef['function']['include'];
                            }
                            $function_name = $vardef['function']['name'] ?? '';
                            if (!empty($function_name)) {
                                $result = call_user_func($function_name, $this->bean, $field, $this->bean->{$field} ?? '', 'MintLogic', $vardef['function']['additional_params']);
                                if (!empty($result)) {
                                    $functionOptionsFields[$field] = $result;
                                }
                            }
                        }
                    }
                    return $functionOptionsFields;
                }
            ]
        ],
    ],
];

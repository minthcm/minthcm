<?php

use MintHCM\Lib\MintLogic\Exceptions\ValidationException;
use MintHCM\Lib\MintLogic\Formula;
use MintHCM\Lib\MintLogic\Hook;

return [
    'rules' => [
        [
            'hooks' => [Hook::ALL],
            'triggerFields' => ['room_surface'],
            'trigger' => true,
            'logic' => [
                'validation' => [
                    'room_surface' => [
                        function ($bean) {
                            /** @var Rooms $bean */
                            $value = $bean->room_surface;
                            if (empty($value) || floatval($value) <= 0) {
                                throw new ValidationException('LBL_NEGATIVE_SURFACE');
                            }
                        },
                    ],
                ],
            ],
        ],
        [
            'hooks' => [Hook::ALL],
            'triggerFields' => ['security_group_id'],
            'trigger' => Formula::notEmpty('$security_group_id'),
            'logic' => [
                'validation' => [
                    'security_group_name' => [
                        function ($bean) {
                            /** @var SecurityGroup $group */
                            $sg_id = $bean->security_group_id;
                            $group = MintHCM\Data\BeanFactory::getBean('SecurityGroups', $sg_id);
                            if (empty($group->id) || $group->group_type !== 'business_unit') {
                                throw new ValidationException('LBL_ERR_CANT_SELECT_SEC_GROUP');
                            }
                        },
                    ],
                ],
            ],
        ],

    ],
];

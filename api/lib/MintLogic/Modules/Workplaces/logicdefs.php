<?php
use MintHCM\Lib\MintLogic\Exceptions\ValidationException;
use MintHCM\Lib\MintLogic\Formula;
use MintHCM\Lib\MintLogic\Hook;

return [
    'rules' => [
        'canSelectRoom' => [
            'hooks' => [Hook::ALL],
            'triggerFields' => ['room_name'],
            'trigger' => Formula::notEmpty('$room_name'),
            'logic' => [
                'validation' => [
                    'room_name' => [
                        function ($bean) {
                            /** @var Workplaces $bean */
                            $room = \BeanFactory::getBean('Rooms', $bean->room_id);
                            if ($room->availability !== 'active') {
                                throw new \MintHCM\Lib\MintLogic\Exceptions\ValidationException('LBL_ERR_CANT_SELECT_ROOM');
                            }
                        },
                    ],
                ],
            ],
        ],
        'canChangeMode' => [
            'hooks' => [Hook::CHANGE],
            'triggerFields' => ['mode'],
            'trigger' => Formula::notEmpty('$mode'),
            'logic' => [
                'validation' => [
                    'mode' => [
                        function ($bean) {
                            /** @var Workplaces $bean */
                            $prev_mode = $bean->fetched_row['mode'] ?? null;
                            if (null !== $prev_mode && $prev_mode !== $bean->mode) {
                                if ($bean->load_relationship('workplaces_allocations')) {
                                    $allocations = $bean->workplaces_allocations->getBeans();
                                    $today = strtotime(date("Y-m-d"));
                                    foreach ($allocations as $allocation) {
                                        $end_date = strtotime($allocation->date_to);
                                        if (empty($end_date) || $end_date >= $today) {
                                            throw new ValidationException('LBL_ERR_CANT_CHANGE_MODE');
                                        }
                                    }
                                }
                            }
                        },
                    ],
                ],
            ],
        ],
    ],
];

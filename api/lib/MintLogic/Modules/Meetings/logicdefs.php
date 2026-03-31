<?php

use MintHCM\Data\MintDateTime;
use MintHCM\Lib\MintLogic\Formula;
use MintHCM\Lib\MintLogic\Hook;

return [
    'rules' => [
        'init' => [
            'hooks' => [Hook::INIT],
            'trigger' => Formula::empty('$id'),
            'logic' => [
                'update' => function ($bean) {
                    if (!empty($bean->date_start) || !empty($bean->date_end)) {
                        return [];
                    }
                    $now = new DateTime('now', new DateTimeZone('UTC'));
                    $minutes = (int) $now->format('i');
                    $minutes = (int) (ceil($minutes / 15) * 15);
                    if ($minutes == 60) {
                        $now->modify('+1 hour');
                        $minutes = 0;
                    }
                    $now->setTime($now->format('H'), $minutes, 0);
                    return [
                        'date_start' => $now->format('Y-m-d H:i:s'),
                        'date_end' => $now->modify('+15 minutes')->format('Y-m-d H:i:s'),
                        'date_diff' => new DateInterval('PT15M'),
                    ];
                },
            ],
        ],
        [
            'hooks' => [Hook::INIT, Hook::CHANGE],
            'triggerFields' => ['date_start'],
            'trigger' => Formula::notEmpty('$date_start'),
            'logic' => [
                'update' => function ($bean) {
                    if (empty($bean->date_diff)) {
                        return [];
                    }
                    $interval = $bean->date_diff;
                    if (!$bean->date_diff instanceof DateInterval) {
                        $spec = sprintf(
                            'P%dY%dM%dDT%dH%dM%dS',
                            $bean->date_diff['y'] ?? 0,
                            $bean->date_diff['m'] ?? 0,
                            $bean->date_diff['d'] ?? 0,
                            $bean->date_diff['h'] ?? 0,
                            $bean->date_diff['i'] ?? 0,
                            $bean->date_diff['s'] ?? 0
                        );

                        $interval = new DateInterval($spec);
                        if (!empty($bean->date_diff['invert'])) {
                            $interval->invert = 1;
                        }
                    }

                    return [
                        'date_end' => (new MintDateTime($bean->date_start))->add($interval)->format('Y-m-d H:i:s'),
                    ];
                },
            ],
        ],
        [
            'hooks' => [Hook::INIT, Hook::CHANGE],
            'triggerFields' => ['date_end'],
            'trigger' => Formula::notEmpty('$date_end'),
            'logic' => [
                'update' => function ($bean) {
                    $date_start = new MintDateTime($bean->date_start);
                    $date_end = new MintDateTime($bean->date_end);

                    $diff = $date_start->diff($date_end);
                    return [
                        'date_diff' => $diff,
                    ];
                },
            ],
        ],
    ],
];

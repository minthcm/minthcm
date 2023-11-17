<?php

namespace MintHCM\MintCLI\SystemRequirements;

class SystemRequirements
{
    public static $sysRequirements = [
        // 'apache2' => [
        //     'version' => 2.4,
        //     'command' => 'apache2 -v',
        // ],
    ];

    public static $frontendRequirements = [
        'node' => [
            'version' => 16.04,
            'command' => 'node -v',
        ],
    ];
}
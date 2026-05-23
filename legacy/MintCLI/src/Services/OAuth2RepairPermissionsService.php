<?php

namespace MintHCM\MintCLI\Services;

class OAuth2RepairPermissionsService
{
    const KEY_DIR = 'api/configs';
    const KEY_TYPES = [
        'private',
        'public'
    ];
    public function repair(): void
    {
        foreach(static::KEY_TYPES as $type){
            exec("chmod 600 " . self::KEY_DIR . '/' . $type . '.key');
        }
    }
}

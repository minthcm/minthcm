<?php
namespace MintHCM\Utils;

class EnvironmentUtils
{
    public static function getMemoryLimitInBytes(): int
    {
        $memory_limit_bytes = 0;
        if (function_exists('ini_get')) {
            $memory_limit = ini_get('memory_limit');
            if (preg_match('/^([0-9]+)([KMG]?)$/i', $memory_limit, $matches)) {
                $memory_limit_bytes = (int) $matches[1];
                $unit = strtoupper($matches[2]);
                switch ($unit) {
                    case 'G':$memory_limit_bytes *= 1024 * 1024 * 1024;
                        break;
                    case 'M':$memory_limit_bytes *= 1024 * 1024;
                        break;
                    case 'K':$memory_limit_bytes *= 1024;
                        break;
                }
            }
        }
        return intval($memory_limit_bytes);
    }

}

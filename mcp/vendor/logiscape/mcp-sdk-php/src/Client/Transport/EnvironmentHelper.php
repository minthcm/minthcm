<?php

/**
 * Model Context Protocol SDK for PHP
 *
 * (c) 2024 Logiscape LLC <https://logiscape.com>
 *
 * Based on the Python SDK for the Model Context Protocol
 * https://github.com/modelcontextprotocol/python-sdk
 *
 * PHP conversion developed by:
 * - Josh Abbott
 * - Claude 3.5 Sonnet (Anthropic AI model)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    logiscape/mcp-sdk-php 
 * @author     Josh Abbott <https://joshabbott.com>
 * @copyright  Logiscape LLC
 * @license    MIT License
 * @link       https://github.com/logiscape/mcp-sdk-php
 *
 * Filename: Client/Transport/EnvironmentHelper.php
 */

declare(strict_types=1);

namespace Mcp\Client\Transport;

/**
 * Gets default environment variables that are safe to inherit
 */
class EnvironmentHelper {
    private static array $defaultInheritedEnvVars;

    public static function initialize(): void {
        self::$defaultInheritedEnvVars = PHP_OS_FAMILY === 'Windows' 
            ? [
                'APPDATA',
                'HOMEDRIVE',
                'HOMEPATH',
                'LOCALAPPDATA',
                'PATH',
                'PROCESSOR_ARCHITECTURE',
                'SYSTEMDRIVE',
                'SYSTEMROOT',
                'TEMP',
                'USERNAME',
                'USERPROFILE',
            ]
            : [
                'HOME',
                'LOGNAME',
                'PATH',
                'SHELL',
                'TERM',
                'USER',
            ];
    }

    public static function getDefaultEnvironment(): array {
        $env = [];

        foreach (self::$defaultInheritedEnvVars as $key) {
            $value = getenv($key);
            if ($value === false) {
                continue;
            }

            if (str_starts_with($value, '()')) {
                // Skip functions, which are a security risk
                continue;
            }

            $env[$key] = $value;
        }

        return $env;
    }
}
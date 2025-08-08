<?php

namespace MintMCP\Handlers;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;

/**
 * Singleton Logger class for MintMCP
 */
class Logger
{
    private static ?MonologLogger $logger = null;

    private function __construct() {}

    private function __clone() {}

    /**
     * Get the Monolog logger instance.
     *
     * @return MonologLogger
     */
    public static function getLogger(): MonologLogger
    {
        if (self::$logger === null) {
            $logFile = './mintmcp.log';

            self::$logger = new MonologLogger('mintmcp');
            $handler = new StreamHandler($logFile, MonologLogger::DEBUG, true, 0664);
            self::$logger->pushHandler($handler);
        }
        return self::$logger;
    }
}

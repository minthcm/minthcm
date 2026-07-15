<?php

namespace MintMCP\Server;

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

class Logger
{
    private static ?LoggerInterface $fallbackLogger = null;

    public static function getLogger(): LoggerInterface
    {
        if (function_exists('logger')) {
            return logger();
        }

        if (self::$fallbackLogger instanceof LoggerInterface) {
            return self::$fallbackLogger;
        }

        self::$fallbackLogger = new class extends AbstractLogger {
            public function log($level, string|\Stringable $message, array $context = []): void
            {
                $logMessage = sprintf(
                    "[%s] [%s] %s %s\n",
                    date('Y-m-d H:i:s'),
                    strtoupper((string) $level),
                    (string) $message,
                    empty($context) ? '' : json_encode($context)
                );
                @file_put_contents(__DIR__ . '/../mintmcp.log', $logMessage, FILE_APPEND);
            }
        };

        return self::$fallbackLogger;
    }
}

<?php

/**
 * Model Context Protocol SDK for PHP
 *
 * (c) 2025 Logiscape LLC <https://logiscape.com>
 *
 * Based on the Python SDK for the Model Context Protocol
 * https://github.com/modelcontextprotocol/python-sdk
 *
 * PHP conversion developed by:
 * - Josh Abbott
 * - Claude 3.5 Sonnet (Anthropic AI model)
 * - ChatGPT o1 pro mode
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    logiscape/mcp-sdk-php 
 * @author     Josh Abbott <https://joshabbott.com>
 * @copyright  Logiscape LLC
 * @license    MIT License
 * @link       https://github.com/logiscape/mcp-sdk-php
 */

/**
 * Common initialization and helper functions for MCP Web Client
 */

declare(strict_types=1);

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors directly, we'll handle them

// Initialize session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Require Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/lib/McpWebClient.php';

// Import required classes
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\BufferHandler;

/**
 * Initialize and configure logger
 */
function initLogger(): Logger {
    $logger = new Logger('mcp-web-tester');
    
    // Create log directory if it doesn't exist
    $logDir = __DIR__ . '/logs';
    if (!file_exists($logDir)) {
        mkdir($logDir, 0755, true);
    }

    // Create a custom formatter with microsecond precision
    $dateFormat = "Y-m-d H:i:s.u";
    $output = "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n";
    $formatter = new LineFormatter($output, $dateFormat);

    // Main log file handler with rotation
    $fileHandler = new RotatingFileHandler(
        $logDir . '/mcp-web-tester.log',
        7, // Keep 7 days of logs
        Logger::DEBUG
    );
    $fileHandler->setFormatter($formatter);

    // Buffer handler for collecting logs during request
    $bufferHandler = new BufferHandler(
        new StreamHandler('php://memory'),
        0, // Buffer size (0 = unlimited)
        Logger::DEBUG,
        true, // Bubble up to other handlers
        true  // Flush on shutdown
    );
    $bufferHandler->setFormatter($formatter);

    $logger->pushHandler($fileHandler);
    $logger->pushHandler($bufferHandler);

    return $logger;
}

/**
 * Get recent log entries from the buffer with context
 */
function getBufferedLogs(Logger $logger): array {
    $logs = [];
    foreach ($logger->getHandlers() as $handler) {
        if ($handler instanceof BufferHandler) {
            $reflection = new ReflectionProperty($handler, 'buffer');
            $reflection->setAccessible(true);
            $buffer = $reflection->getValue($handler);
            
            foreach ($buffer as $record) {
                $logs[] = [
                    'timestamp' => strtotime($record['datetime']->format('Y-m-d H:i:s.u')),
                    'datetime' => $record['datetime']->format('Y-m-d H:i:s.u'),
                    'level' => $record['level_name'],
                    'message' => $record['message'],
                    'context' => $record['context'] ?? []
                ];
            }
        }
    }
    return $logs;
}

/**
 * Send JSON response with proper headers
 */
function sendJsonResponse(array $data, int $statusCode = 200): void {
    // Set security headers
    header('Content-Type: application/json');
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('X-XSS-Protection: 1; mode=block');
    
    // Set status code
    http_response_code($statusCode);
    
    // Send response
    echo json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES);
    exit;
}

/**
 * Handle uncaught exceptions
 */
function handleException(Throwable $e): void {
    global $logger;
    
    $logger->error('Uncaught exception: ' . $e->getMessage(), [
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ]);
    
    sendJsonResponse([
        'success' => false,
        'error' => 'Internal server error: ' . $e->getMessage(),
        'logs' => getBufferedLogs($logger)
    ], 500);
}

/**
 * Validate session ID using new session storage
 */
function validateSession(string $sessionId, McpWebClient $client): bool {
    if (!$client->isSessionValid($sessionId)) {
        sendJsonResponse([
            'success' => false,
            'error' => 'Invalid or expired session',
            'logs' => getBufferedLogs($GLOBALS['logger'])
        ], 401);
        return false;
    }
    return true;
}

/**
 * Get and validate JSON request body
 */
function getJsonRequestBody(): array {
    $input = file_get_contents('php://input');
    if (empty($input)) {
        sendJsonResponse([
            'success' => false,
            'error' => 'Missing request body'
        ], 400);
        exit;
    }

    try {
        return json_decode($input, true, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException $e) {
        sendJsonResponse([
            'success' => false,
            'error' => 'Invalid JSON in request body'
        ], 400);
        exit;
    }
}

// Set up exception handler
set_exception_handler('handleException');

// Initialize logger
$logger = initLogger();

// Create McpWebClient instance
$mcpClient = new McpWebClient($logger);

// No need for cleanup in shutdown function since we're not maintaining persistent connections
register_shutdown_function(function() {
    // Nothing to clean up - connections are closed after each operation
});
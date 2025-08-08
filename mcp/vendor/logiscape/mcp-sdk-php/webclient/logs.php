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
 * Endpoint for retrieving MCP log entries
 * 
 * Handles:
 * - GET: Retrieve log entries with optional filtering
 * - DELETE: Clears the logs
 */

require_once __DIR__ . '/common.php';

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    try {
        $logDir = __DIR__ . '/logs';
        $pattern = $logDir . '/mcp-web-tester-*.log';
        $files = glob($pattern);
        
        foreach ($files as $file) {
            if (unlink($file)) {
                $logger->info('Deleted log file: ' . basename($file));
            } else {
                $logger->warning('Failed to delete log file: ' . basename($file));
            }
        }

        sendJsonResponse([
            'success' => true,
            'message' => 'Logs cleared successfully'
        ]);
    } catch (Throwable $e) {
        $logger->error('Error clearing logs: ' . $e->getMessage());
        sendJsonResponse([
            'success' => false,
            'error' => 'Failed to clear logs'
        ], 500);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendJsonResponse([
        'success' => false,
        'error' => 'Method not allowed'
    ], 405);
}

function getLatestLogFile($directory) {
    $pattern = $directory . '/mcp-web-tester-*.log';
    $files = glob($pattern);
    
    if (empty($files)) {
        return null;
    }
    
    // Sort files by modified time, newest first
    usort($files, function($a, $b) {
        return filemtime($b) - filemtime($a);
    });
    
    return $files[0];
}

try {
    // Get parameters
    $sessionId = $_GET['sessionId'] ?? null;
    $since = $_GET['since'] ?? null;  // Timestamp for filtering
    $level = $_GET['level'] ?? null;  // Minimum log level
    $limit = min((int)($_GET['limit'] ?? 100), 1000);  // Max entries, capped at 1000

    // Validate session if provided
    if ($sessionId !== null && !$mcpClient->isSessionValid($sessionId)) {
        sendJsonResponse([
            'success' => false,
            'error' => 'Invalid session ID'
        ], 400);
    }

    // Validate timestamp if provided
    if ($since !== null && !is_numeric($since)) {
        sendJsonResponse([
            'success' => false,
            'error' => 'Invalid timestamp'
        ], 400);
    }

    // Validate log level if provided
    $validLevels = ['debug', 'info', 'notice', 'warning', 'error', 'critical', 'alert', 'emergency'];
    if ($level !== null && !in_array(strtolower($level), $validLevels)) {
        sendJsonResponse([
            'success' => false,
            'error' => 'Invalid log level'
        ], 400);
    }

    // Read log file
    $logDir = __DIR__ . '/logs';
    $logFile = getLatestLogFile($logDir);
    if (!file_exists($logFile)) {
        sendJsonResponse([
            'success' => true,
            'data' => [
                'entries' => [],
                'hasMore' => false
            ]
        ]);
        exit;
    }

    // Process logs
    $entries = [];
    $lineCount = 0;
    $hasMore = false;

    // Use a generator to avoid loading entire file into memory
    $logLines = function() use ($logFile) {
        $handle = fopen($logFile, 'r');
        while (!feof($handle)) {
            yield fgets($handle);
        }
        fclose($handle);
    };

    foreach ($logLines() as $line) {
        // Parse log line
        if (preg_match('/\[(.*?)\] (\w+[-\w]*)\.(\w+): (.*?)(?:\s+(\{.*\}))?\s*\[(.*?)\]$/', $line, $matches)) {
            $datetime = $matches[1];
            $timestamp = strtotime($datetime);

            // Important: Check timestamp before processing the rest
            if ($since !== null && $timestamp <= $since) {
                continue;  // Skip logs that are older than or equal to our last seen log
            }

            $channel = $matches[2];
            $logLevel = strtolower($matches[3]);
            $message = $matches[4];
            $context = [];

            // Parse context if present (the JSON object)
            if (isset($matches[5]) && !empty($matches[5])) {
                try {
                    $context = json_decode($matches[5], true) ?? [];
                } catch (\Exception $e) {
                    // Ignore JSON parsing errors in context
                }
            }

            // Add entry only if it's newer than our last seen log
            $entries[] = [
                'timestamp' => $timestamp,
                'datetime' => $datetime,
                'channel' => $channel,
                'level' => $logLevel,
                'message' => $message,
                'context' => $context
            ];

            $lineCount++;
        }
    }

    // Sort entries by timestamp (newest first)
    usort($entries, function($a, $b) {
        return $b['timestamp'] - $a['timestamp'];
    });

    // Return response
    sendJsonResponse([
        'success' => true,
        'data' => [
            'entries' => $entries,
            'hasMore' => $hasMore,
            'filters' => [
                'sessionId' => $sessionId,
                'since' => $since,
                'level' => $level,
                'limit' => $limit
            ]
        ]
    ]);

} catch (Throwable $e) {
    // Log unexpected errors and return generic message
    $logger->error('Unexpected error in logs.php', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);

    sendJsonResponse([
        'success' => false,
        'error' => 'Internal server error occurred',
        'logs' => getBufferedLogs($logger)
    ], 500);
}
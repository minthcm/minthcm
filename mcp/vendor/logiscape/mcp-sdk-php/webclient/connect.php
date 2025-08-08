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
 * Endpoint for managing MCP server connections
 * 
 * Handles:
 * - POST: Create new connection to MCP server
 * - DELETE: Close existing connection
 */

require_once __DIR__ . '/common.php';

// Basic security checks for command paths
function validateCommand(string $command): bool {
    // Block obviously dangerous commands
    $dangerousCommands = ['rm', 'sudo', 'chmod', 'chown', '>;', '|'];
    foreach ($dangerousCommands as $dangerous) {
        if (stripos($command, $dangerous) !== false) {
            return false;
        }
    }

    // Common MCP server executables
    $commonExecutables = ['node', 'npm', 'npx', 'uvx', 'python', 'python3', 'pip', 'php', 'uvicorn'];
    
    // Check if it's one of the common executables or a path to a file
    $commandBase = basename($command);
    if (in_array($commandBase, $commonExecutables)) {
        return true;
    }

    // If it's a file path, check that it exists and is executable
    if (file_exists($command)) {
        return is_executable($command);
    }

    // If command contains path separators but file doesn't exist, reject it
    if (strpos($command, '/') !== false || strpos($command, '\\') !== false) {
        return false;
    }

    // Allow other commands to support custom server executables
    return true;
}

// Only allow POST and DELETE methods
$method = $_SERVER['REQUEST_METHOD'];
if (!in_array($method, ['POST', 'DELETE'])) {
    sendJsonResponse([
        'success' => false,
        'error' => 'Method not allowed'
    ], 405);
}

try {
    if ($method === 'POST') {
        // Handle new connection request
        $data = getJsonRequestBody();

        // Validate required fields
        if (empty($data['command'])) {
            sendJsonResponse([
                'success' => false,
                'error' => 'Command is required'
            ], 400);
        }

        // Security check on command
        if (!validateCommand($data['command'])) {
            $logger->warning('Blocked potentially unsafe command', [
                'command' => $data['command']
            ]);
            sendJsonResponse([
                'success' => false,
                'error' => 'Invalid or unsafe command'
            ], 400);
        }

        // Get optional parameters with defaults
        $args = $data['args'] ?? [];
        $env = $data['env'] ?? null;

        // Validate args is array
        if (!is_array($args)) {
            sendJsonResponse([
                'success' => false,
                'error' => 'Arguments must be an array'
            ], 400);
        }

        // Basic argument sanitization
        $args = array_map(function($arg) {
            // Remove any shell operators
            $sanitized = str_replace(['>', '<', '|', '&', ';'], '', $arg);
            return trim($sanitized);
        }, $args);

        // Log connection attempt
        $logger->info('Attempting to connect to MCP server', [
            'command' => $data['command'],
            'args' => $args
        ]);

        // Attempt connection
        $result = $mcpClient->connect($data['command'], $args, $env);

        // Return success response with session info
        sendJsonResponse([
            'success' => true,
            'data' => [
                'sessionId' => $result['sessionId'],
                'capabilities' => $result['capabilities']
            ],
            'logs' => getBufferedLogs($logger)
        ]);

    } else {
        // Handle session cleanup (DELETE)
        $data = getJsonRequestBody();

        // Validate session ID
        if (empty($data['sessionId'])) {
            sendJsonResponse([
                'success' => false,
                'error' => 'Session ID is required'
            ], 400);
        }

        $sessionId = $data['sessionId'];

        // Verify session exists
        if (!$mcpClient->isSessionValid($sessionId)) {
            sendJsonResponse([
                'success' => false,
                'error' => 'Invalid session ID'
            ], 404);
        }

        // Close the session
        $mcpClient->closeSession($sessionId);

        // Return success response
        sendJsonResponse([
            'success' => true,
            'data' => [
                'message' => 'Session closed successfully'
            ],
            'logs' => getBufferedLogs($logger)
        ]);
    }

} catch (RuntimeException $e) {
    // Handle expected errors
    sendJsonResponse([
        'success' => false,
        'error' => $e->getMessage(),
        'logs' => getBufferedLogs($logger)
    ], 400);

} catch (Throwable $e) {
    // Log unexpected errors and return generic message
    $logger->error('Unexpected error in connect.php', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);

    sendJsonResponse([
        'success' => false,
        'error' => 'Internal server error occurred',
        'logs' => getBufferedLogs($logger)
    ], 500);
}
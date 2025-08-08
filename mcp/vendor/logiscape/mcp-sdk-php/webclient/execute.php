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
 * Endpoint for executing MCP server operations
 * 
 * Handles:
 * - POST: Execute operations like list_prompts, list_tools, list_resources, etc.
 */

require_once __DIR__ . '/common.php';

// Only allow POST method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse([
        'success' => false,
        'error' => 'Method not allowed'
    ], 405);
}

/**
 * Validate operation parameters based on operation type
 */
function validateOperationParams(string $operation, array $params): void {
    switch ($operation) {
        case 'get_prompt':
            if (empty($params['name'])) {
                throw new InvalidArgumentException('Prompt name is required');
            }
            break;
            
        case 'call_tool':
            if (empty($params['name'])) {
                throw new InvalidArgumentException('Tool name is required');
            }
            break;
            
        case 'read_resource':
            if (empty($params['uri'])) {
                throw new InvalidArgumentException('Resource URI is required');
            }
            break;
    }
}

try {
    $data = getJsonRequestBody();

    // Validate required fields
    if (empty($data['sessionId'])) {
        sendJsonResponse([
            'success' => false,
            'error' => 'Session ID is required'
        ], 400);
    }

    if (empty($data['operation'])) {
        sendJsonResponse([
            'success' => false,
            'error' => 'Operation is required'
        ], 400);
    }

    $sessionId = $data['sessionId'];
    $operation = $data['operation'];
    $params = $data['params'] ?? [];

    // Validate session is active
    if (!validateSession($sessionId, $mcpClient)) {
        return; // validateSession will send response
    }

    // Validate operation parameters
    try {
        validateOperationParams($operation, $params);
    } catch (InvalidArgumentException $e) {
        sendJsonResponse([
            'success' => false,
            'error' => $e->getMessage(),
            'logs' => getBufferedLogs($logger)
        ], 400);
        return;
    }

    // Log operation attempt
    $logger->info('Executing MCP operation', [
        'sessionId' => $sessionId,
        'operation' => $operation,
        'params' => $params
    ]);

    // Execute the operation
    try {
        $result = $mcpClient->executeOperation($sessionId, $operation, $params);

        // Handle empty results for certain operations
        if ($operation === 'ping') {
            $result['result'] = ['message' => 'Ping successful'];
        }

        // Return success response with result and logs
        sendJsonResponse([
            'success' => true,
            'data' => $result['result'],
            'logs' => array_merge(
                getBufferedLogs($logger),
                $result['logs'] ?? []
            )
        ]);

    } catch (InvalidArgumentException $e) {
        // Handle invalid operation errors
        sendJsonResponse([
            'success' => false,
            'error' => $e->getMessage(),
            'logs' => getBufferedLogs($logger)
        ], 400);

    } catch (RuntimeException $e) {
        // Handle operation execution errors
        $logger->error('Operation failed', [
            'operation' => $operation,
            'error' => $e->getMessage()
        ]);

        sendJsonResponse([
            'success' => false,
            'error' => $e->getMessage(),
            'logs' => getBufferedLogs($logger)
        ], 400);
    }

} catch (Throwable $e) {
    // Log unexpected errors and return generic message
    $logger->error('Unexpected error in execute.php', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);

    sendJsonResponse([
        'success' => false,
        'error' => 'Internal server error occurred',
        'logs' => getBufferedLogs($logger)
    ], 500);
}
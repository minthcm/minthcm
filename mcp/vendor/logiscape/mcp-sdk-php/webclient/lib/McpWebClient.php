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
 * MCP Web Client Wrapper
 * 
 * Provides a web-friendly interface to the MCP client library by creating fresh
 * connections for each operation while maintaining server information and capabilities
 * in PHP sessions.
 */

use Monolog\Logger;
use Mcp\Client\Client;
use Mcp\Client\Transport\StdioServerParameters;
use Mcp\Types\InitializeResult;

class McpWebClient {
    /** @var Client */
    private Client $client;
    
    /** @var Logger */
    private Logger $logger;

    public function __construct(Logger $logger) {
        $this->client = new Client($logger);
        $this->logger = $logger;
        
        // Initialize session storage if not exists
        if (!isset($_SESSION['mcp_servers'])) {
            $_SESSION['mcp_servers'] = [];
        }
    }

    /**
     * Creates a new connection to test a server and stores its capabilities
     */
    public function connect(string $command, array $args = [], ?array $env = null): array {
        $sessionId = $this->generateSessionId($command, $args);
        
        try {
            // Create new connection to test server
            $session = $this->client->connect($command, $args, $env);
            
            // Get server capabilities
            $initResult = $session->getInitializeResult();
            
            // Convert capabilities to a plain array so it serializes cleanly
            $capabilitiesArray = json_decode(json_encode($initResult->capabilities), true);
            
            // Store server info in PHP session
            $_SESSION['mcp_servers'][$sessionId] = [
                'command' => $command,
                'args' => $args,
                'env' => $env,
                'created' => time(),
                'capabilities' => $capabilitiesArray,
                'serverInfo' => $initResult->serverInfo
            ];

            $this->logger->info('Server connection validated', [
                'sessionId' => $sessionId,
                'command' => $command
            ]);

            // Cleanup test connection
            $this->client->close();

            return [
                'sessionId' => $sessionId,
                'capabilities' => $initResult->capabilities
            ];
        } catch (\Exception $e) {
            $this->logger->error("Connection failed: " . $e->getMessage());
            throw new RuntimeException("Failed to connect to MCP server: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Execute an MCP operation with a fresh connection
     */
    public function executeOperation(string $sessionId, string $operation, array $params = []): array {
        if (!isset($_SESSION['mcp_servers'][$sessionId])) {
            throw new RuntimeException('Invalid or expired session');
        }

        $serverInfo = $_SESSION['mcp_servers'][$sessionId];
        
        try {
            // Verify operation is supported
            $this->validateOperationSupport($operation, $serverInfo['capabilities']);
            
            // Create fresh connection
            $session = $this->client->connect(
                $serverInfo['command'],
                $serverInfo['args'],
                $serverInfo['env'] ?? null
            );

            try {
                // Execute operation
                $result = match($operation) {
                    'list_prompts' => [
                        'result' => $session->listPrompts(),
                        'store' => 'prompts'
                    ],
                    'get_prompt' => [
                        'result' => $session->getPrompt($params['name'], $params['arguments'] ?? null)
                    ],
                    'list_tools' => [
                        'result' => $session->listTools(),
                        'store' => 'tools'
                    ],
                    'call_tool' => [
                        'result' => $session->callTool($params['name'], $params['arguments'] ?? null)
                    ],
                    'list_resources' => [
                        'result' => $session->listResources(),
                        'store' => 'resources'
                    ],
                    'read_resource' => [
                        'result' => $session->readResource($params['uri'])
                    ],
                    'ping' => [
                        'result' => $session->sendPing()
                    ],
                    default => throw new InvalidArgumentException("Unknown operation: $operation")
                };

                // Store cacheable results
                if (isset($result['store'])) {
                    $_SESSION['mcp_servers'][$sessionId][$result['store']] = $result['result'];
                }

                return [
                    'result' => $result['result'],
                    'logs' => $this->getRecentLogs()
                ];
            } finally {
                // Always cleanup connection
                $this->client->close();
            }
        } catch (\Exception $e) {
            $this->logger->error("Operation failed: " . $e->getMessage());
            throw new RuntimeException("Failed to execute operation: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Clean up session data
     */
    public function closeSession(string $sessionId): void {
        unset($_SESSION['mcp_servers'][$sessionId]);
        $this->logger->info('Session closed', ['sessionId' => $sessionId]);
    }

    /**
     * Check if session is valid
     */
    public function isSessionValid(string $sessionId): bool {
        return isset($_SESSION['mcp_servers'][$sessionId]);
    }

    /**
     * Get server capabilities
     */
    public function getCapabilities(string $sessionId): ?array {
        return $_SESSION['mcp_servers'][$sessionId]['capabilities'] ?? null;
    }

    /**
     * Get recent log entries
     */
    private function getRecentLogs(): array {
        if ($this->logger instanceof BufferLogger) {
            return $this->logger->getBuffer();
        }
        return [];
    }

    /**
     * Generate a unique session ID based on server config
     */
    private function generateSessionId(string $command, array $args): string {
        $data = json_encode([$command, $args]);
        return hash('sha256', $data);
    }

    /**
     * Validate operation against server capabilities
     */
    private function validateOperationSupport(string $operation, array $capabilities): void {
        $operationMap = [
            'list_prompts' => 'prompts',
            'get_prompt' => 'prompts',
            'list_tools' => 'tools',
            'call_tool' => 'tools',
            'list_resources' => 'resources',
            'read_resource' => 'resources'
        ];

        if (isset($operationMap[$operation])) {
            $requiredCapability = $operationMap[$operation];
            if (!isset($capabilities[$requiredCapability])) {
                throw new RuntimeException("Server does not support $requiredCapability operations");
            }
        }
    }
}
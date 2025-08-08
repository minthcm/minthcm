<?php

namespace MintMCP\Server;

use Mcp\Server\Server;
use MintMCP\Handlers\MCPRequestHandler;
use MintMCP\Tools\AbstractMCPTool;

class MintMCPServer
{
    private Server $server;
    private MCPRequestHandler $requestHandler;

    public function __construct()
    {
        $this->server = new Server('minthcm-mcp-server');
        $this->requestHandler = new MCPRequestHandler();
        $this->registerHandlers();
    }

    private function registerHandlers(): void
    {
        $this->server->registerHandler('tools/list', [$this->requestHandler, 'handleToolsList']);
        $this->server->registerHandler('tools/call', [$this->requestHandler, 'handleToolCall']);
    }

    /**
     * Allows adding a new tool from outside
     */
    public function addTool(AbstractMCPTool $tool): void
    {
        $this->requestHandler->registerTool($tool);
    }

    public function handleHTTPRequest(array $input): array
    {

        if (!$input) {
            throw new \InvalidArgumentException("Invalid JSON input");
        }

        $method = $input['method'] ?? '';
        $params = $input['params'] ?? null;
        $inputId = $input['id'] ?? null;

        try {
            switch ($method) {
                case 'initialize':

                    return [
                        'jsonrpc' => '2.0',
                        'id' => $inputId,
                        'result' => [
                            'protocolVersion' => '2025-03-26',
                            'capabilities' => [
                                'logging' => new \stdClass(), // enable logging support
                                'completions' => new \stdClass(), // enable completions support
                                'tools' => [
                                    'listChanged' => true, // set to true if tools can change at runtime,
                                ],
                                'prompts' => [
                                    'listChanged' => true // set to true if prompts can change at runtime
                                ],
                                'resources' => [
                                    'subscribe' => false,   // set to true if resource subscription is supported
                                    'listChanged' => false  // set to true if resources can change at runtime
                                ]
                            ],
                            'serverInfo' => [
                                'name' => 'MintHCM MCP Server',
                                'version' => '1.0.0'
                            ],
                        ]
                    ];

                case 'resources/templates/list':
                case 'tools/list':
                    $tools = $this->requestHandler->handleToolsList();

                    return [
                        'jsonrpc' => '2.0',
                        'id' => $inputId,
                        'result' => [
                            'tools' => $tools,
                        ]
                    ];

                case 'tools/call':
                    $result = $this->requestHandler->handleToolCall($params);
                    return [
                        'jsonrpc' => '2.0',
                        'id' => $inputId,
                        'result' => ['content' => $result->content]
                    ];

                default:
                    return [
                        'jsonrpc' => '2.0',
                        'id' => $inputId,
                        'error' => ['code' => -32601, 'message' => 'Method not found']
                    ];
            }
        } catch (\Exception $e) {
            return [
                'jsonrpc' => '2.0',
                'id' => $inputId,
                'error' => ['code' => -32603, 'message' => $e->getMessage()]
            ];
        }
    }
}

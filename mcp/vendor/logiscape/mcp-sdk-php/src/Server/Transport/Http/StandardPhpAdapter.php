<?php

/**
 * Model Context Protocol SDK for PHP
 *
 * (c) 2025 Logiscape LLC <https://logiscape.com>
 *
 * Developed by:
 * - Josh Abbott
 * - Claude 3.7 Sonnet (Anthropic AI model)
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
 *
 * Filename: Server/Transport/Http/StandardPhpAdapter.php
 */

declare(strict_types=1);

namespace Mcp\Server\Transport\Http;

use Mcp\Server\Server;
use Mcp\Server\HttpServerRunner;

/**
 * Adapter for standard PHP environments to easily handle MCP server requests.
 * 
 * This class provides a simple interface for integrating MCP server
 * functionality into standard PHP applications.
 */
class StandardPhpAdapter
{
    /**
     * Constructor.
     *
     * @param HttpServerRunner $serverRunner HTTP server runner instance
     */
    public function __construct(
        private readonly HttpServerRunner $serverRunner
    ) {
    }
    
    /**
     * Handle an incoming HTTP request.
     *
     * @return void This method will send the response and exit
     */
    public function handle(): void
    {
        try {
            // Create request from globals
            $request = HttpMessage::fromGlobals();
            
            // Process the request
            $response = $this->serverRunner->handleRequest($request);
            
            // Send the response
            $this->serverRunner->sendResponse($response);
        } catch (\Exception $e) {
            // Log error
            error_log('MCP Server error: ' . $e->getMessage());
            
            // Send error response
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode([
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Create a StandardPhpAdapter from globals.
     *
     * @param Server $server MCP server instance
     * @param array $options HTTP transport options
     * @return self New adapter instance
     */
    public static function createFromGlobals(Server $server, array $options = []): self
    {
        // Create initialization options
        $initOptions = $server->createInitializationOptions();
        
        // Create HTTP server runner
        $serverRunner = new HttpServerRunner($server, $initOptions, $options);
        
        // Create adapter
        return new self($serverRunner);
    }
}

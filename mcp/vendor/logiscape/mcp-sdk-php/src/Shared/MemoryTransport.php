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
 * Filename: Shared/MemoryTransport.php
 */

declare(strict_types=1);

namespace Mcp\Shared;

use Mcp\Client\ClientSession;
use Mcp\Server\Server;

/**
 * Manager for memory-based client-server communication.
 *
 * This class provides a simplified synchronous counterpart to the Python
 * async code. Instead of async streams and context managers, we return
 * pairs of MemoryStream objects that can be used to simulate client-server
 * communication in memory.
 */
class MemoryTransport {
    /**
     * Creates a pair of bidirectional memory streams for client-server communication.
     *
     * In Python, we had asynchronous memory object streams. Here, we use simple
     * MemoryStream instances. The structure:
     *
     * - clientStreams = [serverToClientStream, clientToServerStream]
     * - serverStreams = [clientToServerStream, serverToClientStream]
     *
     * clientStreams[0] is what the client uses to read server messages,
     * clientStreams[1] is what the client uses to send messages to the server.
     *
     * serverStreams[0] is what the server uses to read client messages,
     * serverStreams[1] is what the server uses to send messages to the client.
     *
     * @return array{array{MemoryStream, MemoryStream}, array{MemoryStream, MemoryStream}}
     *   A tuple: [clientStreams, serverStreams]
     */
    public static function createClientServerStreams(): array {
        $serverToClient = new MemoryStream();
        $clientToServer = new MemoryStream();

        $clientStreams = [$serverToClient, $clientToServer];
        $serverStreams = [$clientToServer, $serverToClient];

        return [$clientStreams, $serverStreams];
    }

    /**
     * Creates a ClientSession connected to a running MCP server using memory streams.
     *
     * In the Python code, we have an async context and a task group that runs
     * the server and client concurrently. Here, we call $server->run() directly.
     *
     * For this to work:
     * - $server->run(...) should start processing incoming messages from the
     *   server-side of the memory streams. It should not block indefinitely,
     *   or it should run in a non-blocking manner if possible.
     * - After starting the server, we create a ClientSession and call
     *   $clientSession->initialize() to ensure the client is ready.
     *
     * This is a simplification due to the lack of async in PHP.
     *
     * @param Server $server The server instance to run.
     * @param int|null $readTimeout A timeout in seconds for reading responses, or null for no timeout.
     * @param bool $raiseExceptions Whether the server should raise exceptions internally.
     * @return ClientSession The initialized client session.
     */
    public static function createConnectedSession(
        Server $server,
        ?int $readTimeout = null,
        bool $raiseExceptions = false
    ): ClientSession {
        [$clientStreams, $serverStreams] = self::createClientServerStreams();
        [$clientRead, $clientWrite] = $clientStreams;
        [$serverRead, $serverWrite] = $serverStreams;

        // Run the server, which should start processing messages. Depending on the
        // server implementation, this may block until certain conditions are met.
        // Ideally, server->run() sets up the server and returns control to us.
        $server->run(
            $serverRead,
            $serverWrite,
            $server->createInitializationOptions(),
            $raiseExceptions
        );

        // Create the client session
        $clientSession = new ClientSession(
            $clientRead,
            $clientWrite,
            $readTimeout
        );

        // Initialize the client session
        // This should send an initialization request to the server and wait for a response.
        $clientSession->initialize();

        return $clientSession;
    }
}
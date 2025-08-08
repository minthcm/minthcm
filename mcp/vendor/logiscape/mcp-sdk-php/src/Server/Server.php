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
 * Filename: Server/Server.php
 */

declare(strict_types=1);

namespace Mcp\Server;

use Mcp\Types\JsonRpcMessage;
use Mcp\Types\ServerCapabilities;
use Mcp\Types\ServerPromptsCapability;
use Mcp\Types\ServerResourcesCapability;
use Mcp\Types\ServerToolsCapability;
use Mcp\Types\ServerLoggingCapability;
use Mcp\Types\ExperimentalCapabilities;
use Mcp\Types\LoggingLevel;
use Mcp\Types\RequestId;
use Mcp\Shared\McpError;
use Mcp\Shared\ErrorData as TypesErrorData;
use Mcp\Types\JSONRPCResponse;
use Mcp\Types\JSONRPCError;
use Mcp\Types\JsonRpcErrorObject;
use Mcp\Types\Result;
use Mcp\Shared\ErrorData;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RuntimeException;
use InvalidArgumentException;

/**
 * MCP Server implementation
 *
 * This class manages request and notification handlers, integrates with ServerSession,
 * and handles incoming messages by dispatching them to the appropriate handlers.
 */
class Server {
    /** @var array<string, callable(?array): Result> */
    private array $requestHandlers = [];
    /** @var array<string, callable(?array): void> */
    private array $notificationHandlers = [];
    private ?ServerSession $session = null;
    private LoggerInterface $logger;

    public function __construct(
        private readonly string $name,
        ?LoggerInterface $logger = null
    ) {
        $this->logger = $logger ?? new NullLogger();
        $this->logger->debug("Initializing server '$name'");

        // Register built-in ping handler: returns an EmptyResult as per schema
        $this->registerHandler('ping', function (?array $params): Result {
            // Ping returns an EmptyResult according to the schema
            return new Result();
        });
    }

    /**
     * Creates initialization options for the server.
     */
    public function createInitializationOptions(
        ?NotificationOptions $notificationOptions = null,
        ?array $experimentalCapabilities = null
    ): InitializationOptions {
        $notificationOptions ??= new NotificationOptions();
        $experimentalCapabilities ??= [];

        return new InitializationOptions(
            serverName: $this->name,
            serverVersion: $this->getPackageVersion('mcp'),
            capabilities: $this->getCapabilities($notificationOptions, $experimentalCapabilities)
        );
    }

    /**
     * Gets server capabilities based on registered handlers.
     */
    public function getCapabilities(
        NotificationOptions $notificationOptions,
        array $experimentalCapabilities
    ): ServerCapabilities {
        // Initialize capabilities as null
        $promptsCapability = null;
        $resourcesCapability = null;
        $toolsCapability = null;
        $loggingCapability = null;
    
        if (isset($this->requestHandlers['prompts/list'])) {
            $promptsCapability = new ServerPromptsCapability(
                listChanged: $notificationOptions->promptsChanged
            );
        }
    
        if (isset($this->requestHandlers['resources/list'])) {
            $resourcesCapability = new ServerResourcesCapability(
                subscribe: false, // Adjust based on your requirements
                listChanged: $notificationOptions->resourcesChanged
            );
        }
    
        if (isset($this->requestHandlers['tools/list'])) {
            $toolsCapability = new ServerToolsCapability(
                listChanged: $notificationOptions->toolsChanged
            );
        }
    
        if (isset($this->requestHandlers['logging/setLevel'])) {
            $loggingCapability = new ServerLoggingCapability(
                // Provide necessary initialization parameters
            );
        }
    
        return new ServerCapabilities(
            prompts: $promptsCapability,
            resources: $resourcesCapability,
            tools: $toolsCapability,
            logging: $loggingCapability,
            experimental: new ExperimentalCapabilities($experimentalCapabilities) // Assuming a constructor
        );
    }

    /**
     * Registers a request handler for a given method.
     *
     * The handler should return a `Result` object or throw `McpError`.
     */
    public function registerHandler(string $method, callable $handler): void {
        $this->requestHandlers[$method] = $handler;
        $this->logger->debug("Registered handler for request method: $method");
    }

    public function getHandlers(): array {
        return $this->requestHandlers;
    }

    /**
     * Registers a notification handler for a given method.
     *
     * The handler does not return a result, just processes the notification.
     */
    public function registerNotificationHandler(string $method, callable $handler): void {
        $this->notificationHandlers[$method] = $handler;
        $this->logger->debug("Registered notification handler for method: $method");
    }

    public function getNotificationHandlers(): array {
        return $this->notificationHandlers;
    }

    /**
     * Processes an incoming message from the client.
     */
    public function handleMessage(JsonRpcMessage $message): void {
        $this->logger->debug("Received message: " . json_encode($message));

        $innerMessage = $message->message;

        try {
            if ($innerMessage instanceof \Mcp\Types\JSONRPCRequest) {
                // It's a request
                $this->processRequest($innerMessage);
            } elseif ($innerMessage instanceof \Mcp\Types\JSONRPCNotification) {
                // It's a notification
                $this->processNotification($innerMessage);
            } else {
                // Server does not expect responses from client; ignore or log
                $this->logger->warning("Received unexpected message type: " . get_class($innerMessage));
            }
        } catch (McpError $e) {
            if ($innerMessage instanceof \Mcp\Types\JSONRPCRequest) {
                $this->sendError($innerMessage->id, $e->error);
            }
        } catch (\Exception $e) {
            $this->logger->error("Error handling message: " . $e->getMessage());
            if ($innerMessage instanceof \Mcp\Types\JSONRPCRequest) {
                // Code -32603 is Internal error as per JSON-RPC spec
                $this->sendError($innerMessage->id, new ErrorData(
                    code: -32603,
                    message: $e->getMessage()
                ));
            }
        }
    }

    /**
     * Processes a JSONRPCRequest message.
     */
    private function processRequest(\Mcp\Types\JSONRPCRequest $request): void {
        $method = $request->method;
        $handler = $this->requestHandlers[$method] ?? null;

        if ($handler === null) {
            throw new McpError(new TypesErrorData(
                code: -32601, // Method not found
                message: "Method not found: {$method}"
            ));
        }

        // Handlers take params and return a Result object or throw McpError
        $params = $request->params ?? null;
        $result = $handler($params);

        if (!$result instanceof Result) {
            // If the handler doesn't return a Result, wrap it in a Result or throw error
            // According to schema, result must be a Result object.
            $resultObj = new Result();
            // Populate $resultObj if $result is something else
            // For simplicity, if handler returned array or null, just assign result as is
            // This can be adjusted based on actual schema requirements
            $result = $resultObj;
        }

        $this->sendResponse($request->id, $result);
    }

    /**
     * Processes a JSONRPCNotification message.
     */
    private function processNotification(\Mcp\Types\JSONRPCNotification $notification): void {
        $method = $notification->method;
        $handler = $this->notificationHandlers[$method] ?? null;

        if ($handler !== null) {
            $params = $notification->params ?? null;
            $handler($params);
        } else {
            $this->logger->warning("No handler registered for notification method: $method");
        }
    }

    /**
     * Sends a response to a request.
     *
     * @param RequestId $id The request ID to respond to.
     * @param Result $result The result object.
     */
    private function sendResponse(RequestId $id, Result $result): void {
        if (!$this->session) {
            throw new RuntimeException('No active session');
        }

        // Create a JSONRPCResponse object and wrap in JsonRpcMessage
        $resp = new JSONRPCResponse(
            jsonrpc: '2.0',
            id: $id,
            result: $result
        );
        $resp->validate();

        $msg = new JsonRpcMessage($resp);
        $this->session->writeMessage($msg);
    }

    /**
     * Sends an error response to a request.
     *
     * @param RequestId $id The request ID to respond to.
     * @param ErrorData $error The error data.
     */
    private function sendError(RequestId $id, ErrorData $error): void {
        if (!$this->session) {
            throw new RuntimeException('No active session');
        }

        $errorObj = new JsonRpcErrorObject(
            code: $error->code,
            message: $error->message,
            data: $error->data ?? null
        );

        $errResp = new JSONRPCError(
            jsonrpc: '2.0',
            id: $id,
            error: $errorObj
        );
        $errResp->validate();

        $msg = new JsonRpcMessage($errResp);
        $this->session->writeMessage($msg);
    }

    /**
     * Retrieves the package version.
     *
     * @param string $package The package name.
     * @return string The package version.
     */
    private function getPackageVersion(string $package): string {
        // Return a static version. Actual implementation can read from composer.json or elsewhere.
        return '1.0.0';
    }

    /**
     * Sets the active server session.
     *
     * @param ServerSession $session The server session to set.
     */
    public function setSession(ServerSession $session): void {
        $this->session = $session;
    }

    /**
     * Checks if the connected client supports a specific feature.
     *
     * @param string $feature The feature to check.
     * @return bool True if the feature is supported.
     */
    public function clientSupportsFeature(string $feature): bool {
        if (!$this->session) {
            return false;
        }
        
        return $this->session->clientSupportsFeature($feature);
    }
}

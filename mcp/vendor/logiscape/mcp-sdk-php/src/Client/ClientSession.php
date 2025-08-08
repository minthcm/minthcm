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
 * Filename: Client/ClientSession.php
 */

declare(strict_types=1);

namespace Mcp\Client;

use Mcp\Shared\BaseSession;
use Mcp\Shared\Version;
use Mcp\Shared\MemoryStream;
use Mcp\Types\ClientRequest;
use Mcp\Types\ClientNotification;
use Mcp\Types\ServerRequest;
use Mcp\Types\ServerNotification;
use Mcp\Types\InitializeRequest;
use Mcp\Types\InitializeRequestParams;
use Mcp\Types\InitializeResult;
use Mcp\Types\EmptyResult;
use Mcp\Types\Implementation;
use Mcp\Types\ClientCapabilities;
use Mcp\Types\ClientRootsCapability;
use Mcp\Types\LoggingLevel;
use Mcp\Types\ProgressToken;
use Mcp\Types\ListResourcesResult;
use Mcp\Types\ReadResourceResult;
use Mcp\Types\CallToolResult;
use Mcp\Types\ListPromptsResult;
use Mcp\Types\GetPromptResult;
use Mcp\Types\ListToolsResult;
use Mcp\Types\CompleteResult;
use Mcp\Types\ResourceReference;
use Mcp\Types\PromptReference;
use Mcp\Types\InitializedNotification;
use Mcp\Types\ProgressNotification;
use Mcp\Types\PingRequest;
use Mcp\Types\RootsListChangedNotification;
use Mcp\Types\JsonRpcMessage;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RuntimeException;
use InvalidArgumentException;

/**
 * Class ClientSession
 *
 * Client session for MCP communication.
 *
 * The client interacts with a server by sending requests and notifications, and receiving responses.
 */
class ClientSession extends BaseSession {
    /** @var InitializeResult|null */
    private ?InitializeResult $initResult = null;

    /** @var bool */
    private bool $initialized = false;

    /** @var LoggerInterface */
    private LoggerInterface $logger;

    /** @var MemoryStream|null */
    private ?MemoryStream $readStream = null;

    /** @var MemoryStream|null */
    private ?MemoryStream $writeStream = null;

    /** @var float|null */
    private ?float $readTimeout = null;

    /** @var string|null */
    private ?string $negotiatedProtocolVersion = null;

    /**
     * ClientSession constructor.
     *
     * @param MemoryStream    $readStream   Stream to read incoming messages from.
     * @param MemoryStream    $writeStream  Stream to write outgoing messages to.
     * @param LoggerInterface|null $logger  PSR-3 compliant logger.
     * @param float|null      $readTimeout  Optional read timeout in seconds.
     *
     * @throws InvalidArgumentException If the provided streams are invalid.
     */
    public function __construct(
        MemoryStream $readStream,
        MemoryStream $writeStream,
        ?float $readTimeout = null,
        ?LoggerInterface $logger = null
    ) {
        parent::__construct(
            receiveRequestType: ServerRequest::class,
            receiveNotificationType: ServerNotification::class
        );

        $this->readStream = $readStream;
        $this->writeStream = $writeStream;
        $this->readTimeout = $readTimeout;
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * Initialize the client session by sending an InitializeRequest and then an InitializedNotification.
     *
     * @throws RuntimeException If initialization fails due to unsupported protocol version or other issues.
     *
     * @return void
     */
    public function initialize(): void {
        $this->logger->info('Initializing client session');

        // Create and send InitializeRequest
        $initRequest = new InitializeRequest(
            new InitializeRequestParams(
                protocolVersion: Version::LATEST_PROTOCOL_VERSION,
                capabilities: new ClientCapabilities(
                    //roots: new ClientRootsCapability(listChanged: true)
                ),
                clientInfo: new Implementation(
                    name: 'mcp-client',
                    version: '1.0.0'
                )
            )
        );

        /** @var InitializeResult $result */
        $result = $this->sendRequest($initRequest, InitializeResult::class);

        // Validate protocol version
        if (!in_array($result->protocolVersion, Version::SUPPORTED_PROTOCOL_VERSIONS, true)) {
            throw new RuntimeException(
                "Unsupported protocol version from server: {$result->protocolVersion}"
            );
        }

        $this->negotiatedProtocolVersion = $result->protocolVersion;

        // Send InitializedNotification
        $initializedNotification = new InitializedNotification();
        $this->sendNotification($initializedNotification);

        $this->initResult = $result;
        $this->initialized = true;

        $this->logger->info('Client session initialized successfully');

        // Start message processing if necessary
        $this->startMessageProcessing();
    }

    /**
     * Get the InitializeResult after successful initialization.
     *
     * @throws RuntimeException If the session has not been initialized yet.
     *
     * @return InitializeResult The result of the initialization.
     */
    public function getInitializeResult(): InitializeResult {
        if ($this->initResult === null) {
            throw new RuntimeException('Session not yet initialized');
        }
        return $this->initResult;
    }

    /**
     * Get the negotiated protocol version.
     *
     * @throws RuntimeException If the session has not been initialized yet.
     *
     * @return string The negotiated protocol version.
     */
    public function getNegotiatedProtocolVersion(): string {
        if ($this->negotiatedProtocolVersion === null) {
            throw new RuntimeException('Session not yet initialized');
        }
        return $this->negotiatedProtocolVersion;
    }

    /**
     * Check if the negotiated protocol version supports a specific feature.
     *
     * @param string $feature The feature to check for.
     *
     * @return bool True if the feature is supported.
     */
    public function supportsFeature(string $feature): bool {
        if ($this->negotiatedProtocolVersion === null) {
            return false;
        }
        
        switch ($feature) {
            case 'batch_messages':
            case 'audio_content':
            case 'annotations':
            case 'tool_annotations':
            case 'progress_message':
                return version_compare($this->negotiatedProtocolVersion, '2025-03-26', '>=');
            default:
                return false;
        }
    }

    /**
     * Send a PingRequest to the server.
     *
     * @throws RuntimeException If the session is not initialized or if sending the request fails.
     *
     * @return EmptyResult The result of the ping request.
     */
    public function sendPing(): EmptyResult {
        $this->ensureInitialized();
        $pingRequest = new PingRequest();
        $this->logger->info('Sending PingRequest to server');
        return $this->sendRequest($pingRequest, EmptyResult::class);
    }

    /**
     * Send a progress notification to the server.
     *
     * @param ProgressToken $progressToken The progress token.
     * @param float $progress The progress value.
     * @param float|null $total The total value.
     * @param string|null $message The message to send.
     *
     * @throws RuntimeException If the session is not initialized or if sending the notification fails.
     *
     * @return void
     */
    public function sendProgressNotification(
        ProgressToken $progressToken,
        float $progress,
        ?float $total = null,
        ?string $message = null
    ): void {
        $this->ensureInitialized();
        
        $params = [
            'progressToken' => $progressToken,
            'progress' => $progress
        ];
        
        if ($total !== null) {
            $params['total'] = $total;
        }
        
        // Only include message field for servers that support it
        if ($message !== null && $this->supportsFeature('progress_message')) {
            $params['message'] = $message;
        }
        
        $notificationParams = new \Mcp\Types\ProgressNotificationParams(
            progressToken: $progressToken,
            progress: $progress,
            total: $total,
            message: $this->supportsFeature('progress_message') ? $message : null
        );
        
        $notification = new \Mcp\Types\ProgressNotification($notificationParams);
        $this->sendNotification($notification);
    }

    /**
     * Set the logging level on the server.
     *
     * @param LoggingLevel $level The desired logging level.
     *
     * @throws RuntimeException If the session is not initialized or if sending the request fails.
     *
     * @return EmptyResult The result of the setLoggingLevel request.
     */
    public function setLoggingLevel(LoggingLevel $level): EmptyResult {
        $this->ensureInitialized();
        $setLevelRequest = new \Mcp\Types\SetLevelRequest($level);
        $this->logger->info('Setting logging level on server');
        return $this->sendRequest($setLevelRequest, EmptyResult::class);
    }

    /**
     * List available resources on the server.
     *
     * @throws RuntimeException If the session is not initialized or if sending the request fails.
     *
     * @return ListResourcesResult The list of resources.
     */
    public function listResources(): ListResourcesResult {
        $this->ensureInitialized();
        $listResourcesRequest = new \Mcp\Types\ListResourcesRequest();
        $this->logger->info('Requesting list of resources from server');
        return $this->sendRequest($listResourcesRequest, ListResourcesResult::class);
    }

    /**
     * Read a specific resource from the server.
     *
     * @param string $uri The URI of the resource to read.
     *
     * @throws RuntimeException If the session is not initialized or if sending the request fails.
     *
     * @return ReadResourceResult The content of the resource.
     */
    public function readResource(string $uri): ReadResourceResult {
        $this->ensureInitialized();
        $readResourceRequest = new \Mcp\Types\ReadResourceRequest($uri);
        $this->logger->info("Requesting to read resource: $uri");
        return $this->sendRequest($readResourceRequest, ReadResourceResult::class);
    }

    /**
     * Subscribe to updates for a specific resource.
     *
     * @param string $uri The URI of the resource to subscribe to.
     *
     * @throws RuntimeException If the session is not initialized or if sending the request fails.
     *
     * @return EmptyResult The result of the subscribe request.
     */
    public function subscribeResource(string $uri): EmptyResult {
        $this->ensureInitialized();
        $subscribeRequest = new \Mcp\Types\SubscribeRequest($uri);
        $this->logger->info("Subscribing to resource: $uri");
        return $this->sendRequest($subscribeRequest, EmptyResult::class);
    }

    /**
     * Unsubscribe from updates for a specific resource.
     *
     * @param string $uri The URI of the resource to unsubscribe from.
     *
     * @throws RuntimeException If the session is not initialized or if sending the request fails.
     *
     * @return EmptyResult The result of the unsubscribe request.
     */
    public function unsubscribeResource(string $uri): EmptyResult {
        $this->ensureInitialized();
        $unsubscribeRequest = new \Mcp\Types\UnsubscribeRequest($uri);
        $this->logger->info("Unsubscribing from resource: $uri");
        return $this->sendRequest($unsubscribeRequest, EmptyResult::class);
    }

    /**
     * Call a tool on the server with optional arguments.
     *
     * @param string     $name      The name of the tool to call.
     * @param array|null $arguments Optional arguments for the tool.
     *
     * @throws RuntimeException If the session is not initialized or if sending the request fails.
     *
     * @return CallToolResult The result of the tool call.
     */
    public function callTool(string $name, ?array $arguments = null): CallToolResult {
        $this->ensureInitialized();
        $callToolRequest = new \Mcp\Types\CallToolRequest($name, $arguments);
        $this->logger->info("Calling tool: $name with arguments: " . json_encode($arguments));
        return $this->sendRequest($callToolRequest, CallToolResult::class);
    }

    /**
     * List available prompts on the server.
     *
     * @throws RuntimeException If the session is not initialized or if sending the request fails.
     *
     * @return ListPromptsResult The list of prompts.
     */
    public function listPrompts(): ListPromptsResult {
        $this->ensureInitialized();
        $listPromptsRequest = new \Mcp\Types\ListPromptsRequest();
        $this->logger->info('Requesting list of prompts from server');
        return $this->sendRequest($listPromptsRequest, ListPromptsResult::class);
    }

    /**
     * Get a specific prompt from the server.
     *
     * @param string     $name      The name of the prompt to retrieve.
     * @param array|null $arguments Optional arguments for the prompt.
     *
     * @throws RuntimeException If the session is not initialized or if sending the request fails.
     * @throws InvalidArgumentException If any argument values are not strings.
     *
     * @return GetPromptResult The retrieved prompt.
     */
    public function getPrompt(string $name, ?array $arguments = null): GetPromptResult {
        $this->ensureInitialized();

        // Create PromptArguments object if arguments provided
        $promptArgs = null;
        if ($arguments !== null) {
            try {
                $promptArgs = new \Mcp\Types\PromptArguments($arguments);
            } catch (\InvalidArgumentException $e) {
                $this->logger->error('Invalid prompt arguments', [
                    'name' => $name,
                    'arguments' => $arguments,
                    'error' => $e->getMessage()
                ]);
                throw $e;
            }
        }

        // Create the request parameters
        $params = new \Mcp\Types\GetPromptRequestParams($name, $promptArgs);
        $getPromptRequest = new \Mcp\Types\GetPromptRequest($params);

        $this->logger->info("Requesting prompt: $name with arguments: " . json_encode($arguments));
        return $this->sendRequest($getPromptRequest, GetPromptResult::class);
    }

    /**
     * Complete an action based on a resource or prompt reference.
     *
     * @param ResourceReference|PromptReference $ref       The reference to complete.
     * @param array                             $argument  The arguments for completion.
     *
     * @throws RuntimeException If the session is not initialized or if sending the request fails.
     *
     * @return CompleteResult The result of the completion.
     */
    public function complete(
        ResourceReference|PromptReference $ref,
        array $argument
    ): CompleteResult {
        $this->ensureInitialized();

        // Construct the CompletionArgument object
        if (empty($argument['name']) || !isset($argument['value'])) {
            throw new \InvalidArgumentException('CompleteRequest argument must have "name" and "value"');
        }
        $completionArg = new \Mcp\Types\CompletionArgument($argument['name'], $argument['value']);

        // Construct the params object
        $params = new \Mcp\Types\CompleteRequestParams($completionArg, $ref);

        // Construct the request
        $completeRequest = new \Mcp\Types\CompleteRequest($params);

        $this->logger->info("Completing reference: " . json_encode($ref) . " with argument: " . json_encode($argument));
        return $this->sendRequest($completeRequest, CompleteResult::class);
    }

    /**
     * List available tools on the server.
     *
     * @throws RuntimeException If the session is not initialized or if sending the request fails.
     *
     * @return ListToolsResult The list of tools.
     */
    public function listTools(): ListToolsResult {
        $this->ensureInitialized();
        $listToolsRequest = new \Mcp\Types\ListToolsRequest();
        $this->logger->info('Requesting list of tools from server');
        return $this->sendRequest($listToolsRequest, ListToolsResult::class);
    }

    /**
     * Notify the server that the list of roots has changed.
     *
     * @throws RuntimeException If the session is not initialized or if sending the notification fails.
     *
     * @return void
     */
    public function sendRootsListChanged(): void {
        $this->ensureInitialized();
        $rootsListChangedNotification = new \Mcp\Types\RootsListChangedNotification();
        $this->logger->info('Sending RootsListChangedNotification to server');
        $this->sendNotification($rootsListChangedNotification);
    }

    /**
     * Receive the next incoming message.
     *
     * @return JsonRpcMessage|\Exception|null The received message, an exception, or null if no message is available.
     */
    public function receiveMessage(): JsonRpcMessage|\Exception|null {
        $msg = $this->readStream->receive();
        return $msg; // The transport already returns JsonRpcMessage or Exception or null
    }
    
    protected function getReadTimeout(): ?float {
        return $this->readTimeout;
    }

    /**
     * Ensure that the session has been initialized.
     *
     * @throws RuntimeException If the session is not initialized.
     *
     * @return void
     */
    private function ensureInitialized(): void {
        if (!$this->initialized) {
            throw new RuntimeException('Session not initialized. Call initialize() first.');
        }
    }

    /**
     * Start any additional message processing mechanisms if necessary.
     *
     * @return void
     */
    protected function startMessageProcessing(): void {
        // Implement any background processing if required
        // Currently, messages are processed in the receive loop
    }

    /**
     * Stop any additional message processing mechanisms if necessary.
     *
     * @return void
     */
    protected function stopMessageProcessing(): void {
        // Implement any cleanup for background processing if required
    }

    /**
     * Write a JsonRpcMessage to the write stream.
     *
     * @param JsonRpcMessage $message The JSON-RPC message to send.
     *
     * @throws RuntimeException If writing to the stream fails.
     *
     * @return void
     */
    protected function writeMessage(JsonRpcMessage $message): void {
        $this->logger->debug('Sending message to server: ' . json_encode($message, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        $this->writeStream->send($message);
    }

    /**
     * Wait for a specific response to a request.
     *
     * @param int    $requestIdValue The ID of the request to wait for.
     * @param string $resultType     The expected result type class name.
     * @param \Mcp\Types\McpModel|null $futureResult Reference to store the received result.
     *
     * @throws RuntimeException If the wait times out or if an unexpected response is received.
     *
     * @return \Mcp\Types\McpModel The received result.
     */
    protected function waitForResponse(int $requestIdValue, string $resultType, ?\Mcp\Types\McpModel &$futureResult): \Mcp\Types\McpModel {
        $timeout = $this->getReadTimeout();
        $startTime = microtime(true);

        $this->logger->info("Waiting for response to request ID: $requestIdValue");

        while ($futureResult === null) {
            if ($timeout !== null && (microtime(true) - $startTime) >= $timeout) {
                $this->logger->error("Timed out waiting for response to request ID: $requestIdValue");
                throw new RuntimeException("Timed out waiting for response to request ID: $requestIdValue");
            }

            $message = $this->readNextMessage();
            $this->handleIncomingMessage($message);
        }

        $this->logger->info("Received response for request ID: $requestIdValue");

        return $futureResult;
    }

    /**
     * Read the next message from the read stream.
     *
     * Blocks until a valid JsonRpcMessage is received or an exception occurs.
     *
     * @throws RuntimeException If an invalid message type is received.
     *
     * @return JsonRpcMessage The received JSON-RPC message.
     */
    protected function readNextMessage(): JsonRpcMessage {
        while (true) {
            $msg = $this->readStream->receive();

            if ($msg === null) {
                // No message available, wait briefly to prevent busy waiting
                usleep(10000);
                continue;
            }

            if ($msg instanceof \Exception) {
                $this->logger->error("Exception received from readStream: {$msg->getMessage()}");
                throw $msg;
            }

            if (!$msg instanceof JsonRpcMessage) {
                $this->logger->error("Invalid message type received from readStream");
                throw new RuntimeException("Invalid message type received from readStream");
            }

            $this->logger->debug('Received message from server: ' . json_encode($msg, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            return $msg;
        }
    }
}
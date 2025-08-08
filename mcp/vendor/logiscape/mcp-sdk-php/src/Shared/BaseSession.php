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
 * Filename: Shared/BaseSession.php
 */

declare(strict_types=1);

namespace Mcp\Shared;

use Mcp\Types\JsonRpcMessage;
use Mcp\Types\RequestId;
use Mcp\Shared\ErrorData;
use Mcp\Types\ProgressToken;
use Mcp\Types\ProgressNotification;
use Mcp\Types\JSONRPCRequest;
use Mcp\Types\JSONRPCNotification;
use Mcp\Types\JSONRPCResponse;
use Mcp\Types\JSONRPCError;
use Mcp\Types\McpModel;
use Mcp\Shared\McpError;
use InvalidArgumentException;
use RuntimeException;

/**
 * Base session for managing MCP communication.
 *
 * This class acts as a synchronous equivalent to the Python BaseSession. It does not
 * use async/await or streaming memory objects, but tries to replicate the logic.
 *
 * Subclasses should implement the abstract methods to handle I/O and message processing.
 */
abstract class BaseSession {
    protected bool $isInitialized = false;
    /** @var array<int, callable(JsonRpcMessage):void> */
    private array $responseHandlers = [];
    /** @var callable[] */
    private array $requestHandlers = [];
    /** @var callable[] */
    private array $notificationHandlers = [];
    private int $requestId = 0;

    /**
     * @param string $receiveRequestType       A fully-qualified class name of a type implementing McpModel for incoming requests.
     * @param string $receiveNotificationType  A fully-qualified class name of a type implementing McpModel for incoming notifications.
     */
    public function __construct(
        private readonly string $receiveRequestType,
        private readonly string $receiveNotificationType,
    ) {}

    /**
     * Initializes the session and starts message processing.
     */
    public function initialize(): void {
        if ($this->isInitialized) {
            throw new RuntimeException('Session already initialized');
        }
        $this->isInitialized = true;
        $this->startMessageProcessing();
    }

    /**
     * Checks if the session is initialized.
     */
    public function isInitialized(): bool {
        return $this->isInitialized;
    }

    /**
     * Closes the session and stops message processing.
     */
    public function close(): void {
        if (!$this->isInitialized) {
            return;
        }
        $this->stopMessageProcessing();
        $this->isInitialized = false;
    }

    /**
     * Sends a request and waits for a typed result. If an error response is received, throws an exception.
     * @param McpModel $request A typed request object (e.g., InitializeRequest, PingRequest).
     * @param string $resultType The fully-qualified class name of the expected result type (must implement McpModel).
     * @return McpModel The validated result object.
     * @throws McpError If an error response is received.
     */
    public function sendRequest(McpModel $request, string $resultType): McpModel {
        $this->validateRequestObject($request);

        $requestIdValue = $this->requestId++;
        $requestId = new RequestId($requestIdValue);

        // Convert the typed request into a JSON-RPC request message
        // Assuming $request has public properties: method, params
        $jsonRpcRequest = new JsonRpcMessage(new JSONRPCRequest(
            jsonrpc: '2.0',
            id: $requestId,
            method: $request->method,
            params: $request->params ?? null
        ));

        // Store a handler that will be called when a response with this requestId is received
        $futureResult = null;
        $this->responseHandlers[$requestIdValue] = function (JsonRpcMessage $message) use (&$futureResult, $resultType): void {
            $innerMessage = $message->message;

            if ($innerMessage instanceof JSONRPCError) {
                // It's an error response
                // Convert JsonRpcErrorObject into ErrorData
                $errorData = new \Mcp\Shared\ErrorData(
                    code: $innerMessage->error->code,
                    message: $innerMessage->error->message,
                    data: $innerMessage->error->data
                );
                throw new McpError($errorData);
            } elseif ($innerMessage instanceof JSONRPCResponse) {
                // It's a success response
                // Validate the result using $resultType
                /** @var McpModel $resultInstance */
                $resultInstance = $resultType::fromResponseData($innerMessage->result);
                $futureResult = $resultInstance;
            } else {
                // Invalid response
                throw new InvalidArgumentException('Invalid JSON-RPC response received');
            }
        };

        // Send the request message
        $this->writeMessage($jsonRpcRequest);

        // Wait for the response synchronously
        return $this->waitForResponse($requestIdValue, $resultType, $futureResult);
    }

    /**
     * Sends a notification. Notifications do not expect a response.
     * @param McpModel $notification A typed notification object.
     */
    public function sendNotification(McpModel $notification): void {
        // Convert the typed notification into a JSON-RPC notification message
        $jsonRpcNotification = new JSONRPCNotification(
            jsonrpc: '2.0',
            method: $notification->method,
            params: $notification->params ?? null
        );

        $jsonRpcMessage = new JsonRpcMessage($jsonRpcNotification);

        $this->writeMessage($jsonRpcMessage);
    }

    /**
     * Sends a response to a previously received request.
     * @param RequestId $requestId The request ID to respond to.
     * @param McpModel|ErrorData $response Either a typed result model or an ErrorData for an error response.
     */
    public function sendResponse(RequestId $requestId, mixed $response): void {
        if ($response instanceof ErrorData) {
            // Error response
            $jsonRpcError = new JSONRPCError(
                jsonrpc: '2.0',
                id: $requestId,
                error: new JsonRpcErrorObject(
                    code: $response->code,
                    message: $response->message,
                    data: $response->data ?? null
                )
            );
            $message = new JsonRpcMessage($jsonRpcError);
        } else {
            // Success result
            // Assuming $response implements jsonSerialize()
            $jsonRpcResponse = new JSONRPCResponse(
                jsonrpc: '2.0',
                id: $requestId,
                result: $response
            );
            $message = new JsonRpcMessage($jsonRpcResponse);
        }

        $this->writeMessage($message);
    }

    /**
     * Sends a progress notification for a request currently in progress.
     */
    public function sendProgressNotification(
        ProgressToken $progressToken,
        float $progress,
        ?float $total = null
    ): void {
        $progressNotification = new ProgressNotification(
            progressToken: $progressToken,
            progress: $progress,
            total: $total
        );

        $jsonRpcNotification = new JSONRPCNotification(
            jsonrpc: '2.0',
            method: $progressNotification->method,
            params: $progressNotification->params
        );

        $jsonRpcMessage = new JsonRpcMessage($jsonRpcNotification);

        $this->writeMessage($jsonRpcMessage);
    }

    /**
     * Registers a callback to handle incoming requests.
     * The callback will receive a RequestResponder as argument.
     */
    public function onRequest(callable $handler): void {
        $this->requestHandlers[] = $handler;
    }

    /**
     * Registers a callback to handle incoming notifications.
     */
    public function onNotification(callable $handler): void {
        $this->notificationHandlers[] = $handler;
    }

    /**
     * Handles an incoming message. Called by the subclass that implements message processing.
     * @param JsonRpcMessage $message The incoming message.
     */
    protected function handleIncomingMessage(JsonRpcMessage $message): void {
        $this->validateMessage($message);

        $innerMessage = $message->message;

        // Add support for batch requests and responses
        if ($innerMessage instanceof JSONRPCBatchRequest || $innerMessage instanceof JSONRPCBatchResponse) {
            // Process each item in the batch as if it were a standalone JSON-RPC message
            foreach ($innerMessage->messages as $subMsg) {
                $this->handleIncomingMessage(
                    new JsonRpcMessage($subMsg)
                );
            }
            return;
        }

        if ($innerMessage instanceof JSONRPCRequest) {
            // It's a request
            $request = $this->validateIncomingRequest($innerMessage);

            // Validate request
            $request->validate();

            $paramsArray = [];
            if ($innerMessage->params instanceof \Mcp\Types\McpModel) {
                // Convert to array. This ensures even empty \stdClass is cast to [].
                $serialized = $innerMessage->params->jsonSerialize();
                if ($serialized instanceof \stdClass) {
                    $serialized = (array) $serialized;
                }
                $paramsArray = (array) $serialized; 
            } elseif (is_array($innerMessage->params)) {
                $paramsArray = $innerMessage->params;
            }

            // Now pass the entire param array into RequestResponder
            $responder = new RequestResponder(
                requestId: $innerMessage->id,
                params: $paramsArray,
                request: $request,
                session: $this
            );

            // Call onRequest handlers
            foreach ($this->requestHandlers as $handler) {
                $handler($responder);
            }
        } elseif ($innerMessage instanceof JSONRPCResponse || $innerMessage instanceof JSONRPCError) {
            // It's a response
            $requestIdValue = $innerMessage->id->getValue();
            if (isset($this->responseHandlers[$requestIdValue])) {
                $handler = $this->responseHandlers[$requestIdValue];
                unset($this->responseHandlers[$requestIdValue]);
                $handler($message);
            } else {
                // Received a response for an unknown request ID
                // Log or handle error as appropriate
            }
        } elseif ($innerMessage instanceof JSONRPCNotification) {
            // It's a notification
            $notification = $this->validateIncomingNotification($innerMessage);
            $notification->validate();

            // Call onNotification handlers
            foreach ($this->notificationHandlers as $handler) {
                $handler($notification);
            }
        } else {
            // Invalid message type
            throw new InvalidArgumentException('Invalid message type received');
        }
    }

    private function validateMessage(JsonRpcMessage $message): void {
        $innerMessage = $message->message;
        if ($innerMessage->jsonrpc !== '2.0') {
            throw new InvalidArgumentException('Invalid JSON-RPC version');
        }
    }

    private function validateRequestObject(McpModel $request): void {
        // Check if request has a method property
        if (!property_exists($request, 'method') || empty($request->method)) {
            throw new InvalidArgumentException('Request must have a method');
        }
    }

    /**
     * Converts an incoming JSONRPCRequest into a typed request object.
     * @throws InvalidArgumentException If instantiation fails.
     */
    private function validateIncomingRequest(JSONRPCRequest $message): McpModel {
        $requestClass = $this->receiveRequestType;
        
        $params = $message->params ?? [];
        if (is_object($params)) {
            // Force cast to array
            $params = (array) $params->jsonSerialize();
        }
        
        $request = $requestClass::fromMethodAndParams($message->method, $params);
        return $request;
    }

    /**
     * Converts an incoming JSONRPCNotification into a typed notification object.
     * @throws InvalidArgumentException If instantiation fails.
     */
    private function validateIncomingNotification(JSONRPCNotification $message): McpModel {
        $notificationClass = $this->receiveNotificationType;
        
        $params = $message->params ?? [];
        if (is_object($params)) {
            // Force cast to array
            $params = (array) $params->jsonSerialize();
        }
    
        $notification = $notificationClass::fromMethodAndParams($message->method, $params);
        return $notification;
    }

    /**
     * Waits for a response with the given requestId, blocking until it arrives.
     * In a synchronous environment, this might mean reading messages from the underlying transport
     * until we find a response with the correct ID.
     *
     * @param int $requestIdValue The numeric request ID value.
     * @param string $resultType The expected result type.
     * @param McpModel|null $futureResult A reference that will be set by the response handler closure.
     * @return McpModel The result object.
     * @throws McpError If an error response is received.
     * @throws InvalidArgumentException If no result is received.
     */
    protected function waitForResponse(int $requestIdValue, string $resultType, ?McpModel &$futureResult): McpModel {
        // The handler we set above will set $futureResult when the response arrives.
        // So we run a loop reading messages until $futureResult is not null or an error is thrown.

        while ($futureResult === null) {
            $message = $this->readNextMessage();
            $this->handleIncomingMessage($message);
            // If the response handler threw an exception (McpError), it won't reach here.
            // Otherwise, we keep looping until futureResult is set.
        }

        return $futureResult;
    }

    /**
     * Reads the next message from the underlying transport.
     * This must be implemented by subclasses and should block until a message is available.
     */
    abstract protected function readNextMessage(): JsonRpcMessage;

    /**
     * Starts message processing. For a synchronous model, this might be a no-op or set up resources.
     */
    abstract protected function startMessageProcessing(): void;

    /**
     * Stops message processing. For synchronous model, may close streams or sockets.
     */
    abstract protected function stopMessageProcessing(): void;

    /**
     * Writes a JsonRpcMessage to the underlying transport.
     * Implementations must serialize the message to JSON and send it to the peer.
     */
    abstract protected function writeMessage(JsonRpcMessage $message): void;
}
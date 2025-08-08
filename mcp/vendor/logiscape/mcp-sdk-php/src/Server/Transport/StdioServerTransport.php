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
 * Filename: Server/Transport/StdioServerTransport.php
 */

declare(strict_types=1);

namespace Mcp\Server\Transport;

use Mcp\Types\JsonRpcMessage;
use Mcp\Types\RequestId;
use Mcp\Shared\McpError;
use Mcp\Shared\ErrorData as TypesErrorData;
use Mcp\Types\JsonRpcErrorObject;
use Mcp\Types\JSONRPCRequest;
use Mcp\Types\JSONRPCNotification;
use Mcp\Types\JSONRPCResponse;
use Mcp\Types\JSONRPCError;
use Mcp\Types\JSONRPCBatchRequest;
use Mcp\Types\JSONRPCBatchResponse;
use Mcp\Types\RequestParams;
use Mcp\Types\NotificationParams;
use Mcp\Types\Result;
use RuntimeException;
use InvalidArgumentException;

/**
 * Class StdioServerTransport
 *
 * STDIO-based transport implementation for MCP servers.
 * Handles reading from STDIN and writing to STDOUT using JSON-RPC 2.0 protocol.
 */
class StdioServerTransport implements Transport {
    /** @var resource */
    private $stdin;
    /** @var resource */
    private $stdout;
    /** @var array<string> */
    private array $writeBuffer = [];
    /** @var bool */
    private bool $isStarted = false;

    /**
     * StdioServerTransport constructor.
     *
     * @param resource|null $stdin  Input stream (defaults to STDIN)
     * @param resource|null $stdout Output stream (defaults to STDOUT)
     *
     * @throws InvalidArgumentException If provided streams are not valid resources.
     */
    public function __construct(
        $stdin = null,
        $stdout = null
    ) {
        if ($stdin !== null && !is_resource($stdin)) {
            throw new InvalidArgumentException('stdin must be a valid resource.');
        }
        if ($stdout !== null && !is_resource($stdout)) {
            throw new InvalidArgumentException('stdout must be a valid resource.');
        }

        $this->stdin = $stdin ?? STDIN;
        $this->stdout = $stdout ?? STDOUT;
    }

    /**
     * Starts the transport by setting streams to non-blocking mode if applicable.
     *
     * @throws RuntimeException If the transport is already started or if setting non-blocking mode fails.
     */
    public function start(): void {
        if ($this->isStarted) {
            throw new RuntimeException('Transport already started');
        }

        // Determine the operating system
        $os = PHP_OS_FAMILY;

        // Set streams to non-blocking mode if not on Windows
        if ($os !== 'Windows') {
            if (!stream_set_blocking($this->stdin, false)) {
                throw new RuntimeException('Failed to set stdin to non-blocking mode');
            }
            if (!stream_set_blocking($this->stdout, false)) {
                throw new RuntimeException('Failed to set stdout to non-blocking mode');
            }
        }

        $this->isStarted = true;
    }

    /**
     * Stops the transport and flushes any remaining messages in the buffer.
     */
    public function stop(): void {
        if (!$this->isStarted) {
            return;
        }

        $this->flush();
        $this->isStarted = false;
    }

    /**
     * Checks if there is data available to read from STDIN.
     *
     * @return bool True if data is available, false otherwise.
     */
    public function hasDataAvailable(): bool {
        $read = [$this->stdin];
        $write = $except = [];
        // Timeout of 0 for non-blocking check
        return stream_select($read, $write, $except, 0) > 0;
    }

    /**
     * Reads the next JSON-RPC message from STDIN.
     * Now supports detection of a top-level JSON array (batch).
     *
     * @return JsonRpcMessage|null
     *   Returns a JsonRpcMessage if successfully parsed,
     *   or null if no data is available.
     * @throws RuntimeException if transport not started
     * @throws McpError on JSON parsing or validation error
     */
    public function readMessage(): ?JsonRpcMessage {
        if (!$this->isStarted) {
            throw new RuntimeException('Transport not started');
        }

        $line = fgets($this->stdin);
        if ($line === false) {
            // No data to read
            return null;
        }

        // Decode JSON with strict error handling
        try {
            $decoded = json_decode($line, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            // JSON parse error
            throw new McpError(
                new TypesErrorData(
                    code: -32700,
                    message: 'Parse error: ' . $e->getMessage()
                )
            );
        }

        // If top-level is an array => batch
        if (is_array($decoded) && $this->isListArray($decoded)) {
            return $this->instantiateBatch($decoded);
        }

        // Otherwise, parse as a single message
        return $this->instantiateSingleMessage($decoded);
    }

    /**
     * Determine if an array's keys are 0..n-1 (i.e. a list).
     */
    private function isListArray(array $data): bool {
        // If you're on PHP 8.1+, you can simply do: return array_is_list($data);
        return array_keys($data) === range(0, count($data) - 1);
    }

    /**
     * Convert a JSON array of objects (batch) into a single JsonRpcMessage
     * containing a JSONRPCBatchRequest or JSONRPCBatchResponse.
     *
     * @param array $batchData - An indexed array of JSON-RPC sub-messages.
     *
     * @throws McpError on invalid sub-message
     */
    private function instantiateBatch(array $batchData): JsonRpcMessage {
        if (count($batchData) === 0) {
            // JSON-RPC spec says an empty array is a valid batch but requires no response.
            // You can decide whether to treat it as an error or just return null.
            // For illustration, let's just throw an error:
            throw new McpError(
                new TypesErrorData(
                    code: -32600,
                    message: 'Invalid Request: empty batch'
                )
            );
        }

        $subMessages = [];
        foreach ($batchData as $item) {
            if (!is_array($item)) {
                // Each sub-message must be a valid JSON object
                throw new McpError(
                    new TypesErrorData(
                        code: -32600,
                        message: 'Invalid sub-message in batch (not an object)'
                    )
                );
            }
            // Reuse single-message parsing
            $subMessages[] = $this->instantiateSingleMessage($item)->message;
        }

        // Heuristics to decide request batch vs. response batch:
        $firstMsg = $subMessages[0];
        if ($firstMsg instanceof JSONRPCRequest || $firstMsg instanceof JSONRPCNotification) {
            return new JsonRpcMessage(new JSONRPCBatchRequest($subMessages));
        } else {
            return new JsonRpcMessage(new JSONRPCBatchResponse($subMessages));
        }
    }

    /**
     * Parse one single JSON‐RPC message object into the appropriate subtype.
     *
     * @param array $data Decoded JSON object.
     */
    private function instantiateSingleMessage(array $data): JsonRpcMessage {
        // Must have "jsonrpc": "2.0"
        if (!isset($data['jsonrpc']) || $data['jsonrpc'] !== '2.0') {
            throw new McpError(
                new TypesErrorData(
                    code: -32600,
                    message: 'Invalid Request: jsonrpc version must be "2.0"'
                )
            );
        }

        // Check which fields are present
        $hasMethod = array_key_exists('method', $data);
        $hasId = array_key_exists('id', $data);
        $hasResult = array_key_exists('result', $data);
        $hasError = array_key_exists('error', $data);

        // Initialize a RequestId if present
        $id = null;
        if ($hasId) {
            $id = new RequestId($data['id']);
        }

        try {
            if ($hasError) {
                // JSONRPCError
                return new JsonRpcMessage($this->buildErrorMessage($data, $id));
            } elseif ($hasMethod && $hasId && !$hasResult) {
                // JSONRPCRequest
                return new JsonRpcMessage($this->buildRequestMessage($data, $id));
            } elseif ($hasMethod && !$hasId && !$hasResult && !$hasError) {
                // JSONRPCNotification
                return new JsonRpcMessage($this->buildNotificationMessage($data));
            } elseif ($hasId && $hasResult && !$hasMethod && !$hasError) {
                // JSONRPCResponse
                return new JsonRpcMessage($this->buildResponseMessage($data, $id));
            } else {
                // Could not classify
                throw new McpError(
                    new TypesErrorData(
                        code: -32600,
                        message: 'Invalid Request: could not determine message type'
                    )
                );
            }
        } catch (McpError $e) {
            // Bubble up as-is
            throw $e;
        } catch (\Exception $e) {
            // Other exceptions become parse errors
            throw new McpError(
                new TypesErrorData(
                    code: -32700,
                    message: 'Parse error: ' . $e->getMessage()
                )
            );
        }
    }

    /**
     * Build a JSONRPCError object from decoded data.
     */
    private function buildErrorMessage(array $data, ?RequestId $id): JSONRPCError {
        $errorData = $data['error'];
        if (!isset($errorData['code']) || !isset($errorData['message'])) {
            throw new McpError(
                new TypesErrorData(
                    code: -32600,
                    message: 'Invalid Request: error object must contain code and message'
                )
            );
        }
        $errorObj = new JsonRpcErrorObject(
            code: $errorData['code'],
            message: $errorData['message'],
            data: $errorData['data'] ?? null
        );
        $msg = new JSONRPCError(
            jsonrpc: '2.0',
            id: $id ?? new RequestId(''), // per JSON-RPC, error typically has an ID
            error: $errorObj
        );
        $msg->validate();
        return $msg;
    }

    /**
     * Build a JSONRPCRequest object from decoded data.
     */
    private function buildRequestMessage(array $data, ?RequestId $id): JSONRPCRequest {
        $method = $data['method'];
        $params = isset($data['params']) && is_array($data['params'])
            ? $this->parseRequestParams($data['params'])
            : null;

        $req = new JSONRPCRequest(
            jsonrpc: '2.0',
            id: $id,
            method: $method,
            params: $params
        );
        $req->validate();
        return $req;
    }

    /**
     * Build a JSONRPCNotification object from decoded data.
     */
    private function buildNotificationMessage(array $data): JSONRPCNotification {
        $method = $data['method'];
        $params = isset($data['params']) && is_array($data['params'])
            ? $this->parseNotificationParams($data['params'])
            : null;

        $not = new JSONRPCNotification(
            jsonrpc: '2.0',
            method: $method,
            params: $params
        );
        $not->validate();
        return $not;
    }

    /**
     * Build a JSONRPCResponse object from decoded data.
     */
    private function buildResponseMessage(array $data, ?RequestId $id): JSONRPCResponse {
        // E.g. you do a "generic" mapping to a simple Result object
        $resultArr = $data['result'];
        $resultObj = new Result();
        if (is_array($resultArr)) {
            foreach ($resultArr as $k => $v) {
                if ($k !== '_meta') {
                    $resultObj->$k = $v;
                }
            }
        }
        $resp = new JSONRPCResponse(
            jsonrpc: '2.0',
            id: $id,
            result: $resultObj
        );
        $resp->validate();
        return $resp;
    }

    /**
     * Writes a JSON-RPC message to STDOUT.
     *
     * @param JsonRpcMessage $message The JSON-RPC message to send.
     *
     * @throws RuntimeException If the transport is not started or if writing fails.
     */
    public function writeMessage(JsonRpcMessage $message): void {
        if (!$this->isStarted) {
            throw new RuntimeException('Transport not started');
        }

        // Encode the JsonRpcMessage to JSON
        $json = json_encode($message, JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE);
        if ($json === false) {
            throw new RuntimeException('Failed to encode message as JSON: ' . json_last_error_msg());
        }

        // Append newline as per JSON-RPC over STDIO specification
        $json .= "\n";

        // Buffer the message
        $this->writeBuffer[] = $json;

        // Attempt to flush immediately for non-blocking behavior
        $this->flush();
    }

    /**
     * Flushes the write buffer by sending all buffered messages to STDOUT.
     *
     * @throws RuntimeException If writing to STDOUT fails.
     */
    public function flush(): void {
        if (!$this->isStarted) {
            return;
        }

        while (!empty($this->writeBuffer)) {
            $data = array_shift($this->writeBuffer);
            $written = fwrite($this->stdout, $data);

            if ($written === false) {
                throw new RuntimeException('Failed to write to stdout');
            }

            // Handle partial writes by re-buffering the unwritten part
            if ($written < strlen($data)) {
                $this->writeBuffer = [substr($data, $written), ...$this->writeBuffer];
            } else {
                break;
            }
        }

        // Ensure all buffered data is sent
        fflush($this->stdout);
    }

    /**
     * Creates a new instance of StdioServerTransport with default STDIN and STDOUT.
     *
     * @param resource|null $stdin  Input stream (defaults to STDIN)
     * @param resource|null $stdout Output stream (defaults to STDOUT)
     *
     * @return self
     */
    public static function create($stdin = null, $stdout = null): self {
        return new self($stdin, $stdout);
    }

    /**
     * Parses request parameters from an associative array.
     *
     * @param array $params The parameters array from the JSON-RPC request.
     *
     * @return RequestParams The parsed RequestParams object.
     */
    private function parseRequestParams(array $params): RequestParams {
        $meta = isset($params['_meta']) ? $this->metaFromArray($params['_meta']) : null;

        // Correctly passing $meta as the first argument
        $requestParams = new RequestParams($meta);

        // Assign other parameters dynamically
        foreach ($params as $key => $value) {
            if ($key !== '_meta') {
                $requestParams->$key = $value;
            }
        }

        return $requestParams;
    }

    /**
     * Parses notification parameters from an associative array.
     *
     * @param array $params The parameters array from the JSON-RPC notification.
     *
     * @return NotificationParams The parsed NotificationParams object.
     */
    private function parseNotificationParams(array $params): NotificationParams {
        $meta = isset($params['_meta']) ? $this->metaFromArray($params['_meta']) : null;

        // Correctly passing $meta as the first argument
        $notificationParams = new NotificationParams($meta);

        // Assign other parameters dynamically
        foreach ($params as $key => $value) {
            if ($key !== '_meta') {
                $notificationParams->$key = $value;
            }
        }

        return $notificationParams;
    }

    /**
     * Helper method to create a Meta object from an associative array.
     *
     * @param array $metaArr The meta information array.
     *
     * @return \Mcp\Types\Meta The constructed Meta object.
     */
    private function metaFromArray(array $metaArr): \Mcp\Types\Meta {
        $meta = new \Mcp\Types\Meta();
        foreach ($metaArr as $key => $value) {
            $meta->$key = $value;
        }
        return $meta;
    }
}

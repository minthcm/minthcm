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
 * Filename: Client/Transport/StreamableHttpTransport.php
 */

 declare(strict_types=1);

 namespace Mcp\Client\Transport;
 
 use Mcp\Shared\MemoryStream;
 use Mcp\Types\JsonRpcMessage;
 use Mcp\Types\JSONRPCResponse;
 use Mcp\Types\JSONRPCError;
 use Mcp\Types\JsonRpcErrorObject;
 use Mcp\Types\RequestId;
 use Psr\Log\LoggerInterface;
 use Psr\Log\NullLogger;
 use RuntimeException;
 use InvalidArgumentException;
 use CurlHandle;
 
 /**
  * Implements the Streamable HTTP transport for MCP.
  * 
  * This transport uses HTTP POST for sending messages to the server and
  * supports both direct JSON responses and Server-Sent Events (SSE) for
  * receiving messages from the server.
  */
 class StreamableHttpTransport {
     /**
      * The session manager for handling MCP session state
      */
     private HttpSessionManager $sessionManager;
     
     /**
      * The HTTP configuration
      */
     private HttpConfiguration $config;
     
     /**
      * The logger instance
      */
     private LoggerInterface $logger;
     
     /**
      * The SSE connection (if active)
      */
     private ?SseConnection $sseConnection = null;
     
     /**
      * Whether to automatically attempt to use SSE
      */
     private bool $autoSse;
     
     /**
      * Queue of pending SSE messages
      */
     private array $pendingMessages = [];
     
     /**
      * Creates a new StreamableHttpTransport.
      *
      * @param HttpConfiguration $config Configuration for the HTTP transport
      * @param bool $autoSse Whether to automatically use SSE when available
      * @param LoggerInterface|null $logger PSR-3 compatible logger
      * 
      * @throws RuntimeException If cURL extension is not available
      */
     public function __construct(
         HttpConfiguration $config,
         bool $autoSse = true,
         ?LoggerInterface $logger = null
     ) {
         if (!extension_loaded('curl')) {
             throw new RuntimeException('cURL extension is required for StreamableHttpTransport');
         }
         
         $this->config = $config;
         $this->autoSse = $autoSse && $config->isSseEnabled();
         $this->logger = $logger ?? new NullLogger();
         $this->sessionManager = new HttpSessionManager($this->logger);
     }
     
     /**
      * Establishes connection to the MCP server.
      *
      * @return array{MemoryStream, MemoryStream} Tuple of read and write streams
      * 
      * @throws RuntimeException If connection fails
      */
     public function connect(): array {
         $this->logger->info("Connecting to MCP endpoint: {$this->config->getEndpoint()}");
         
         // Initialize read and write streams
         $readStream = $this->createReadStream();
         $writeStream = $this->createWriteStream();
         
         if ($this->autoSse) {
             // Attempt to establish an SSE connection             
             $this->attemptSseConnection();            
         }
         
         return [$readStream, $writeStream];
     }
     
     /**
      * Attempts to establish an SSE connection for receiving server messages.
      * 
      * This is an optimization - if successful, we'll have a channel for the server
      * to send us messages without us having to poll.
      */
     private function attemptSseConnection(): void {
         try {
             $headers = $this->prepareRequestHeaders([
                 'Accept' => 'text/event-stream'
             ]);
             
             $ch = curl_init($this->config->getEndpoint());
             if ($ch === false) {
                 throw new RuntimeException('Failed to initialize cURL');
             }
             
             $this->configureCurlHandle($ch, $headers);
             curl_setopt($ch, CURLOPT_HTTPGET, true);
             
             // Set up write callback - we're going to check headers to see if this is an SSE stream
             $responseHeaders = [];
             $headerCallback = function($ch, $header) use (&$responseHeaders) {
                 $length = strlen($header);
                 
                 // Parse header line
                 $parts = explode(':', $header, 2);
                 if (count($parts) == 2) {
                     $name = trim($parts[0]);
                     $value = trim($parts[1]);
                     $responseHeaders[strtolower($name)] = $value;
                 }
                 
                 return $length;
             };
             
             curl_setopt($ch, CURLOPT_HEADERFUNCTION, $headerCallback);
             
             // Set a small buffer to check if the server responds with SSE
             $buffer = '';
             $writeCallback = function($ch, $data) use (&$buffer) {
                 $buffer .= $data;
                 // Only capture a little bit to check the response type
                 return strlen($data) > 0 && strlen($buffer) < 256 ? strlen($data) : 0;
             };
             
             curl_setopt($ch, CURLOPT_WRITEFUNCTION, $writeCallback);
             
             // Execute the request
             $result = curl_exec($ch);
             $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
             curl_close($ch);
             
             // Check if the server supports SSE
             if ($result !== false && $httpCode === 200) {
                 $contentType = $responseHeaders['content-type'] ?? '';
                 if (strpos($contentType, 'text/event-stream') !== false) {
                     $this->logger->info('Server supports SSE, will establish streaming connection');
                     
                     // Create an actual SSE connection (reusing the configuration)
                     $this->sseConnection = new SseConnection(
                         $this->config,
                         $this->sessionManager,
                         $this->logger
                     );
                     
                     // Start the connection (which runs in a separate thread/process)
                     $this->sseConnection->start();
                     return;
                 }
             }
             
             // If we get here, SSE is not supported or failed
             $this->logger->info('Server does not support SSE or returned error, will use polling');
             
         } catch (\Exception $e) {
             $this->logger->warning("Failed to establish SSE connection: {$e->getMessage()}");
         }
     }
     
     /**
      * Sends a JSON-RPC message to the server via HTTP POST.
      * 
      * @param JsonRpcMessage $message The message to send
      * @return array The response data [statusCode, headers, body]
      * 
      * @throws RuntimeException If the request fails
      */
     public function sendMessage(JsonRpcMessage $message): array {
         $endpoint = $this->config->getEndpoint();
         $payload = json_encode($message->jsonSerialize());
         
         if ($payload === false) {
             throw new RuntimeException('Failed to encode message: ' . json_last_error_msg());
         }
         
         $headers = $this->prepareRequestHeaders([
             'Content-Type' => 'application/json',
             'Accept' => 'application/json, text/event-stream'
         ]);
         
         $ch = curl_init($endpoint);
         if ($ch === false) {
             throw new RuntimeException('Failed to initialize cURL');
         }
         
         $this->configureCurlHandle($ch, $headers);
         
         // Configure for POST request with the JSON payload
         curl_setopt($ch, CURLOPT_POST, true);
         curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
         
         // Set up response capture
         $responseBody = '';
         curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($ch, $data) use (&$responseBody) {
             $responseBody .= $data;            
             return strlen($data);
         });
         
         $responseHeaders = [];
         curl_setopt($ch, CURLOPT_HEADERFUNCTION, function($ch, $header) use (&$responseHeaders) {
             $length = strlen($header);
             
             // Parse header line
             $parts = explode(':', $header, 2);
             if (count($parts) == 2) {
                 $name = trim(strtolower($parts[0]));
                 $value = trim($parts[1]);
                 $responseHeaders[$name] = $value;
             }
             
             return $length;
         });
         
         // Execute the request
         $result = curl_exec($ch);
         
         if ($result === false) {
             $error = curl_error($ch);
             $errno = curl_errno($ch);
             curl_close($ch);
             throw new RuntimeException("HTTP request failed: ({$errno}) {$error}");
         }
         
         $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
         curl_close($ch);
         
         // Check if we should process the response differently based on content-type
         $contentType = $responseHeaders['content-type'] ?? '';
         
         // Process SSE response if that's what we got
         if (strpos($contentType, 'text/event-stream') !== false) {
             $this->logger->info('Received SSE response to JSON-RPC message');
             return $this->processSseResponse($responseBody, $responseHeaders, $statusCode);
         }
         
         // Update session based on response
         $isInitialization = $this->isInitializationMessage($message);
         $sessionValid = $this->sessionManager->processResponseHeaders($responseHeaders, $statusCode, $isInitialization);
         
         if (!$sessionValid) {
             $this->logger->warning('Session invalidated, client should reinitialize');
         }
         
         // ENQUEUE the HTTP JSON‑RPC response for the read loop
         $decoded = json_decode($responseBody, true);
         if (json_last_error() === JSON_ERROR_NONE) {
             // Support both single and batch responses
             $batch = isset($decoded[0]) && is_array($decoded) ? $decoded : [ $decoded ];
             foreach ($batch as $item) {
                 // Success response?
                 if (isset($item['jsonrpc'], $item['id']) && array_key_exists('result', $item)) {
                     $idObj = new RequestId($item['id']);

                     $inner = new JSONRPCResponse(
                         jsonrpc: $item['jsonrpc'],
                         id:      $idObj,
                         result:  $item['result']
                     );
                     $this->pendingMessages[] = new JsonRpcMessage($inner);

                 // Error response?
                 } elseif (isset($item['jsonrpc'], $item['id'], $item['error'])) {
                     $err = $item['error'];
                     
                     // Validate error structure
                     if (!is_array($err)) {
                         continue;
                     }
                     
                     if (!isset($err['code']) || !isset($err['message'])) {
                         continue;
                     }
                     
                     // Ensure code is integer and message is string
                     $errorCode = is_numeric($err['code']) ? (int)$err['code'] : 0;
                     $errorMessage = is_string($err['message']) ? $err['message'] : 'Error desconocido';
                     $errorData = isset($err['data']) ? $err['data'] : null;
                     
                     $idObj = new RequestId($item['id']);

                     $inner = new JSONRPCError(
                         jsonrpc: $item['jsonrpc'],
                         id:      $idObj,
                         error:   new JsonRpcErrorObject(
                             code:    $errorCode,
                             message: $errorMessage,
                             data:    $errorData
                         )
                     );
                     $this->pendingMessages[] = new JsonRpcMessage($inner);
                 }
             }
         }

         return [
             'statusCode' => $statusCode,
             'headers' => $responseHeaders,
             'body' => $responseBody
         ];
     }
     
     /**
      * Process an SSE response to a JSON-RPC message.
      * 
      * @param string $initialBody Initial chunk of the SSE stream
      * @param array $headers HTTP response headers
      * @param int $statusCode HTTP status code
      * @return array Processed response data
      */
     private function processSseResponse(string $initialBody, array $headers, int $statusCode): array {
         // We need to determine if this is an initialization response by examining the SSE data
         $jsonData = $this->extractJsonFromSse($initialBody);
         $decoded = json_decode($jsonData, true);     
         $isInitialization = false;
         
         // Check if this is a response to an initialize request
         if (json_last_error() === JSON_ERROR_NONE && isset($decoded['id']) && $decoded['id'] === 0) {
             // ID 0 is typically used for the initialize request
             $isInitialization = true;
         }
         
         // Update session based on response headers
         $sessionValid = $this->sessionManager->processResponseHeaders($headers, $statusCode, $isInitialization);
         
         if (!$sessionValid) {
             $this->logger->warning('Session invalidated during SSE response');
         }
         
         // Extract JSON data from SSE format
         $jsonData = $this->extractJsonFromSse($initialBody);
         
         // ENQUEUE the HTTP JSON‑RPC response for the read loop (same as normal HTTP processing)
         $decoded = json_decode($jsonData, true);
         if (json_last_error() === JSON_ERROR_NONE) {
             // Support both single and batch responses
             $batch = isset($decoded[0]) && is_array($decoded) ? $decoded : [ $decoded ];
             foreach ($batch as $item) {
                 // Success response?
                 if (isset($item['jsonrpc'], $item['id']) && array_key_exists('result', $item)) {
                     $idObj = new RequestId($item['id']);

                     $inner = new JSONRPCResponse(
                         jsonrpc: $item['jsonrpc'],
                         id:      $idObj,
                         result:  $item['result']
                     );
                     $this->pendingMessages[] = new JsonRpcMessage($inner);

                 // Error response?
                 } elseif (isset($item['jsonrpc'], $item['id'], $item['error'])) {
                     $err = $item['error'];
                     
                     // Validate error structure
                     if (!is_array($err)) {
                         continue;
                     }
                     
                     if (!isset($err['code']) || !isset($err['message'])) {
                         continue;
                     }
                     
                     // Ensure code is integer and message is string
                     $errorCode = is_numeric($err['code']) ? (int)$err['code'] : 0;
                     $errorMessage = is_string($err['message']) ? $err['message'] : 'Error desconocido';
                     $errorData = isset($err['data']) ? $err['data'] : null;
                     
                     $idObj = new RequestId($item['id']);

                     $inner = new JSONRPCError(
                         jsonrpc: $item['jsonrpc'],
                         id:      $idObj,
                         error:   new JsonRpcErrorObject(
                             code:    $errorCode,
                             message: $errorMessage,
                             data:    $errorData
                         )
                     );
                     $this->pendingMessages[] = new JsonRpcMessage($inner);
                 }
             }
         }
         
         return [
             'statusCode' => $statusCode,
             'headers' => $headers,
             'body' => $initialBody,
             'isEventStream' => true
         ];
     }
     
     /**
      * Extract JSON data from SSE format.
      * 
      * @param string $sseData The raw SSE data
      * @return string The extracted JSON data
      */
     private function extractJsonFromSse(string $sseData): string {
         // Parse SSE format: look for "data: " lines
         $lines = preg_split('/\r\n|\n|\r/', $sseData);
         $jsonData = '';
         
         foreach ($lines as $line) {
             if (strpos($line, 'data: ') === 0) {
                 $jsonData .= substr($line, 6); // Remove "data: " prefix
             }
         }
         
         return $jsonData;
     }
     
     /**
      * Check if a message is an initialization message.
      * 
      * @param JsonRpcMessage $message The message to check
      * @return bool True if it's an initialization message
      */
     private function isInitializationMessage(JsonRpcMessage $message): bool {
         // Examine the inner message to see if it's an initialize request
         $innerMessage = $message->message;
         
         // Check if it's a request with method "initialize"
         if (property_exists($innerMessage, 'method') && $innerMessage->method === 'initialize') {
             return true;
         }
         
         return false;
     }
     
     /**
      * Prepares HTTP headers for a request, merging defaults with the
      * configuration headers and session headers.
      * 
      * @param array $additionalHeaders Additional headers to include
      * @return array Complete set of headers for the request
      */
     private function prepareRequestHeaders(array $additionalHeaders = []): array {
         // Start with headers from configuration
         $headers = $this->config->getHeaders();
         
         // Add session headers if available
         if ($this->sessionManager->isInitialized()) {
             $headers = array_merge($headers, $this->sessionManager->getRequestHeaders());
         }
         
         // Add any additional headers for this specific request
         $headers = array_merge($headers, $additionalHeaders);
         
         // Convert to cURL format (array of "Name: Value" strings)
         $curlHeaders = [];
         foreach ($headers as $name => $value) {
             $curlHeaders[] = "{$name}: {$value}";
         }
         
         return $curlHeaders;
     }
     
     /**
      * Configures a cURL handle with common options.
      * 
      * @param CurlHandle $ch The cURL handle to configure
      * @param array $headers HTTP headers to set
      */
     private function configureCurlHandle($ch, array $headers): void {
         // Set common cURL options
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
         curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, (int)($this->config->getConnectionTimeout() * 1000));
         curl_setopt($ch, CURLOPT_TIMEOUT_MS, (int)($this->config->getReadTimeout() * 1000));
         
         // Configure TLS verification
         if ($this->config->isVerifyTlsEnabled()) {
             curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
             curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
             
             // Use custom CA file if provided
             if ($this->config->getCaFile() !== null) {
                 curl_setopt($ch, CURLOPT_CAINFO, $this->config->getCaFile());
             }
         } else {
             curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
             curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
         }
         
         // Apply any additional cURL options from configuration
         foreach ($this->config->getCurlOptions() as $option => $value) {
             curl_setopt($ch, $option, $value);
         }
     }
     
     /**
      * Creates a memory stream for reading responses from the server.
      * 
      * @return MemoryStream The read stream
      */
     private function createReadStream(): MemoryStream {
         return new class($this) extends MemoryStream {
             private StreamableHttpTransport $transport;
             
             public function __construct(StreamableHttpTransport $transport) {
                 $this->transport = $transport;
             }
             
             /**
              * Receive a message from the transport.
              * 
              * @return JsonRpcMessage|null The received message or null if none available
              */
             public function receive(): mixed {              
                 // First check if we have any pending messages from the SSE connection
                 if ($message = $this->transport->receiveFromSse()) {
                     return $message;
                 }

                 // Check if we have any pending messages from the HTTP JSON‑RPC response queue
                 if ($message = $this->transport->receiveFromHttp()) {
                     return $message;
                 }
                 
                 // No messages available right now
                 return null;
             }
         };
     }
     
     /**
      * Creates a memory stream for writing messages to the server.
      * 
      * @return MemoryStream The write stream
      */
     private function createWriteStream(): MemoryStream {
         return new class($this) extends MemoryStream {
             private StreamableHttpTransport $transport;
             
             public function __construct(StreamableHttpTransport $transport) {
                 $this->transport = $transport;
             }
             
             /**
              * Send a message through the transport.
              * 
              * @param mixed $message The message to send
              * @throws InvalidArgumentException If the message is not a JsonRpcMessage
              */
             public function send(mixed $message): void {
                 if (!$message instanceof JsonRpcMessage) {
                     throw new InvalidArgumentException('StreamableHttpTransport can only send JsonRpcMessage objects');
                 }
                 
                 // Send the message via HTTP POST
                 $this->transport->sendMessage($message);
             }
         };
     }
     
     /**
      * Receives a message from the SSE connection, if available.
      * 
      * @return JsonRpcMessage|null The received message or null if none available
      */
     public function receiveFromSse(): ?JsonRpcMessage {
         // Check if we have an active SSE connection
         if ($this->sseConnection === null) {
             return null;
         }
         
         // Check if connection is healthy
         if (!$this->sseConnection->isActive()) {
             $this->logger->warning('SSE connection is no longer active');
             $this->sseConnection = null;
             return null;
         }
         
         // Try to get a message from the SSE connection
         return $this->sseConnection->receiveMessage();
     }

     /**
      * Receives a message from the HTTP JSON‑RPC response queue.
      * 
      * @return JsonRpcMessage|null The received message or null if none available
      * @throws RuntimeException When there are persistent errors and no more messages
      */
     public function receiveFromHttp(): ?JsonRpcMessage {
          
         $message = array_shift($this->pendingMessages);
         
         if ($message !== null) {
    
             // Check if this is an error message
             $innerMessage = $message->message;
             if ($innerMessage instanceof \Mcp\Types\JSONRPCError) {
                 $error = $innerMessage->error;
                 // If it's a critical error  throw an exception     
                 $this->logger->error("Critical MCP error: {$error->message} (code: {$error->code})");          
                 throw new RuntimeException("Critical MCP error: {$error->message} (code: {$error->code})");
                 
             }
             
             return $message;
         } else {            
             return null;
         }
     }
     
     /**
      * Closes the transport connection.
      */
     public function close(): void {
         $this->logger->info('Closing HTTP transport connection');
         
         // Close SSE connection if active
         if ($this->sseConnection !== null) {
             $this->sseConnection->stop();
             $this->sseConnection = null;
         }
         
         // If we have a valid session, send DELETE request to terminate it
         if ($this->sessionManager->isValid() && $this->sessionManager->getSessionId() !== null) {
             try {
                 $this->terminateSession();
             } catch (\Exception $e) {
                 $this->logger->warning("Failed to terminate session: {$e->getMessage()}");
             }
         }
     }
     
     /**
      * Explicitly terminates the MCP session by sending an HTTP DELETE request.
      */
     private function terminateSession(): void {
         $endpoint = $this->config->getEndpoint();
         $headers = $this->prepareRequestHeaders();
         
         $ch = curl_init($endpoint);
         if ($ch === false) {
             throw new RuntimeException('Failed to initialize cURL for session termination');
         }
         
         $this->configureCurlHandle($ch, $headers);
         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
         
         // We don't care about the response content
         curl_setopt($ch, CURLOPT_NOBODY, true);
         
         // Execute the request
         $result = curl_exec($ch);
         $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
         curl_close($ch);
         
         if ($result === false) {
             $this->logger->warning('Failed to send session termination request');
         } else {
             $this->logger->info("Session termination request sent, status: {$statusCode}");
         }
         
         // Invalidate our session state
         $this->sessionManager->invalidateSession();
     }
 }

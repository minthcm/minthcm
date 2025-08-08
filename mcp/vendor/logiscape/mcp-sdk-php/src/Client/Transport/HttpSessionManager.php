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
 * Filename: Client/Transport/HttpSessionManager.php
 */

 declare(strict_types=1);

 namespace Mcp\Client\Transport;
 
 use Psr\Log\LoggerInterface;
 use Psr\Log\NullLogger;
 
 /**
  * Manages MCP session state for HTTP-based transports.
  * 
  * This class handles client-side session responsibilities as defined in the 
  * Streamable HTTP transport specification, including:
  * - Storing session IDs received from servers during initialization
  * - Including session IDs in all subsequent requests
  * - Tracking the last event ID for resumable connections
  * - Managing session termination and renewal
  */
 class HttpSessionManager {
     /**
      * The MCP session ID header name
      */
     private const SESSION_HEADER = 'Mcp-Session-Id';
     
     /**
      * The last event ID header name for resumable SSE connections
      */
     private const LAST_EVENT_HEADER = 'Last-Event-ID';
     
     /**
      * The session ID received from the server during initialization
      */
     private ?string $sessionId = null;
     
     /**
      * The ID of the last SSE event received
      */
     private ?string $lastEventId = null;
     
     /**
      * Whether the session has been initialized
      */
     private bool $initialized = false;
     
     /**
      * Whether the session has been invalidated (e.g., by receiving a 404)
      */
     private bool $invalidated = false;
 
     /**
      * @param LoggerInterface|null $logger PSR-3 compatible logger
      */
     public function __construct(
         private LoggerInterface $logger = new NullLogger()
     ) {}
 
     /**
      * Get HTTP headers that should be included in all requests.
      * 
      * @return array Key-value pairs of headers
      */
     public function getRequestHeaders(): array {
         $headers = [];
         
         // Include session ID if available
         if ($this->sessionId !== null) {
             $headers[self::SESSION_HEADER] = $this->sessionId;
         }
         
         // Include last event ID if available (for SSE resumption)
         if ($this->lastEventId !== null) {
             $headers[self::LAST_EVENT_HEADER] = $this->lastEventId;
         }
         
         return $headers;
     }
 
     /**
      * Process response headers to extract and update session information.
      * 
      * @param array $headers Response headers (normalized to key-value pairs)
      * @param int $statusCode HTTP status code
      * @param bool $isInitialization Whether this is the initial response
      * @return bool True if session is still valid, false if it needs to be reinitialized
      */
     public function processResponseHeaders(array $headers, int $statusCode, bool $isInitialization = false): bool {
         // Handle session initialization
         $normalized = array_change_key_case($headers, CASE_LOWER);
         $sessionKey = strtolower(self::SESSION_HEADER);

         if ($isInitialization) {
             $this->initialized = true;

             if (isset($normalized[$sessionKey])) {
                 $this->sessionId    = $normalized[$sessionKey];
                 $this->invalidated  = false;
                 $this->logger->info("Initialized MCP session with ID: {$this->sessionId}");
             } else {
                 $this->sessionId = null;
                 $this->logger->debug("Server did not provide a session ID during initialization");
             }

             return true;
         }
         
         // Handle potential session invalidation (404 response)
         if ($statusCode === 404 && $this->sessionId !== null) {
             $this->logger->warning("Session ID {$this->sessionId} was invalidated (received 404)");
             $this->invalidateSession();
             return false;
         }
         
         // Handle other response codes
         if ($statusCode >= 400) {
             $this->logger->error("Received error status code: {$statusCode}");
             return true; // Still maintain session, error might be request-specific
         }
         
         // All is well, session continues
         return true;
     }
 
     /**
      * Update the last event ID for resumable SSE connections.
      * 
      * @param string $eventId The last event ID received
      */
     public function updateLastEventId(string $eventId): void {
         $this->lastEventId = $eventId;
         $this->logger->debug("Updated last event ID: {$eventId}");
     }
 
     /**
      * Clear the last event ID (e.g., when starting a new SSE connection).
      */
     public function clearLastEventId(): void {
         $this->lastEventId = null;
     }
 
     /**
      * Invalidate the current session.
      * 
      * This marks the session as invalid, requiring reinitialization
      * before further requests can be made.
      */
     public function invalidateSession(): void {
         $this->invalidated = true;
         // We keep the session ID for reference/logging but it's no longer used
     }
 
     /**
      * Check if the session is initialized.
      * 
      * @return bool True if initialized, false otherwise
      */
     public function isInitialized(): bool {
         return $this->initialized;
     }
 
     /**
      * Check if the session is valid.
      * 
      * @return bool True if initialized and not invalidated, false otherwise
      */
     public function isValid(): bool {
         return $this->initialized && !$this->invalidated;
     }
 
     /**
      * Check if the session has been invalidated.
      * 
      * @return bool True if invalidated, false otherwise
      */
     public function isInvalidated(): bool {
         return $this->invalidated;
     }
 
     /**
      * Check if the session requires a session ID.
      * 
      * @return bool True if a session ID is required for requests, false otherwise
      */
     public function requiresSessionId(): bool {
         return $this->sessionId !== null && !$this->invalidated;
     }
 
     /**
      * Get the current session ID.
      * 
      * @return string|null The session ID or null if not set
      */
     public function getSessionId(): ?string {
         return $this->sessionId;
     }
 
     /**
      * Get the last event ID.
      * 
      * @return string|null The last event ID or null if not set
      */
     public function getLastEventId(): ?string {
         return $this->lastEventId;
     }
 
     /**
      * Reset the session state entirely.
      * 
      * This completely clears all session state, as if creating a new instance.
      */
     public function reset(): void {
         $this->sessionId = null;
         $this->lastEventId = null;
         $this->initialized = false;
         $this->invalidated = false;
         $this->logger->info("Session state completely reset");
     }
 }
 
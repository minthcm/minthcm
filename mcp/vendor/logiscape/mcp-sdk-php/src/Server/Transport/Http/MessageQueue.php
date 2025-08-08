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
 * Filename: Server/Transport/Http/MessageQueue.php
 */

declare(strict_types=1);

namespace Mcp\Server\Transport\Http;

use Mcp\Types\JsonRpcMessage;
use Mcp\Types\JSONRPCRequest;
use Mcp\Types\JSONRPCResponse;
use Mcp\Types\JSONRPCError;
use Mcp\Types\JSONRPCNotification;
use Mcp\Types\JSONRPCBatchRequest;
use Mcp\Types\JSONRPCBatchResponse;

/**
 * Message queue for HTTP transport.
 * 
 * This class manages queues of incoming and outgoing JSON-RPC messages,
 * associating outgoing messages with specific sessions.
 */
class MessageQueue
{
    /**
     * Maximum queue size.
     *
     * @var int
     */
    private int $maxQueueSize;
    
    /**
     * Queue of incoming messages.
     *
     * @var JsonRpcMessage[]
     */
    private array $incomingQueue = [];
    
    /**
     * Queues of outgoing messages, indexed by session ID.
     *
     * @var array<string, JsonRpcMessage[]>
     */
    private array $outgoingQueues = [];
    
    /**
     * Message IDs mapped to their respective session IDs.
     * Used to route responses to the correct session.
     *
     * @var array<string|int, string>
     */
    private array $messageIdToSession = [];
    
    /**
     * Constructor.
     *
     * @param int $maxQueueSize Maximum queue size
     */
    public function __construct(int $maxQueueSize = 1000)
    {
        $this->maxQueueSize = $maxQueueSize;
    }
    
    /**
     * Queue an incoming message for processing.
     *
     * @param JsonRpcMessage $message JSON-RPC message
     * @return bool True if successful, false if queue is full
     */
    public function queueIncoming(JsonRpcMessage $message): bool
    {
        // Check if the queue is full
        if (count($this->incomingQueue) >= $this->maxQueueSize) {
            return false;
        }
        
        // Handle batch requests by expanding them into individual messages
        $innerMessage = $message->message;
        if ($innerMessage instanceof JSONRPCBatchRequest) {
            // For batch requests, queue each message individually
            foreach ($innerMessage->messages as $subMessage) {
                $wrappedMessage = new JsonRpcMessage($subMessage);
                $this->incomingQueue[] = $wrappedMessage;
            }
        } else {
            // Add the single message to the queue
            $this->incomingQueue[] = $message;
        }
        
        return true;
    }
    
    /**
     * Dequeue the next incoming message.
     *
     * @return JsonRpcMessage|null Next message or null if queue is empty
     */
    public function dequeueIncoming(): ?JsonRpcMessage
    {
        if (empty($this->incomingQueue)) {
            return null;
        }
        
        return array_shift($this->incomingQueue);
    }
    
    /**
     * Queue an outgoing message for a specific session.
     *
     * @param JsonRpcMessage $message JSON-RPC message
     * @param string $sessionId Session ID
     * @return bool True if successful, false if queue is full
     */
    public function queueOutgoing(JsonRpcMessage $message, string $sessionId): bool
    {
        // Initialize the session's queue if it doesn't exist
        if (!isset($this->outgoingQueues[$sessionId])) {
            $this->outgoingQueues[$sessionId] = [];
        }
        
        // Check if the queue is full
        if (count($this->outgoingQueues[$sessionId]) >= $this->maxQueueSize) {
            return false;
        }
        
        // Handle batch messages
        $innerMessage = $message->message;
        if ($innerMessage instanceof JSONRPCBatchResponse) {
            // For batch responses, track each message ID
            foreach ($innerMessage->messages as $subMessage) {
                if ($subMessage instanceof JSONRPCResponse || $subMessage instanceof JSONRPCError) {
                    $requestId = $subMessage->id;
                    $requestIdValue = $requestId->getValue();
                    $this->messageIdToSession[$requestIdValue] = $sessionId;
                }
            }
        } elseif ($innerMessage instanceof JSONRPCRequest) {
            // For individual requests, track the message ID
            $requestId = $innerMessage->id;
            $requestIdValue = $requestId->getValue();
            $this->messageIdToSession[$requestIdValue] = $sessionId;
        }
        
        // Add the message to the queue (whether batch or individual)
        $this->outgoingQueues[$sessionId][] = $message;
        
        return true;
    }
    
    /**
     * Queue a response message for the session associated with the request ID.
     *
     * @param JsonRpcMessage $message JSON-RPC response message
     * @return bool True if successful, false if no matching session found
     */
    public function queueResponse(JsonRpcMessage $message): bool
    {
        $innerMessage = $message->message;
        
        // Handle batch responses
        if ($innerMessage instanceof JSONRPCBatchResponse) {
            // For batch responses, find the session for the first response
            // (all responses in a batch should be for the same session)
            if (empty($innerMessage->messages)) {
                return false;
            }
            
            $firstResponse = $innerMessage->messages[0];
            $requestId = $firstResponse->id;
            $requestIdValue = $requestId->getValue();
            
            // Find the session that sent the request
            if (!isset($this->messageIdToSession[$requestIdValue])) {
                return false;
            }
            
            $sessionId = $this->messageIdToSession[$requestIdValue];
            
            // Queue the batch response
            $result = $this->queueOutgoing($message, $sessionId);
            
            // Clean up the mappings for all responses in the batch
            if ($result) {
                foreach ($innerMessage->messages as $response) {
                    $responseId = $response->id;
                    $responseIdValue = $responseId->getValue();
                    unset($this->messageIdToSession[$responseIdValue]);
                }
            }
            
            return $result;
        }
        
        // Handle individual responses/errors
        if ($innerMessage instanceof JSONRPCResponse || $innerMessage instanceof JSONRPCError) {
            $requestId = $innerMessage->id;
            $requestIdValue = $requestId->getValue();
            
            // Find the session that sent the request
            if (!isset($this->messageIdToSession[$requestIdValue])) {
                return false;
            }
            
            $sessionId = $this->messageIdToSession[$requestIdValue];
            
            // Queue the response
            $result = $this->queueOutgoing($message, $sessionId);
            
            // Clean up the mapping
            if ($result) {
                unset($this->messageIdToSession[$requestIdValue]);
            }
            
            return $result;
        }
        
        return false;
    }
    
    /**
     * Flush all outgoing messages for a session.
     *
     * @param string $sessionId Session ID
     * @param bool $consolidate Whether to consolidate responses into batches (default: false)
     * @return JsonRpcMessage[] Array of messages
     */
    public function flushOutgoing(string $sessionId, bool $consolidate = false): array
    {
        if (!isset($this->outgoingQueues[$sessionId])) {
            return [];
        }
        
        $messages = $this->outgoingQueues[$sessionId];
        $this->outgoingQueues[$sessionId] = [];
        
        // If consolidation is requested, batch compatible messages together
        if ($consolidate && count($messages) > 1) {
            return $this->consolidateMessages($messages);
        }
        
        return $messages;
    }
    
    /**
     * Consolidate multiple messages into batches where appropriate.
     *
     * @param JsonRpcMessage[] $messages Array of messages
     * @return JsonRpcMessage[] Consolidated array of messages
     */
    private function consolidateMessages(array $messages): array
    {
        $responses = [];
        $other = [];
        
        // Separate responses from other message types
        foreach ($messages as $message) {
            $innerMessage = $message->message;
            if ($innerMessage instanceof JSONRPCResponse || $innerMessage instanceof JSONRPCError) {
                $responses[] = $innerMessage;
            } else {
                $other[] = $message;
            }
        }
        
        $result = [];
        
        // Create a batch for responses if there are multiple
        if (count($responses) > 1) {
            $batchResponse = new JSONRPCBatchResponse($responses);
            $result[] = new JsonRpcMessage($batchResponse);
        } elseif (count($responses) === 1) {
            // Re-wrap single response
            $result[] = new JsonRpcMessage($responses[0]);
        }
        
        // Add other message types
        return array_merge($result, $other);
    }
    
    /**
     * Check if a session has outgoing messages.
     *
     * @param string $sessionId Session ID
     * @return bool True if the session has outgoing messages
     */
    public function hasOutgoing(string $sessionId): bool
    {
        return isset($this->outgoingQueues[$sessionId]) && !empty($this->outgoingQueues[$sessionId]);
    }
    
    /**
     * Count the number of outgoing messages for a session.
     *
     * @param string $sessionId Session ID
     * @return int Number of outgoing messages
     */
    public function countOutgoing(string $sessionId): int
    {
        if (!isset($this->outgoingQueues[$sessionId])) {
            return 0;
        }
        
        return count($this->outgoingQueues[$sessionId]);
    }
    
    /**
     * Count the number of incoming messages.
     *
     * @return int Number of incoming messages
     */
    public function countIncoming(): int
    {
        return count($this->incomingQueue);
    }
    
    /**
     * Set the maximum queue size.
     *
     * @param int $maxQueueSize Maximum queue size
     * @return void
     */
    public function setMaxQueueSize(int $maxQueueSize): void
    {
        $this->maxQueueSize = $maxQueueSize;
    }
    
    /**
     * Get the maximum queue size.
     *
     * @return int Maximum queue size
     */
    public function getMaxQueueSize(): int
    {
        return $this->maxQueueSize;
    }
    
    /**
     * Clear all queues.
     *
     * @return void
     */
    public function clear(): void
    {
        $this->incomingQueue = [];
        $this->outgoingQueues = [];
        $this->messageIdToSession = [];
    }
    
    /**
     * Clean up expired sessions.
     *
     * @param array<string> $expiredSessionIds Array of expired session IDs
     * @return void
     */
    public function cleanupExpiredSessions(array $expiredSessionIds): void
    {
        foreach ($expiredSessionIds as $sessionId) {
            unset($this->outgoingQueues[$sessionId]);
            
            // Remove message ID mappings for this session
            foreach ($this->messageIdToSession as $messageId => $mappedSessionId) {
                if ($mappedSessionId === $sessionId) {
                    unset($this->messageIdToSession[$messageId]);
                }
            }
        }
    }
}

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
 * Filename: Server/Transport/Http/HttpSession.php
 */

declare(strict_types=1);

namespace Mcp\Server\Transport\Http;

/**
 * HTTP session management for MCP server.
 * 
 * This class manages sessions for the HTTP transport, including
 * session creation, identification, and expiration handling.
 */
class HttpSession
{
    /**
     * Session ID.
     *
     * @var string
     */
    protected string $id;
    
    /**
     * Session creation timestamp.
     *
     * @var int
     */
    protected int $createdAt;
    
    /**
     * Last activity timestamp.
     *
     * @var int
     */
    protected int $lastActivity;
    
    /**
     * Session metadata.
     *
     * @var array<string, mixed>
     */
    protected array $metadata = [];
    
    /**
     * Session state.
     *
     * @var string One of: 'new', 'active', 'expired'
     */
    protected string $state = 'new';
    
    /**
     * Constructor.
     *
     * @param string|null $id Session ID (generated if null)
     */
    public function __construct(?string $id = null)
    {
        $this->id = $id ?? $this->generateId();
        $this->createdAt = $this->lastActivity = time();
    }
    
    /**
     * Get the session ID.
     *
     * @return string Session ID
     */
    public function getId(): string
    {
        return $this->id;
    }
    
    /**
     * Update the last activity timestamp.
     *
     * @return void
     */
    public function updateActivity(): void
    {
        $this->lastActivity = time();
    }
    
    /**
     * Check if the session has expired.
     *
     * @param int $timeout Session timeout in seconds
     * @return bool True if the session has expired
     */
    public function isExpired(int $timeout): bool
    {
        if ($this->state === 'expired') {
            return true;
        }
        
        return (time() - $this->lastActivity) > $timeout;
    }
    
    /**
     * Set session metadata.
     *
     * @param string $key Metadata key
     * @param mixed $value Metadata value
     * @return void
     */
    public function setMetadata(string $key, $value): void
    {
        $this->metadata[$key] = $value;
    }
    
    /**
     * Get session metadata.
     *
     * @param string $key Metadata key
     * @param mixed|null $default Default value if key not found
     * @return mixed Metadata value or default
     */
    public function getMetadata(string $key, $default = null)
    {
        return $this->metadata[$key] ?? $default;
    }
    
    /**
     * Get all session metadata.
     *
     * @return array<string, mixed> All metadata
     */
    public function getAllMetadata(): array
    {
        return $this->metadata;
    }
    
    /**
     * Get the session creation timestamp.
     *
     * @return int Creation timestamp
     */
    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }
    
    /**
     * Get the last activity timestamp.
     *
     * @return int Last activity timestamp
     */
    public function getLastActivity(): int
    {
        return $this->lastActivity;
    }
    
    /**
     * Mark the session as active.
     *
     * @return void
     */
    public function activate(): void
    {
        if ($this->state === 'expired') {
            throw new \RuntimeException('Cannot activate an expired session');
        }
        
        $this->state = 'active';
        $this->updateActivity();
    }
    
    /**
     * Mark the session as expired.
     *
     * @return void
     */
    public function expire(): void
    {
        $this->state = 'expired';
    }
    
    /**
     * Get the session state.
     *
     * @return string Session state
     */
    public function getState(): string
    {
        return $this->state;
    }
    
    /**
     * Check if the session is active.
     *
     * @return bool True if the session is active
     */
    public function isActive(): bool
    {
        return $this->state === 'active';
    }
    
    /**
     * Generate a secure random session ID.
     *
     * @return string Session ID
     */
    private function generateId(): string
    {
        // Generate a cryptographically secure random string
        $randomBytes = random_bytes(32);
        
        // Convert to hexadecimal for safe use in headers and URLs
        return bin2hex($randomBytes);
    }
    
    /**
     * Get session information as an array.
     *
     * @return array Session information
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->createdAt,
            'last_activity' => $this->lastActivity,
            'state' => $this->state,
            'metadata' => $this->metadata,
        ];
    }

    /**
     * Create a new session from an array.
     *
     * @param array $data Session data
     * @return self New session instance
     */
    public static function fromArray(array $data): self
    {
        $sessionId = $data['id'] ?? null;
        $session = new self($sessionId);
        $session->createdAt = $data['created_at'] ?? time();
        $session->lastActivity = $data['last_activity'] ?? time();
        $session->metadata = $data['metadata'] ?? [];
        $session->state = $data['state'] ?? 'new';

        return $session;
    }

}

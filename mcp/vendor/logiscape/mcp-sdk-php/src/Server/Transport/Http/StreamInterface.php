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
 * Filename: Server/Transport/Http/StreamInterface.php
 */

declare(strict_types=1);

namespace Mcp\Server\Transport\Http;

/**
 * Interface for HTTP streaming responses (for future SSE support).
 * 
 * This interface defines the methods required for implementing
 * Server-Sent Events (SSE) or other streaming responses.
 */
interface StreamInterface
{
    /**
     * Write data to the stream.
     *
     * @param mixed $data Data to write
     * @return void
     * @throws \RuntimeException If the stream is closed.
     */
    public function write($data): void;
    
    /**
     * Close the stream.
     *
     * @return void
     */
    public function close(): void;
    
    /**
     * Check if the stream is active.
     *
     * @return bool True if the stream is active
     */
    public function isActive(): bool;
}

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
 * Filename: Server/Transport/Http/SessionStoreInterface.php
 */

declare(strict_types=1);

namespace Mcp\Server\Transport\Http;

/**
 * A pluggable interface for loading, saving, and deleting sessions.
 */
interface SessionStoreInterface
{
    /**
     * Load a session by ID.
     *
     * Return null if no session is found or it’s invalid/expired.
     */
    public function load(string $sessionId): ?HttpSession;

    /**
     * Save (or overwrite) the given session.
     */
    public function save(HttpSession $session): void;

    /**
     * Delete a session (for logout or forced expiration).
     */
    public function delete(string $sessionId): void;
}

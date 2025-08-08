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
 * Filename: Server/Transport/Http/InMemorySessionStore.php
 */

declare(strict_types=1);

namespace Mcp\Server\Transport\Http;

/**
 * Stores session data in memory.
 */
class InMemorySessionStore implements SessionStoreInterface
{
    /**
     * @var array<string, HttpSession>
     */
    private array $sessions = [];

    public function load(string $sessionId): ?HttpSession
    {
        return $this->sessions[$sessionId] ?? null;
    }

    public function save(HttpSession $session): void
    {
        $this->sessions[$session->getId()] = $session;
    }

    public function delete(string $sessionId): void
    {
        unset($this->sessions[$sessionId]);
    }
}

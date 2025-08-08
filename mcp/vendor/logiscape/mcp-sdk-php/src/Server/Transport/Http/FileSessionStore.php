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
 * Filename: Server/Transport/Http/FileSessionStore.php
 */

declare(strict_types=1);

namespace Mcp\Server\Transport\Http;

/**
 * Stores session data in a file to persist across requests on a typical web server.
 */
class FileSessionStore implements SessionStoreInterface
{
    public function __construct(private string $directory)
    {
        // Ensure directory exists
        if (!is_dir($this->directory)) {
            mkdir($this->directory, 0750, true);
        }
    }

    public function load(string $sessionId): ?HttpSession
    {
        $path = $this->getPath($sessionId);
        if (!is_file($path)) {
            return null;
        }

        $data = json_decode(file_get_contents($path), true);
        if (!$data) {
            return null;
        }

        // Convert the array back to an HttpSession
        return HttpSession::fromArray($data);
    }

    public function save(HttpSession $session): void
    {
        $path = $this->getPath($session->getId());
        file_put_contents($path, json_encode($session->toArray()));
    }

    public function delete(string $sessionId): void
    {
        $path = $this->getPath($sessionId);
        if (is_file($path)) {
            unlink($path);
        }
    }

    private function getPath(string $sessionId): string
    {
        return rtrim($this->directory, '/\\') . '/session-' . $sessionId . '.json';
    }
}

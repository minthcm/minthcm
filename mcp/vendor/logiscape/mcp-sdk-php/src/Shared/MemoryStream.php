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
 * Filename: Shared/MemoryStream.php
 */

declare(strict_types=1);

namespace Mcp\Shared;

use Mcp\Types\JsonRpcMessage;
use Exception;

/**
 * A simple in-memory message queue for MCP communication.
 *
 * This class simulates a transport channel in memory. It can store both
 * JsonRpcMessage objects and Exception objects. The receive method returns
 * the oldest inserted item first, acting as a FIFO queue.
 */
class MemoryStream {
    /** @var array<JsonRpcMessage|Exception> */
    private array $queue = [];

    /**
     * Sends a message or an exception into the stream.
     *
     * @param mixed $item
     */
    public function send(mixed $item): void {
        $this->queue[] = $item;
    }

    /**
     * Receives the oldest message or exception from the stream.
     *
     * @return mixed Returns the next item or null if empty.
     */
    public function receive(): mixed {
        return array_shift($this->queue) ?? null;
    }

    /**
     * Checks if the stream is empty.
     */
    public function isEmpty(): bool {
        return empty($this->queue);
    }
}
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
 * Filename: Shared/McpError.php
 */

declare(strict_types=1);

namespace Mcp\Shared;

use Exception;

/**
 * Exception type raised when an error arrives over an MCP connection.
 *
 * Wraps the ErrorData object in an exception for easy error handling.
 */
class McpError extends Exception {
    public function __construct(
        public readonly ErrorData $error,
        ?\Throwable $previous = null
    ) {
        parent::__construct($error->message, $error->code, $previous);
    }
}
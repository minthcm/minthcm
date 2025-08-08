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
 * Filename: Server/Transport/NonBlockingTransport.php
 */

declare(strict_types=1);

namespace Mcp\Server\Transport;

/**
 * Interface for transports that support non-blocking operations
 */
interface NonBlockingTransport extends Transport {
    /**
     * Check if the transport has data available to read
     *
     * @return bool True if data is available to read
     */
    public function hasDataAvailable(): bool;
}
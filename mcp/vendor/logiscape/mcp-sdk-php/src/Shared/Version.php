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
 * Filename: Shared/Version.php
 */

declare(strict_types=1);

namespace Mcp\Shared;

/**
 * Provides version constants for the MCP protocol.
 *
 * Aligns with the Python constants:
 * LATEST_PROTOCOL_VERSION = "2024-11-05"
 * SUPPORTED_PROTOCOL_VERSIONS = [1, LATEST_PROTOCOL_VERSION]
 */
class Version {
    public const LATEST_PROTOCOL_VERSION = '2025-03-26';
    public const SUPPORTED_PROTOCOL_VERSIONS = [
        '2024-11-05',
        '2025-03-26'
    ];
}
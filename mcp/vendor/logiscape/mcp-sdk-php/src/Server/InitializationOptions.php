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
 * Filename: Server/InitializationOptions.php
 */

declare(strict_types=1);

namespace Mcp\Server;

use Mcp\Types\McpModel;
use Mcp\Types\ServerCapabilities;
use Mcp\Types\ExtraFieldsTrait;
use InvalidArgumentException;

/**
 * Options used to initialize an MCP server
 */
class InitializationOptions implements McpModel {
    use ExtraFieldsTrait;

    public function __construct(
        public readonly string $serverName,
        public readonly string $serverVersion,
        public readonly ServerCapabilities $capabilities
    ) {}

    public function validate(): void {
        if (empty($this->serverName)) {
            throw new \InvalidArgumentException('Server name cannot be empty');
        }
        if (empty($this->serverVersion)) {
            throw new \InvalidArgumentException('Server version cannot be empty');
        }
        $this->capabilities->validate();
    }

    public function jsonSerialize(): mixed {
        $data = [
            'server_name' => $this->serverName,
            'server_version' => $this->serverVersion,
            'capabilities' => $this->capabilities,
        ];
        return array_merge($data, $this->extraFields);
    }
}
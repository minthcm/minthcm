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
 * Filename: Client/Transport/StdioServerParameters.php
 */

declare(strict_types=1);

namespace Mcp\Client\Transport;

use InvalidArgumentException;

/**
 * Parameters for configuring a stdio server connection
 */
class StdioServerParameters {
    public function __construct(
        private readonly string $command,
        private readonly array $args = [],
        private readonly ?array $env = null,
    ) {
        if (empty($command)) {
            throw new InvalidArgumentException('Command cannot be empty');
        }
    }

    public function getCommand(): string {
        return $this->command;
    }

    public function getArgs(): array {
        return $this->args;
    }

    public function getEnv(): ?array {
        return $this->env;
    }
}
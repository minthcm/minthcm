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
 * Filename: Types/ExtraFieldsTrait.php
 */

declare(strict_types=1);

namespace Mcp\Types;

/**
 * Base trait for all MCP models to allow extra fields.
 */
trait ExtraFieldsTrait
{
    protected array $extraFields = [];

    public function __set(string $name, mixed $value): void
    {
        $this->extraFields[$name] = $value;
    }

    public function __get(string $name): mixed
    {
        return $this->extraFields[$name] ?? null;
    }

    public function __isset(string $name): bool
    {
        return isset($this->extraFields[$name]);
    }
}

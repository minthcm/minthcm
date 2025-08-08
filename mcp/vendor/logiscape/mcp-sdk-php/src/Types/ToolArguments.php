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
 * Filename: Types/ToolArguments.php
 */

declare(strict_types=1);

namespace Mcp\Types;

/**
 * Represents the `arguments` object in CallToolRequest
 * { [key: string]: unknown }
 */
class ToolArguments implements McpModel {
    use ExtraFieldsTrait;

    public function __construct(array $args = []) {
        foreach ($args as $k => $v) {
            $this->$k = $v;
        }
    }

    public function validate(): void {
        // no required fields
    }

    public function jsonSerialize(): mixed {
        return empty($this->extraFields) ? new \stdClass() : $this->extraFields;
    }
}
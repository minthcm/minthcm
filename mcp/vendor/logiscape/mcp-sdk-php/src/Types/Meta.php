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
 * Filename: Types/Meta.php
 */

declare(strict_types=1);

namespace Mcp\Types;

/**
 * Represents the `_meta` object found in various structures (e.g. Result, Request params).
 * This is an open object, so we just allow arbitrary fields.
 */
class Meta implements McpModel {
    use ExtraFieldsTrait;

    public function validate(): void {
        // No required fields, just arbitrary data allowed
    }

    public function jsonSerialize(): mixed {
        // Return only extra fields, since there are no defined properties
        return empty($this->extraFields) ? new \stdClass() : $this->extraFields;
    }
}
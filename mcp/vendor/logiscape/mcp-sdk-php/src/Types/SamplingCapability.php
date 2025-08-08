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
 * Filename: Types/SamplingCapability.php
 */

declare(strict_types=1);

namespace Mcp\Types;

/**
 * Represents the `sampling` object in ClientCapabilities.
 * The schema says sampling?: object with arbitrary fields.
 */
class SamplingCapability implements McpModel {
    use ExtraFieldsTrait;

    public function validate(): void {
        // No specific fields required, just arbitrary data allowed.
    }

    public function jsonSerialize(): mixed {
        // Just return extra fields (arbitrary fields)
        return empty($this->extraFields) ? new \stdClass() : $this->extraFields;
    }
}
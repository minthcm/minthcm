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
 * Filename: Types/ServerCompletionsCapability.php
 */

declare(strict_types=1);

namespace Mcp\Types;

/**
 * Represents the `completions` object in ServerCapabilities.
 * According to the schema: completions?: object (with arbitrary fields)
 */
class ServerCompletionsCapability implements McpModel {
    use ExtraFieldsTrait;

    public static function fromArray(array $data): self {
        $obj = new self();
        foreach ($data as $k => $v) {
            $obj->$k = $v;
        }

        $obj->validate();
        return $obj;
    }

    public function validate(): void {
        // No mandatory fields, arbitrary fields allowed.
    }

    public function jsonSerialize(): mixed {
        return empty($this->extraFields) ? new \stdClass() : $this->extraFields; // No defined properties
    }
}
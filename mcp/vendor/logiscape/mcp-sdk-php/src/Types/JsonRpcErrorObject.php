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
 * Filename: Types/JsonRpcErrorObject.php
 */

declare(strict_types=1);

namespace Mcp\Types;

/**
 * Represents the error object for JSON-RPC errors:
 * { code: number; message: string; data?: unknown }
 */
class JsonRpcErrorObject implements McpModel {
    use ExtraFieldsTrait;

    public function __construct(
        public readonly int $code,
        public readonly string $message,
        public readonly mixed $data = null,
    ) {}

    public function validate(): void {
        // code must be integer, message must be string and non-empty
        if ($this->message === '') {
            throw new \InvalidArgumentException('Error message cannot be empty');
        }
    }

    public function jsonSerialize(): mixed {
        $data = [
            'code' => $this->code,
            'message' => $this->message,
        ];
        if ($this->data !== null) {
            $data['data'] = $this->data;
        }
        return array_merge($data, $this->extraFields);
    }
}
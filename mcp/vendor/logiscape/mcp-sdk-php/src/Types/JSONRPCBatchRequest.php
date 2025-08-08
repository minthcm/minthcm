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
 * Filename: Types/JSONRPCBatchRequest.php
 */

declare(strict_types=1);

namespace Mcp\Types;

class JSONRPCBatchRequest implements McpModel, \JsonSerializable {
    use ExtraFieldsTrait;

    /** @var array<JSONRPCRequest|JSONRPCNotification> */
    public array $messages;
    
    public function __construct(array $messages) {
        $this->messages = $messages;
    }

    public function validate(): void {
        // Validate each message in the batch
        foreach ($this->messages as $msg) {
            $msg->validate();
        }
    }

    public function jsonSerialize(): array {
        return array_map(fn($msg) => $msg->jsonSerialize(), $this->messages);
    }
}
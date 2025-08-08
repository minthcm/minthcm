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
 * Filename: Types/JSONRPCNotification.php
 */

declare(strict_types=1);

namespace Mcp\Types;

/**
 * A notification which does not expect a response
 *
 * {
 *   "jsonrpc": "2.0",
 *   "method": string,
 *   "params"?: NotificationParams
 * }
 */
class JSONRPCNotification extends Notification {
    use ExtraFieldsTrait;

    public function __construct(
        public readonly string $jsonrpc,
        ?NotificationParams $params = null,
        string $method = '',
    ) {
        parent::__construct(method: $method, params: $params);
    }

    public function validate(): void {
        if ($this->jsonrpc !== '2.0') {
            throw new \InvalidArgumentException('JSON-RPC version must be "2.0"');
        }
        if (empty($this->method)) {
            throw new \InvalidArgumentException('Notification method cannot be empty');
        }
        if ($this->params !== null) {
            $this->params->validate();
        }
    }

    public function jsonSerialize(): mixed {
        $data = parent::jsonSerialize();
        $data['jsonrpc'] = $this->jsonrpc;
        return $data;
    }
}
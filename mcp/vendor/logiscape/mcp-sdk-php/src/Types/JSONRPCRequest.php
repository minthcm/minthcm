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
 * Filename: Types/JSONRPCRequest.php
 */

declare(strict_types=1);

namespace Mcp\Types;

/**
 * A request that expects a response
 *
 * {
 *   "jsonrpc": "2.0",
 *   "id": string|number,
 *   "method": string,
 *   "params"?: RequestParams
 * }
 */
class JSONRPCRequest extends Request {
    use ExtraFieldsTrait;

    public function __construct(
        public readonly string $jsonrpc,
        public RequestId $id,
        ?RequestParams $params = null,
        string $method = '',
    ) {
        parent::__construct(method: $method, params: $params);
    }

    public function validate(): void {
        if ($this->jsonrpc !== '2.0') {
            throw new \InvalidArgumentException('JSON-RPC version must be "2.0"');
        }
        if (empty($this->method)) {
            throw new \InvalidArgumentException('Request method cannot be empty');
        }
        $this->id->validate();
        if ($this->params !== null) {
            $this->params->validate();
        }
    }

    public function jsonSerialize(): mixed {
        $data = parent::jsonSerialize();
        $data['jsonrpc'] = $this->jsonrpc;
        $data['id'] = $this->id;
        return $data;
    }
}
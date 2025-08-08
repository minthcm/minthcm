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
 * Filename: Types/Request.php
 */

declare(strict_types=1);

namespace Mcp\Types;

/**
 * Base class for all requests
 */
abstract class Request implements McpModel {
    use ExtraFieldsTrait;

    public function __construct(
        public readonly string $method,
        public ?RequestParams $params = null,
    ) {}

    public function validate(): void {
        if (empty($this->method)) {
            throw new \InvalidArgumentException('Request method cannot be empty');
        }
        if ($this->params !== null) {
            $this->params->validate();
        }
    }

    public function jsonSerialize(): mixed {
        $data = [];

        // Only include "method" if not empty
        if (!empty($this->method)) {
           $data['method'] = $this->method;
        }

        // If $this->params is not null, and not empty, we add it
        if ($this->params !== null) {
            // We'll let the params object decide how to omit its fields
            $serializedParams = $this->params->jsonSerialize();
            // Only include 'params' if there's something in it
            if (!empty($serializedParams)) {
                $data['params'] = $serializedParams;
            }
        }

        // Merge extraFields only if non-empty
        if (!empty($this->extraFields)) {
            $data = array_merge($data, $this->extraFields);
        }
        return $data;
    }
}
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
 * Filename: Types/SubscribeRequestParams.php
 */

declare(strict_types=1);

namespace Mcp\Types;

/**
 * Params for SubscribeRequest:
 * { uri: string }
 */
class SubscribeRequestParams implements McpModel {
    use ExtraFieldsTrait;

    public function __construct(
        public readonly string $uri
    ) {}

    public function validate(): void {
        if (empty($this->uri)) {
            throw new \InvalidArgumentException('Resource URI cannot be empty');
        }
    }

    public function jsonSerialize(): mixed {
        return array_merge(['uri' => $this->uri], $this->extraFields);
    }
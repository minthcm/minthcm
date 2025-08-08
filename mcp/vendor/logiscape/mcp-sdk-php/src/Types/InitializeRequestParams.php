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
 * Filename: Types/InitializeRequestParams.php
 */

declare(strict_types=1);

namespace Mcp\Types;

class InitializeRequestParams extends RequestParams {
    public function __construct(
        public readonly string $protocolVersion,
        public readonly ClientCapabilities $capabilities,
        public readonly Implementation $clientInfo,
        ?Meta $_meta = null
    ) {
        // Call parent constructor, passing $_meta if needed. If you don't have meta for Initialize, you can just pass null.
        parent::__construct($_meta);
    }

    public function validate(): void {
        // First call parent to validate _meta if present
        parent::validate();

        if (empty($this->protocolVersion)) {
            throw new \InvalidArgumentException('Protocol version cannot be empty');
        }
        $this->capabilities->validate();
        $this->clientInfo->validate();
    }

    public function jsonSerialize(): mixed {
        $data = [
            'protocolVersion' => $this->protocolVersion,
            'capabilities' => $this->capabilities,
            'clientInfo' => $this->clientInfo,
        ];

        // Merge with extra fields from parent (ExtraFieldsTrait)
        return array_merge($data, $this->extraFields);
    }
}
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
 * Filename: Types/LoggingMessageNotificationParams.php
 */

declare(strict_types=1);

namespace Mcp\Types;

/**
 * Params for LoggingMessageNotification
 * {
 *   level: LoggingLevel;
 *   logger?: string;
 *   data: unknown;
 * }
 */
class LoggingMessageNotificationParams implements McpModel {
    use ExtraFieldsTrait;

    public function __construct(
        public readonly LoggingLevel $level,
        public readonly mixed $data,
        public ?string $logger = null,
    ) {}

    public function validate(): void {
        if ($this->data === null) {
            throw new \InvalidArgumentException('Logging message data cannot be null');
        }
    }

    public function jsonSerialize(): mixed {
        $data = [
            'level' => $this->level->value,
            'data' => $this->data,
        ];
        if ($this->logger !== null) {
            $data['logger'] = $this->logger;
        }
        return array_merge($data, $this->extraFields);
    }
}
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
 * Filename: Types/CancelledNotification.php
 */

declare(strict_types=1);

namespace Mcp\Types;

/**
 * Notification for cancelled requests
 */
class CancelledNotification extends Notification {
    public function __construct(
        public readonly RequestId $requestId,
        public ?string $reason = null,
    ) {
        parent::__construct('notifications/cancelled');
    }

    public function validate(): void {
        parent::validate();
        $this->requestId->validate();
    }
}
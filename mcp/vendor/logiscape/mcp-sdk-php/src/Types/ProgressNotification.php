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
 * Filename: Types/ProgressNotification.php
 */

declare(strict_types=1);

namespace Mcp\Types;

class ProgressNotification extends Notification {
    public function __construct(
        ProgressNotificationParams $params
    ) {
        parent::__construct('notifications/progress', $params);
    }

    public function validate(): void {
        parent::validate();
        if ($this->params !== null && $this->params instanceof ProgressNotificationParams) {
            $this->params->validate();
        }
    }
}
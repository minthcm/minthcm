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
 * - ChatGPT o1 pro mode
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
 * Filename: Types/ServerNotification.php
 */

declare(strict_types=1);

namespace Mcp\Types;

/**
 * Union type for server notifications:
 * type ServerNotification =
 *   | CancelledNotification
 *   | ProgressNotification
 *   | ResourceListChangedNotification
 *   | ResourceUpdatedNotification
 *   | PromptListChangedNotification
 *   | ToolListChangedNotification
 *   | LoggingMessageNotification
 *
 * This acts as a root model holding one valid Notification variant and provides
 * a factory method to construct the correct variant from method and params.
 */
class ServerNotification implements McpModel {
    use ExtraFieldsTrait;

    private Notification $notification;

    public function __construct(Notification $notification) {
        if (!(
            $notification instanceof CancelledNotification ||
            $notification instanceof ProgressNotification ||
            $notification instanceof ResourceListChangedNotification ||
            $notification instanceof ResourceUpdatedNotification ||
            $notification instanceof PromptListChangedNotification ||
            $notification instanceof ToolListChangedNotification ||
            $notification instanceof LoggingMessageNotification
        )) {
            throw new \InvalidArgumentException('Invalid server notification type');
        }
        $this->notification = $notification;
    }

    /**
     * Factory method to create a ServerNotification from method and params.
     *
     * @param string $method The notification method (e.g., 'notifications/cancelled')
     * @param array|null $params The notification parameters
     */
    public static function fromMethodAndParams(string $method, ?array $params): self {
        $params = $params ?? [];

        return match ($method) {
            'notifications/cancelled' => self::createCancelledNotification($params),
            'notifications/progress' => self::createProgressNotification($params),
            'notifications/resources/list_changed' => self::createResourceListChangedNotification($params),
            'notifications/resources/updated' => self::createResourceUpdatedNotification($params),
            'notifications/prompts/list_changed' => self::createPromptListChangedNotification($params),
            'notifications/tools/list_changed' => self::createToolListChangedNotification($params),
            'notifications/message' => self::createLoggingMessageNotification($params),
            default => throw new \InvalidArgumentException("Unknown server notification method: $method")
        };
    }

    private static function createCancelledNotification(array $params): self {
        if (!isset($params['requestId'])) {
            throw new \InvalidArgumentException('CancelledNotification requires "requestId"');
        }
        $requestId = new RequestId($params['requestId']);
        $reason = $params['reason'] ?? null;

        $notification = new CancelledNotification($requestId, $reason);

        // Extra fields not defined in schema can be stored in $notification
        foreach ($params as $k => $v) {
            if (!in_array($k, ['requestId', 'reason'], true)) {
                $notification->$k = $v;
            }
        }

        return new self($notification);
    }

    private static function createProgressNotification(array $params): self {
        if (!isset($params['progressToken'])) {
            throw new \InvalidArgumentException('ProgressNotification requires "progressToken"');
        }
        if (!isset($params['progress'])) {
            throw new \InvalidArgumentException('ProgressNotification requires "progress"');
        }

        $progressToken = new ProgressToken($params['progressToken']);
        $progress = (float)$params['progress'];
        $total = isset($params['total']) ? (float)$params['total'] : null;

        $progressParams = new ProgressNotificationParams(
            progressToken: $progressToken,
            progress: $progress,
            total: $total
        );

        // Extra fields
        foreach ($params as $k => $v) {
            if (!in_array($k, ['progressToken', 'progress', 'total'], true)) {
                $progressParams->$k = $v;
            }
        }

        return new self(new ProgressNotification($progressParams));
    }

    private static function createResourceListChangedNotification(array $params): self {
        // No required params
        $notification = new ResourceListChangedNotification();
        foreach ($params as $k => $v) {
            $notification->$k = $v;
        }
        return new self($notification);
    }

    private static function createResourceUpdatedNotification(array $params): self {
        if (!isset($params['uri'])) {
            throw new \InvalidArgumentException('ResourceUpdatedNotification requires "uri"');
        }

        $uri = $params['uri'];
        $notification = new ResourceUpdatedNotification($uri);

        // ResourceUpdatedNotificationParams will hold uri, extra fields can be set there if needed.
        // We have no direct access to params object from here. If we need to set extra fields:
        // Retrieve the params object and set fields on it.
        $paramsObj = $notification->params;
        foreach ($params as $k => $v) {
            if ($k !== 'uri') {
                $paramsObj->$k = $v;
            }
        }

        return new self($notification);
    }

    private static function createPromptListChangedNotification(array $params): self {
        $notification = new PromptListChangedNotification();
        foreach ($params as $k => $v) {
            $notification->$k = $v;
        }
        return new self($notification);
    }

    private static function createToolListChangedNotification(array $params): self {
        $notification = new ToolListChangedNotification();
        foreach ($params as $k => $v) {
            $notification->$k = $v;
        }
        return new self($notification);
    }

    private static function createLoggingMessageNotification(array $params): self {
        if (!isset($params['level'])) {
            throw new \InvalidArgumentException('LoggingMessageNotification requires "level"');
        }
        if (!isset($params['data'])) {
            throw new \InvalidArgumentException('LoggingMessageNotification requires "data"');
        }

        $level = LoggingLevel::from($params['level']);
        $data = $params['data'];
        $logger = $params['logger'] ?? null;

        $loggingParams = new LoggingMessageNotificationParams(
            level: $level,
            data: $data,
            logger: $logger
        );

        // Extra fields not level/data/logger
        foreach ($params as $k => $v) {
            if (!in_array($k, ['level', 'data', 'logger'], true)) {
                $loggingParams->$k = $v;
            }
        }

        return new self(new LoggingMessageNotification($loggingParams));
    }

    public function validate(): void {
        $this->notification->validate();
    }

    public function getNotification(): Notification {
        return $this->notification;
    }

    public function jsonSerialize(): mixed {
        $data = $this->notification->jsonSerialize();
        return array_merge((array)$data, $this->extraFields);
    }
}
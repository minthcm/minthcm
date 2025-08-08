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
 * Filename: Types/ServerResourcesCapability.php
 */

declare(strict_types=1);

namespace Mcp\Types;

/**
 * Structure for server resources capability.
 * According to the schema:
 * resources?: {
 *   subscribe?: boolean;
 *   listChanged?: boolean;
 *   [key: string]: unknown;
 * }
 */
class ServerResourcesCapability implements McpModel {
    use ExtraFieldsTrait;

    public function __construct(
        public readonly ?bool $listChanged = null,
        public readonly ?bool $subscribe = null,
    ) {}

    public static function fromArray(array $data): self {
        $listChanged = $data['listChanged'] ?? null;
        $subscribe = $data['subscribe'] ?? null;

        unset($data['listChanged'], $data['subscribe']);

        $obj = new self(
            listChanged: $listChanged === null ? null : (bool)$listChanged,
            subscribe: $subscribe === null ? null : (bool)$subscribe
        );

        foreach ($data as $k => $v) {
            $obj->$k = $v;
        }

        $obj->validate();
        return $obj;
    }

    public function validate(): void {
        // No required fields.
    }

    public function jsonSerialize(): mixed {
        $data = [];
        if ($this->listChanged !== null) {
            $data['listChanged'] = $this->listChanged;
        }
        if ($this->subscribe !== null) {
            $data['subscribe'] = $this->subscribe;
        }
        return array_merge($data, $this->extraFields);
    }
}
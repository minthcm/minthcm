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
 * Filename: Types/ServerPromptsCapability.php
 */

declare(strict_types=1);

namespace Mcp\Types;

/**
 * Structure for server prompts capability.
 * According to the schema:
 * prompts?: {
 *   listChanged?: boolean;
 *   [key: string]: unknown;
 * }
 */
class ServerPromptsCapability implements McpModel {
    use ExtraFieldsTrait;

    public function __construct(
        public readonly ?bool $listChanged = null,
    ) {}

    public static function fromArray(array $data): self {
        $listChanged = $data['listChanged'] ?? null;
        unset($data['listChanged']);

        $obj = new self($listChanged === null ? null : (bool)$listChanged);

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
        return array_merge($data, $this->extraFields);
    }
}
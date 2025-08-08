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
 * Filename: Types/ModelHint.php
 */

declare(strict_types=1);

namespace Mcp\Types;

class ModelHint implements McpModel {
    use ExtraFieldsTrait;

    public function __construct(
        public ?string $name = null,
    ) {}

    public static function fromArray(array $data): self {
        $name = $data['name'] ?? null;
        unset($data['name']);

        $obj = new self($name);

        foreach ($data as $k => $v) {
            $obj->$k = $v; // ModelHint uses ExtraFieldsTrait
        }

        $obj->validate();
        return $obj;
    }

    public function validate(): void {
        // No required fields
    }

    public function jsonSerialize(): mixed {
        $data = [];
        if ($this->name !== null) {
            $data['name'] = $this->name;
        }
        return array_merge($data, $this->extraFields);
    }
}
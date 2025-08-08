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
 * Filename: Types/Root.php
 */

declare(strict_types=1);

namespace Mcp\Types;

class Root implements McpModel {
    use ExtraFieldsTrait;

    public function __construct(
        public readonly string $uri,
        public ?string $name = null,
    ) {}

    public static function fromArray(array $data): self {
        $uri = $data['uri'] ?? '';
        $name = $data['name'] ?? null;
        unset($data['uri'], $data['name']);

        $obj = new self($uri, $name);

        foreach ($data as $k => $v) {
            $obj->$k = $v; // Root uses ExtraFieldsTrait
        }

        $obj->validate();
        return $obj;
    }

    public function validate(): void {
        if (empty($this->uri)) {
            throw new \InvalidArgumentException('Root URI cannot be empty');
        }
        if (!str_starts_with($this->uri, 'file://')) {
            throw new \InvalidArgumentException('Root URI must start with file://');
        }
    }

    public function jsonSerialize(): mixed {
        $data = ['uri' => $this->uri];
        if ($this->name !== null) {
            $data['name'] = $this->name;
        }
        return array_merge($data, $this->extraFields);
    }
}
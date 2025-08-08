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
 * Filename: Types/PromptArgument.php
 */

declare(strict_types=1);

namespace Mcp\Types;

class PromptArgument implements McpModel {
    use ExtraFieldsTrait;

    public function __construct(
        public readonly string $name,
        public ?string $description = null,
        public bool $required = false,
    ) {}

    public static function fromArray(array $data): self {
        $name = $data['name'] ?? '';
        $description = $data['description'] ?? null;
        $required = $data['required'] ?? false;
        unset($data['name'], $data['description'], $data['required']);

        $obj = new self(
            name: $name,
            description: $description,
            required: (bool)$required
        );

        foreach ($data as $k => $v) {
            $obj->$k = $v;
        }

        $obj->validate();
        return $obj;
    }

    public function validate(): void {
        if (empty($this->name)) {
            throw new \InvalidArgumentException('Prompt argument name cannot be empty');
        }
    }

    public function jsonSerialize(): mixed {
        $data = [
            'name' => $this->name,
        ];
        if ($this->description !== null) {
            $data['description'] = $this->description;
        }
        if ($this->required) {
            $data['required'] = $this->required;
        }
        return array_merge($data, $this->extraFields);
    }
}
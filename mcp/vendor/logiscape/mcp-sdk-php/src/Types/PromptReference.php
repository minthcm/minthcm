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
 * Filename: Types/PromptReference.php
 */

declare(strict_types=1);

namespace Mcp\Types;

/**
 * Identifies a prompt
 */
class PromptReference implements McpModel {
    use ExtraFieldsTrait;

    public function __construct(
        public readonly string $name,
        public readonly string $type = 'ref/prompt',
    ) {}

    public static function fromArray(array $data): self {
        $name = $data['name'] ?? '';
        $type = $data['type'] ?? 'ref/prompt';
        unset($data['name'], $data['type']);

        $obj = new self($name, $type);

        foreach ($data as $k => $v) {
            $obj->$k = $v; // PromptReference uses ExtraFieldsTrait
        }

        $obj->validate();
        return $obj;
    }

    public function validate(): void {
        if (empty($this->name)) {
            throw new \InvalidArgumentException('Prompt reference name cannot be empty');
        }
        if ($this->type !== 'ref/prompt') {
            throw new \InvalidArgumentException('Prompt reference type must be "ref/prompt"');
        }
    }

    public function jsonSerialize(): mixed {
        $data = get_object_vars($this);
        return array_merge($data, $this->extraFields);
    }
}
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
 * Filename: Types/Prompt.php
 */

declare(strict_types=1);

namespace Mcp\Types;

class Prompt implements McpModel {
    use ExtraFieldsTrait;

    /**
     * @param PromptArgument[] $arguments
     */
    public function __construct(
        public readonly string $name,
        public ?string $description = null,
        public array $arguments = [],
    ) {}

    public static function fromArray(array $data): self {
        $name = $data['name'] ?? '';
        $description = $data['description'] ?? null;
        $argumentsData = $data['arguments'] ?? [];
        unset($data['name'], $data['description'], $data['arguments']);

        $arguments = [];
        if (is_array($argumentsData)) {
            foreach ($argumentsData as $arg) {
                if (!is_array($arg)) {
                    throw new \InvalidArgumentException('Invalid prompt argument data');
                }
                $arguments[] = PromptArgument::fromArray($arg);
            }
        }

        $obj = new self($name, $description, $arguments);

        foreach ($data as $k => $v) {
            $obj->$k = $v;
        }

        $obj->validate();
        return $obj;
    }

    public function validate(): void {
        if (empty($this->name)) {
            throw new \InvalidArgumentException('Prompt name cannot be empty');
        }
        foreach ($this->arguments as $argument) {
            if (!$argument instanceof PromptArgument) {
                throw new \InvalidArgumentException('Prompt arguments must be instances of PromptArgument');
            }
            $argument->validate();
        }
    }

    public function jsonSerialize(): mixed {
        $data = [
            'name' => $this->name,
        ];
        if ($this->description !== null) {
            $data['description'] = $this->description;
        }
        if (!empty($this->arguments)) {
            $data['arguments'] = $this->arguments;
        }
        return array_merge($data, $this->extraFields);
    }
}
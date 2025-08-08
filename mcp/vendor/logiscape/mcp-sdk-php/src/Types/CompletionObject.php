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
 * Filename: Types/CompletionObject.php
 */

declare(strict_types=1);

namespace Mcp\Types;

/**
 * Represents the completion object in CompleteResult
 * {
 *   values: string[],
 *   total?: number,
 *   hasMore?: boolean,
 *   [key: string]: unknown
 * }
 */
class CompletionObject implements McpModel {
    use ExtraFieldsTrait;

    /**
     * @param string[] $values
     */
    public function __construct(
        public readonly array $values,
        public ?int $total = null,
        public ?bool $hasMore = null,
    ) {}

    public static function fromArray(array $data): self {
        $values = $data['values'] ?? [];
        $total = $data['total'] ?? null;
        $hasMore = $data['hasMore'] ?? null;

        unset($data['values'], $data['total'], $data['hasMore']);

        $obj = new self(
            values: $values,
            total: $total !== null ? (int)$total : null,
            hasMore: $hasMore !== null ? (bool)$hasMore : null
        );

        foreach ($data as $k => $v) {
            $obj->$k = $v;
        }

        $obj->validate();
        return $obj;
    }

    public function validate(): void {
        if (count($this->values) > 100) {
            throw new \InvalidArgumentException('Completion values cannot exceed 100 items');
        }
    }

    public function jsonSerialize(): mixed {
        $data = ['values' => $this->values];
        if ($this->total !== null) {
            $data['total'] = $this->total;
        }
        if ($this->hasMore !== null) {
            $data['hasMore'] = $this->hasMore;
        }
        return array_merge($data, $this->extraFields);
    }
}
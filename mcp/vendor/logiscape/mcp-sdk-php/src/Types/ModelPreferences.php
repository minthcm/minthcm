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
 * Filename: Types/ModelPreferences.php
 */

declare(strict_types=1);

namespace Mcp\Types;

class ModelPreferences implements McpModel {
    use ExtraFieldsTrait;

    /**
     * @param ModelHint[] $hints
     */
    public function __construct(
        public ?float $costPriority = null,
        public ?float $speedPriority = null,
        public ?float $intelligencePriority = null,
        public array $hints = [],
    ) {}

    public function addHint(ModelHint $hint): void {
        $this->hints[] = $hint;
    }

    public static function fromArray(array $data): self {
        $costPriority = $data['costPriority'] ?? null;
        $speedPriority = $data['speedPriority'] ?? null;
        $intelligencePriority = $data['intelligencePriority'] ?? null;
        $hintsData = $data['hints'] ?? [];
        unset($data['costPriority'], $data['speedPriority'], $data['intelligencePriority'], $data['hints']);

        $hints = [];
        if (is_array($hintsData)) {
            foreach ($hintsData as $hint) {
                if (!is_array($hint)) {
                    throw new \InvalidArgumentException('Invalid hint data');
                }
                $hints[] = ModelHint::fromArray($hint);
            }
        }

        $obj = new self(
            costPriority: $costPriority !== null ? (float)$costPriority : null,
            speedPriority: $speedPriority !== null ? (float)$speedPriority : null,
            intelligencePriority: $intelligencePriority !== null ? (float)$intelligencePriority : null,
            hints: $hints
        );

        foreach ($data as $k => $v) {
            $obj->$k = $v;
        }

        $obj->validate();
        return $obj;
    }

    public function validate(): void {
        foreach ([$this->costPriority, $this->speedPriority, $this->intelligencePriority] as $priority) {
            if ($priority !== null && ($priority < 0 || $priority > 1)) {
                throw new \InvalidArgumentException('Priority values must be between 0 and 1');
            }
        }
        foreach ($this->hints as $hint) {
            if (!$hint instanceof ModelHint) {
                throw new \InvalidArgumentException('Hints must be instances of ModelHint');
            }
            $hint->validate();
        }
    }

    public function jsonSerialize(): mixed {
        $data = [];
        if ($this->costPriority !== null) {
            $data['costPriority'] = $this->costPriority;
        }
        if ($this->speedPriority !== null) {
            $data['speedPriority'] = $this->speedPriority;
        }
        if ($this->intelligencePriority !== null) {
            $data['intelligencePriority'] = $this->intelligencePriority;
        }
        if (!empty($this->hints)) {
            $data['hints'] = $this->hints;
        }
        return array_merge($data, $this->extraFields);
    }
}
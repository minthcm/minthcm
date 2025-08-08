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
 * Filename: Types/ToolAnnotations.php
 */

declare(strict_types=1);

namespace Mcp\Types;

/**
 * Represents additional properties describing a Tool to clients.
 */
class ToolAnnotations implements McpModel {
    use ExtraFieldsTrait;

    public function __construct(
        public ?string $title = null,
        public ?bool $readOnlyHint = null,
        public ?bool $destructiveHint = null,
        public ?bool $idempotentHint = null,
        public ?bool $openWorldHint = null,
    ) {}

    public static function fromArray(array $data): self {
        $title = $data['title'] ?? null;
        $readOnlyHint = isset($data['readOnlyHint']) ? (bool)$data['readOnlyHint'] : null;
        $destructiveHint = isset($data['destructiveHint']) ? (bool)$data['destructiveHint'] : null;
        $idempotentHint = isset($data['idempotentHint']) ? (bool)$data['idempotentHint'] : null;
        $openWorldHint = isset($data['openWorldHint']) ? (bool)$data['openWorldHint'] : null;

        unset($data['title'], $data['readOnlyHint'], $data['destructiveHint'], 
              $data['idempotentHint'], $data['openWorldHint']);

        $obj = new self(
            title: $title,
            readOnlyHint: $readOnlyHint,
            destructiveHint: $destructiveHint,
            idempotentHint: $idempotentHint,
            openWorldHint: $openWorldHint
        );

        foreach ($data as $k => $v) {
            $obj->$k = $v;
        }

        $obj->validate();
        return $obj;
    }

    public function validate(): void {
        // No mandatory fields to validate.
    }

    public function jsonSerialize(): mixed {
        $data = [];
        if ($this->title !== null) {
            $data['title'] = $this->title;
        }
        if ($this->readOnlyHint !== null) {
            $data['readOnlyHint'] = $this->readOnlyHint;
        }
        if ($this->destructiveHint !== null) {
            $data['destructiveHint'] = $this->destructiveHint;
        }
        if ($this->idempotentHint !== null) {
            $data['idempotentHint'] = $this->idempotentHint;
        }
        if ($this->openWorldHint !== null) {
            $data['openWorldHint'] = $this->openWorldHint;
        }
        return array_merge($data, $this->extraFields);
    }
}
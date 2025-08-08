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
 * Filename: Types/ListPromptsResult.php
 */

declare(strict_types=1);

namespace Mcp\Types;

class ListPromptsResult extends PaginatedResult {
    /**
     * @param Prompt[] $prompts
     */
    public function __construct(
        public readonly array $prompts,
        ?string $nextCursor = null,
        ?Meta $_meta = null,
    ) {
        parent::__construct($nextCursor, $_meta);
    }

    public static function fromResponseData(array $data): self {
        [$meta, $nextCursor, $data] = self::extractPaginatedBase($data);

        $promptsData = $data['prompts'] ?? [];
        unset($data['prompts']);

        $prompts = [];
        foreach ($promptsData as $p) {
            if (!is_array($p)) {
                throw new \InvalidArgumentException('Invalid prompt data in ListPromptsResult');
            }
            $prompts[] = Prompt::fromArray($p);
        }

        $obj = new self($prompts, $nextCursor, $meta);

        // Extra fields
        foreach ($data as $k => $v) {
            $obj->$k = $v;
        }

        $obj->validate();
        return $obj;
    }

    public function validate(): void {
        parent::validate();
        foreach ($this->prompts as $prompt) {
            if (!$prompt instanceof Prompt) {
                throw new \InvalidArgumentException('Prompts must be instances of Prompt');
            }
            $prompt->validate();
        }
    }

    public function jsonSerialize(): mixed {
        $data = parent::jsonSerialize();
        $data['prompts'] = $this->prompts;
        return $data;
    }
}
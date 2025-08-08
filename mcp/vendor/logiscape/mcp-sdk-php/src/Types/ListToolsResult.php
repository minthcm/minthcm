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
 * Filename: Types/ListToolsResult.php
 */

declare(strict_types=1);

namespace Mcp\Types;

class ListToolsResult extends PaginatedResult {
    /**
     * @param Tool[] $tools
     */
    public function __construct(
        public readonly array $tools,
        ?string $nextCursor = null,
        ?Meta $_meta = null,
    ) {
        parent::__construct($nextCursor, $_meta);
    }

    public static function fromResponseData(array $data): self {
        [$meta, $nextCursor, $data] = self::extractPaginatedBase($data);

        $toolsData = $data['tools'] ?? [];
        unset($data['tools']);

        $tools = [];
        foreach ($toolsData as $t) {
            if (!is_array($t)) {
                throw new \InvalidArgumentException('Invalid tool data in ListToolsResult');
            }
            $tools[] = Tool::fromArray($t);
        }

        $obj = new self($tools, $nextCursor, $meta);

        // Extra fields
        foreach ($data as $k => $v) {
            $obj->$k = $v;
        }

        $obj->validate();
        return $obj;
    }

    public function validate(): void {
        parent::validate();
        foreach ($this->tools as $tool) {
            if (!$tool instanceof Tool) {
                throw new \InvalidArgumentException('Tools must be instances of Tool');
            }
            $tool->validate();
        }
    }

    public function jsonSerialize(): mixed {
        $data = parent::jsonSerialize();
        $data['tools'] = $this->tools;
        return $data;
    }
}
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
 * Filename: Types/ListResourceTemplatesResult.php
 */
 
declare(strict_types=1);

namespace Mcp\Types;

class ListResourceTemplatesResult extends PaginatedResult {
    /**
     * @param ResourceTemplate[] $resourceTemplates
     */
    public function __construct(
        public readonly array $resourceTemplates,
        ?string $nextCursor = null,
        ?Meta $_meta = null,
    ) {
        parent::__construct($nextCursor, $_meta);
    }

    public function validate(): void {
        parent::validate();
        foreach ($this->resourceTemplates as $template) {
            if (!$template instanceof ResourceTemplate) {
                throw new \InvalidArgumentException('Resource templates must be instances of ResourceTemplate');
            }
            $template->validate();
        }
    }

    public function jsonSerialize(): mixed {
        $data = parent::jsonSerialize();
        $data['resourceTemplates'] = $this->resourceTemplates;
        return $data;
    }

    public static function fromResponseData(array $data): self {
        [$meta, $nextCursor, $data] = self::extractPaginatedBase($data);

        $templatesData = $data['resourceTemplates'] ?? [];
        unset($data['resourceTemplates']);

        $resourceTemplates = [];
        foreach ($templatesData as $t) {
            if (!is_array($t)) {
                throw new \InvalidArgumentException('Invalid resource template data in ListResourceTemplatesResult');
            }
            $resourceTemplates[] = ResourceTemplate::fromArray($t);
        }

        $obj = new self($resourceTemplates, $nextCursor, $meta);

        // Extra fields
        foreach ($data as $k => $v) {
            $obj->$k = $v;
        }

        $obj->validate();
        return $obj;
    }
}
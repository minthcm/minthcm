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
 * Filename: Types/ListResourcesResult.php
 */

declare(strict_types=1);

namespace Mcp\Types;

class ListResourcesResult extends PaginatedResult {
    /**
     * @param Resource[] $resources
     */
    public function __construct(
        public readonly array $resources,
        ?string $nextCursor = null,
        ?Meta $_meta = null,
    ) {
        parent::__construct($nextCursor, $_meta);
    }

    public static function fromResponseData(array $data): self {
        [$meta, $nextCursor, $data] = self::extractPaginatedBase($data);

        $resourcesData = $data['resources'] ?? [];
        unset($data['resources']);

        $resources = [];
        foreach ($resourcesData as $r) {
            if (!is_array($r)) {
                throw new \InvalidArgumentException('Invalid resource data in ListResourcesResult');
            }
            $resources[] = Resource::fromArray($r);
        }

        $obj = new self($resources, $nextCursor, $meta);

        // Extra fields
        foreach ($data as $k => $v) {
            $obj->$k = $v;
        }

        $obj->validate();
        return $obj;
    }

    public function validate(): void {
        parent::validate();
        foreach ($this->resources as $resource) {
            if (!$resource instanceof Resource) {
                throw new \InvalidArgumentException('Resources must be instances of Resource');
            }
            $resource->validate();
        }
    }

    public function jsonSerialize(): mixed {
        $data = parent::jsonSerialize();
        $data['resources'] = $this->resources;
        return $data;
    }
}
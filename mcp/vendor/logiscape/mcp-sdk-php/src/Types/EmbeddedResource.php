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
 * Filename: Types/EmbeddedResource.php
 */

declare(strict_types=1);

namespace Mcp\Types;

/**
 * Embedded resource in a prompt or tool call result
 */
class EmbeddedResource implements McpModel {
    use ExtraFieldsTrait;

    public function __construct(
        public readonly ResourceContents $resource,
        public readonly string $type = 'resource',
        public ?Annotations $annotations = null,
    ) {}

    public static function fromArray(array $data): self {
        $type = $data['type'] ?? 'resource';
        unset($data['type']);

        $resourceData = $data['resource'] ?? [];
        unset($data['resource']);

        $annotations = null;
        if (isset($data['annotations']) && is_array($data['annotations'])) {
            $annotations = Annotations::fromArray($data['annotations']);
            unset($data['annotations']);
        }

        // Determine resource type
        $resource = null;
        if (isset($resourceData['text'])) {
            $resource = TextResourceContents::fromArray($resourceData);
        } else {
            $resource = BlobResourceContents::fromArray($resourceData);
        }

        $obj = new self($resource, $type, $annotations);

        foreach ($data as $k => $v) {
            $obj->$k = $v; // EmbeddedResource uses ExtraFieldsTrait
        }

        $obj->validate();
        return $obj;
    }

    public function validate(): void {
        $this->resource->validate();
        if ($this->type !== 'resource') {
            throw new \InvalidArgumentException('Embedded resource type must be "resource"');
        }
        if ($this->annotations !== null) {
            $this->annotations->validate();
        }
    }

    public function jsonSerialize(): mixed {
        $data = [
            'type' => $this->type,
            'resource' => $this->resource,
        ];
        if ($this->annotations !== null) {
            $data['annotations'] = $this->annotations;
        }
        return array_merge($data, $this->extraFields);
    }
}
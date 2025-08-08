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
 * Filename: Types/ReadResourceResult.php
 */

declare(strict_types=1);

namespace Mcp\Types;

class ReadResourceResult extends Result {
    /**
     * @param (TextResourceContents|BlobResourceContents)[] $contents
     */
    public function __construct(
        public readonly array $contents,
        ?Meta $_meta = null,
    ) {
        parent::__construct($_meta);
    }

    public static function fromResponseData(array $data): self {
        // Extract _meta
        $meta = null;
        if (isset($data['_meta'])) {
            $metaData = $data['_meta'];
            unset($data['_meta']);
            $meta = new Meta();
            foreach ($metaData as $k => $v) {
                $meta->$k = $v;
            }
        }

        $contentsData = $data['contents'] ?? [];
        unset($data['contents']);

        $contents = [];
        foreach ($contentsData as $c) {
            if (!is_array($c)) {
                throw new \InvalidArgumentException('Invalid content data in ReadResourceResult');
            }

            if (isset($c['text'])) {
                $contents[] = TextResourceContents::fromArray($c);
                } else {
                $contents[] = BlobResourceContents::fromArray($c);
            }
        }

        $obj = new self($contents, $meta);

        // Extra fields
        foreach ($data as $k => $v) {
            $obj->$k = $v;
        }

        $obj->validate();
        return $obj;
    }

    public function validate(): void {
        parent::validate();
        foreach ($this->contents as $content) {
            if (!($content instanceof TextResourceContents || $content instanceof BlobResourceContents)) {
                throw new \InvalidArgumentException('Contents must be TextResourceContents or BlobResourceContents');
            }
            $content->validate();
        }
    }

    public function jsonSerialize(): mixed {
        $data = parent::jsonSerialize();
        $data['contents'] = $this->contents;
        return $data;
    }
}
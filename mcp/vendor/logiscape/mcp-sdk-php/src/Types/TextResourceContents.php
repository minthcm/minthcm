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
 * Filename: Types/TextResourceContents.php
 */

declare(strict_types=1);

namespace Mcp\Types;

class TextResourceContents extends ResourceContents {
    public function __construct(
        public readonly string $text,
        string $uri,
        ?string $mimeType = null,
    ) {
        parent::__construct($uri, $mimeType);
    }

    public static function fromArray(array $data): self {
        $uri = $data['uri'] ?? '';
        $mimeType = $data['mimeType'] ?? null;
        $text = $data['text'] ?? '';

        unset($data['uri'], $data['mimeType'], $data['text']);

        $obj = new self($text, $uri, $mimeType);

        // No extraFieldsTrait in ResourceContents (?), if ResourceContents uses it, we can do the same pattern:
        // If ResourceContents does not use ExtraFieldsTrait, we simply ignore extra fields or handle them differently.
        // Let's assume ResourceContents does not allow extra fields for now:
        if (!empty($data)) {
            // If we want to allow extra fields, we must add ExtraFieldsTrait to ResourceContents.
            // If not, throwing an exception might be appropriate, but to maintain consistency, let's just ignore them.
            // Or we can handle them if ResourceContents also uses ExtraFieldsTrait. If it doesn't, do nothing.
        }

        $obj->validate();
        return $obj;
    }

    public function validate(): void {
        parent::validate();
        if (empty($this->text)) {
            throw new \InvalidArgumentException('Resource text cannot be empty');
        }
    }

    public function jsonSerialize(): mixed {
        $data = parent::jsonSerialize();
        $data['text'] = $this->text;
        return $data;
    }
}
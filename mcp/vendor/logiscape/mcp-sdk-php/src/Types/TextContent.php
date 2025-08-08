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
 * Filename: Types/TextContent.php
 */

declare(strict_types=1);

namespace Mcp\Types;

/**
 * Text content for messages
 */
class TextContent extends Content {
    public function __construct(
        public readonly string $text,
        ?Annotations $annotations = null,
    ) {
        parent::__construct('text', $annotations);
    }

    public static function fromArray(array $data): self {
        $text = $data['text'] ?? '';
        unset($data['text']);

        $annotations = null;
        if (isset($data['annotations']) && is_array($data['annotations'])) {
            $annotations = Annotations::fromArray($data['annotations']);
            unset($data['annotations']);
        }

        $obj = new self($text, $annotations);

        // If Content or TextContent uses ExtraFieldsTrait, handle extra fields similarly.
        // TextContent extends Content, which may not have ExtraFieldsTrait, but from the pattern, Content probably doesn't.
        // If needed, add ExtraFieldsTrait to Content and merge extra fields here. 
        // For now, TextContent doesn't use ExtraFieldsTrait directly. Let's assume no extra fields at this level.
        // If you want to support unknown fields, you'd need ExtraFieldsTrait on Content as well.

        $obj->validate();
        return $obj;
    }

    public function validate(): void {
        if (is_null($this->text)) {
            throw new \InvalidArgumentException('Text content cannot be null');
        }
        if ($this->annotations !== null) {
            $this->annotations->validate();
        }
    }

    public function jsonSerialize(): mixed {
        $data = parent::jsonSerialize();
        $data['text'] = $this->text;
        return $data;
    }
}
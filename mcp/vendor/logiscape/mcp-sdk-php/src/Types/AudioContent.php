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
 * Filename: Types/AudioContent.php
 */

declare(strict_types=1);

namespace Mcp\Types;

/**
 * Audio content for messages
 */
class AudioContent extends Content {
    public function __construct(
        public readonly string $data,
        public readonly string $mimeType,
        ?Annotations $annotations = null,
    ) {
        parent::__construct('audio', $annotations);
    }

    public static function fromArray(array $data): self {
        $audioData = $data['data'] ?? '';
        $mimeType = $data['mimeType'] ?? '';
        unset($data['data'], $data['mimeType']);

        $annotations = null;
        if (isset($data['annotations']) && is_array($data['annotations'])) {
            $annotations = Annotations::fromArray($data['annotations']);
            unset($data['annotations']);
        }

        $obj = new self($audioData, $mimeType, $annotations);

        $obj->validate();
        return $obj;
    }

    public function validate(): void {
        if (empty($this->data)) {
            throw new \InvalidArgumentException('Audio data cannot be empty');
        }
        if (empty($this->mimeType)) {
            throw new \InvalidArgumentException('Audio mimeType cannot be empty');
        }
        if ($this->annotations !== null) {
            $this->annotations->validate();
        }
    }

    public function jsonSerialize(): mixed {
        $data = parent::jsonSerialize();
        $data['data'] = $this->data;
        $data['mimeType'] = $this->mimeType;
        return $data;
    }
}
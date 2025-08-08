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
 * Filename: Types/BlobResourceContents.php
 */

declare(strict_types=1);

namespace Mcp\Types;

/**
 * Binary resource contents
 */
class BlobResourceContents extends ResourceContents {
    public function __construct(
        public readonly string $blob,
        string $uri,
        ?string $mimeType = null,
    ) {
        parent::__construct($uri, $mimeType);
    }

    public static function fromArray(array $data): self {
        $uri = $data['uri'] ?? '';
        $mimeType = $data['mimeType'] ?? null;
        $blob = $data['blob'] ?? '';

        unset($data['uri'], $data['mimeType'], $data['blob']);

        $obj = new self($blob, $uri, $mimeType);

        // See notes in TextResourceContents regarding extra fields.

        $obj->validate();
        return $obj;
    }

    public function validate(): void {
        parent::validate();
        if (empty($this->blob)) {
            throw new \InvalidArgumentException('Resource blob cannot be empty');
        }
    }
}
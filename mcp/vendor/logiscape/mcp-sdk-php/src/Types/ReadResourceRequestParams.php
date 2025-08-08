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
 * Filename: Types/ReadResourceRequestParams.php
 */

declare(strict_types=1);

namespace Mcp\Types;

/**
 * Params for ReadResourceRequest:
 * { uri: string }
 */
class ReadResourceRequestParams extends RequestParams {
    use ExtraFieldsTrait;

    public function __construct(
        public readonly string $uri,
        ?Meta $_meta = null
    ) {
        parent::__construct($_meta);
    }

    public function validate(): void {
        parent::validate();
        
        if (empty($this->uri)) {
            throw new \InvalidArgumentException('Resource URI cannot be empty');
        }
    }

    public function jsonSerialize(): mixed {
        // Get parent data
        $parentData = parent::jsonSerialize();
        if ($parentData instanceof \stdClass) {
            $parentData = (array)$parentData;
        }

        // Merge with local fields
        $merged = array_merge(
            $parentData,
            ['uri' => $this->uri],
            $this->extraFields
        );

        return !empty($merged) ? $merged : new \stdClass();
    }
}
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
 * Filename: Types/PaginatedResult.php
 */

declare(strict_types=1);

namespace Mcp\Types;

class PaginatedResult extends Result {
    public function __construct(
        public ?string $nextCursor = null,
        ?Meta $_meta = null,
    ) {
        parent::__construct($_meta);
    }

    public function validate(): void {
        parent::validate();
        // no extra validation needed for nextCursor
    }

    public function jsonSerialize(): mixed {
        // Get data from parent (which now handles empty objects correctly)
        $data = parent::jsonSerialize();
        
        // Convert stdClass to array if needed
        if ($data instanceof \stdClass) {
            $data = (array)$data;
        }
        
        // Add nextCursor if it exists
        if ($this->nextCursor !== null) {
            $data['nextCursor'] = $this->nextCursor;
        }
        
        // Return empty object if data is empty
        return !empty($data) ? $data : new \stdClass();
    }

    /**
     * This helper method extracts `_meta` and `nextCursor` from the given $data array
     * and returns them along with the leftover $data. Subclasses can call this method
     * to build their `fromResponseData()` methods without duplicating code.
     *
     * @param array $data The raw result data from the response.
     * @return array{0: Meta|null, 1: string|null, 2: array} [$_meta, $nextCursor, $dataWithoutMetaAndCursor]
     */
    protected static function extractPaginatedBase(array $data): array {
        $meta = null;
        if (isset($data['_meta'])) {
            $metaData = $data['_meta'];
            unset($data['_meta']);
            $meta = new Meta();
            foreach ($metaData as $k => $v) {
                $meta->$k = $v;
            }
        }

        $nextCursor = $data['nextCursor'] ?? null;
        unset($data['nextCursor']);

        return [$meta, $nextCursor, $data];
    }
}
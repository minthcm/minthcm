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
 * Filename: Types/Result.php
 */

declare(strict_types=1);

namespace Mcp\Types;

/**
 * Base class for all responses (Result)
 * Schema: result objects can have `_meta?: object` and arbitrary fields
 */
class Result implements McpModel {
    use ExtraFieldsTrait;

    public function __construct(
        public ?Meta $_meta = null,
    ) {}

    public function validate(): void {
        if ($this->_meta !== null) {
            $this->_meta->validate();
        }
        // Additional validation can be done in subclasses or specialized results
    }

    public function jsonSerialize(): mixed {
        $data = [];
        
        // Only include _meta if it's not null
        if ($this->_meta !== null) {
            $data['_meta'] = $this->_meta;
        }
        
        // Get object properties but exclude _meta (already handled) and extraFields (handled separately)
        $vars = get_object_vars($this);
        unset($vars['_meta'], $vars['extraFields']);
        
        // Add non-null properties
        foreach ($vars as $key => $value) {
            if ($value !== null) {
                $data[$key] = $value;
            }
        }
        
        // Merge any extra fields
        if (!empty($this->extraFields)) {
            $data = array_merge($data, $this->extraFields);
        }
        
        // Return empty object if data is empty
        return !empty($data) ? $data : new \stdClass();
    }
}
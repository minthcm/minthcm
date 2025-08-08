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
 * Filename: Types/ToolInputProperties.php
 */

declare(strict_types=1);

namespace Mcp\Types;

/**
 * Represents the properties object in ToolInputSchema:
 * properties?: { [key: string]: object }
 *
 * We allow arbitrary fields (keys = string, values = object).
 */
class ToolInputProperties implements McpModel, \IteratorAggregate {
    use ExtraFieldsTrait;

    public static function fromArray(array $data): self {
        $obj = new self();
        // All fields are arbitrary, so store them in extraFields
        foreach ($data as $k => $v) {
            $obj->$k = $v;
        }

        $obj->validate();
        return $obj;
    }

    public function validate(): void {
        // Arbitrary fields allowed. Not validating contents.
    }

    public function jsonSerialize(): mixed {
        return empty($this->extraFields) ? new \stdClass() : $this->extraFields;
    }

    public function getIterator(): \Traversable {
        return new \ArrayIterator($this->extraFields);
    }
}

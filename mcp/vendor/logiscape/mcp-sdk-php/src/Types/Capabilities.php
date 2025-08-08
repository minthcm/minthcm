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
 * Filename: Types/Capabilities.php
 */

declare(strict_types=1);

namespace Mcp\Types;

/**
 * Base class for capabilities
 * According to schema:
 * capabilities?: {
 *   experimental?: { [key: string]: object }
 *   ...and more fields in subclasses
 * }
 */
abstract class Capabilities implements McpModel {
    use ExtraFieldsTrait;

    public function __construct(
        public ?ExperimentalCapabilities $experimental = null,
    ) {}

    public static function fromArray(array $data): self {
        $experimentalData = $data['experimental'] ?? null;
        unset($data['experimental']);

        $experimental = null;
        if ($experimentalData !== null && is_array($experimentalData)) {
            $experimental = ExperimentalCapabilities::fromArray($experimentalData);
        }

        $obj = new self($experimental);

        // Extra fields
        foreach ($data as $k => $v) {
            $obj->$k = $v;
        }

        $obj->validate();
        return $obj;
    }

    public function validate(): void {
        if ($this->experimental !== null) {
            $this->experimental->validate();
        }
    }

    public function jsonSerialize(): mixed {
        $data = [];
        if ($this->experimental !== null) {
            $data['experimental'] = $this->experimental;
        }
        return array_merge($data, $this->extraFields);
    }
}
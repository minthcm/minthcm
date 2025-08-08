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
 * Filename: Types/EmptyResult.php
 */

declare(strict_types=1);

namespace Mcp\Types;

/**
 * A placeholder object for responses that have no meaningful result data.
 */
class EmptyResult extends Result
{
    /**
     * Construct from server response data.
     */
    public static function fromResponseData(array $data): static
    {
        // If the server might return _meta, handle it:
        $meta = null;
        if (isset($data['_meta'])) {
            $metaData = $data['_meta'];
            unset($data['_meta']);
            $meta = new Meta();
            foreach ($metaData as $k => $v) {
                $meta->$k = $v;
            }
        }

        $obj = new static($meta);

        // If the server returns arbitrary keys, set them as extra fields:
        foreach ($data as $k => $v) {
            $obj->$k = $v;
        }

        $obj->validate();
        return $obj;
    }

    /**
     * Validate the received data.
     */
    public function validate(): void
    {
        // For an “empty” result, you might do nothing special or call parent:
        parent::validate();
    }
}
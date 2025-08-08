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
 * Filename: Types/ListRootsRequest.php
 */

declare(strict_types=1);

namespace Mcp\Types;

/**
 * Request sent from the server to the client to list roots.
 * According to the schema:
 * interface ListRootsRequest extends Request {
 *   method: "roots/list";
 * }
 *
 * No params are described, so we assume none are required.
 */
class ListRootsRequest extends Request {
    public function __construct() {
        parent::__construct('roots/list');
    }

    public function validate(): void {
        parent::validate();
        // No additional validation needed, no params
    }
}
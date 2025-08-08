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
 * Filename: Types/CallToolRequest.php
 */

declare(strict_types=1);

namespace Mcp\Types;

/**
 * Request to call a tool
 */
class CallToolRequest extends Request {
    /**
     * @param string $name The name of the tool to call
     * @param array<string, mixed>|null $arguments Optional arguments for the tool call
     */
    public function __construct(string $name, ?array $arguments = null) {
        $params = new CallToolRequestParams($name, $arguments);
        parent::__construct('tools/call', $params);
    }

    public function validate(): void {
        parent::validate();
        if ($this->params instanceof CallToolRequestParams) {
            $this->params->validate();
        }
    }
}
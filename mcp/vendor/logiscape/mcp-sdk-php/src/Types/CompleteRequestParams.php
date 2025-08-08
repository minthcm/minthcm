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
 * Filename: Types/CompleteRequestParams.php
 */

declare(strict_types=1);

namespace Mcp\Types;

/**
 * Params for CompleteRequest:
 * {
 *   argument: CompletionArgument,
 *   ref: PromptReference | ResourceReference
 * }
 */
class CompleteRequestParams extends RequestParams
{
    public function __construct(
        public readonly CompletionArgument $argument,
        public readonly PromptReference|ResourceReference $ref,
        ?Meta $_meta = null
    ) {
        parent::__construct($_meta);
    }

    public function validate(): void
    {
        parent::validate(); // validates $_meta if present
        
        // Validate the CompletionArgument
        $this->argument->validate();
        
        // Validate the reference
        $this->ref->validate();
    }

    public function jsonSerialize(): mixed
    {
        $data = [
            'argument' => $this->argument,
            'ref' => $this->ref,
        ];
        
        // Get parent data
        $parentData = parent::jsonSerialize();

        // If parentData is a stdClass (i.e. empty), cast to array so array_merge won't error
        if ($parentData instanceof \stdClass) {
            $parentData = (array)$parentData;
        }

        $merged = array_merge($data, $parentData);

        return !empty($merged) ? $merged : new \stdClass();
    }
}
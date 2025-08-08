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
 * Filename: Shared/ProgressManager.php
 */

declare(strict_types=1);

namespace Mcp\Shared;

use InvalidArgumentException;

/**
 * Creates a progress context for tracking progress
 *
 * Similar to the Python code's context manager `progress()`, but here we just return a ProgressContext instance.
 */
class ProgressManager {
    /**
     * Creates a ProgressContext from a RequestContext.
     * Throws an exception if no progress token is provided.
     */
    public static function createContext(RequestContext $ctx, ?float $total = null): ProgressContext {
        $meta = $ctx->getMeta();
        if ($meta === null || $meta->getProgressToken() === null) {
            throw new InvalidArgumentException('No progress token provided');
        }

        return new ProgressContext(
            $ctx->getSession(),
            $meta->getProgressToken(),
            $total
        );
    }
}
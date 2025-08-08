<?php

/**
 * Model Context Protocol SDK for PHP
 *
 * (c) 2025 Logiscape LLC <https://logiscape.com>
 *
 * Developed by:
 * - Josh Abbott
 * - Claude Opus 4 (Anthropic AI model)
 * - OpenAI Codex
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
 * Filename: Server/Auth/TokenValidationResult.php
 */

declare(strict_types=1);

namespace Mcp\Server\Auth;

/**
 * Result of a token validation attempt.
 */
class TokenValidationResult
{
    /**
     * Constructor.
     *
     * @param bool $valid Whether the token was valid
     * @param array<string,mixed> $claims Token claims if valid
     * @param string|null $error Optional validation error message
     */
    public function __construct(
        public readonly bool $valid,
        public readonly array $claims = [],
        public readonly ?string $error = null
    ) {
    }
}

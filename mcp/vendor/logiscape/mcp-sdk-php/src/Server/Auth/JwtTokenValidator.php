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
 * Filename: Server/Auth/JwtTokenValidator.php
 */

declare(strict_types=1);

namespace Mcp\Server\Auth;

/**
 * Basic JWT validator supporting HS256 and RS256 algorithms with optional
 * claim verification.
 */
class JwtTokenValidator implements TokenValidatorInterface
{
    public function __construct(
        private readonly string $key,
        private readonly string $algorithm = 'HS256',
        private readonly ?string $issuer = null,
        private readonly ?string $audience = null,
    ) {
    }

    public function validate(string $token): TokenValidationResult
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return new TokenValidationResult(false, [], 'Malformed token');
        }

        [$encodedHeader, $encodedPayload, $encodedSig] = $parts;
        $header = json_decode($this->base64UrlDecode($encodedHeader), true);
        $payload = json_decode($this->base64UrlDecode($encodedPayload), true);
        if ($header === null || $payload === null) {
            return new TokenValidationResult(false, [], 'Invalid encoding');
        }

        $alg = $header['alg'] ?? $this->algorithm;
        $data = $encodedHeader . '.' . $encodedPayload;
        $signature = $this->base64UrlDecode($encodedSig);

        $valid = false;
        if ($alg === 'HS256') {
            $expected = hash_hmac('sha256', $data, $this->key, true);
            $valid = hash_equals($expected, $signature);
        } elseif ($alg === 'RS256') {
            $valid = openssl_verify($data, $signature, $this->key, OPENSSL_ALGO_SHA256) === 1;
        } else {
            return new TokenValidationResult(false, [], 'Unsupported algorithm');
        }

        if (!$valid) {
            return new TokenValidationResult(false, [], 'Signature verification failed');
        }

        $now = time();

        if (isset($payload['exp']) && $now >= (int) $payload['exp']) {
            return new TokenValidationResult(false, [], 'Token expired');
        }

        if (isset($payload['nbf']) && $now < (int) $payload['nbf']) {
            return new TokenValidationResult(false, [], 'Token not yet valid');
        }

        if (isset($payload['iat']) && $now < (int) $payload['iat']) {
            return new TokenValidationResult(false, [], 'Token issued in the future');
        }

        if ($this->issuer !== null && ($payload['iss'] ?? null) !== $this->issuer) {
            return new TokenValidationResult(false, [], 'Invalid issuer');
        }

        if ($this->audience !== null) {
            $aud = $payload['aud'] ?? null;
            $audValid = false;
            if (is_string($aud)) {
                $audValid = $aud === $this->audience;
            } elseif (is_array($aud)) {
                $audValid = in_array($this->audience, $aud, true);
            }
            if (!$audValid) {
                return new TokenValidationResult(false, [], 'Invalid audience');
            }
        }

        return new TokenValidationResult(true, $payload);
    }

    private function base64UrlDecode(string $input): string
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $input .= str_repeat('=', 4 - $remainder);
        }
        return base64_decode(strtr($input, '-_', '+/')) ?: '';
    }
}

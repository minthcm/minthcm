<?php

namespace MintMCP\Auth\Internal;

use MintMCP\Auth\Services\TokenService;
use MintMCP\Server\Logger;

/**
 * Internal Authentication Handler for MintHCM MCP
 * 
 * Handles internal token generation using a signed proof from the API
 * (`mcp_csrf_token`), so it works when the app and MCP run on different hosts.
 */
class SessionTokenAuth
{
    private TokenService $tokenService;
    private ?array $validatedSignedToken = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tokenService = new TokenService();
    }

    /**
     * Handle internal token request (POST)
     * 
     * @return array Response with access token or error
     */
    public function handleTokenRequest(): array
    {
        // Ensure session is accessible
        if (session_status() === PHP_SESSION_NONE) {
            Logger::getLogger()->error('Session not started in handleTokenRequest');
            return [
                'status' => 500,
                'body' => [
                    'error' => 'server_error',
                    'error_description' => 'Session not initialized'
                ]
            ];
        }

        if (!$this->validateSignedProofHeader()) {
            Logger::getLogger()->warning('Invalid or missing signed proof in internal token request');
            return [
                'status' => 403,
                'body' => [
                    'error' => 'forbidden',
                    'error_description' => 'Invalid or missing CSRF token'
                ]
            ];
        }

        $userId = $this->getSignedTokenUserId();
        if (!$userId) {
            Logger::getLogger()->warning('Internal token request missing user id from signed proof');
            return [
                'status' => 401,
                'body' => [
                    'error' => 'unauthorized',
                    'error_description' => 'User not authenticated'
                ]
            ];
        }

        try {
            $tokenData = $this->tokenService->createInternalToken($userId);

            Logger::getLogger()->info('Internal token generated', [
                'user_id' => $userId
            ]);

            return [
                'status' => 200,
                'body' => $tokenData
            ];
        } catch (\Exception $e) {
            Logger::getLogger()->error('Failed to generate internal token', [
                'error' => $e->getMessage(),
                'user_id' => $userId
            ]);

            return [
                'status' => 500,
                'body' => [
                    'error' => 'server_error',
                    'error_description' => 'Failed to generate token'
                ]
            ];
        }
    }

    private function validateSignedProofHeader(): bool
    {
        $headers = getallheaders();
        $proof = null;
        foreach (['X-CSRF-Token', 'x-csrf-token', 'X-Csrf-Token'] as $headerName) {
            if (isset($headers[$headerName])) {
                $proof = $headers[$headerName];
                break;
            }
        }

        if (empty($proof) || !is_string($proof)) {
            Logger::getLogger()->warning('Signed proof not found in request headers');
            return false;
        }

        $signedTokenPayload = $this->validateSignedToken($proof);
        if ($signedTokenPayload === null) {
            Logger::getLogger()->warning('Signed proof validation failed');
            return false;
        }

        $this->validatedSignedToken = $signedTokenPayload;
        return true;
    }

    private function getSignedTokenUserId(): ?string
    {
        $userId = $this->validatedSignedToken['user_id'] ?? null;
        return is_string($userId) && $userId !== '' ? $userId : null;
    }

    private function validateSignedToken(string $token): ?array
    {
        $parts = explode('.', $token, 2);
        if (count($parts) !== 2) {
            return null;
        }

        [$payloadEncoded, $signature] = $parts;
        $payloadJson = $this->base64UrlDecode($payloadEncoded);
        if ($payloadJson === null) {
            return null;
        }

        $payload = json_decode($payloadJson, true);
        if (!is_array($payload)) {
            return null;
        }

        if (($payload['purpose'] ?? null) !== 'mcp_internal_session_token') {
            return null;
        }

        $userId = $payload['user_id'] ?? null;
        $expiresAt = $payload['exp'] ?? null;
        if (!is_string($userId) || $userId === '' || !is_int($expiresAt)) {
            return null;
        }

        if ($expiresAt < time()) {
            return null;
        }

        $secret = $this->getSigningSecret();
        if ($secret === '') {
            return null;
        }

        $expectedSignature = hash_hmac('sha256', $payloadJson, $secret);
        if (!hash_equals($expectedSignature, $signature)) {
            return null;
        }

        return $payload;
    }

    private function base64UrlDecode(string $value): ?string
    {
        $padded = strtr($value, '-_', '+/');
        $padding = strlen($padded) % 4;
        if ($padding > 0) {
            $padded .= str_repeat('=', 4 - $padding);
        }

        $decoded = base64_decode($padded, true);
        return $decoded === false ? null : $decoded;
    }

    private function getSigningSecret(): string
    {
        global $sugar_config;

        return (string) ($sugar_config['unique_key'] ?? '');
    }
}

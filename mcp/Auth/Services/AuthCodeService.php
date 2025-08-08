<?php

namespace MintMCP\Auth\Services;

use BeanFactory;
use Exception;
use MintMCP\Auth\Utils\LegacyBridge;
use MintMCP\Handlers\Logger;

/**
 * Handles authorization code operations
 */
class AuthCodeService
{
    /**
     * Generate authorization code
     */
    public function generateAuthCode(
        string $clientId,
        string $scope,
        string $codeChallenge,
        string $codeChallengeMethod,
        string $userId
    ): string {
        $code = bin2hex(random_bytes(32));

        chdir('../legacy');
        $codeBean = BeanFactory::newBean('OAuth2Codes');
        $codeBean->code = $code;
        $codeBean->client_id = $clientId;
        $codeBean->user_id = $userId;
        $codeBean->scope = $scope;
        $codeBean->code_challenge = $codeChallenge;
        $codeBean->code_challenge_method = $codeChallengeMethod;
        $codeBean->used = 0;
        $codeBean->code_expires = date('Y-m-d H:i:s', strtotime('+10 minutes')); // 10 minutes expiry

        $success = $codeBean->save();
        chdir('../mcp');

        if (!$success) {
            throw new Exception('Failed to save authorization code');
        }

        Logger::getLogger()->info('Generated auth code', [
            'code' => substr($code, 0, 8) . '...',
            'client_id' => $clientId,
            'user_id' => $userId
        ]);

        return $code;
    }

    /**
     * Gets a stored authorization code
     */
    public function getStoredAuthCode(string $code): ?array
    {
        chdir('../legacy');
        $codeBean = BeanFactory::newBean('OAuth2Codes');
        $found = $codeBean->retrieve_by_string_fields([
            'code' => $code,
            'used' => 0,
            'deleted' => 0
        ]);
        chdir('../mcp');

        if (!empty($found->id)) {
            return [
                'client_id' => $found->client_id,
                'scope' => $found->scope,
                'code_challenge' => $found->code_challenge,
                'code_challenge_method' => $found->code_challenge_method,
                'user_id' => $found->user_id,
                'code_expires' => $found->code_expires,
            ];
        }

        return null;
    }

    /**
     * Deletes a used authorization code
     */
    public function deleteAuthCode(string $code): void
    {
        chdir('../legacy');
        $codeBean = BeanFactory::newBean('OAuth2Codes');
        $found = $codeBean->retrieve_by_string_fields([
            'code' => $code,
            'used' => 0,
            'deleted' => 0
        ]);

        if (empty($found->id)) {
            throw new Exception('Authorization code not found or already used');
        }

        $codeBean = $found;
        $codeBean->used = 1;
        $codeBean->date_modified = date('Y-m-d H:i:s');
        $codeBean->save();
        $codeBean->mark_deleted($codeBean->id);
        chdir('../mcp');
    }

    /**
     * Validates PKCE
     */
    public function validatePKCE(string $codeChallenge, string $codeVerifier, string $method = 'S256'): bool
    {
        if ($method === 'S256') {
            $calculatedChallenge = rtrim(strtr(base64_encode(hash('sha256', $codeVerifier, true)), '+/', '-_'), '=');
            return hash_equals($codeChallenge, $calculatedChallenge);
        }

        if ($method === 'plain') {
            return hash_equals($codeChallenge, $codeVerifier);
        }

        return false;
    }
}

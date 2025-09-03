<?php

namespace MintMCP\Auth\Services;

use BeanFactory;
use DateInterval;
use DateTime;
use Exception;
use MintMCP\Handlers\Logger;
use OAuth2Clients;
use OAuth2Tokens;

/**
 * Handles token-related operations
 */
class TokenService
{
    private ClientService $clientService;

    public function __construct()
    {
        $this->clientService = new ClientService();
    }

    /**
     * Introspect a token to verify its validity
     * 
     * @param string $token Access token to introspect
     * @return array|null Token data if valid, null otherwise
     */
    public function introspectToken(string $token): ?array
    {
        global $timedate;
        chdir('../legacy');
        $tokenBean = $this->getTokenBean($token);
        chdir('../mcp');

        if (!$tokenBean) {
            return null;
        }

        $now = $timedate->nowDb();
        // Check if token is active (not revoked and not expired)
        if ($tokenBean->token_is_revoked || strtotime($tokenBean->access_token_expires) < strtotime($now)) {
            return null;
        }

        chdir('../legacy');
        $clientBean = $this->clientService->getClientById($tokenBean->client);
        chdir('../mcp');

        if (!$clientBean) {
            return null;
        }

        // Return token data
        return [
            'active' => true,
            'scope' => $tokenBean->scope ?? 'mcp:read mcp:write',
            'client_id' => $tokenBean->client,
            'user_id' => $tokenBean->assigned_user_id,
            'exp' => strtotime($tokenBean->access_token_expires),
            'token_type' => $tokenBean->token_type
        ];
    }

    /**
     * Create tokens and store them
     * 
     * @param OAuth2Clients $clientBean Client bean
     * @param string $scope Token scope
     * @param string $userId User ID
     * @return array Token data
     */
    public function createTokens(OAuth2Clients $clientBean, string $scope, string $userId): array
    {
        if (empty($userId)) {
            throw new Exception('User ID is required for token creation');
        }

        Logger::getLogger()->info('Creating tokens', [
            'client_id' => $clientBean->id,
            'user_id' => $userId,
            'scope' => $scope
        ]);

        // Generate tokens
        $accessToken = bin2hex(random_bytes(32));
        $refreshToken = bin2hex(random_bytes(32));

        // Set token expiry (1 hour for access token, 30 days for refresh)
        $expiresIn = 3600; // 1 hour

        chdir('../legacy');
        $tokenBean = BeanFactory::newBean('OAuth2Tokens');
        $tokenBean->client = $clientBean->id;
        $tokenBean->access_token = $accessToken;
        $tokenBean->token_type = 'Bearer';
        $tokenBean->refresh_token = $refreshToken;
        $tokenBean->access_token_expires = $this->getExpiryDate($expiresIn);
        $tokenBean->refresh_token_expires = $this->getExpiryDate($expiresIn * 24 * 30); // 30 days
        $tokenBean->scope = $scope;
        $tokenBean->assigned_user_id = $userId;

        $result = $tokenBean->save();
        chdir('../mcp');

        if (!$result) {
            throw new Exception('Failed to save OAuth tokens');
        }

        // Return token response
        return [
            'access_token' => $accessToken,
            'token_type' => 'Bearer',
            'expires_in' => $expiresIn,
            'refresh_token' => $refreshToken,
            'scope' => $scope
        ];
    }

    /**
     * Get token by access token
     */
    private function getTokenBean(string $accessToken): ?OAuth2Tokens
    {
        $tokenBean = BeanFactory::newBean('OAuth2Tokens');
        $found = $tokenBean->retrieve_by_string_fields([
            'access_token' => $accessToken,
            'token_is_revoked' => 0,
            'deleted' => 0
        ]);

        return $found ?: null;
    }

    /**
     * Get token by refresh token
     */
    public function getTokenByRefreshToken(string $refreshToken): ?OAuth2Tokens
    {
        $tokenBean = BeanFactory::newBean('OAuth2Tokens');
        $found = $tokenBean->retrieve_by_string_fields([
            'refresh_token' => $refreshToken,
            'token_is_revoked' => 0,
            'deleted' => 0
        ]);

        return $found ?: null;
    }

    /**
     * Get expiry date
     */
    private function getExpiryDate(int $secondsFromNow): string
    {
        global $timedate;
        $date = new DateTime();
        $date->add(new DateInterval('PT' . $secondsFromNow . 'S'));
        return $timedate->asDb($date);
    }
}

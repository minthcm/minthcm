<?php

namespace MintMCP\Auth;

use Exception;
use MintMCP\Auth\Services\AuthCodeService;
use MintMCP\Auth\Services\ClientService;
use MintMCP\Auth\Services\TokenService;
use MintMCP\Auth\Services\UserService;
use MintMCP\Auth\Utils\UrlHelper;
use MintMCP\Server\Logger;

/**
 * OAuth2 Server Implementation for MintHCM
 * 
 * Provides OAuth 2.1 functionality including authorization code flow,
 * token refresh, and userinfo endpoints
 */
class OAuth2Server
{
    private static ?OAuth2Server $instance = null;
    private TokenService $tokenService;
    private ClientService $clientService;
    private AuthCodeService $authCodeService;
    private UserService $userService;
    private UrlHelper $urlHelper;

    /**
     * Get singleton instance of OAuth2Server
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct()
    {
        $this->tokenService = new TokenService();
        $this->clientService = new ClientService();
        $this->authCodeService = new AuthCodeService();
        $this->userService = new UserService();
        $this->urlHelper = new UrlHelper();
    }

    /**
     * Handle OAuth discovery endpoint
     * 
     * @return array Discovery endpoint data
     */
    public function handleDiscovery(): array
    {
        $domainUrl = $this->urlHelper->getDomainUrl();

        return [
            'resource' => $domainUrl . '/',
            'resource_name' => 'MintHCM MCP Server',
            'authorization_servers' => [$domainUrl],
            'jwks_uri' => $domainUrl . '/oauth/jwks',
            'scopes_supported' => ['openid', 'profile', 'mcp:read', 'mcp:write'],
        ];
    }

    /**
     * Return authorization server metadata (RFC 8414)
     * 
     * @return array Authorization metadata
     */
    public function handleAuthorization(): array
    {
        $domainUrl = $this->urlHelper->getDomainUrl();
        $oauthBase = $domainUrl . '/oauth';

        return [
            // REQUIRED
            'issuer' => $domainUrl,
            'authorization_endpoint' => $oauthBase . '/authorize',
            'token_endpoint' => $oauthBase . '/token',
            // RECOMMENDED
            'jwks_uri' => $oauthBase . '/jwks',
            'registration_endpoint' => $oauthBase . '/register',
            'scopes_supported' => ['openid', 'profile', 'email', 'mcp:read', 'mcp:write'],
            'response_types_supported' => ['code'],
            'code_challenge_methods_supported' => ['S256'],
            'grant_types_supported' => ['authorization_code', 'refresh_token'],
            // Required by OIDC Discovery 1.0 §3 — Bun/Claude Code SDK validates these as arrays.
            'subject_types_supported' => ['public'],
            'id_token_signing_alg_values_supported' => ['RS256'],
        ];
    }

    /**
     * Validate a token from headers
     * 
     * @param array $headers Request headers
     * @return array|null Token data or null if invalid
     */
    public function validateToken(array $headers): ?array
    {
        $auth = $headers['Authorization'] ?? $headers['authorization'] ?? null;
        if (!$auth || !preg_match('/Bearer\s+(\S+)/', $auth, $matches)) {
            return null;
        }

        $token = $matches[1];
        return $this->introspectToken($token);
    }

    /**
     * Introspect a token to verify its validity
     * 
     * @param string $token Access token to introspect
     * @return array|null Token data if valid, null otherwise
     */
    public function introspectToken(string $token): ?array
    {
        return $this->tokenService->introspectToken($token);
    }

    /**
     * First leg of the authorization code flow. Validates the incoming request
     * and ALWAYS redirects to the Vue consent page — even when the user already
     * has an authenticated session — so the user must explicitly approve.
     * The second leg (completeAuthorization) is invoked by McpController after
     * the user clicks Authorize on the consent screen.
     */
    public function handleAuthorizeRequest(array $params): array
    {
        $normalized = [
            'clientId' => $params['client_id'] ?? '',
            'redirectUri' => $params['redirect_uri'] ?? '',
            'scope' => $params['scope'] ?? 'mcp:read',
            'state' => $params['state'] ?? '',
            'codeChallenge' => $params['code_challenge'] ?? '',
            'codeChallengeMethod' => $params['code_challenge_method'] ?? 'S256',
        ];

        if (!$this->validateAuthorizationParams($normalized)) {
            return $this->createError(400, 'invalid_request', 'Missing required parameters');
        }

        chdir('../legacy');
        $clientBean = $this->clientService->getClientById($normalized['clientId']);
        chdir('../mcp');

        if (!$clientBean) {
            return $this->createError(400, 'invalid_client', 'Client not found');
        }

        // Validate redirect_uri up front so a malicious client can't use the consent
        // page as a confused-deputy to bounce the browser to an arbitrary URL.
        if (!$this->clientService->isValidRedirectUri($clientBean, $normalized['redirectUri'])) {
            return $this->createError(400, 'invalid_request', 'Invalid redirect_uri');
        }

        return $this->redirectToLogin(
            $normalized['clientId'],
            $clientBean->name,
            $normalized['redirectUri'],
            $normalized['scope'],
            $normalized['state'],
            $normalized['codeChallenge'],
            $normalized['codeChallengeMethod']
        );
    }

    /**
     * Issue the authorization code after explicit user consent. Called from
     * McpController::authorize() which has already validated the consent_token
     * and confirmed the session user.
     *
     * @param array $flow The stored oauth_flows[flow_id] entry.
     * @param string $userId ID of the authenticated user granting consent.
     */
    public function completeAuthorization(array $flow, string $userId): array
    {
        $clientId = (string) ($flow['client_id'] ?? '');
        $redirectUri = (string) ($flow['redirect_uri'] ?? '');
        $scope = (string) ($flow['scope'] ?? '');
        $state = (string) ($flow['state'] ?? '');
        $codeChallenge = (string) ($flow['code_challenge'] ?? '');
        $codeChallengeMethod = (string) ($flow['code_challenge_method'] ?? 'S256');

        chdir('../legacy');
        $clientBean = $this->clientService->getClientById($clientId);
        chdir('../mcp');

        if (!$clientBean) {
            return $this->createError(400, 'invalid_client', 'Client not found');
        }

        if (!$this->clientService->isValidRedirectUri($clientBean, $redirectUri)) {
            return $this->createError(400, 'invalid_request', 'Invalid redirect_uri');
        }

        chdir('../legacy');
        if (empty($clientBean->assigned_user_id)) {
            $clientBean->assigned_user_id = $userId;
            $clientBean->save();
        }
        chdir('../mcp');

        $authCode = $this->authCodeService->generateAuthCode(
            $clientBean->id,
            $scope,
            $codeChallenge,
            $codeChallengeMethod,
            $userId
        );

        Logger::getLogger()->info('Issuing authorization code after consent', [
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'auth_code' => substr($authCode, 0, 8) . '...',
            'state' => $state,
        ]);

        return [
            'status' => 302,
            'headers' => [
                'Location' => $redirectUri . '?' . http_build_query([
                    'code' => $authCode,
                    'state' => $state,
                ])
            ]
        ];
    }

    /**
     * Validate authorization parameters
     */
    private function validateAuthorizationParams(array $params): bool
    {
        return !empty($params['clientId']) &&
            !empty($params['redirectUri']) &&
            !empty($params['codeChallenge']);
    }

    public const FLOW_TTL_SECONDS = 900;

    private function redirectToLogin(
        string $clientId,
        string $clientName,
        string $redirectUri,
        string $scope,
        string $state,
        string $codeChallenge,
        string $codeChallengeMethod
    ): array {
        $flowId = bin2hex(random_bytes(16));
        $consentToken = bin2hex(random_bytes(32));
        $now = time();

        if (!isset($_SESSION['oauth_flows']) || !is_array($_SESSION['oauth_flows'])) {
            $_SESSION['oauth_flows'] = [];
        }

        // Drop expired flows so abandoned consent screens don't accumulate in the session.
        foreach ($_SESSION['oauth_flows'] as $id => $flow) {
            if (!is_array($flow) || ($flow['expires_at'] ?? 0) < $now) {
                unset($_SESSION['oauth_flows'][$id]);
            }
        }

        $_SESSION['oauth_flows'][$flowId] = [
            'client_id' => $clientId,
            'client_name' => $clientName,
            'redirect_uri' => $redirectUri,
            'scope' => $scope,
            'state' => $state,
            'code_challenge' => $codeChallenge,
            'code_challenge_method' => $codeChallengeMethod,
            'consent_token' => $consentToken,
            'expires_at' => $now + self::FLOW_TTL_SECONDS,
        ];

        // site_url points at the Mint/Vue host; HTTP_HOST would point at the MCP host where /#/mcp/login doesn't exist.
        $loginUrl = $this->getMintSiteUrl() . '#/mcp/login?flow=' . urlencode($flowId);

        Logger::getLogger()->info('Redirecting to login', [
            'loginUrl' => $loginUrl,
            'flow_id' => $flowId,
        ]);

        return [
            'status' => 302,
            'headers' => [
                'Location' => $loginUrl
            ]
        ];
    }

    private function getMintSiteUrl(): string
    {
        $siteUrl = $GLOBALS['sugar_config']['site_url'] ?? '';
        if (!is_string($siteUrl) || $siteUrl === '') {
            $siteUrl = $this->urlHelper->getDomainUrl();
        }
        return rtrim($siteUrl, '/') . '/';
    }

    /**
     * Handle token request (exchange code for tokens)
     * 
     * @return array Response with status code and body
     */
    public function handleTokenRequest(): array
    {
        $grantType = $_POST['grant_type'] ?? '';

        switch ($grantType) {
            case 'authorization_code':
                return $this->handleAuthorizationCodeGrant();
            case 'refresh_token':
                return $this->handleRefreshTokenGrant();
            default:
                return $this->createError(400, 'unsupported_grant_type', 'Grant type not supported');
        }
    }

    /**
     * Handle authorization code grant
     * 
     * @return array Response with status code and body
     */
    private function handleAuthorizationCodeGrant(): array
    {
        $params = $this->getAuthCodeParams();

        // Get stored auth code
        $authCodeData = $this->authCodeService->getStoredAuthCode($params['code']);
        if (!$authCodeData) {
            return $this->createError(400, 'invalid_grant', 'Invalid authorization code');
        }

        // Validate PKCE
        if (!$this->authCodeService->validatePKCE(
            $authCodeData['code_challenge'],
            $params['codeVerifier'],
            $authCodeData['code_challenge_method']
        )) {
            return $this->createError(400, 'invalid_grant', 'PKCE validation failed');
        }

        // Check code expiry (10 minutes)
        if (strtotime($authCodeData['code_expires']) < time()) {
            return $this->createError(400, 'invalid_grant', 'Authorization code expired');
        }

        // Generate tokens
        chdir('../legacy');
        $clientBean = $this->clientService->getClientById($authCodeData['client_id']);
        chdir('../mcp');
        if (!$clientBean) {
            return $this->createError(400, 'invalid_client', 'Client not found');
        }

        // Create the tokens
        try {
            $tokenData = $this->tokenService->createTokens(
                $clientBean,
                $authCodeData['scope'],
                $authCodeData['user_id']
            );

            // Delete the used authorization code
            $this->authCodeService->deleteAuthCode($params['code']);

            return [
                'status' => 200,
                'body' => $tokenData
            ];
        } catch (Exception $e) {
            Logger::getLogger()->error('Token creation failed', ['error' => $e->getMessage()]);
            return $this->createError(500, 'server_error', 'Failed to create tokens');
        }
    }

    /**
     * Get authorization code parameters from request
     */
    private function getAuthCodeParams(): array
    {
        return [
            'code' => $_POST['code'] ?? '',
            'clientId' => $_POST['client_id'] ?? '',
            'redirectUri' => $_POST['redirect_uri'] ?? '',
            'codeVerifier' => $_POST['code_verifier'] ?? '',
        ];
    }

    /**
     * Handle refresh token grant
     * 
     * @return array Response with status code and body
     */
    private function handleRefreshTokenGrant(): array
    {
        $refreshToken = $_POST['refresh_token'] ?? '';

        // Validate refresh token
        chdir('../legacy');
        $tokenBean = $this->tokenService->getTokenByRefreshToken($refreshToken);
        chdir('../mcp');
        if (!$tokenBean) {
            return $this->createError(400, 'invalid_grant', 'Invalid refresh token');
        }

        // Check if refresh token is expired
        if (
            $tokenBean->refresh_token_expires &&
            strtotime($tokenBean->refresh_token_expires) < time()
        ) {
            return $this->createError(400, 'invalid_grant', 'Refresh token expired');
        }

        $clientId = $tokenBean->client;
        // Get client
        chdir('../legacy');
        $clientBean = $this->clientService->getClientById($clientId);
        chdir('../mcp');

        if (!$clientBean) {
            return $this->createError(400, 'invalid_client', 'Client not found');
        }

        // Revoke old token
        chdir('../legacy');
        $tokenBean->token_is_revoked = true;
        $tokenBean->save();
        chdir('../mcp');

        // Create new tokens
        $userId = $clientBean->assigned_user_id;
        $scope = $tokenBean->scope;

        $tokenData = $this->tokenService->createTokens($clientBean, $scope, $userId);

        return [
            'status' => 200,
            'body' => $tokenData
        ];
    }

    /**
     * Handle userinfo request
     * 
     * @param array $headers Request headers
     * @return array Response with status code and body
     */
    public function handleUserinfoRequest(array $headers): array
    {
        $tokenData = $this->validateToken($headers);
        if (!$tokenData) {
            return $this->createError(401, 'invalid_token', 'Invalid or expired token');
        }

        $userId = $tokenData['user_id'];
        $userInfo = $this->userService->getUserInfo($userId);

        return [
            'status' => 200,
            'body' => $userInfo
        ];
    }

    /**
     * Handle Dynamic Client Registration (RFC 7591)
     * 
     * @param array $input Client registration data
     * @return array Response with status code and body
     */
    public function handleClientRegistration(array $input): array
    {
        $result = $this->clientService->registerClient($input);

        if (isset($result['error'])) {
            return $this->createError($result['status'], $result['error'], $result['error_description']);
        }

        return [
            'status' => $result['status'],
            'body' => $result['data']
        ];
    }

    public function sendOAuthChallenge()
    {
        http_response_code(401);

        $domainUrl = $this->urlHelper->getDomainUrl();

        $wwwAuthenticateValue = sprintf(
            'Bearer realm="MCP Server", ' .
                'resource_metadata="%s/.well-known/oauth-protected-resource", ' .
                'authorization_uri="%s/oauth/authorize", ' .
                'resource="%s", ' .
                'authorization_servers="%s", ' .
                'scope="mcp:read mcp:write"',
            $domainUrl,
            $domainUrl,
            $domainUrl,
            $domainUrl
        );

        header('WWW-Authenticate: ' . $wwwAuthenticateValue);
        header('Link: <' . $domainUrl . '/.well-known/oauth-protected-resource>; rel="oauth-protected-resource"');
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Expose-Headers: WWW-Authenticate, Link');

        echo json_encode([
            'error' => 'unauthorized',
            'error_description' => 'Valid OAuth 2.1 access token required',
            'authorization_server_discovery' => $domainUrl . '/.well-known/oauth-protected-resource'
        ]);
        exit;
    }

    /**
     * Create error response
     */
    private function createError(int $status, string $error, string $description = ''): array
    {
        Logger::getLogger()->error('OAuth error', [
            'status' => $status,
            'error' => $error,
            'description' => $description
        ]);

        return [
            'status' => $status,
            'body' => [
                'error' => $error,
                'error_description' => $description
            ]
        ];
    }
}

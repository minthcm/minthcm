<?php

namespace MintMCP\Auth;

use Exception;
use MintMCP\Auth\Services\AuthCodeService;
use MintMCP\Auth\Services\ClientService;
use MintMCP\Auth\Services\TokenService;
use MintMCP\Auth\Services\UserService;
use MintMCP\Auth\Utils\UrlHelper;
use MintMCP\Handlers\Logger;

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
            'response_types_supported' => ['code']
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
     * Handle the authorization request
     * 
     * @return array Response data with status and headers/body
     */
    public function handleAuthorizeRequest(): array
    {
        $params = $this->getAuthorizationParams();
        if (!$this->validateAuthorizationParams($params)) {
            return $this->createError(400, 'invalid_request', 'Missing required parameters');
        }

        chdir('../legacy');
        $clientBean = $this->clientService->getClientById($params['clientId']);
        chdir('../mcp');

        if (!$clientBean) {
            return $this->createError(400, 'invalid_client', 'Client not found');
        }

        // Check if user is already authenticated
        $authenticatedUserId = $this->userService->getAuthenticatedUserId();
        if (!$authenticatedUserId) {
            // Redirect to login form
            return $this->redirectToLogin(
                $params['clientId'],
                $clientBean->name,
                $params['redirectUri'],
                $params['scope'],
                $params['state'],
                $params['codeChallenge'],
                $params['codeChallengeMethod']
            );
        }

        if (!$this->clientService->isValidRedirectUri($clientBean, $params['redirectUri'])) {
            return $this->createError(400, 'invalid_request', 'Invalid redirect_uri');
        }

        chdir('../legacy');
        if (empty($clientBean->assigned_user_id)) {
            $clientBean->assigned_user_id = $authenticatedUserId;
            $clientBean->save();
        }
        chdir('../mcp');

        // Generate authorization code with logged in user ID
        $authCode = $this->authCodeService->generateAuthCode(
            $clientBean->id,
            $params['scope'],
            $params['codeChallenge'],
            $params['codeChallengeMethod'],
            $authenticatedUserId
        );

        Logger::getLogger()->info("Redirecting to client after authorization", [
            'client_id' => $params['clientId'],
            'redirect_uri' => $params['redirectUri'],
            'auth_code' => substr($authCode, 0, 8) . '...',
            'state' => $params['state']
        ]);

        return [
            'status' => 302,
            'headers' => [
                'Location' => $params['redirectUri'] . '?' . http_build_query([
                    'code' => $authCode,
                    'state' => $params['state']
                ])
            ]
        ];
    }

    /**
     * Get and validate authorization request parameters
     */
    private function getAuthorizationParams(): array
    {
        return [
            'clientId' => $_GET['client_id'] ?? '',
            'redirectUri' => $_GET['redirect_uri'] ?? '',
            'scope' => $_GET['scope'] ?? 'mcp:read',
            'state' => $_GET['state'] ?? '',
            'codeChallenge' => $_GET['code_challenge'] ?? '',
            'codeChallengeMethod' => $_GET['code_challenge_method'] ?? 'S256',
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

    /**
     * Redirect to login form
     */
    private function redirectToLogin(
        string $clientId,
        string $clientName,
        string $redirectUri,
        string $scope,
        string $state,
        string $codeChallenge,
        string $codeChallengeMethod
    ): array {
        $_SESSION['oauth_params'] = [
            'client_id' => $clientId,
            'client_name' => $clientName,
            'redirect_uri' => $redirectUri,
            'scope' => $scope,
            'state' => $state,
            'code_challenge' => $codeChallenge,
            'code_challenge_method' => $codeChallengeMethod
        ];

        $loginUrl = $this->urlHelper->getOAuthBaseUrl() . '/login';
        Logger::getLogger()->info('Redirecting to login', [
            'loginUrl' => $loginUrl,
        ]);

        return [
            'status' => 302,
            'headers' => [
                'Location' => $loginUrl
            ]
        ];
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

        // Validate client
        if ($authCodeData['client_id'] !== $params['clientId']) {
            return $this->createError(400, 'invalid_grant', 'Client ID mismatch');
        }

        // Check code expiry (10 minutes)
        if (strtotime($authCodeData['code_expires']) < time()) {
            return $this->createError(400, 'invalid_grant', 'Authorization code expired');
        }

        // Generate tokens
        chdir('../legacy');
        $clientBean = $this->clientService->getClientById($params['clientId']);
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
        $clientId = $_POST['client_id'] ?? '';

        // Validate refresh token
        chdir('../legacy');
        $tokenBean = $this->tokenService->getTokenByRefreshToken($refreshToken);
        chdir('../mcp');
        if (!$tokenBean) {
            return $this->createError(400, 'invalid_grant', 'Invalid refresh token');
        }

        // Check if refresh token belongs to this client
        if ($tokenBean->client !== $clientId) {
            return $this->createError(400, 'invalid_grant', 'Refresh token not issued to this client');
        }

        // Check if refresh token is expired
        if (
            $tokenBean->refresh_token_expires &&
            strtotime($tokenBean->refresh_token_expires) < time()
        ) {
            return $this->createError(400, 'invalid_grant', 'Refresh token expired');
        }

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
        $scope = $tokenBean->scopes;

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

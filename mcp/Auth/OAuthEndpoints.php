<?php

namespace MintMCP\Auth;

use Monolog\Logger as MonologLogger;
use MintMCP\Handlers\Logger;
use Throwable;

/**
 * OAuth Endpoint Handler
 * 
 * Routes HTTP requests to the appropriate OAuth handler methods
 */
class OAuthEndpoints
{
    private OAuth2Server $oauth2Server;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->oauth2Server = OAuth2Server::getInstance();
    }

    /**
     * Handle OAuth requests
     * 
     * @param string $endpoint The requested endpoint
     */
    public function handleRequest(string $endpoint): void
    {
        try {
            $this->logRequest($endpoint);

            switch ($endpoint) {
                case 'authorization':
                    $this->validateGetRequest();
                    $this->handleAuthorization();
                    break;

                case 'authorize':
                    $this->validateGetRequest();
                    $this->handleAuthorize();
                    break;

                case 'login':
                    $this->handleLogin();
                    break;

                case 'token':
                    $this->validatePostRequest();
                    $this->handleToken();
                    break;

                case 'userinfo':
                    $this->validateGetRequest();
                    $this->handleUserinfo();
                    break;

                case 'jwks':
                    $this->validateGetRequest();
                    $this->handleJWKS();
                    break;

                case 'discovery':
                    $this->validateGetRequest();
                    $this->handleDiscovery();
                    break;

                case 'introspect':
                    $this->validatePostRequest();
                    $this->handleIntrospect();
                    break;

                case 'register':
                    $this->validatePostRequest();
                    $this->handleRegister();
                    break;

                default:
                    $this->sendResponse(404, ['error' => 'endpoint_not_found']);
                    break;
            }
        } catch (Throwable $e) {
            $this->handleError($e);
        }
    }

    /**
     * Log incoming request
     */
    private function logRequest(string $endpoint): void
    {
        Logger::getLogger()->info('OAuth Request', [
            'endpoint' => $endpoint,
            'method' => $_SERVER['REQUEST_METHOD'],
            'query' => $_GET,
            'post' => $_SERVER['REQUEST_METHOD'] === 'POST' ? $this->sanitizePostData($_POST) : [],
        ]);
    }

    /**
     * Sanitize POST data for logging (remove sensitive info)
     */
    private function sanitizePostData(array $postData): array
    {
        $sanitized = $postData;

        // Mask sensitive fields
        foreach (['code', 'refresh_token', 'access_token', 'token', 'password', 'client_secret'] as $field) {
            if (isset($sanitized[$field])) {
                $sanitized[$field] = substr($sanitized[$field], 0, 6) . '...';
            }
        }

        return $sanitized;
    }

    /**
     * Handle errors
     */
    private function handleError(Throwable $e): void
    {
        Logger::getLogger()->error('OAuth Error', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        $this->sendResponse(500, [
            'error' => 'server_error',
            'error_description' => 'An internal server error occurred'
        ]);
    }

    /**
     * Handle login endpoint
     */
    private function handleLogin(): void
    {
        require_once __DIR__ . '/login.php';
        exit;
    }

    /**
     * Handle authorization endpoint
     */
    private function handleAuthorize(): void
    {
        $response = $this->oauth2Server->handleAuthorizeRequest();

        if ($response['status'] === 302 && !empty($response['headers']['Location'])) {
            header('Location: ' . $response['headers']['Location']);
            exit;
        }

        $redirectUri = $_GET['redirect_uri'] ?? '';
        if (!empty($redirectUri) && !empty($response['body'])) {
            header('Location: ' . $redirectUri . '?' . http_build_query($response['body']));
            exit;
        }

        $this->sendResponse($response['status'], $response['body'] ?? []);
    }

    /**
     * Handle token endpoint
     */
    private function handleToken(): void
    {
        $response = $this->oauth2Server->handleTokenRequest();
        $this->sendResponse($response['status'], $response['body']);
    }

    /**
     * Handle userinfo endpoint
     */
    private function handleUserinfo(): void
    {
        $response = $this->oauth2Server->handleUserinfoRequest(getallheaders());
        $this->sendResponse($response['status'], $response['body']);
    }

    /**
     * Handle introspection endpoint
     */
    private function handleIntrospect(): void
    {
        $token = $_POST['token'] ?? '';
        if (empty($token)) {
            $this->sendResponse(400, [
                'error' => 'invalid_request',
                'error_description' => 'Missing token parameter'
            ]);
            return;
        }

        $tokenData = $this->oauth2Server->introspectToken($token);

        if ($tokenData) {
            $this->sendResponse(200, $tokenData);
        } else {
            $this->sendResponse(200, ['active' => false]);
        }
    }

    /**
     * Handle Dynamic Client Registration (RFC 7591)
     */
    private function handleRegister(): void
    {
        $input = $this->parseJsonBody();
        if ($input === null) {
            $this->sendResponse(400, [
                'error' => 'invalid_request',
                'error_description' => 'Invalid JSON in request body'
            ]);
            return;
        }

        $response = $this->oauth2Server->handleClientRegistration($input);
        $this->sendResponse($response['status'], $response['body']);
    }

    /**
     * Parse JSON request body
     * 
     * @return array|null Parsed JSON or null if invalid
     */
    private function parseJsonBody(): ?array
    {
        $input = json_decode(file_get_contents('php://input'), true);
        return json_last_error() === JSON_ERROR_NONE ? $input : null;
    }

    /**
     * Handle JWKS endpoint (for token validation)
     */
    private function handleJWKS(): void
    {
        // For simplicity, just return empty key set
        // In production, implement proper JWKS
        $this->sendResponse(200, ['keys' => []]);
    }

    /**
     * Handle authorization endpoint
     */
    private function handleAuthorization(): void
    {
        $authorizationInfo = $this->oauth2Server->handleAuthorization();
        $this->sendResponse(200, $authorizationInfo);
    }

    /**
     * Handle discovery endpoint
     */
    private function handleDiscovery(): void
    {
        $discoveryInfo = $this->oauth2Server->handleDiscovery();
        $this->sendResponse(200, $discoveryInfo);
    }

    /**
     * Send HTTP response
     * 
     * @param int $status HTTP status code
     * @param array $body Response body
     */
    private function sendResponse(int $status, array $body): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($body, JSON_PRETTY_PRINT);
        exit;
    }

    /**
     * Validate POST request
     */
    private function validatePostRequest(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(405, ['error' => 'method_not_allowed']);
        }
    }

    /**
     * Validate GET request
     */
    private function validateGetRequest(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->sendResponse(405, ['error' => 'method_not_allowed']);
        }
    }
}

<?php

namespace MintMCP\Auth\Internal;

use MintMCP\Server\Logger;
use Throwable;

/**
 * Internal Endpoint Handler
 * 
 * Routes HTTP requests to the appropriate internal handler methods
 * and handles CORS for cross-origin requests
 */
class SessionEndpoints
{
    private SessionTokenAuth $sessionTokenAuth;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sessionTokenAuth = new SessionTokenAuth();
    }

    /**
     * Handle internal token request
     */
    public function handleRequest(): void
    {
        try {
            $this->handleCORS(); // Needed only in development when frontend dev server is on different origin.
            $this->logRequest();

            $this->validatePostRequest();
            $response = $this->sessionTokenAuth->handleTokenRequest();
            $this->sendResponse($response['status'], $response['body']);
        } catch (Throwable $e) {
            $this->handleError($e);
        }
    }

    /**
     * Handle CORS headers for cross-origin requests
     * 
     * Only needed in development when frontend dev server is on different origin.
     * Note: When using Access-Control-Allow-Credentials: true,
     * Access-Control-Allow-Origin cannot be '*', must be specific origin
     */
    private function handleCORS(): void
    {
        $origin = TrustedOrigins::normalizeOrigin($_SERVER['HTTP_ORIGIN'] ?? null);
        $allowedOrigins = TrustedOrigins::allAllowedOrigins();

        if (!$origin || !in_array($origin, $allowedOrigins, true)) {
            if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
                http_response_code(403);
                exit;
            }
            $this->sendResponse(403, [
                'error' => 'forbidden_origin',
                'error_description' => 'Request origin is not allowed'
            ]);
        }

        header('Vary: Origin');
        header('Access-Control-Allow-Origin: ' . $origin);
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: X-CSRF-Token, Content-Type, Accept, Authorization');
        header('Access-Control-Max-Age: 86400');
        
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
    }

    /**
     * Log incoming request
     */
    private function logRequest(): void
    {
        Logger::getLogger()->info('Internal Token Request', [
            'method' => $_SERVER['REQUEST_METHOD'],
            'request_uri' => $_SERVER['REQUEST_URI'] ?? '',
        ]);
    }

    /**
     * Handle errors
     */
    private function handleError(Throwable $e): void
    {
        Logger::getLogger()->error('Internal Endpoint Error', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        $this->sendResponse(500, [
            'error' => 'server_error',
            'error_description' => 'An internal server error occurred'
        ]);
    }

    /**
     * Validate POST request
     */
    private function validatePostRequest(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(405, [
                'error' => 'method_not_allowed',
                'error_description' => 'POST method required'
            ]);
        }
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
}

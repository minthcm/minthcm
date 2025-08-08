<?php
/**
 * MintHCM MCP OAuth 2.1 Server Entry Point
 */

if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}

// Initialize Sugar environment
chdir('../legacy/');
require_once 'include/entryPoint.php';
require_once 'include/MVC/SugarApplication.php';

// Start user session
$app = new \SugarApplication();
$app->startSession();

// Switch back to MCP directory
chdir('../mcp/');
require_once __DIR__ . '/vendor/autoload.php';

// Determine requested endpoint from server variables
$endpoint = $_SERVER['REDIRECT_OAUTH_ENDPOINT'] ?? $_SERVER['OAUTH_ENDPOINT'] ?? null;

if (!$endpoint) {
    http_response_code(400);
    echo json_encode(['error' => 'invalid_request', 'error_description' => 'No OAuth endpoint specified']);
    exit;
}

// Process the OAuth request
$handler = new \MintMCP\Auth\OAuthEndpoints();
$handler->handleRequest($endpoint);

<?php
/**
 * MintHCM MCP Internal Endpoint Entry Point (Needed for MintAiChat component).
 *
 * Handles internal token generation for authenticated MintHCM users.
 */

if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}

require_once __DIR__ . '/vendor/autoload.php';

use MintMCP\Auth\Internal\TrustedOrigins;

$origin = TrustedOrigins::normalizeOrigin($_SERVER['HTTP_ORIGIN'] ?? null);

@include __DIR__ . '/../legacy/config.php';
$allowedOrigins = TrustedOrigins::allAllowedOrigins($sugar_config['site_url'] ?? null);
$isAllowedOrigin = TrustedOrigins::isAllowed($_SERVER['HTTP_ORIGIN'] ?? null, $sugar_config['site_url'] ?? null);

if ($isAllowedOrigin) {
    header('Vary: Origin');
    header('Access-Control-Allow-Origin: ' . $origin);
    header('Access-Control-Allow-Credentials: true');
}

/**
 * Handle CORS preflight before bootstrapping legacy app.
 *
 * Legacy bootstrap can trigger redirects (e.g. auth/session flow), which
 * browsers reject for preflight requests. Respond early for OPTIONS.
 */
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    if ($isAllowedOrigin) {
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: X-CSRF-Token, Content-Type, Accept, Authorization');
        header('Access-Control-Max-Age: 86400');
        http_response_code(200);
    } else {
        http_response_code(403);
    }
    exit;
}

if (!$isAllowedOrigin) {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode([
        'error' => 'forbidden_origin',
        'error_description' => 'Request origin is not allowed',
    ]);
    exit;
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

// Process the session token request
$handler = new \MintMCP\Auth\Internal\SessionEndpoints();
$handler->handleRequest();

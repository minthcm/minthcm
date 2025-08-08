<?php

/**
 * JWT Test Token Generator for MCP Server Testing
 * 
 * (c) 2025 Logiscape LLC <https://logiscape.com>
 * 
 * Developed by:
 * - Josh Abbott
 * - Claude Opus 4 (Anthropic AI model)
 * 
 * This utility generates valid JWT tokens for testing the MCP auth server.
 * Upload this to your web hosting and access it via browser to generate tokens.
 *
 * WARNING: This is for testing only! Do not use in production!
 *
 * @package    logiscape/mcp-sdk-php
 * @author     Josh Abbott <https://joshabbott.com>
 * @copyright  Logiscape LLC
 * @license    MIT License
 * @link       https://github.com/logiscape/mcp-sdk-php
 */

// Include configuration file
require_once __DIR__ . '/mcp-config.php';

// Configuration is loaded from mcp-config.php
$JWT_SECRET = MCP_JWT_SECRET;
$AUTH_ISSUER = MCP_AUTH_ISSUER;
$RESOURCE_ID = MCP_RESOURCE_ID;

// Handle form submission
$generatedToken = null;
$tokenDetails = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get form inputs
        $expiresIn = (int)($_POST['expires_in'] ?? 3600);
        $scope = $_POST['scope'] ?? 'mcp';
        $username = $_POST['username'] ?? 'test_user';
        $customClaims = $_POST['custom_claims'] ?? '';
        
        // Parse custom claims if provided
        $additionalClaims = [];
        if (!empty($customClaims)) {
            $additionalClaims = json_decode($customClaims, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid JSON in custom claims');
            }
        }
        
        // Create token header
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];
        
        // Create token payload
        $now = time();
        $payload = array_merge([
            'iss' => $AUTH_ISSUER,
            'aud' => $RESOURCE_ID,
            'iat' => $now,
            'nbf' => $now,
            'exp' => $now + $expiresIn,
            'sub' => $username,
            'scope' => $scope,
            'jti' => bin2hex(random_bytes(16))
        ], $additionalClaims);
        
        // Encode header and payload
        $encodedHeader = base64UrlEncode(json_encode($header));
        $encodedPayload = base64UrlEncode(json_encode($payload));
        
        // Create signature
        $signature = hash_hmac('sha256', $encodedHeader . '.' . $encodedPayload, $JWT_SECRET, true);
        $encodedSignature = base64UrlEncode($signature);
        
        // Combine to create JWT
        $generatedToken = $encodedHeader . '.' . $encodedPayload . '.' . $encodedSignature;
        
        // Store details for display
        $tokenDetails = [
            'header' => $header,
            'payload' => $payload,
            'expires_at' => date('Y-m-d H:i:s', $payload['exp'])
        ];
        
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

function base64UrlEncode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MCP JWT Test Token Generator</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }
        input, textarea, select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        textarea {
            min-height: 80px;
            font-family: monospace;
        }
        button {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        .token-display {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            margin-top: 20px;
        }
        .token-value {
            background: #e9ecef;
            padding: 10px;
            border-radius: 4px;
            word-break: break-all;
            font-family: monospace;
            font-size: 12px;
            margin: 10px 0;
        }
        .token-details {
            margin-top: 15px;
            font-size: 14px;
        }
        .token-details pre {
            background: #e9ecef;
            padding: 10px;
            border-radius: 4px;
            overflow-x: auto;
        }
        .error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 12px;
            border-radius: 4px;
            margin-top: 10px;
        }
        .usage-section {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid #dee2e6;
        }
        .code-block {
            background: #e9ecef;
            padding: 15px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 14px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="container">
    <h1>🔐 MCP For PHP Auth Token Generator</h1>
        
        <div class="warning">
            <strong>⚠️ Testing Only!</strong> Never use this in production. Always use proper OAuth 2.0 authorization servers for production deployments.
        </div>

        <form method="POST">
            <div class="form-group">
                <label for="username">Username (sub claim):</label>
                <input type="text" id="username" name="username" value="test_user" required>
            </div>
            
            <div class="form-group">
                <label for="scope">Scope:</label>
                <input type="text" id="scope" name="scope" value="mcp" required>
                <small style="color: #666;">The scope must include 'mcp' to access the server</small>
            </div>
            
            <div class="form-group">
                <label for="expires_in">Token Lifetime:</label>
                <select id="expires_in" name="expires_in">
                    <option value="300">5 minutes</option>
                    <option value="900">15 minutes</option>
                    <option value="1800">30 minutes</option>
                    <option value="3600">1 hour</option>
                    <option value="86400" selected>24 hours</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="custom_claims">Custom Claims (JSON, optional):</label>
                <textarea id="custom_claims" name="custom_claims" placeholder='{"role": "admin", "permissions": ["read", "write"]}'></textarea>
            </div>
            
            <button type="submit">Generate Token</button>
        </form>

        <?php if ($error): ?>
            <div class="error">
                <strong>Error:</strong> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($generatedToken): ?>
            <div class="token-display">
                <h2>Generated JWT Token</h2>
                <div class="token-value"><?= htmlspecialchars($generatedToken) ?></div>
                
                <div class="token-details">
                    <h3>Token Details</h3>
                    <pre><?= htmlspecialchars(json_encode($tokenDetails, JSON_PRETTY_PRINT)) ?></pre>
                </div>
                
                <div class="usage-section">
                    <h3>How to Use This Token</h3>
                    <p>Include this token in the Authorization header when making requests to the MCP server:</p>
                    <div class="code-block">Authorization: Bearer <?= htmlspecialchars($generatedToken) ?></div>
                </div>
            </div>
        <?php endif; ?>

        <div class="usage-section">
            <h3>Server Configuration</h3>
            <p>Your MCP auth server is configured with these settings:</p>
            <ul>
                <li><strong>Issuer:</strong> <?= htmlspecialchars($AUTH_ISSUER) ?></li>
                <li><strong>Audience:</strong> <?= htmlspecialchars($RESOURCE_ID) ?></li>
                <li><strong>Algorithm:</strong> HS256</li>
            </ul>
            
            <h3>Testing Steps</h3>
            <ol>
                <li>Generate a token using the form above</li>
                <li>Copy the generated token</li>
                <li>Use a tool like cURL, Postman, or the MCP test client to make requests</li>
                <li>Include the token in the Authorization header</li>
                <li>Verify that requests without tokens or with invalid tokens are rejected</li>
            </ol>
        </div>
    </div>
</body>
</html>
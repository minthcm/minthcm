<?php

use MintHCM\Api\Controllers\McpController;
use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;

// auth: false on all MCP routes — authorization is enforced manually in the
// controller via the consent_token + oauth_flows[flowId] session entry, because
// the consent screen runs before a normal authenticated Mint session exists.

$routes = [
    'mcp.oauthInfo' => [
        'method' => 'GET',
        'path' => '/mcp/oauth-info',
        'class' => McpController::class,
        'function' => 'oauthInfo',
        'desc' => 'Returns client_name, scope and CSRF consent token for the pending OAuth authorization flow (reads session oauth_flows[flow])',
        'options' => [
            'auth' => false,
        ],
        'pathParams' => [],
        'queryParams' => [
            'flow' => [
                'type' => StringType::class,
                'required' => true,
                'desc' => 'Flow identifier issued by OAuth2Server::redirectToLogin()',
            ],
        ],
        'bodyParams' => [],
    ],
    'mcp.authorize' => [
        'method' => 'POST',
        'path' => '/mcp/authorize',
        'class' => McpController::class,
        'function' => 'authorize',
        'desc' => 'Complete MCP OAuth2 authorization after login — validates CSRF consent token, reads the flow from session and returns the redirect URL with the auth code',
        'options' => [
            'auth' => false,
        ],
        'pathParams' => [],
        'queryParams' => [],
        'bodyParams' => [
            'flow' => [
                'type' => StringType::class,
                'required' => true,
                'desc' => 'Flow identifier issued by OAuth2Server::redirectToLogin()',
            ],
            'consent_token' => [
                'type' => StringType::class,
                'required' => true,
                'desc' => 'CSRF consent token obtained from GET /mcp/oauth-info',
            ],
        ],
    ],
    'mcp.deny' => [
        'method' => 'POST',
        'path' => '/mcp/deny',
        'class' => McpController::class,
        'function' => 'deny',
        'desc' => 'Deny the pending MCP OAuth2 authorization — returns a redirect URL to the client with error=access_denied (RFC 6749 §4.1.2.1)',
        'options' => [
            'auth' => false,
        ],
        'pathParams' => [],
        'queryParams' => [],
        'bodyParams' => [
            'flow' => [
                'type' => StringType::class,
                'required' => true,
                'desc' => 'Flow identifier issued by OAuth2Server::redirectToLogin()',
            ],
            'consent_token' => [
                'type' => StringType::class,
                'required' => true,
                'desc' => 'CSRF consent token obtained from GET /mcp/oauth-info',
            ],
        ],
    ],
];

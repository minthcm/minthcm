<?php

/**
 * Example MCP HTTP Server That Requires Authentication
 * 
 * This server provides the same functionality as the HTTP test server but requires OAuth 2.1 for authentication.
 * It's designed to work in standard PHP hosting environments.
 *
 * (c) 2025 Logiscape LLC <https://logiscape.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    logiscape/mcp-sdk-php
 * @author     Josh Abbott <https://joshabbott.com>
 * @copyright  Logiscape LLC
 * @license    MIT License
 * @link       https://github.com/logiscape/mcp-sdk-php
 */

// Autoload dependencies
require __DIR__ . '/vendor/autoload.php';

use Mcp\Server\Server;
use Mcp\Server\HttpServerRunner;
use Mcp\Server\Transport\Http\StandardPhpAdapter;
use Mcp\Server\Transport\Http\Environment;
use Mcp\Server\Transport\Http\FileSessionStore;
use Mcp\Server\Auth\JwtTokenValidator;
use Mcp\Types\Prompt;
use Mcp\Types\PromptArgument;
use Mcp\Types\PromptMessage;
use Mcp\Types\ListPromptsResult;
use Mcp\Types\TextContent;
use Mcp\Types\Role;
use Mcp\Types\GetPromptResult;
use Mcp\Types\GetPromptRequestParams;
use Mcp\Types\Tool;
use Mcp\Types\ToolInputSchema;
use Mcp\Types\ToolInputProperties;
use Mcp\Types\ListToolsResult;
use Mcp\Types\CallToolResult;
use Mcp\Types\Resource;
use Mcp\Types\ResourceTemplate;
use Mcp\Types\ListResourcesResult;
use Mcp\Types\ListResourceTemplatesResult;
use Mcp\Types\ReadResourceResult;
use Mcp\Types\TextResourceContents;

// Handle MCP requests to allowed endpoints
$requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$allowedPaths = [
    '/server_auth.php',
    '/.well-known/oauth-protected-resource',
    '/.well-known/oauth-protected-resource/server_auth.php'
];

if (!in_array($requestUri, $allowedPaths)) {
    http_response_code(404);
    echo json_encode(['error' => 'Not found']);
    exit;
}

ini_set('display_errors', '1');
error_reporting(E_ALL);

// Configure error handling for production
//if (getenv('MCP_DEBUG') !== 'true') {
//    error_reporting(E_ERROR | E_PARSE);
//    ini_set('display_errors', '0');
//}

// Check HTTP method - allow GET for metadata endpoint
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$isMetadataEndpoint = (stripos($requestUri, '/.well-known/oauth-protected-resource') !== false);

if ($isMetadataEndpoint && $method !== 'GET') {
    http_response_code(405);
    header('Allow: GET');
    echo json_encode(['error' => 'Method not allowed']);
    exit;
} elseif (!$isMetadataEndpoint && !in_array($method, ['GET', 'POST', 'DELETE'])) {
    http_response_code(405);
    header('Allow: GET, POST, DELETE');
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Create server instance
$server = new Server('mcp-example-auth-server');

// Register prompt handlers
$server->registerHandler('prompts/list', function($params) {
    $prompt = new Prompt(
        name: 'example-prompt',
        description: 'An example prompt template',
        arguments: [
            new PromptArgument(
                name: 'arg1',
                description: 'Example argument',
                required: true
            )
        ]
    );
    return new ListPromptsResult([$prompt]);
});

$server->registerHandler('prompts/get', function($params) {
    $name = $params->name;
    $arguments = $params->arguments;
    if ($name !== 'example-prompt') {
        throw new \InvalidArgumentException("Unknown prompt: {$name}");
    }
    
    // Get argument value safely
    $argValue = $arguments->arg1 ?? 'none';
    
    return new GetPromptResult(
        messages: [
            new PromptMessage(
                role: Role::USER,
                content: new TextContent(
                    text: "Example prompt text with argument: $argValue"
                )
            )
        ],
        description: 'Example prompt'
    );
});

// Register tool handlers
$server->registerHandler('tools/list', function($params) {
    // Create properties object
    $properties = ToolInputProperties::fromArray([
        'num1' => [
            'type' => 'number',
            'description' => 'First number'
        ],
        'num2' => [
            'type' => 'number',
            'description' => 'Second number'
        ]
    ]);

    // Create schema with properties and required fields
    $inputSchema = new ToolInputSchema(
        properties: $properties,
        required: ['num1', 'num2']
    );

    // Create calculator tool
    $calculator = new Tool(
        name: 'add-numbers',
        description: 'Adds two numbers together',
        inputSchema: $inputSchema
    );
    
    // Create a second tool for testing
    $properties2 = ToolInputProperties::fromArray([
        'text' => [
            'type' => 'string',
            'description' => 'Text to transform'
        ]
    ]);
    
    $inputSchema2 = new ToolInputSchema(
        properties: $properties2,
        required: ['text']
    );
    
    $textTool = new Tool(
        name: 'uppercase',
        description: 'Converts text to uppercase',
        inputSchema: $inputSchema2
    );

    return new ListToolsResult([$calculator, $textTool]);
});

$server->registerHandler('tools/call', function($params) {
    $name = $params->name;
    $arguments = $params->arguments ?? [];

    switch ($name) {
        case 'add-numbers':
            // Validate and convert arguments to numbers
            $num1 = filter_var($arguments['num1'] ?? null, FILTER_VALIDATE_FLOAT);
            $num2 = filter_var($arguments['num2'] ?? null, FILTER_VALIDATE_FLOAT);

            if ($num1 === false || $num2 === false) {
                return new CallToolResult(
                    content: [new TextContent(
                        text: "Error: Both arguments must be valid numbers"
                    )],
                    isError: true
                );
            }

            $sum = $num1 + $num2;
            return new CallToolResult(
                content: [new TextContent(
                    text: "The sum of {$num1} and {$num2} is {$sum}"
                )]
            );
            
        case 'uppercase':
            $text = $arguments['text'] ?? '';
            if (empty($text)) {
                return new CallToolResult(
                    content: [new TextContent(
                        text: "Error: Text cannot be empty"
                    )],
                    isError: true
                );
            }
            
            return new CallToolResult(
                content: [new TextContent(
                    text: "Uppercase version: " . strtoupper($text)
                )]
            );
            
        default:
            throw new \InvalidArgumentException("Unknown tool: {$name}");
    }
});

// Register resource handlers
$server->registerHandler('resources/list', function($params) {
    $resources = [
        new Resource(
            uri: 'example://greeting',
            name: 'Greeting Text',
            description: 'A simple greeting message',
            mimeType: 'text/plain'
        ),
        new Resource(
            uri: 'example://server-info',
            name: 'Server Information',
            description: 'Information about the server environment',
            mimeType: 'text/plain'
        )
    ];
    
    return new ListResourcesResult($resources);
});

$server->registerHandler('resources/read', function($params) {
    $uri = $params->uri;
    
    switch ($uri) {
        case 'example://greeting':
            return new ReadResourceResult(
                contents: [new TextResourceContents(
                    uri: $uri,
                    text: "Hello from the example MCP HTTP server!",
                    mimeType: 'text/plain'
                )]
            );
            
        case 'example://server-info':
            $info = [
                "Server Time: " . date('Y-m-d H:i:s'),
                "PHP Version: " . PHP_VERSION,
                "Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'),
                "HTTP Transport: MCP HTTP Transport",
                "Environment: " . (Environment::isSharedHosting() ? 'Shared Hosting' : 'Standard Server'),
                "Session Timeout: " . (Environment::detectMaxExecutionTime() ?: 'No limit') . " seconds"
            ];
            
            return new ReadResourceResult(
                contents: [new TextResourceContents(
                    uri: $uri,
                    text: implode("\n", $info),
                    mimeType: 'text/plain'
                )]
            );
            
        default:
            throw new \InvalidArgumentException("Unknown resource: {$uri}");
    }
});

// Include auth configuration file
require_once __DIR__ . '/mcp-config.php';

// === AUTHORIZATION CONFIGURATION ===
// Configuration is loaded from mcp-config.php
$JWT_SECRET = MCP_JWT_SECRET;
$AUTH_ISSUER = MCP_AUTH_ISSUER;
$RESOURCE_ID = MCP_RESOURCE_ID;

// Create JWT validator
$tokenValidator = new JwtTokenValidator(
    key: $JWT_SECRET,
    algorithm: 'HS256',
    issuer: $AUTH_ISSUER,
    audience: $RESOURCE_ID
);

// Configure HTTP options
$httpOptions = [
    'session_timeout' => 1800, // 30 minutes
    'max_queue_size' => 500,   // Smaller queue for shared hosting
    'enable_sse' => false,     // No SSE for compatibility
    'shared_hosting' => true,  // Assume shared hosting for max compatibility
    'server_header' => 'MCP-PHP-Server/1.0',
    'auth_enabled' => true, // Enable authentication
    'authorization_servers' => [$AUTH_ISSUER],
    'resource' => $RESOURCE_ID,
    'token_validator' => $tokenValidator,
];

try {
    // Create the adapter and handle the request
    // 1) Create a file-based store, pointing to an absolute or relative path
    $fileStore = new FileSessionStore(__DIR__ . '/mcp_sessions'); 
    
    // 2) Create a runner that uses that store
    $runner = new HttpServerRunner($server, $server->createInitializationOptions(), $httpOptions, null, $fileStore);
    
    // 3) Create a StandardPhpAdapter and pass your runner in directly
    $adapter = new StandardPhpAdapter($runner);
    
    // 4) Handle the request
    $adapter->handle();
} catch (\Exception $e) {
    // Log error to error_log
    error_log('MCP Server error: ' . $e->getMessage());
    
    // Return error response
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'error' => 'Internal Server Error',
        'message' => getenv('MCP_DEBUG') === 'true' ? $e->getMessage() : 'An error occurred'
    ]);
}
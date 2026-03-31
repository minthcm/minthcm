# Middlewares

Middlewares are layers that process HTTP requests before they reach controllers and responses before they're sent to clients.

## What Are Middlewares?

Middlewares sit between the client and your controllers, forming a pipeline:

```
Client Request
      ↓
┌─────────────────┐
│  Middleware 1   │ ← Authentication
└────────┬────────┘
         ↓
┌─────────────────┐
│  Middleware 2   │ ← Authorization
└────────┬────────┘
         ↓
┌─────────────────┐
│  Middleware 3   │ ← Validation
└────────┬────────┘
         ↓
┌─────────────────┐
│   Controller    │ ← Your business logic
└────────┬────────┘
         ↓
    Response
```

Middlewares can:
- Authenticate requests
- Validate data
- Log requests
- Modify requests/responses
- Handle CORS
- Check permissions

## Middleware Execution Order

Middlewares execute in **LIFO (Last In, First Out)** order:

```php
$app->add(Middleware1::class);  // Executes 3rd
$app->add(Middleware2::class);  // Executes 2nd
$app->add(Middleware3::class);  // Executes 1st
```

**Request flow:** Middleware3 → Middleware2 → Middleware1 → Controller

**Response flow:** Controller → Middleware1 → Middleware2 → Middleware3

## Built-In Middlewares

The API includes several built-in middlewares:

### Authentication Middleware

**Location:** `app/Middlewares/Auth/AuthMiddleware.php`

Validates JWT tokens and sets current user.

```php
public function __invoke(Request $request, RequestHandler $handler): Response
{
    // Extract and validate JWT token
    $token = $this->extractToken($request);
    
    if (!$token) {
        // Allow public routes
        return $handler->handle($request);
    }
    
    $user = $this->validateToken($token);
    
    // Add user to request attributes
    $request = $request->withAttribute('current_user', $user);
    
    return $handler->handle($request);
}
```

### Route Access Middleware

**Location:** `app/Middlewares/Routes/RouteAccessMiddleware.php`

Checks if user has permission to access the route.

```php
public function __invoke(Request $request, RequestHandler $handler): Response
{
    $user = $request->getAttribute('current_user');
    $route = $request->getAttribute('route');
    
    if (!$this->hasAccess($user, $route)) {
        $response = new Response();
        return $response->withStatus(403)->withJson(['error' => 'Forbidden']);
    }
    
    return $handler->handle($request);
}
```

### Params Middleware

**Location:** `app/Middlewares/Params/ParamsMiddleware.php`

Validates and processes request parameters.

```php
public function __invoke(Request $request, RequestHandler $handler): Response
{
    $params = $request->getQueryParams();
    
    // Validate and sanitize parameters
    $validatedParams = $this->validate($params);
    
    $request = $request->withAttribute('validated_params', $validatedParams);
    
    return $handler->handle($request);
}
```

### JSON Body Parser Middleware

**Location:** `app/Middlewares/Parsers/JsonBodyParserMiddleware.php`

Parses JSON request bodies.

```php
public function __invoke(Request $request, RequestHandler $handler): Response
{
    $contentType = $request->getHeaderLine('Content-Type');
    
    if (strpos($contentType, 'application/json') !== false) {
        $body = (string) $request->getBody();
        $data = json_decode($body, true);
        
        $request = $request->withParsedBody($data);
    }
    
    return $handler->handle($request);
}
```

## Creating Custom Middlewares

### Basic Middleware Structure

```php
<?php

namespace MintHCM\Custom\Api\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class MyCustomMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        // Pre-processing (before controller)
        // ...
        
        // Call next middleware/controller
        $response = $handler->handle($request);
        
        // Post-processing (after controller)
        // ...
        
        return $response;
    }
}
```

### Example: Logging Middleware

```php
<?php

namespace MintHCM\Custom\Api\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class LoggingMiddleware
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $method = $request->getMethod();
        $uri = (string) $request->getUri();
        $startTime = microtime(true);
        
        // Log request
        $this->logger->info("Request: {$method} {$uri}");
        
        // Process request
        $response = $handler->handle($request);
        
        // Log response
        $duration = microtime(true) - $startTime;
        $status = $response->getStatusCode();
        
        $this->logger->info(
            "Response: {$status} ({$duration}s)"
        );
        
        return $response;
    }
}
```

### Example: API Key Middleware

```php
<?php

namespace MintHCM\Custom\Api\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class ApiKeyMiddleware
{
    private $validApiKeys = [
        'key1',
        'key2',
        'key3',
    ];

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $apiKey = $request->getHeaderLine('X-API-Key');
        
        if (empty($apiKey)) {
            $response = new Response();
            return $response->withStatus(401)->withJson([
                'error' => 'API key required'
            ]);
        }
        
        if (!in_array($apiKey, $this->validApiKeys)) {
            $response = new Response();
            return $response->withStatus(403)->withJson([
                'error' => 'Invalid API key'
            ]);
        }
        
        // Valid API key - continue
        return $handler->handle($request);
    }
}
```

### Example: Rate Limiting Middleware

```php
<?php

namespace MintHCM\Custom\Api\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class RateLimitMiddleware
{
    private $limits = [];
    private $maxRequests = 100;
    private $timeWindow = 3600; // 1 hour

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $clientIp = $request->getServerParams()['REMOTE_ADDR'] ?? 'unknown';
        $currentTime = time();
        
        // Initialize or clean old entries
        if (!isset($this->limits[$clientIp])) {
            $this->limits[$clientIp] = [];
        }
        
        $this->limits[$clientIp] = array_filter(
            $this->limits[$clientIp],
            function ($timestamp) use ($currentTime) {
                return ($currentTime - $timestamp) < $this->timeWindow;
            }
        );
        
        // Check limit
        if (count($this->limits[$clientIp]) >= $this->maxRequests) {
            $response = new Response();
            return $response->withStatus(429)->withJson([
                'error' => 'Rate limit exceeded'
            ]);
        }
        
        // Record request
        $this->limits[$clientIp][] = $currentTime;
        
        // Continue
        $response = $handler->handle($request);
        
        // Add rate limit headers
        $remaining = $this->maxRequests - count($this->limits[$clientIp]);
        $response = $response
            ->withHeader('X-RateLimit-Limit', (string) $this->maxRequests)
            ->withHeader('X-RateLimit-Remaining', (string) $remaining);
        
        return $response;
    }
}
```

### Example: CORS Middleware

```php
<?php

namespace MintHCM\Custom\Api\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class CorsMiddleware
{
    private $allowedOrigins = [
        'https://example.com',
        'https://app.example.com',
    ];

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        // Handle preflight requests
        if ($request->getMethod() === 'OPTIONS') {
            $response = new Response();
            return $this->addCorsHeaders($response, $request);
        }
        
        // Process request
        $response = $handler->handle($request);
        
        // Add CORS headers to response
        return $this->addCorsHeaders($response, $request);
    }

    private function addCorsHeaders(Response $response, Request $request): Response
    {
        $origin = $request->getHeaderLine('Origin');
        
        if (in_array($origin, $this->allowedOrigins)) {
            $response = $response
                ->withHeader('Access-Control-Allow-Origin', $origin)
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
                ->withHeader('Access-Control-Max-Age', '3600');
        }
        
        return $response;
    }
}
```

## Registering Middlewares

### Global Middlewares

Register in `ApiManager`:

**File:** `custom/app/ApiManager.php`

```php
<?php

namespace MintHCM\Custom\Api;

use MintHCM\Api\ApiManager as BaseApiManager;
use MintHCM\Custom\Api\Middlewares\LoggingMiddleware;
use MintHCM\Custom\Api\Middlewares\RateLimitMiddleware;

class ApiManager extends BaseApiManager
{
    protected function addBeforeRouteMiddlewares()
    {
        // Call parent to register core middlewares
        parent::addBeforeRouteMiddlewares();
        
        // Add custom middlewares
        $this->app->add(new RateLimitMiddleware());
        $this->app->add(new LoggingMiddleware());
    }
}
```

### Route-Specific Middlewares

Apply middleware to specific routes:

```php
$route = $app->get('/protected', ProtectedController::class);
$route->add(new AuthMiddleware());
```

## Middleware Patterns

### Pattern: Request Modification

```php
public function __invoke(Request $request, RequestHandler $handler): Response
{
    // Add custom attribute
    $request = $request->withAttribute('processed_at', time());
    
    // Add custom header
    $request = $request->withHeader('X-Custom', 'value');
    
    return $handler->handle($request);
}
```

### Pattern: Response Modification

```php
public function __invoke(Request $request, RequestHandler $handler): Response
{
    $response = $handler->handle($request);
    
    // Add custom header to response
    $response = $response->withHeader('X-Powered-By', 'MintHCM API');
    
    // Modify response body
    $body = $response->getBody();
    $data = json_decode((string) $body, true);
    $data['timestamp'] = time();
    
    $newBody = json_encode($data);
    $response->getBody()->rewind();
    $response->getBody()->write($newBody);
    
    return $response;
}
```

### Pattern: Conditional Execution

```php
public function __invoke(Request $request, RequestHandler $handler): Response
{
    $path = $request->getUri()->getPath();
    
    // Skip middleware for certain paths
    if (strpos($path, '/public') === 0) {
        return $handler->handle($request);
    }
    
    // Execute middleware logic
    // ...
    
    return $handler->handle($request);
}
```

### Pattern: Early Return

```php
public function __invoke(Request $request, RequestHandler $handler): Response
{
    // Check condition
    if (!$this->isValid($request)) {
        // Return response without calling next middleware
        $response = new Response();
        return $response->withStatus(400)->withJson([
            'error' => 'Invalid request'
        ]);
    }
    
    // Continue processing
    return $handler->handle($request);
}
```

## Accessing Request Data in Middlewares

### Query Parameters

```php
$queryParams = $request->getQueryParams();
$page = $queryParams['page'] ?? 1;
```

### Body Data

```php
$body = $request->getParsedBody();
$name = $body['name'] ?? null;
```

### Headers

```php
$token = $request->getHeaderLine('Authorization');
$contentType = $request->getHeaderLine('Content-Type');
```

### Attributes

```php
// Set attribute
$request = $request->withAttribute('user_id', '123');

// Get attribute
$userId = $request->getAttribute('user_id');
```

### Server Variables

```php
$serverParams = $request->getServerParams();
$clientIp = $serverParams['REMOTE_ADDR'] ?? 'unknown';
$userAgent = $serverParams['HTTP_USER_AGENT'] ?? '';
```

## Best Practices

### 1. Single Responsibility

```php
// ✅ Good - one purpose
class AuthMiddleware
{
    // Only handles authentication
}

// ❌ Bad - multiple purposes
class MegaMiddleware
{
    // Handles auth, validation, logging, caching...
}
```

### 2. Don't Modify Global State

```php
// ✅ Good - use request attributes
$request = $request->withAttribute('user', $user);

// ❌ Bad - modify globals
$GLOBALS['current_user'] = $user;
```

### 3. Always Call Next Handler (Unless Terminating)

```php
// ✅ Good
public function __invoke(Request $request, RequestHandler $handler): Response
{
    // Process...
    return $handler->handle($request);
}

// ❌ Bad - never continues
public function __invoke(Request $request, RequestHandler $handler): Response
{
    // Process...
    return new Response();  // Never calls $handler->handle()!
}
```

### 4. Use Dependency Injection

```php
// ✅ Good
class LoggingMiddleware
{
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}

// ❌ Bad - hardcoded dependency
class LoggingMiddleware
{
    public function __construct()
    {
        $this->logger = new FileLogger();
    }
}
```

### 5. Handle Errors Gracefully

```php
public function __invoke(Request $request, RequestHandler $handler): Response
{
    try {
        $this->validate($request);
        return $handler->handle($request);
    } catch (\Exception $e) {
        $response = new Response();
        return $response->withStatus(500)->withJson([
            'error' => 'Internal server error'
        ]);
    }
}
```

## Debugging Middlewares

### Log Middleware Execution

```php
public function __invoke(Request $request, RequestHandler $handler): Response
{
    error_log('MyMiddleware: Before handler');
    
    $response = $handler->handle($request);
    
    error_log('MyMiddleware: After handler');
    
    return $response;
}
```

### Inspect Request/Response

```php
public function __invoke(Request $request, RequestHandler $handler): Response
{
    // Log request
    error_log('Request: ' . $request->getMethod() . ' ' . $request->getUri());
    
    $response = $handler->handle($request);
    
    // Log response
    error_log('Response: ' . $response->getStatusCode());
    
    return $response;
}
```

## Next Steps

- Learn about [Controllers & Actions](./09-controllers.md)
- Understand [Routing System](./05-routing.md)
- Explore [Extending the API](./08-extending-api.md)

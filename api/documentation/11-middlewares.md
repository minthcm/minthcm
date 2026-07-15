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

Validates JWT tokens and legacy sessions, and sets the global `$current_user`. Reads the `auth` and `optional_auth` options from the matched route to decide its behavior (see [Routing — Optional Authentication Flag](./05-routing.md#optional-authentication-flag)).

```php
public function __invoke(Request $request, RequestHandler $handler): Response
{
    [$runLogic, $optionalAuth] = $this->getAuthOptions($request);

    if ($runLogic || $optionalAuth) {
        // 1. No session cookie → validate OAuth2 Bearer token
        // 2. Session cookie exists → validate legacy session + refresh token if needed
        // If validation fails and $optionalAuth is false → throw HttpUnauthorizedException
        // If validation fails and $optionalAuth is true  → proceed without user context
    }

    return $handler->handle($request);
}
```

#### How authentication options are resolved

```php
protected function getAuthOptions(Request $request): array
{
    $route_data = $this->getRouteData($request);
    return [
        $route_data['options']['auth'] ?? true,            // default: authentication required
        $route_data['options']['optional_auth'] ?? false,   // default: failure is not allowed
    ];
}
```

## Authentication Methods

MintHCM supports four authentication methods. The active method is determined by the legacy configuration and is transparent to the API layer — the `AuthMiddleware` delegates credential validation to the legacy `AuthenticationController`, which picks the correct backend.

### Core (SugarAuthenticate) — default

Standard username + password authentication against the `users` table. This is the default when no external provider is configured.

- **Config:** `$sugar_config['authenticationClass']` is unset or `'SugarAuthenticate'`
- **Login flow:** `POST /api/login` with `username`, `password`, and `client_secret` → OAuth2 token pair (access + refresh)
- **Implementation:** `legacy/modules/Users/authentication/SugarAuthenticate/`

### LDAP (LDAPAuthenticate)

Authenticates users against an external LDAP / Active Directory server. User records are still stored in MintHCM's `users` table — LDAP is used only for credential verification (and optional attribute sync on login).

- **Config toggle:** Admin → Password Management → Enable LDAP Authentication (`system_ldap_enabled` setting in `config` table)
- **Key config values (stored in `config` table):**

  | Setting | Description | Default |
  |---------|-------------|---------|
  | `ldap_hostname` | LDAP server URL | — |
  | `ldap_port` | Connection port | `389` |
  | `ldap_base_dn` | Base DN for user search | — |
  | `ldap_bind_attr` | Attribute used to bind (e.g. `cn`, `uid`) | — |
  | `ldap_login_attr` | Attribute matching the MintHCM username | — |
  | `ldap_group` | Enable group membership check | — |
  | `ldap_group_user_attr` | User attribute for group comparison | — |
  | `ldap_group_attr` | Group attribute to compare | — |

- **Attribute mapping** (`legacy/modules/Users/authentication/LDAPAuthenticate/LDAPConfigs/default.php`):

  ```php
  // LDAP attribute → MintHCM user field
  'givenName'              => 'first_name',
  'sn'                     => 'last_name',
  'mail'                   => 'email1',
  'telephoneNumber'        => 'phone_work',
  'facsimileTelephoneNumber' => 'phone_fax',
  'mobile'                 => 'phone_mobile',
  'street'                 => 'primary_address_street',
  'l'                      => 'primary_address_city',
  'st'                     => 'primary_address_state',
  'postalCode'             => 'primary_address_postalcode',
  'c'                      => 'primary_address_country',
  ```

- **LDAP options set by the connector:**
  - `LDAP_OPT_PROTOCOL_VERSION` = 3
  - `LDAP_OPT_REFERRALS` = 0 (required for Active Directory)
  - `LDAP_OPT_NETWORK_TIMEOUT` = 60 s
- **Login flow:** Same `POST /api/login` endpoint — the API layer calls `AuthenticationController::loginAuthenticate()` which transparently uses `LDAPAuthenticateUser` when LDAP is enabled.
- **Implementation:** `legacy/modules/Users/authentication/LDAPAuthenticate/`
- **API-side check:** `AuthController::IsLdapOn()` and `UsersRepository::isLdapOn()` both read `system_ldap_enabled` from the `config` table.

### SAML 2.0 (SAML2Authenticate)

Federated SSO via an external Identity Provider (IdP) using the SAML 2.0 protocol. Uses the **OneLogin php-saml** library.

- **Config toggle:** Set `$sugar_config['authenticationClass'] = 'SAML2Authenticate';` in `config_override.php`
- **SAML settings:** `legacy/modules/Users/authentication/SAML2Authenticate/lib/onelogin/settings.php` — standard OneLogin settings array (SP entity ID, IdP SSO/SLO URLs, certificates, NameID format, etc.)
- **SP Metadata:** `legacy/modules/Users/authentication/SAML2Authenticate/SAML2Metadata.php` — generates XML metadata for registering the Service Provider with the IdP.
- **Login flow:**
  1. The frontend detects SAML is active (via `AuthHelper::isSAML2On()`)
  2. The user is redirected to the IdP's SSO URL
  3. The IdP posts a `SAMLResponse` back to MintHCM's ACS endpoint
  4. `SAML2Authenticate::pre_login()` validates the response and stores the NameID in `$_SESSION['samlNameId']`
  5. `SAML2AuthenticateUser::loadUserOnLogin()` matches the NameID to a `users` record
  6. An OAuth2 token pair is issued as with any other login
- **Logout flow:** `POST /api/logout` calls `AuthenticationController->authController->preLogout()` and `->logout()`, which triggers SAML SLO if configured. The logout route uses `optional_auth` because at the moment of SLO callback the session may already be terminated by the IdP.
- **User matching:** SAML NameID is matched against `User::findUserPassword($name)`. Users must exist in MintHCM — SAML does not auto-provision accounts. The `external_auth_only` flag on a user record indicates the user authenticates exclusively via SAML (no local password).
- **Implementation:** `legacy/modules/Users/authentication/SAML2Authenticate/`
- **API-side check:** `AuthHelper::isSAML2On()` reads `$sugar_config['authenticationClass']`.

### OpenID Connect (OIDCAuthenticate)

Federated SSO via an external Identity Provider (IdP) using the OpenID Connect authorization code flow. Implemented without a third-party library — the class talks to the IdP's authorization and token endpoints directly.

- **Config toggle:** Set `$sugar_config['authenticationClass'] = 'OIDCAuthenticate';` in `config_override.php`
- **OIDC settings** (stored in `config` table, editable via Admin → Password Management): `OIDC_clientId`, `OIDC_clientSecret`, `OIDC_authorizationEndpoint`, `OIDC_tokenEndpoint`, `OIDC_logoutEndpoint` (optional), `OIDC_scope` (default `openid profile email`), `OIDC_usernameClaim` (default `preferred_username`)
- **Login flow:**
  1. The frontend detects OIDC is active and the user is redirected to the IdP's authorization endpoint (`OIDCAuthenticate::pre_login()` builds the URL and stores a CSRF `state` value in both the session and a short-lived `oidc_state` cookie)
  2. The IdP redirects back to MintHCM's login URL with `?code=...&state=...`
  3. `OIDCAuthenticate::pre_login()` validates `state`, exchanges the code for tokens at the token endpoint, and decodes the ID token to resolve the username from the configured claim
  4. `OIDCAuthenticateUser::loadUserOnLogin()` matches the resolved username to a `users` record
  5. An OAuth2 token pair is issued as with any other login
- **Logout flow:** `POST /api/logout` triggers RP-Initiated Logout against `OIDC_logoutEndpoint` when configured, passing `id_token_hint` and `post_logout_redirect_uri`.
- **User matching:** the resolved username is matched against `User::findUserPassword($name)`. Users must exist in MintHCM with `external_auth_only = 1` — OIDC does not auto-provision accounts.
- **Implementation:** `legacy/modules/Users/authentication/OIDCAuthenticate/`
- **API-side check:** `AuthHelper::isOIDCOn()` reads `$sugar_config['authenticationClass']`.

### Detecting active method from the API

`AuthController` exposes a private `IsLdapOn()` helper used internally during login/logout. SAML and OIDC detection live in the shared `MintHCM\Api\Utils\AuthHelper` class, used by `AuthController`, `LoginAction` and `UsersRepository` alike:

```php
// AuthController — returns true when LDAP authentication is enabled
private function IsLdapOn(): bool
{
    global $system_config;
    return !empty($system_config->settings['system_ldap_enabled'])
        && $system_config->settings['system_ldap_enabled'] == true;
}

// AuthHelper — returns true when SAML 2.0 authentication is active
public static function isSAML2On(): bool
{
    global $sugar_config;
    return !empty($sugar_config['authenticationClass'])
        && (
            $sugar_config['authenticationClass'] == 'SAML2Authenticate'
            || is_subclass_of($sugar_config['authenticationClass'], 'SAML2Authenticate')
        );
}

// AuthHelper — returns true when OpenID Connect authentication is active
public static function isOIDCOn(): bool
{
    global $sugar_config;
    return !empty($sugar_config['authenticationClass'])
        && (
            $sugar_config['authenticationClass'] === 'OIDCAuthenticate'
            || is_subclass_of($sugar_config['authenticationClass'], 'OIDCAuthenticate')
        );
}
```

The `LoginAction` also exposes `ldap_enabled` in the login response body (`$response_body['global']['ldap_enabled']`) so the frontend can adapt the login form accordingly.

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

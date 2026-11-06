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

### Core Middleware Chain

`ApiManager::execute()` (`app/ApiManager.php`) registers the middlewares in this order: `addBeforeRouteMiddlewares()` (Slim body parsing, `ParamsMiddleware`, `RouteAccessMiddleware`, `AdminMiddleware`, `AuthMiddleware`, `JsonBodyParserMiddleware`), then Slim's routing middleware, the routes, the error middleware and finally `addAfterErrorMiddlewares()` (`SecurityHeadersMiddleware`). Because of LIFO, a request passes them in this order:

```
SecurityHeadersMiddleware      (outermost - also sees error responses)
  → ErrorMiddleware            (MintExceptionHandler)
    → RoutingMiddleware        (matches the route; route data available from here on)
      → JsonBodyParserMiddleware
        → AuthMiddleware       (options.auth / optional_auth)
          → AdminMiddleware    (options.admin_only)
            → RouteAccessMiddleware (options.access)
              → ParamsMiddleware    (pathParams / queryParams / bodyParams)
                → BodyParsingMiddleware (Slim)
                  → Controller
```

All core middlewares except `JsonBodyParserMiddleware` and `SecurityHeadersMiddleware` are created with `CustomLoader::getObject()`, so each can be replaced by a subclass in `custom/app/Middlewares/...` (namespace `MintHCM\Custom\Api\Middlewares\...`).

## Built-In Middlewares

The API includes several built-in middlewares. Most of them read the matched route's definition through the abstract base class `MintHCM\Api\Middlewares\Middleware` (`app/Middlewares/Middleware.php`): `getRouteData($request)` returns the route array from the route files (including `options`), `getRoute($request)` the Slim route object.

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

#### Session lifecycle for cookie-based (legacy) authentication

The default PHP file session handler holds an exclusive lock on the session file for the entire request. For cookie-authenticated requests, `AuthMiddleware::runLegacyAuthorization()` calls `session_write_close()` (via the private `closeSessionIfOpen()` helper) as soon as legacy auth succeeds — releasing the lock instead of holding it until the response is sent. Without this, concurrent requests from the same user (e.g. the dozens a `RecordView` fires on load) serialize on the server side even though the browser sends them in parallel.

This means **`$_SESSION` is closed before the matched route's controller runs**. Any endpoint that needs to write to `$_SESSION` after this point (e.g. mass action `Export`/`Merge` in `api/data/MassActions/Actions/`, or `AuthController::logout()`) must call `session_start()` itself first to resume the same session — `session_write_close()` only releases the lock, it does not destroy session data:

```php
public function execute()
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION['uids'] = implode(',', $this->ids);
    // ...
}
```

When adding a new endpoint or mass action that writes to `$_SESSION`, check whether it runs after `AuthMiddleware` (i.e. `'auth' => true` on the route) and apply the same guard.

### Security Headers Middleware

**Location:** `app/Middlewares/Security/SecurityHeadersMiddleware.php`

The API counterpart of legacy `send_security_headers()`. Added in `ApiManager::addAfterErrorMiddlewares()`, i.e. after the error middleware, which makes it the outermost one – error responses get the headers too.

- `Cache-Control: no-store` – API responses carry personal data. An endpoint that sets its own `Cache-Control` (e.g. a file download) keeps it.
- `X-Content-Type-Options: nosniff`.
- `Strict-Transport-Security: max-age=N` – only when `$sugar_config['hsts_max_age']` is greater than 0 and the request came over HTTPS. Off by default: HSTS on an instance without a valid certificate locks the users out until `max-age` passes. Legacy pages read the same setting.

### Error responses

Error details (`file`, `line`, `stack`) are controlled by `$sugar_config['developerMode']` in `MintExceptionHandler` – see [Error Handling](19-error-handling.md). Without developer mode client errors (4xx) keep their message and server errors (5xx) get `Internal Server Error`; the exception with its trace always goes to the log. Error responses also carry the headers of the Security Headers Middleware above.

### Admin Middleware

**Location:** `app/Middlewares/Admin/AdminMiddleware.php`

Restricts routes marked with `'admin_only' => true` in their `options` to administrators (`$current_user->isAdmin()`). Other users get `403` with `{"error": "Forbidden", "message": "Admin access required"}`. It runs after `AuthMiddleware` (it is added before it, see the chain above), so `$current_user` is already set. See [Admin Views](./20-admin-views.md).

### Route Access Middleware

**Location:** `app/Middlewares/Routes/RouteAccessMiddleware.php`

Restricts a route to certain API clients via `options.access`. The client type comes from the global `$api_client` set by `AuthMiddleware` (`frontend` for the SPA session, `mobile`, anything else = `external`):

```php
public function __invoke(Request $request, RequestHandler $handler): Response
{
    if (!$this->hasAccessToRoute($request)) {
        throw new HttpNotFoundException($request);   // the route "does not exist" for this client
    }

    return $handler->handle($request);
}
```

```php
'options' => [
    'access' => 'frontend',                 // or ['frontend', 'mobile']; default 'all'
],
```

### Params Middleware

**Location:** `app/Middlewares/Params/ParamsMiddleware.php`

Validates the request against the parameter definitions of the matched route and exposes the parsed values as request attributes:

```php
public function __invoke(Request $request, RequestHandler $handler): Response
{
    $route_data = $this->getRouteData($request);
    $params = $request->getQueryParams();
    $bodyParams = $request->getParsedBody() ?? [];
    $this->checkPathParams($request, $route_data['pathParams'] ?? []);
    $this->validationParams($request, $params, $route_data['queryParams'] ?? [], 'queryParams');
    $this->validationParams($request, $bodyParams, $route_data['bodyParams'] ?? [], 'bodyParams');

    return $handler->handle($request);
}
```

- Every parameter `type` must be a ParamType class (`MintHCM\Api\Middlewares\Params\ParamTypes\StringType`, `IntType`, `BoolType`, `EmailType`, `ArrayType`, `FileType`); otherwise → `400`.
- Query/body parameters that are not declared → `400`. Globally accepted parameters are listed in `app/Constants/global_params.php` (currently only `XDEBUG_SESSION_START`).
- Missing required or invalid values → `400` (`HttpBadRequestException`).
- Each declared parameter is set with `$request->withAttribute($name, $parsedValue)`; controllers read it with `$request->getAttribute($name)`.

### JSON Body Parser Middleware

**Location:** `app/Middlewares/Parsers/JsonBodyParserMiddleware.php`

Parses JSON request bodies. It is a PSR-15 middleware (`MiddlewareInterface::process()`):

```php
public function process(Request $request, RequestHandler $handler): Response
{
    $contentType = $request->getHeaderLine('Content-Type');

    if (strstr($contentType, 'application/json')) {
        $contents = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $request = $request->withParsedBody($contents);
        }
    }
    // Falls back to the PSR-7 body stream when php://input was empty
    // ...
    return $handler->handle($request);
}
```

## Authentication Methods

MintHCM supports four authentication methods. The active method is determined by the legacy configuration. Credentials are checked at login by `AuthController` / `UsersRepository::getUserEntityByUserCredentials()`, which use the legacy `AuthenticationController` (or LDAP / SSO finalization) as needed; afterwards `AuthMiddleware` only validates the OAuth2 token or the legacy session.

### Core (SugarAuthenticate) — default

Standard username + password authentication against the `users` table. This is the default when no external provider is configured.

- **Config:** `$sugar_config['authenticationClass']` is unset or `'SugarAuthenticate'`
- **Login flow:** `POST /api/login` with `username`, `password`, and `client_secret` → OAuth2 token pair (access + refresh)
- **Implementation:** `legacy/modules/Users/authentication/SugarAuthenticate/`
- **The `frontend` client secret is public by design.** The SPA fetches it from the unauthenticated `POST /api/getInternalFrontendToken` and sends it back with the login request. A browser application cannot keep a secret, so this value is not a credential — the user's password (or a pending SSO handshake in the same session) is. Do not report or "fix" its exposure; decision: GHSA-h46x-ffpv-f4g3 / #187657, reconfirmed in #193558.
- **Client secret check:** `ClientRepository::validateClient()` compares the stored secret (plaintext or its sha256 hash; the frontend grant accepts only the stored value) with `hash_equals()`. A confidential client with an empty stored secret never authenticates.

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
- **API-side check:** `AuthController::IsLdapOn()` and `UsersRepository::IsLdapOn()` (both private) read `system_ldap_enabled` from the `config` table.

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
- **OIDC settings** (stored in `config` table, editable via Admin → Password Management): `OIDC_clientId`, `OIDC_clientSecret`, `OIDC_authorizationEndpoint`, `OIDC_tokenEndpoint`, `OIDC_issuer` (required — the IdP `issuer`; ID tokens from any other issuer are rejected), `OIDC_logoutEndpoint` (optional), `OIDC_scope` (default `openid profile email`), `OIDC_usernameClaim` (default `preferred_username`)
- **Login flow:**
  1. The frontend detects OIDC is active and the user is redirected to the IdP's authorization endpoint (`OIDCAuthenticate::pre_login()` builds the URL and stores a CSRF `state` value and an ID-token `nonce` in both the session and short-lived `oidc_state` / `oidc_nonce` cookies)
  2. The IdP redirects back to MintHCM's login URL with `?code=...&state=...`
  3. `OIDCAuthenticate::pre_login()` validates `state`, exchanges the code for tokens at the token endpoint, decodes the ID token and validates its claims with `OIDCIdTokenValidator` (`iss`, `aud`/`azp`, `exp`, `iat`, `nonce`; the signature is not checked — the token comes from the token endpoint over TLS, as OpenID Connect Core §3.1.3.7 allows), then resolves the username from the configured claim
  4. `OIDCAuthenticateUser::loadUserOnLogin()` matches the resolved username to a `users` record and checks the ID token `sub` with `OIDCSubjectBinder`
  5. An OAuth2 token pair is issued as with any other login
- **Logout flow:** `POST /api/logout` triggers RP-Initiated Logout against `OIDC_logoutEndpoint` when configured, passing `id_token_hint` and `post_logout_redirect_uri`.
- **User matching:** the resolved username is matched against `User::findUserPassword($name)`. Users must exist in MintHCM — OIDC does not auto-provision accounts.
- **Subject binding (nOAuth protection):** the username claim (`preferred_username`, `email`) is mutable at the IdP, so the first successful OIDC login stores the immutable `sub` claim in `users.oidc_subject` (trust on first use); every later login must present the same `sub`, and one `sub` cannot be bound to two accounts. `OIDCSubjectBinder` is the only writer of the field (`api-visible => false`, dropped from the legacy edit form). Reset a binding — after the account was recreated in the IdP or the IdP changed — with the **Reset SSO binding** button on the user record (full administrators) or `php MintCLI users:resetOidcBinding --user=<user_name>|--all [--force]`.
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

### Session and Login Hardening

- **Session id rotation:** `AuthController::login()` (and `finalizeSAMLAuth()` / `finalizeOIDCAuth()`) call `session_regenerate_id(true)` right after a successful authentication, before the OAuth tokens are written to the session. A session id known to an attacker before login is therefore useless afterwards. Session data is kept, only the id changes.
- **Logout revokes tokens:** `AuthController::logout()` revokes the `MintToken` row behind the session (or the `Authorization: Bearer` header) through `MintHCM\Lib\Services\Auth\SessionTokenRevoker`. The row holds both the access and the refresh token. Revoking sets `token_is_revoked` and `deleted`; the Doctrine `soft_delete` filter hides `deleted` rows, so a revoked token is also reported as "not found" (= revoked) by `isAccessTokenRevoked()` / `isRefreshTokenRevoked()`. The token from the session is trusted and revoked as is; a token from the `Bearer` header is revoked only when its `assigned_user_id` is the current user. Tokens issued to the `frontend` client carry no user (`assigned_user_id` is empty), so they are not usable as a Bearer token – the SPA uses them through the session only.
- **Session timeout:** the OAuth tokens of a browser session are refreshed silently by `AuthMiddleware`, so without a limit the session never ends while the user returns at least once per refresh token TTL. `SuiteCRM\Utility\SessionTimeout` (`legacy/lib/Utility/`, shared by legacy and the API) keeps two markers in the session – `mint_login_time` and `mint_last_activity` – set at login (`AuthController::login()`, `finalizeSAMLAuth()`, `finalizeOIDCAuth()`).
  - `$sugar_config['session_idle_timeout']` (default `PT8H`) – the session ends after this long without a user request.
  - `$sugar_config['session_absolute_timeout']` (default `P7D`) – the session ends this long after the login, whatever the activity.
  - Both are ISO 8601 durations; `''` or `'0'` disables a limit, an invalid value is logged and the default is used.
  - The API checks it in `AuthMiddleware::runLegacyAuthorization()`: an expired session gets its tokens revoked, is destroyed and the request answers 401. Legacy checks it in `SugarApplication::loadUser()` and redirects to the login page.
  - Requests the frontend sends on its own (polling, e.g. alerts every 60 s) carry `X-Mint-Background-Request: 1` (`AuthMiddleware::BACKGROUND_REQUEST_HEADER`) and do not count as activity – otherwise an open tab would keep the session alive. Add the header to any new polling call.
  - A session logged in before the timeout existed has no markers; it is started on its first request instead of being logged out.
  - Bearer clients (`mobile`, `password` grant) have no session and are not covered.
- **Password hash upgrade:** the API login (`UsersRepository`) and the legacy login (`User::findUserPassword()`) re-hash an outdated `user_hash` (plain md5 or an old bcrypt cost) after a successful login – only `user_hash` is written, `pwd_last_changed` stays. Plain md5 hashes are accepted while `$sugar_config['passwordsetting']['legacy_md5_allowed']` is not `false`; set it to `false` once the users have logged in, the accounts that still hold md5 then need a password reset. The policy lives in `MintHCM\Lib\Services\Auth\PasswordHash` (API) and `User::checkPasswordMD5()` (legacy) – change both together.
- **Login throttling:** `MintHCM\Lib\Services\Auth\LoginThrottle` is called from `UsersRepository::getUserEntityByUserCredentials()`, which every password-based grant (`frontend` → `POST /api/login`, `password`, `mobile` → `POST /api/access_token`) goes through. It limits attempts per account and per IP and answers with `429 too_many_attempts`; a locked account is rejected even when the password is correct. SSO finalize calls (SAML/OIDC, no password) are not throttled.
  - Per account: `assertAllowed()` *reserves* an attempt before the password is checked, under a MySQL named lock (`GET_LOCK`), so parallel requests cannot slip past the limit (10 parallel attempts with a limit of 5 give 5 rejections). If the lock cannot be obtained within 3 seconds (`$sugar_config['login_throttle']['lock_timeout']`) the attempt is rejected. If the database cannot run `GET_LOCK()` at all (query error or `NULL` – e.g. Percona XtraDB Cluster with `pxc_strict_mode = ENFORCING`, or a SQL proxy that blocks it) a warning is logged and the counters are updated without the lock: the limits still apply, only parallel requests may slip a few attempts past them. A successful login resets the counter. The counter lives in the user preferences (`loginfailed`, `loginfailed_last`); usernames that do not exist are tracked in the cache, so the behaviour is the same for them. A counter without `loginfailed_last` (raised by the legacy login form) is not treated as a lockout.
  - Per IP: failed attempts counted in `sugar_cache`; the counter is updated under a MySQL named lock (`mint_login_ip_<hash>`), so parallel failures are not lost. When that lock cannot be obtained within the timeout the request is answered with `429` (a database without `GET_LOCK()` support is handled as described above). Without a persistent cache backend (APCu, Redis, Memcached or the file cache) `sugar_cache` lives for a single request and this limit is inactive; the per-account limit works regardless. The address comes from `MintHCM\Lib\Services\Http\ClientIpResolver`: `REMOTE_ADDR`, or behind a reverse proxy listed in `$sugar_config['trusted_proxies']` (e.g. `['10.0.0.5']`) the right-most `X-Forwarded-For` address that is not a trusted proxy. Without this setting every client behind the proxy shares one address and 20 failures from anyone lock the login for everybody.
  - Thresholds come from `$sugar_config['passwordsetting']` (set them in `config_override.php`): `lockoutexpirationlogin` – attempts before lockout (default 5), `lockoutexpirationtime` × `lockoutexpirationtype` – lockout length, `type` being the multiplier in minutes (default 15 minutes; 60 = hours). The `lockoutexpiration` on/off flag is ignored. `$sugar_config['login_throttle']` holds the remaining settings: `max_ip_failures` – failures from one IP per lockout period (default 20), `lock_timeout` – seconds to wait for the named lock (default 3). Empty or invalid values fall back to the defaults.
  - The class is created with `CustomLoader::getObject()`, so it can be overridden in `custom/`.
- **Session cookie:** `legacy/include/entryPoint.php` (loaded by legacy and by the API before any `session_start()`) enables `session.use_strict_mode` and `session.use_only_cookies` and sets the `PHPSESSID` cookie parameters: `HttpOnly`, `Secure` when the request is HTTPS, `SameSite=Lax`. HTTPS is detected from `$_SERVER['HTTPS']`; the `X-Forwarded-Proto` / `X-Forwarded-SSL` headers are honoured only when `REMOTE_ADDR` is listed in `$sugar_config['trusted_proxies']`. Set `$sugar_config['session_cookie_secure']` to `true`/`false` to force the flag (e.g. behind a TLS-terminating proxy that does not send these headers). Override the SameSite value with `$sugar_config['session_cookie_samesite']` (`Lax`, `Strict`, `None`; `None` without HTTPS falls back to `Lax`). Use `None` only when MintHCM has to be embedded cross-site or a SAML IdP posts back to the ACS URL (cross-site POST does not carry a `Lax` cookie). A `Header edit Set-Cookie … SameSite=None` rule in the web server config (the installer writes one to `legacy/.htaccess`) appends a second `SameSite` attribute, and browsers use the last one, so remove that rule for the PHP setting to take effect.
- **`MSID` (seamless login):** `SugarApplication::startSession()` adopts the session id from `?MSID=` only for a web service session (SOAP/REST v2–v4) that has just called `seamless_login` – the call sets `$_SESSION['seamless_login_until']` (60 seconds) and the first `MSID` request consumes it. UI sessions never carry that marker, so a leaked UI session id cannot be adopted through `MSID`.
- **Forgotten password:** `POST /api/forget_password` is limited by `MintHCM\Lib\Services\Auth\ForgotPasswordThrottle` – 5 requests per username and 20 per IP in a 15 minute window (`$sugar_config['forgot_password_throttle']` = `['max_per_username' => …, 'max_per_ip' => …, 'window_minutes' => …]`), above that `429`. Every request counts and the counters live in `sugar_cache` for every username alike, so the limit does not reveal which accounts exist (needs a persistent cache backend, like the per-IP login limit). The reset link is stored and e-mailed after the response has been sent (`AuthController::runAfterResponse()`, `fastcgi_finish_request()`), so a valid login + e-mail pair is not slower to answer. Under mod_php there is no `fastcgi_finish_request()` and the work runs before the response – the timing difference stays, limited by the throttle only.
- **Uniform errors:** `UsersRepository` returns `null` for an unknown user, a wrong password and an LDAP reject alike, so the OAuth server answers with the same `invalid_grant` and neither the password nor the existence of an account appears in the response.

## Creating Custom Middlewares

Custom middlewares go to `custom/app/Middlewares/` (namespace `MintHCM\Custom\Api\Middlewares`, autoloaded). Return the PSR-7 `ResponseInterface`; create new responses with `new \Slim\Psr7\Response()`. Responses have no `withJson()` — write the JSON body yourself.

### Basic Middleware Structure

```php
<?php

namespace MintHCM\Custom\Api\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

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

The legacy logger (`$GLOBALS['log']`, writes to the MintHCM log) is available because the API bootstraps legacy `entryPoint.php`. No PSR `LoggerInterface` is registered in the DI container.

```php
<?php

namespace MintHCM\Custom\Api\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class LoggingMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $method = $request->getMethod();
        $path = $request->getUri()->getPath();
        $startTime = microtime(true);

        $response = $handler->handle($request);

        $duration = round(microtime(true) - $startTime, 3);
        $GLOBALS['log']->info("API {$method} {$path}: {$response->getStatusCode()} ({$duration}s)");

        return $response;
    }
}
```

### Example: API Key Middleware

Restricting a middleware to selected routes: extend the core base class and read a custom key from the route's `options`.

```php
<?php

namespace MintHCM\Custom\Api\Middlewares;

use MintHCM\Api\Middlewares\Middleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class ApiKeyMiddleware extends Middleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $route_data = $this->getRouteData($request);
        if (empty($route_data['options']['api_key'])) {
            return $handler->handle($request);   // route does not require an API key
        }

        global $sugar_config;
        $validKeys = $sugar_config['my_api_keys'] ?? [];   // never hard-code secrets
        $apiKey = $request->getHeaderLine('X-API-Key');

        if ($apiKey === '' || !in_array($apiKey, $validKeys, true)) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['error' => 'Invalid or missing API key']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }

        return $handler->handle($request);
    }
}
```

Route definition:

```php
'options' => [
    'auth' => false,
    'api_key' => true,   // custom option read by ApiKeyMiddleware
],
```

### Rate Limiting

A middleware keeps no state between requests (every PHP request builds a new instance), so counters must live in a shared store. See the core implementation for login attempts: `MintHCM\Lib\Services\Auth\LoginThrottle` (counters in `sugar_cache` and user preferences, MySQL named locks against races) and `ForgotPasswordThrottle`.

### Example: CORS Middleware

```php
<?php

namespace MintHCM\Custom\Api\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

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
            return $this->addCorsHeaders(new \Slim\Psr7\Response(), $request);
        }

        // Process request
        $response = $handler->handle($request);

        // Add CORS headers to response
        return $this->addCorsHeaders($response, $request);
    }

    private function addCorsHeaders(Response $response, Request $request): Response
    {
        $origin = $request->getHeaderLine('Origin');

        if (in_array($origin, $this->allowedOrigins, true)) {
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

Note: routes are matched before the custom middleware runs, so a preflight `OPTIONS` request to a route that only defines `GET`/`POST` is rejected by the routing middleware (405) unless the route also accepts `OPTIONS` (`'method' => ['GET', 'OPTIONS']`).

## Registering Middlewares

### Global Middlewares

Register in a custom `ApiManager` (created through `CustomLoader`, so the subclass is used automatically):

**File:** `custom/app/ApiManager.php`

```php
<?php

namespace MintHCM\Custom\Api;

use MintHCM\Api\ApiManager as BaseApiManager;
use MintHCM\Custom\Api\Middlewares\ApiKeyMiddleware;
use MintHCM\Custom\Api\Middlewares\LoggingMiddleware;

class ApiManager extends BaseApiManager
{
    protected function addBeforeRouteMiddlewares()
    {
        // Call parent to register core middlewares
        parent::addBeforeRouteMiddlewares();

        // Added last = executed first (after routing, before JsonBodyParser/Auth)
        $this->app->add(new ApiKeyMiddleware());
        $this->app->add(new LoggingMiddleware());
    }
}
```

Middlewares with constructor dependencies can be created with `CustomLoader::getObject(MyMiddleware::class)`, which resolves them from the DI container. To wrap error responses as well, override `addAfterErrorMiddlewares()` instead.

### Route-Specific Behaviour

Routes are defined as arrays in route files, and `RouteManager` does not attach per-route middlewares (there is no `->add()` on individual routes). Per-route behaviour is configured through the route's `options` and evaluated by global middlewares:

| Option | Middleware |
|--------|-----------|
| `auth`, `optional_auth` | `AuthMiddleware` |
| `admin_only` | `AdminMiddleware` |
| `access` | `RouteAccessMiddleware` |
| custom keys | your middleware extending `MintHCM\Api\Middlewares\Middleware` (see API Key example) |

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
    
    // Modify JSON response body - replace the stream instead of overwriting it
    // (rewind() + write() leaves trailing bytes when the new body is shorter)
    $data = json_decode((string) $response->getBody(), true);
    if (is_array($data)) {
        $data['timestamp'] = time();
        $stream = (new \Slim\Psr7\Factory\StreamFactory())->createStream(json_encode($data));
        $response = $response->withBody($stream);
    }

    return $response;
}
```

### Pattern: Conditional Execution

```php
public function __invoke(Request $request, RequestHandler $handler): Response
{
    // Prefer route options over URL matching (the path includes the base path, e.g. /api/...)
    $route_data = $this->getRouteData($request);   // class extends MintHCM\Api\Middlewares\Middleware
    if (($route_data['options']['auth'] ?? true) === false) {
        return $handler->handle($request);   // skip for public routes
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
        $response = new \Slim\Psr7\Response();
        $response->getBody()->write(json_encode(['error' => 'Invalid request']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    
    // Continue processing
    return $handler->handle($request);
}
```

## Accessing Request Data in Middlewares

### Query Parameters

```php
$queryParams = $request->getQueryParams();
$page = $queryParams['page'] ?? 1;   // raw value; validated attributes exist only after ParamsMiddleware
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

### 2. Keep Global State to the Core

`AuthMiddleware` deliberately sets the legacy globals `$current_user` and `$api_client`, because legacy code (ACL, beans, MintLogic) depends on them. Custom middlewares should not change these globals; pass additional data down with request attributes:

```php
// ✅ Good - use request attributes for your own data
$request = $request->withAttribute('my_tenant', $tenant);

// ❌ Bad - overriding the user chosen by AuthMiddleware
$GLOBALS['current_user'] = $otherUser;
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
    return new \Slim\Psr7\Response();  // Never calls $handler->handle()!
}
```

### 4. Use Dependency Injection

Constructor dependencies are resolved from the DI container when the middleware is created with `CustomLoader::getObject()` (as the core does). Only `EntityManagerInterface` and autowirable concrete classes are available.

```php
// ✅ Good - dependency from the container
class AuditMiddleware
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }
}
$this->app->add(CustomLoader::getObject(AuditMiddleware::class));

// ❌ Bad - building the dependency inside the middleware
class AuditMiddleware
{
    public function __construct()
    {
        $this->entityManager = /* own EntityManager setup */;
    }
}
```

### 5. Handle Errors Gracefully

Throw Slim HTTP exceptions; the error middleware (`MintExceptionHandler`) turns them into JSON error responses, logs them and hides details outside developer mode (see [Error Handling](./19-error-handling.md)). Do not wrap `$handler->handle()` in a catch-all — that would hide controller errors from the central handler.

```php
public function __invoke(Request $request, RequestHandler $handler): Response
{
    if (!$this->isValid($request)) {
        throw new \Slim\Exception\HttpBadRequestException($request, 'Invalid request');
    }

    return $handler->handle($request);
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

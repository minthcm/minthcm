# MCP Login Flow

The MintHCM API provides a CSRF-protected OAuth2 consent flow that replaces the legacy PHP login form (`mcp/Auth/login.php`) with a Vue SPA consent page at `/#/mcp/login`.

## Overview

When an MCP client initiates an OAuth2 authorization request, the MCP server stores the flow parameters in the session and redirects the browser to the Vue SPA. The SPA fetches flow details via the API, optionally prompts the user to log in, and then presents an explicit consent screen. The user must click **Authorize** or **Cancel** — authorization is never automatic, even for an already-authenticated session.

## Session State

The MCP server (`mcp/Auth/OAuth2Server.php`) stores each pending authorization as an entry in `$_SESSION['oauth_flows']`:

```php
$_SESSION['oauth_flows'][$flowId] = [
    'client_id'             => string,
    'client_name'           => string,
    'redirect_uri'          => string,
    'scope'                 => string,   // e.g. "mcp:read mcp:write"
    'state'                 => string,
    'code_challenge'        => string,   // PKCE
    'code_challenge_method' => 'S256',
    'consent_token'         => string,   // 64 hex chars, anti-CSRF
    'expires_at'            => int,      // time() + 900 (15 minutes)
];
```

Each authorization gets its own random `$flowId` (`bin2hex(random_bytes(16))`), so parallel browser tabs do not overwrite each other's state. Expired flows are garbage-collected on the next call to `OAuth2Server::redirectToLogin()`.

## API Endpoints

All three endpoints have `auth: false` — they enforce authorization manually via `consent_token` and session state, because the consent screen runs before a normal authenticated Mint session exists.

### `GET /mcp/oauth-info`

Returns the pending flow details needed by the Vue SPA to render the consent page.

**Query params:** `flow` (string, required) — the `flow_id` from the redirect URL.

**Response `200`:**
```json
{
  "client_name": "My MCP Client",
  "scope": "mcp:read mcp:write",
  "consent_token": "<64-hex-chars>",
  "authenticated_user": "John Doe"
}
```
`authenticated_user` is the display name of the currently authenticated session user, or an empty string if the user is not logged in.

**Response headers:** `Cache-Control: no-store`, `Pragma: no-cache` (per RFC 6749 §5.1 — the response contains a CSRF secret).

**Response `400`:** flow not found or expired.

### `POST /mcp/authorize`

Completes the authorization after the user clicks **Authorize**.

**Body (JSON):**
```json
{ "flow": "<flow_id>", "consent_token": "<token>" }
```

**Response `200`:**
```json
{ "redirect_url": "https://client.example.com/callback?code=...&state=..." }
```

**Error responses:**
- `400` — flow not found or expired
- `401` — user not authenticated
- `403` — invalid or missing `consent_token`
- `500` — internal error from `OAuth2Server::completeAuthorization()`

### `POST /mcp/deny`

Cancels the authorization and returns a RFC 6749 `access_denied` redirect URL. The `redirect_uri` is re-validated against the registered client before being returned, preventing open-redirect attacks via a tampered session.

**Body (JSON):**
```json
{ "flow": "<flow_id>", "consent_token": "<token>" }
```

**Response `200`:**
```json
{ "redirect_url": "https://client.example.com/callback?error=access_denied&state=..." }
```

**Error responses:**
- `400` — flow not found/expired, or invalid `redirect_uri`
- `403` — invalid or missing `consent_token`

## Sequence Diagram

```
MCP Client           Browser           mcp/OAuth2Server      Vue SPA          api/McpController
    |                   |                      |                  |                    |
    |-- GET /oauth/authorize?... ------------->|                  |                    |
    |                   |         store oauth_flows[flowId]       |                    |
    |                   |<-- 302 /#/mcp/login?flow=<id> ---------|                    |
    |                   |-- load SPA --------->|                  |                    |
    |                   |                      |-- GET /mcp/oauth-info?flow=<id> ----->|
    |                   |                      |<-- { client_name, scope,              |
    |                   |                      |     consent_token, authenticated_user }|
    |                   |                      |                  |                    |
    |   [if not logged in: POST /login → session established]     |                    |
    |                   |                      |                  |                    |
    |   [user clicks Authorize]                |                  |                    |
    |                   |                      |-- POST /mcp/authorize { flow, token } >|
    |                   |                      |<-- { redirect_url: ...?code=... } ----|
    |                   |-- window.location = redirect_url        |                    |
    |<-- redirect with code -------------------|                  |                    |
```

## Security Notes

1. **CSRF — `consent_token`**: 32 random bytes (`bin2hex(random_bytes(32))`), stored in the session entry and returned to the SPA via `oauth-info`. Required in the body of `authorize` and `deny`. Compared with `hash_equals()` (timing-safe).
2. **TTL**: Each flow expires after 15 minutes (`FLOW_TTL_SECONDS = 900`). `McpController::getFlow()` rejects expired flows with HTTP 400.
3. **Parallel flows**: `flow_id` is the key in `$_SESSION['oauth_flows']`, so two browser tabs do not collide.
4. **No auto-authorization**: `OAuth2Server::handleAuthorizeRequest()` always redirects to the consent page, even for an authenticated session.
5. **Deny is not an open redirect**: `McpController::deny()` re-validates `redirect_uri` against the registered client before returning the URL.

## Controller: `McpController`

**File:** `api/app/Controllers/McpController.php`

The controller has no constructor dependencies — it uses a private `initLegacySession()` helper to start the Sugar PHP session when needed:

```php
private function initLegacySession(): void
{
    chdir('../legacy/');
    require_once 'include/MVC/SugarApplication.php';
    (new \SugarApplication())->startSession();
    chdir('../api/');
}
```

This pattern (manual `chdir` + `SugarApplication::startSession()`) is used instead of `LegacyConnector` because the controller needs direct session access (`$_SESSION`) rather than proxied method calls. See [Legacy Integration](./07-legacy-integration.md) for the standard `LegacyConnector` approach.

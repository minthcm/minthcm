# MCP Server Authentication Guide for PHP

This guide explains how to set up a Model Context Protocol (MCP) server with OAuth 2.1 authentication in a standard web hosting environment, such as cPanel.

## Overview

The MCP SDK for PHP supports OAuth 2.1 authentication, allowing you to protect your MCP server endpoints and control access to resources. This example demonstrates how to configure and deploy an authenticated MCP server in a standard web hosting environment.

## Installation Steps

### 1. Install the MCP SDK

First, install the MCP SDK using Composer:

```bash
composer require logiscape/mcp-sdk-php
```

This will create a `vendor` directory containing all necessary dependencies.

### 2. Upload Files

Upload the following files to your web document root (typically `public_html`):

```
/public_html/
├── vendor/                 # Upload entire directory from Composer
├── generate-token.php      # Test token generator (for testing only)
├── mcp-config.php         # Configuration file
├── server_auth.php        # The actual MCP server
└── .htaccess             # Apache configuration (create or modify)
```

### 3. Configure Your Server

Edit `mcp-config.php` with your specific values:

```php
// Set configuration constants
define('MCP_JWT_SECRET', 'test-secret-key-change-in-production');
define('MCP_AUTH_ISSUER', 'https://example.com/auth_server');
define('MCP_RESOURCE_ID', 'https://yoursite.com/server_auth.php');
```

**Important Configuration Notes:**

- **`MCP_JWT_SECRET`**: Replace with a strong, random secret key. Use at least 32 characters.
- **`MCP_AUTH_ISSUER`**: URL of your OAuth authorization server
- **`MCP_RESOURCE_ID`**: Full URL to your MCP server endpoint (server_auth.php)

### 4. Configure Apache (.htaccess)

Add the following rules to your `.htaccess` file in the document root:

```apache
# 1. Pass Authorization header to PHP (REQUIRED for MCP)
RewriteEngine On
RewriteCond %{HTTP:Authorization} ^(.*)
RewriteRule .* - [e=HTTP_AUTHORIZATION:%1]

# 2. Route .well-known endpoint to your MCP server
RewriteRule ^\.well-known/oauth-protected-resource(/.*)?$ /server_auth.php [L]
```

**Why This Is Necessary:**
- Many shared hosting environments strip the `Authorization` header by default
- The first rule ensures OAuth bearer tokens reach your PHP scripts
- The second rule enables OAuth discovery via the well-known endpoint

### 5. Create Session Directory

Create a directory for storing sessions and protect it:

1. Create directory: `mcp_sessions/`
2. Set permissions to 755
3. Create `mcp_sessions/.htaccess` with:
   ```apache
   Deny from all
   ```

## Testing Your Setup

### Development Testing

The included `generate-token.php` file provides a simple interface for generating test JWT tokens during development:

1. Access `https://yoursite.com/generate-token.php`
2. Generate a token with appropriate claims
3. Use the token to test your MCP server

**⚠️ Security Warning**: This token generator is for **testing only**. Never use it in production.

### Verify OAuth Compliance

Your authenticated MCP server will:

1. **Reject unauthenticated requests** with HTTP 401
2. **Return metadata** at `/.well-known/oauth-protected-resource`
3. **Accept valid tokens** in the `Authorization: Bearer <token>` header
4. **Create sessions** for authenticated clients

Test the authentication flow:

The included file test-client.html features a basic MCP server test tool. Connecting to server_auth.php without a Bearer Token should allow you to fetch the resource metadata, but an Initialize Request should fail with a 401 error. Connecting with a valid Bearer Token should allow you to initialize a session, and then fetch the list of available tools.

## Production Deployment

### 1. Use a Real Authorization Server

For production, replace the test token generator with a proper OAuth 2.1 authorization server.

### 2. Generate Secure Keys

Generate a cryptographically secure JWT secret:

```php
// Generate a secure key (run once, save the output)
echo bin2hex(random_bytes(32));
```

## Troubleshooting

### Common Issues

**Authorization header not reaching PHP:**
- Verify `.htaccess` rules are applied
- Check with your hosting provider for restrictions
- Some hosts require different RewriteRule syntax

**404 errors on well-known endpoint:**
- Ensure `.htaccess` routing is working and points to your actual MCP server file
- Verify mod_rewrite is enabled

**Token validation failures:**
- Confirm token audience matches `MCP_RESOURCE_ID`
- Check token expiration
- Verify JWT secret matches between issuer and server

### Hosting Provider Notes

This configuration works on standard cPanel setups. However, some providers may:
- Disable `.htaccess` modifications
- Use custom Apache configurations
- Require support tickets for Authorization header access

Contact your hosting provider if the standard configuration doesn't work.

## How It Works

1. **Client Request**: MCP client sends request without authentication
2. **401 Response**: Server returns 401 with `WWW-Authenticate` header
3. **Discovery**: Client fetches `/.well-known/oauth-protected-resource`
4. **Authorization**: Client obtains token from authorization server
5. **Authenticated Request**: Client includes `Authorization: Bearer <token>`
6. **Token Validation**: Server validates token and processes request
7. **Session Creation**: Server creates session for subsequent requests

## Example MCP Server

The included `server_auth.php` demonstrates:
- OAuth token validation
- Session management
- Protected MCP tools and resources
- Proper error responses

Customize the handlers to implement your specific MCP functionality while maintaining the authentication framework.

## Additional Resources

- [MCP Specification - Authorization - Latest Draft](https://modelcontextprotocol.io/specification/draft/basic/authorization)
- [MCP SDK for PHP Documentation](https://github.com/logiscape/mcp-sdk-php)
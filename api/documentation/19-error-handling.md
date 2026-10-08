# 19. Error Handling

## Overview

MintHCM API centralizes all exception handling in `MintExceptionHandler`. The handler adapts
its response verbosity to the current environment: in development mode it exposes full
diagnostic details, while in production it withholds internal system information that could
be exploited by an attacker.

## MintExceptionHandler

**Location:** `api/app/ExceptionHandlers/MintExceptionHandler.php`

The handler is registered as Slim's error handler in `api/app/ApiManager.php` and is
invoked for every unhandled `\Throwable`.

### Behavior by environment

The environment is determined by reading `$sugar_config['developerMode']` (the same flag
administrators control via the Legacy UI under Admin › System Settings). When the key is
absent the value defaults to `false` (production-safe behavior).

#### Developer mode (`developerMode = true`)

Full diagnostic information is returned:

```json
{
    "message": "Some exception message",
    "code": 404,
    "file": "/var/www/MintHCM/api/app/Controllers/SomeController.php",
    "line": 42,
    "stack": "#0 ..."
}
```

#### Production mode (`developerMode = false`)

For **5xx errors** a generic message is returned to avoid leaking internal state:

```json
{
    "message": "Internal Server Error",
    "code": 500
}
```

For **4xx errors** the exception message is preserved (it helps API clients understand the
problem) but diagnostic fields are stripped:

```json
{
    "message": "Resource not found",
    "code": 404
}
```

### Logging

Full error details (`message`, `file`, `line`, full stack trace) are **always** written to
the Sugar logger via `$GLOBALS['log']->error(...)`, regardless of the current mode. This
ensures production incidents remain observable in server logs while not exposing them to API
consumers.

The logger call is guarded with `!empty($GLOBALS['log'])` so the handler degrades gracefully
in unit-test contexts where the legacy bootstrap is not loaded.

## Doctrine dev_mode

**Location:** `api/data/ORM/Doctrine/DoctrineContainerBuilder.php`

Doctrine's `dev_mode` flag controls whether ORM metadata is read from source annotations on
every request (slow, always fresh) or from a pre-built cache (fast, must be manually
cleared after schema changes).

Previously hardcoded to `true`, it now reads the same `developerMode` flag:

```php
'dev_mode' => (bool)($sugar_config['developerMode'] ?? false),
```

In production (`developerMode = false`) Doctrine uses its metadata cache, which
significantly reduces bootstrap overhead.

> **Note:** After deploying from development to production (or vice-versa), clear the
> Doctrine cache directory (`api/cache/doctrine/`) so that it is regenerated under the
> correct mode.

## Enabling developer mode

In the Legacy UI: **Admin › System Settings › Developer Mode**.

Alternatively, set directly in `config_override.php`:

```php
$sugar_config['developerMode'] = true;
```

# Getting Started

This guide will help you set up your development environment and make your first changes to the MintHCM API.

## Prerequisites

- PHP 8.2
- Composer
- MySQL/MariaDB
- Working MintHCM installation
- Web server (Apache/Nginx)

## Verify API

1. Check if the API responds (public endpoint, no authentication required):
   ```bash
   curl http://your-minthcm-instance/api/login
   ```

2. Run the unit tests to verify everything works:
   ```bash
   cd /path/to/minthcm/api
   composer test   # = php vendor/phpunit/phpunit/phpunit --configuration phpunit.xml
   ```

## Project Structure Overview

```
api/
├── app/                    # Core application code (namespace MintHCM\Api)
│   ├── ApiManager.php     # Registers middlewares, routes and error handling
│   ├── Config/            # AppConfig (base path)
│   ├── Constants/         # global_params.php
│   ├── Controllers/       # Request handlers
│   ├── Entities/          # Doctrine ORM entities (auto-generated)
│   ├── EntityTraits/      # Traits shared by entities
│   ├── ExceptionHandlers/ # Error handlers
│   ├── Middlewares/       # Request/response processors
│   ├── RecordView/        # Record view action merging
│   ├── Repositories/      # Doctrine repositories
│   ├── Routes/            # Route definitions + RouteManager
│   └── Utils/             # AuthHelper
├── bin/                   # Doctrine CLI (bin/doctrine)
├── cache/                 # Generated cache (git-ignored)
├── configs/               # OAuth2 keys + configs/mint/ ($mint_config)
├── constants/             # Application constants
├── custom/                # Your customizations go here
├── data/                  # BeanFactory, MintBean, Doctrine ORM layer
├── lib/                   # Services, MintLogic, Search, DuplicateDetection
├── modules/               # Module-owned API code and routes
├── tests/                 # PHPUnit tests (tests/unit/)
├── utils/                 # Utility classes (CustomLoader, LegacyConnector, ...)
├── vendor/                # Composer dependencies
├── index.php              # Application entry point
├── composer.json          # Dependency definitions
└── phpunit.xml            # PHPUnit configuration
```

## First Steps

### 1. Explore Existing Routes

Check what routes are available:

```bash
# View all route files
ls -la app/Routes/routes/

# Example route file
cat app/Routes/routes/base.php
```

### 2. Add Your First Route

The `custom/app/` directory does not exist in a fresh checkout — create it (`mkdir -p custom/app/Routes/routes custom/app/Controllers`). Classes in `custom/app/` use the `MintHCM\Custom\Api\` namespace (PSR-4 in `composer.json`).

Create the controller in `custom/app/Controllers/HelloController.php`:

```php
<?php

namespace MintHCM\Custom\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HelloController
{
    public function sayHello(Request $request, Response $response, array $args): Response
    {
        $response->getBody()->write(json_encode(['message' => 'Hello, World!']));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
```

Then register the route in `custom/app/Routes/routes/test.php`:

```php
<?php

use MintHCM\Custom\Api\Controllers\HelloController;

$routes = [
    'test_hello' => [
        'method' => 'GET',
        'path' => '/test/hello',
        'class' => HelloController::class,
        'function' => 'sayHello',
        'options' => [
            'auth' => true,  // default; the request needs a valid token or session
        ],
    ],
];
```

**Note:** If the `class` does not exist (or the `function` is missing), `RouteManager::shouldSkipRoute()` silently skips the route and the endpoint returns 404.

See [Routing System](./05-routing.md) for more details.

### 3. Test Your Route

Access your new endpoint:

```bash
curl -H "Authorization: Bearer <access_token>" http://your-minthcm-instance/api/test/hello
```

Without a token (or a logged-in browser session) the request returns `401`. For a quick public test set `'auth' => false` in the route options.

## Development Workflow

1. **Make Changes** - Edit files in `custom/` directory to extend functionality
2. **Test Locally** - Run `composer test` (unit tests in `tests/unit/`) and test manually
3. **Check Errors** - View the log in `legacy/minthcm.log` (logger name `minthcm` in `legacy/config.php`) or check the browser console
4. **Deploy** - Copy changes to production environment

## Common Tasks

### Adding Dependencies

```bash
composer require vendor/package-name
```

### Clearing Cache

```bash
# Doctrine metadata cache and proxies (regenerated automatically)
rm -rf cache/doctrine/*
```

## Next Steps

- Learn about [Directory Structure](./02-directory-structure.md)
- Understand [Architecture & Design Patterns](./03-architecture.md)
- Read about [Routing System](./05-routing.md)

## Troubleshooting

### API Returns 404

- Check `.htaccess` file in the root directory
- Verify Apache `mod_rewrite` is enabled
- Check `AppConfig::getBasePath()` returns correct path

### Database Connection Errors

- Verify `$mint_config['database']` in `configs/mint/config_override.php` (written by the installer; defaults in `configs/mint/config.php`) — Doctrine uses these settings, not `legacy/config.php`
- Check Doctrine entity mappings

### Authentication Issues

- Clear session cache
- Check OAuth2 configuration
- Verify user credentials and permissions
- Regenerate OAuth2 client secret if needed:
  ```bash
  ./MintCLI oauth2:regenerateClientSecret
  ```

## Getting Help

- Check existing route implementations in `app/Routes/`
- Review controller examples in `app/Controllers/`
- See test files in `tests/` for usage patterns
- Refer to [Legacy Integration](./07-legacy-integration.md) for working with existing code

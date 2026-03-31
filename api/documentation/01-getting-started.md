# Getting Started

This guide will help you set up your development environment and make your first changes to the MintHCM API.

## Prerequisites

- PHP 8.2
- Composer
- MySQL/MariaDB
- Working MintHCM installation
- Web server (Apache/Nginx)

### Verify API

1. Check if the API responds:
   ```bash
   curl http://your-minthcm-instance/api/
   ```

2. Run tests to verify everything works:
   ```bash
   cd /path/to/minthcm/api
   ```

## Project Structure Overview

```
api/
├── app/                    # Core application code
│   ├── ApiManager.php     # Main application manager
│   ├── Controllers/       # Request handlers
│   ├── Entities/          # Doctrine ORM entities
│   ├── Middlewares/       # Request/response processors
│   └── Routes/            # Route definitions
├── constants/             # Application constants
├── custom/                # Your customizations go here
├── utils/                 # Utility classes
├── vendor/                # Composer dependencies
├── index.php              # Application entry point
└── composer.json          # Dependency definitions
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

Create a simple test route in `custom/app/Routes/routes/test.php`:

```php
<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

$routes = [
    'test.hello' => [
        'method' => 'GET',
        'path' => '/test/hello',
        'class' => HelloController::class,
        'function' => 'sayHello',
    ],
];
```
  
See [Routing System](./05-routing.md) for more details.

### 3. Test Your Route

Access your new endpoint:

```bash
curl http://your-minthcm-instance/api/test/hello
```

## Development Workflow

1. **Make Changes** - Edit files in `custom/` directory to extend functionality
2. **Test Locally** - Use PHPUnit or manual testing
3. **Check Errors** - View logs in `logs/` or check browser console
4. **Deploy** - Copy changes to production environment

## Common Tasks

### Adding Dependencies

```bash
composer require vendor/package-name
```

### Clearing Cache

```bash
rm -rf cache/doctrine/*
rm -rf cache/smarty/*
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

- Verify database credentials in `configs/`
- Check Doctrine entity mappings
- Review database connection settings

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

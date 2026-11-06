# Directory Structure

Understanding the directory structure is essential for navigating and extending the MintHCM API.

## Root Level

```
api/
├── app/                    # Core application code
├── bin/                    # Executable scripts (bin/doctrine - Doctrine CLI)
├── cache/                  # Generated cache files (git-ignored)
├── configs/                # OAuth2 keys and $mint_config (configs/mint/)
├── constants/              # Application-wide constants
├── custom/                 # Customization directory (see Extending the API)
├── data/                   # Data layer utilities
├── documentation/          # This documentation
├── lib/                    # Shared libraries
├── modules/                # Module-specific code
├── tests/                  # PHPUnit tests
├── utils/                  # Utility classes
├── vendor/                 # Composer dependencies
├── composer.json           # Dependency definitions
├── composer.lock           # Locked dependency versions
├── index.php               # Application entry point
├── phpstan.neon            # PHPStan configuration (+ phpstan-baseline.neon)
├── phpunit.xml             # PHPUnit configuration
└── runTests.php            # Legacy test runner (obsolete, see below)
```

## app/ Directory

The `app/` directory contains the core application logic:

```
app/
├── ApiManager.php              # Registers middlewares, routes and error handling
├── Config/
│   └── AppConfig.php          # Base path resolution
├── Constants/
│   └── global_params.php      # Query/body params accepted on every route
├── Controllers/               # HTTP request handlers
│   ├── ModuleController.php  # Generic module operations (create, update, delete, ...)
│   ├── AuthController.php    # Authentication
│   ├── Actions/              # Single-action (__invoke) controllers
│   ├── Admin/                # Administration views
│   ├── Init/                 # Initialization controllers
│   ├── Module/               # Module list/init controllers
│   ├── OAuth2/               # OAuth2 server and controllers
│   └── User/                 # User-related controllers
├── Entities/                  # Doctrine ORM entities (auto-generated)
│   ├── Employees.php
│   ├── Users.php
│   ├── OAuth2/
│   └── ...                   # One per module/bean (e.g. Employees -> table users)
├── EntityTraits/              # Traits used by generated entities
├── ExceptionHandlers/         # MintExceptionHandler, Doctrine handlers
├── Middlewares/               # Request/response processing
│   ├── Middleware.php        # Base class (route data lookup)
│   ├── Admin/                # admin_only route option
│   ├── Auth/                 # Authentication middleware
│   ├── Params/               # Parameter validation
│   ├── Parsers/              # Body parsers
│   ├── Routes/               # Route access control (access option)
│   └── Security/             # Security headers
├── RecordView/                # RecordViewActionsMerger
├── Repositories/              # Doctrine repositories (data access)
├── Routes/                    # Route definitions
│   ├── RouteManager.php      # Route registration logic
│   ├── routes/               # Global route files
│   │   ├── base.php
│   │   ├── auth.php
│   │   └── ...
│   └── modules/              # Generic routes registered for EVERY module
│       └── module.php        # (/{Module}/Detail/{id}, /{Module}/Create, ...)
└── Utils/
    └── AuthHelper.php
```

The Doctrine/PHP-DI container is built by `MintHCM\Data\ORM\Doctrine\DoctrineContainerBuilder` (`data/ORM/Doctrine/DoctrineContainerBuilder.php`).

## cache/ Directory

Generated cache files (the whole `api/cache` directory is git-ignored):

```
cache/
└── doctrine/              # Doctrine metadata cache and proxies (doctrine/orm/Proxies)
```

Other subdirectories (e.g. `modules/`, `smarty/`) may appear when legacy code runs with `api/` as the working directory.

**Note:** The contents of `cache/doctrine/` can be safely deleted to clear the cache. They are regenerated automatically.

## configs/ Directory

Configuration files for the application:

```
configs/
├── .gitignore             # *.key, /*/*, !/*/config.php
├── private.key            # OAuth2 private key (git-ignored)
├── public.key             # OAuth2 public key (git-ignored)
└── mint/
    ├── config.php         # Defines $mint_config defaults (committed, autoloaded via composer.json "files")
    └── config_override.php # Instance settings, e.g. database (git-ignored, written by the installer)
```

`configs/mint/config.php` includes every other file in `configs/mint/`. Keys and `config_override.php` contain sensitive information and are not committed. See [Configuration vs Constants](./04-config-vs-constants.md).

## constants/ Directory

Application-wide constants that can be extended via the `custom/` directory:

```
constants/
├── colored_enum.php                    # Color definitions for enums
├── global_search_excluded_modules.php  # Modules excluded from global search
├── legacy_views.php                    # Legacy view mappings
├── list_constants.php                  # List view constants
├── menu_icons.php                      # Menu icon mappings
├── module_icons.php                    # Module icon definitions
└── quick_create.php                    # Quick create configurations
```

See [Configuration vs Constants](./04-config-vs-constants.md) for details.

## custom/ Directory

**This is where you put your customizations!**

The `custom/` directory mirrors the structure of the main API and allows you to extend or override functionality without modifying core files:

```
custom/
├── app/
│   ├── Controllers/       # Custom controllers
│   ├── Entities/          # Extended entities
│   ├── Middlewares/       # Custom middlewares
│   ├── Repositories/      # Extended repositories
│   └── Routes/            # Custom routes
│       ├── routes/        # Global custom routes
│       └── modules/       # Module-specific custom routes
├── constants/             # Extended constants (merged with core; quick_create replaces it)
│   ├── module_icons/      # Additional module icons
│   │   └── my_custom.php
│   └── ...
├── data/                  # Custom data classes (MintHCM\Custom\Data), e.g. MassActions/
├── modules/               # Custom module code/routes (MintHCM\Custom\Modules)
```

Only `custom/data/` exists in a fresh checkout; create the other directories as needed. Class overrides are picked up by `CustomLoader` (namespace `MintHCM\X` → `MintHCM\Custom\X`); composer autoloads `custom/app/`, `custom/modules/` and `custom/data/` only.

See [Extending the API](./08-extending-api.md) for how to use this directory.

## data/ Directory

Data layer utilities and base classes:

```
data/
├── BeanFactory.php        # MintHCM\Data\BeanFactory - chdir-safe wrapper over legacy BeanFactory
├── MintBean.php           # Wrapper around a legacy bean returned by BeanFactory
├── MintDateTime.php       # PHP value of Doctrine date/datetime columns
├── TimeDate.php           # Date/time utilities
├── MassActions/           # Mass action implementations
└── ORM/Doctrine/          # DoctrineContainerBuilder, MintEntity, MintEntityRepository,
                           # MintRepositoryFactory, SoftDeleteFilter, MintTypes, MintEventManager
```

## lib/ Directory

Shared libraries and business logic:

```
lib/
├── DuplicateDetection/    # Duplicate detection service
├── MintLogic/             # Dynamic form logic and validation
├── Search/                # Search functionality (ElasticSearch)
└── Services/              # Shared services (Auth, Http, ...)
```

## modules/ Directory

Module-specific API implementations:

```
modules/
├── Alerts/
│   └── api/
│       └── routes/
│           └── route.php      # Alerts-specific routes
├── Employees/
│   └── api/
│       └── routes/
│           └── sidepanel.php
└── ...
```

Each module can have its own `api/routes/` directory for module-specific endpoints (today: Alerts, Candidatures, Employees, Favorites, Home, Meetings, News, Recruitments, Trackers, Users). The routes are prefixed with `/{ModuleName}`.

## tests/ Directory

PHPUnit test files:

```
tests/
├── bootstrap.php          # PHPUnit bootstrap
├── unit/                  # Unit tests
│   ├── Controllers/
│   ├── ExceptionHandlers/
│   ├── Legacy/
│   ├── Lib/
│   ├── MassActions/
│   ├── Middlewares/
│   ├── RecordView/
│   ├── Repositories/
│   └── utils/
└── Unit/                  # Further unit tests (Comments/, Search/, DynamicPropertiesTest.php)
```

Run them with `composer test`.

## utils/ Directory

Utility classes used throughout the application:

```
utils/
├── ConstantsLoader.php    # Loads constants with custom overrides
├── CustomLoader.php       # Loads classes with custom extensions
├── CyclicRecordsSaver.php # Saving of cyclic records
├── LanguageUtils.php      # Language helpers
├── LegacyConnector.php    # Bridges to legacy code
├── LegacyStaticConnector.php # Bridges to legacy singletons (getInstance())
├── LuxonMapper.php        # Date/time mapping utility
└── MintResponse.php       # Legacy, unused (wrong namespace, not autoloadable) - do not use
```

## vendor/ Directory

Composer dependencies. This directory is generated by Composer and should not be modified directly.

Key dependencies (`composer.json`; locked versions from `composer.lock`):
- `slim/slim` 4.* (4.14) - Slim Framework; `slim/psr7` 1.5.1 - PSR-7 implementation
- `doctrine/orm` ^2.16 (2.20) with `doctrine/dbal` 3.9 - Doctrine ORM
- `php-di/php-di` ^6.3 (6.4) - Dependency injection container
- `league/oauth2-server` ^8.5 - OAuth2 server (pulls in `lcobucci/jwt` transitively)
- `symfony/cache`, `doctrine/annotations`, `ramsey/uuid-doctrine`
- Dev: `phpunit/phpunit` ^11.5, `phpstan/phpstan` ^2.2

## Key Files

### index.php

The application entry point. This file:
1. Initializes the legacy environment
2. Sets up Composer autoloading
3. Creates the Slim application with Doctrine container
4. Configures the base path
5. Executes the ApiManager
6. Runs the application

### composer.json

Defines all PHP dependencies, PSR-4 autoloading rules and the `test` script. To add a new dependency, run `composer require vendor/package`.

### phpunit.xml

PHPUnit test configuration: one test suite (`tests/`), bootstrap file `tests/bootstrap.php`, colors and testdox output.

### runTests.php

Obsolete test runner. It only picks up `tests/*Test.php`, and there are no such files, so it exits with "No test files found". Use `composer test` instead.

## Navigation Conventions

### Core Files

Core files live directly in their respective directories (`app/`, `utils/`, etc.).

### Custom Extensions

Custom extensions mirror the core structure but live under `custom/`:
- Core: `app/Controllers/MyController.php`
- Custom: `custom/app/Controllers/MyController.php`

### Module Files

Module route files can live in:
1. `app/Routes/modules/*.php` - Generic routes registered for every module in `$beanList`
2. `custom/app/Routes/modules/*.php` - Custom generic routes for every module
3. `modules/{ModuleName}/api/routes/` - Module-owned routes
4. `custom/modules/{ModuleName}/api/routes/` - Custom module routes

## Best Practices

1. **Never modify core files directly** - Always use the `custom/` directory
2. **Keep customizations organized** - Mirror the core structure in `custom/`
3. **Use clear naming conventions** - Prefix custom classes if needed
4. **Document your changes** - Add comments explaining customizations
5. **Test thoroughly** - Add tests in `tests/unit/` for new functionality

## Next Steps

- Learn about [Architecture & Design Patterns](./03-architecture.md)
- Understand [Configuration vs Constants](./04-config-vs-constants.md)
- Read about [Extending the API](./08-extending-api.md)

# Architecture & Design Patterns

The MintHCM API follows modern PHP architectural patterns while maintaining integration with the legacy SuiteCRM codebase.

## Architectural Overview

```
┌─────────────┐
│   Client    │
└──────┬──────┘
       │ HTTP Request
       ▼
┌─────────────────────────────────────────┐
│  Security headers + Error middleware    │
└──────┬──────────────────────────────────┘
       │
       ▼
┌─────────────────────────────────────────┐
│     Slim Routing Middleware (match)     │
└──────┬──────────────────────────────────┘
       │
       ▼
┌─────────────────────────────────────────┐
│         Middleware Pipeline             │
│  ┌────────────────────────────────────┐ │
│  │  1. JSON Body Parser               │ │
│  │  2. Authentication (OAuth2/session)│ │
│  │  3. Admin-only check               │ │
│  │  4. Route Access Control           │ │
│  │  5. Parameter Validation           │ │
│  └────────────────────────────────────┘ │
└──────┬──────────────────────────────────┘
       │
       ▼
┌─────────────────────────────────────────┐
│            Controller                    │
│  (Business Logic Entry Point)           │
└──────┬──────────────────────────────────┘
       │
       ├──────────────────┬─────────────────┐
       ▼                  ▼                 ▼
┌─────────────┐   ┌──────────────┐  ┌─────────────┐
│ Repository  │   │    Entity    │  │   Legacy    │
│  (Doctrine) │   │  (Doctrine)  │  │ (Connector/ │
│             │   │              │  │ BeanFactory)│
└──────┬──────┘   └──────┬───────┘  └──────┬──────┘
       │                 │                  │
       ▼                 ▼                  ▼
┌─────────────┐   ┌──────────────┐  ┌─────────────┐
│  Database   │   │  Database    │  │   Legacy    │
│   (MySQL)   │   │   (MySQL)    │  │    Code     │
└─────────────┘   └──────────────┘  └─────────────┘
```

## Core Design Patterns

### 1. MVC (Model-View-Controller) Pattern

While the API doesn't have traditional "views" (it returns JSON), it follows MVC concepts:

- **Model**: Entities and Repositories (data layer)
- **View**: JSON written to the PSR-7 response body
- **Controller**: Controllers that handle HTTP requests

**Example** (simplified from `app/Controllers/PositionsController.php` pattern):

```php
use Doctrine\ORM\EntityManagerInterface;
use MintHCM\Api\Entities\Positions;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MyController
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function detail(Request $request, Response $response, array $args): Response
    {
        // Model: entity loaded through its repository
        $position = $this->entityManager->getRepository(Positions::class)->find($args['id']);

        // View: JSON response
        $response->getBody()->write(json_encode(['data' => $position?->getSerialized()]));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
```

**Note:** Slim's `Slim\Psr7\Response` has no `withJson()` method. `utils/MintResponse.php` is legacy and unused (its namespace is not autoloadable) - always write JSON to the body as shown above.

### 2. Dependency Injection (DI)

The API uses a **PHP-DI** container. When a route is dispatched, `RouteManager::prepareConstructorArguments()` reads the controller's constructor and calls `$container->get(<type>)` for each parameter; `CustomLoader::getObject()` does the same (falling back to the extra arguments passed to it).

**How it works:**

```php
use Doctrine\ORM\EntityManagerInterface;

class MyController
{
    // Resolved from the container by type
    public function __construct(private EntityManagerInterface $entityManager) {}

    // Repositories are obtained from the EntityManager, not injected directly
    // (PHP-DI cannot build a Doctrine repository on its own)
}
```

**Configured in:** `data/ORM/Doctrine/DoctrineContainerBuilder.php` (`MintHCM\Data\ORM\Doctrine\DoctrineContainerBuilder`). It defines `EntityManagerInterface::class`; other classes are autowired by PHP-DI.

### 3. Repository Pattern

Data access is abstracted through repositories, separating business logic from database operations.

```php
// Entity (app/Entities/Kudos.php) - auto-generated, extends MintEntity
/**
 * @ORM\Entity(repositoryClass="MintHCM\Api\Repositories\KudosRepository")
 */
class Kudos extends MintEntity
{
    // Properties map to database columns
}

// Repository (app/Repositories/KudosRepository.php)
use MintHCM\Data\ORM\Doctrine\MintRepository\MintEntityRepository;

class KudosRepository extends MintEntityRepository
{
    // Example custom query method
    public function findNewest(int $limit): array
    {
        return $this->createQueryBuilder('k')
            ->orderBy('k.date_entered', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
```

Entities without a `repositoryClass` get `MintEntityRepository` as their default repository.

### 4. Middleware Pipeline Pattern

Requests pass through a chain of middlewares before reaching controllers.

**Middleware execution order:**

```php
// app/ApiManager.php
public function execute()
{
    $this->addBeforeRouteMiddlewares();
    $this->app->addRoutingMiddleware();
    $this->routeManager->execute();
    $this->setErrorMiddleware();        // MintExceptionHandler + DoctrineConnectionExceptionHandler
    $this->addAfterErrorMiddlewares();  // SecurityHeadersMiddleware
}

protected function addBeforeRouteMiddlewares()
{
    $this->app->addBodyParsingMiddleware();                                  // 6. Slim body parsing
    $this->app->add(CustomLoader::getObject(ParamsMiddleware::class));       // 5. Validate parameters
    $this->app->add(CustomLoader::getObject(RouteAccessMiddleware::class));  // 4. Route `access` option
    $this->app->add(CustomLoader::getObject(AdminMiddleware::class));        // 3. Route `admin_only` option
    $this->app->add(CustomLoader::getObject(AuthMiddleware::class));         // 2. Authenticate user
    $this->app->add(CustomLoader::getObject(JsonBodyParserMiddleware::class)); // 1. Parse JSON body
}
```

**Note:** Slim executes middlewares in reverse order of adding (LIFO). The full order for a request, outermost first, is: `SecurityHeadersMiddleware` → error middleware → routing middleware (matches the route) → `JsonBodyParserMiddleware` → `AuthMiddleware` → `AdminMiddleware` → `RouteAccessMiddleware` → `ParamsMiddleware` → Slim body parsing → controller. The route is matched **before** the custom middlewares run, because they read the route definition via `RouteContext`.

### 5. Custom Loader Pattern

The **CustomLoader** pattern allows extending any class via the `custom/` directory without modifying core files.

```php
// utils/CustomLoader.php
public static function getObject($class, ...$args)
{
    // MintHCM\Api\X -> MintHCM\Custom\Api\X (custom/app/X.php)
    $classReflection = new \ReflectionClass($class);
    $custom_class = str_replace('MintHCM', 'MintHCM\Custom', $classReflection->getName());
    if (class_exists($custom_class) && is_subclass_of($custom_class, $class)) {
        $class = $custom_class;
    }

    // Each constructor parameter is resolved from the DI container by type;
    // if the container cannot provide it, the next value from $args is used
    $arguments = static::prepareConstructorArguments($class, $args);
    return new $class(...$arguments);
}
```

**Usage:**

```php
// Instead of: new MyController()
// Use:
$controller = CustomLoader::getObject(MyController::class);

// If custom/app/Controllers/MyController.php exists and extends the original,
// it will be instantiated instead
```

### 6. Legacy Connector Pattern

The **LegacyConnector** bridges the API to legacy SuiteCRM/SugarCRM code.

```php
// utils/LegacyConnector.php (simplified)
class LegacyConnector
{
    public function __construct($class_name, $link = null, $params = array())
    {
        $old_cwd = getcwd();
        chdir('../legacy/');  // Switch to legacy directory
        try {
            if (isset($link)) {
                require_once $link;
            }
            $this->class = new $class_name(...$params);
        } finally {
            chdir($old_cwd);  // Restore the previous working directory
        }
    }

    public function __call($name, $arguments)
    {
        $old_cwd = getcwd();
        chdir('../legacy/');
        try {
            return $this->class->$name(...$arguments);
        } finally {
            chdir($old_cwd);
        }
    }
    // + __get, __set, __callStatic, callFunction(), runCallable()
}
```

**Usage:**

```php
use MintHCM\Utils\LegacyConnector;

// Instantiate a legacy class and call it from the API
$timeDate = new LegacyConnector('TimeDate', 'include/TimeDate.php');
$now = $timeDate->nowDb();

// Run any block of legacy code inside legacy/
$result = LegacyConnector::runCallable(fn() => \BeanFactory::getBean('Employees', $id)->ACLAccess('edit'));
```

For records, prefer `MintHCM\Data\BeanFactory` (chdir-safe, returns a `MintBean` wrapper).

See [Legacy Integration](./07-legacy-integration.md) for more details.

### 7. Singleton Pattern

Key managers use the Singleton pattern to ensure single instance:

```php
// app/ApiManager.php
class ApiManager
{
    protected static $_instance;
    
    public static function getInstance()
    {
        if (!is_object(self::$_instance)) {
            self::$_instance = CustomLoader::getObject(ApiManager::class);
        }
        return self::$_instance;
    }
}
```

**Used by:**
- `ApiManager`
- `RouteManager`

### 8. Route Registry Pattern

Routes are defined declaratively in PHP arrays and registered automatically:

```php
// app/Routes/routes/base.php
$routes = array(
    "global_search" => array(
        "method" => "GET",
        "path" => "/global_search",
        "class" => GlobalSearchController::class,
        "function" => "getData",
        "options" => array('auth' => true),
        // pathParams / queryParams / bodyParams ...
    ),
);
```

The `RouteManager` automatically:
1. Scans route directories
2. Loads route definitions
3. Registers routes with Slim
4. Lets a later location (e.g. `custom/app/Routes/routes/`) override a core route with the same key; a duplicate key in two files of the same location throws an exception

See [Routing System](./05-routing.md) for details.

## Application Flow

### Request Lifecycle

1. **HTTP Request** arrives at `index.php`
2. **Legacy Bootstrap** initializes SuiteCRM environment
3. **Composer Autoloader** loads classes
4. **Slim App** created with Doctrine container
5. **ApiManager** configures middlewares, routes and error handling
6. **Routing middleware** matches the request to a route
7. **Middleware Pipeline** processes request:
   - Parse JSON body
   - Authenticate user (OAuth2 bearer token or legacy session)
   - Check `admin_only` and `access` route options
   - Validate parameters
8. **Controller** executes business logic:
   - May call repositories
   - May call legacy code
   - May process data
9. **Response** body written as JSON
10. **Middlewares** process response (in reverse order; security headers are added last)
11. **HTTP Response** sent to client

### Example Request Flow

```
POST /api/Employees/Get/{uuid}

1. index.php receives request, legacy environment initialized (include/entryPoint.php)
2. Slim app created with the Doctrine/PHP-DI container
3. Routing middleware matches route "Employees___get_record"
   (generic module route from app/Routes/modules/module.php, prefixed with /Employees)
4. Middlewares process request:
   - JSON parser parses the body
   - AuthMiddleware validates the OAuth2 bearer token or the PHPSESSID session
   - AdminMiddleware / RouteAccessMiddleware check route options
   - ParamsMiddleware validates "id" and the body params ("links")
5. ModuleController::getRecord() called
6. Controller loads the record and writes JSON to the response body
7. Response sent with Content-Type: application/json
```

## Key Components

### ApiManager

Central application orchestrator:
- Configures middlewares
- Sets up error handling
- Initializes routing

### RouteManager

Handles route registration:
- Scans route directories
- Loads route files
- Supports module-specific routes (`{Module}___{key}` route names)
- Lets custom route files override core routes by key

### CustomLoader

Enables extensibility:
- Loads custom class extensions
- Handles dependency injection
- Supports constructor arguments

### LegacyConnector

Bridges to legacy code:
- Manages directory context
- Proxies method calls
- Handles legacy class instantiation

## Best Practices

### 1. Use Dependency Injection

```php
// ✅ Good - use constructor injection
class MyController
{
    public function __construct(EntityManagerInterface $entityManager) { }
}

// ❌ Bad - don't create dependencies manually
class MyController
{
    public function __construct()
    {
        $this->entityManager = (new DoctrineContainerBuilder())->build()->get(EntityManagerInterface::class);
    }
}
```

### 2. Keep Controllers Thin

```php
// ✅ Good - delegate to services/repositories
public function create(Request $request, Response $response, array $args): Response
{
    $data = $this->myService->create($request->getParsedBody());
    $response->getBody()->write(json_encode($data));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
}

// ❌ Bad - too much logic in controller
public function create(Request $request, Response $response, array $args): Response
{
    // 50+ lines of validation, business logic, database queries...
}
```

### 3. Use CustomLoader for Extensibility

```php
// ✅ Good - allows custom override
$manager = CustomLoader::getObject(ApiManager::class);

// ❌ Bad - can't be extended
$manager = new ApiManager();
```

### 4. Separate Concerns

- **Controllers**: Handle HTTP, delegate to services
- **Repositories**: Data access only
- **Entities**: Data structure and simple getters/setters
- **Middlewares**: Cross-cutting concerns (auth, validation)
- **Services/Logic**: Complex business logic

## Design Principles

- **Open/Closed**: Extend via `custom/` (CustomLoader, custom route files, custom constants) without modifying core
- **Substitutability**: A custom class is used only if it extends the core class (`is_subclass_of` check in `CustomLoader`)
- **Shared code**: Reusable helpers live in `utils/`, `lib/Services/` and `data/`

## Next Steps

- Understand [Configuration vs Constants](./04-config-vs-constants.md)
- Learn about [Routing System](./05-routing.md)
- Explore [Database Communication](./06-database.md)
- Read about [Extending the API](./08-extending-api.md)

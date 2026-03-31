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
│         Slim Framework Router           │
└──────┬──────────────────────────────────┘
       │
       ▼
┌─────────────────────────────────────────┐
│         Middleware Pipeline             │
│  ┌────────────────────────────────────┐ │
│  │  1. JSON Body Parser               │ │
│  │  2. Authentication (JWT/OAuth2)    │ │
│  │  3. Route Access Control           │ │
│  │  4. Parameter Validation           │ │
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
│  (Doctrine) │   │  (Doctrine)  │  │  Connector  │
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
- **View**: JSON responses (via `MintResponse`)
- **Controller**: Controllers that handle HTTP requests

**Example:**

```php
// Controller (app/Controllers/ModuleController.php)
public function list(Request $request, Response $response, array $args): Response
{
    $module = $this->getModuleFromRoute($request);
    
    // Get data from repository/entity
    $records = $this->repository->findByModule($module);
    
    // Return JSON response
    return $response->withJson(['data' => $records]);
}
```

### 2. Dependency Injection (DI)

The API uses **PHP-DI** container for automatic dependency injection.

**How it works:**

```php
// Container automatically resolves dependencies
class MyController
{
    private $repository;
    
    // Dependencies injected automatically via constructor
    public function __construct(MyRepository $repository)
    {
        $this->repository = $repository;
    }
}
```

**Configured in:** `app/Containers/Doctrine/DoctrineContainerBuilder.php`

### 3. Repository Pattern

Data access is abstracted through repositories, separating business logic from database operations.

```php
// Entity (app/Entities/Employee.php)
/**
 * @ORM\Entity(repositoryClass="MintHCM\Api\Repositories\EmployeeRepository")
 */
class Employee
{
    // Properties map to database columns
}

// Repository (app/Repositories/EmployeeRepository.php)
class EmployeeRepository extends EntityRepository
{
    public function findActiveEmployees(): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.status = :status')
            ->setParameter('status', 'Active')
            ->getQuery()
            ->getResult();
    }
}
```

### 4. Middleware Pipeline Pattern

Requests pass through a chain of middlewares before reaching controllers.

**Middleware execution order:**

```php
// app/ApiManager.php
protected function addBeforeRouteMiddlewares()
{
    $this->app->addBodyParsingMiddleware();
    $this->app->add(ParamsMiddleware::class);         // 4. Validate parameters
    $this->app->add(RouteAccessMiddleware::class);    // 3. Check permissions
    $this->app->add(AuthMiddleware::class);           // 2. Authenticate user
    $this->app->add(JsonBodyParserMiddleware::class); // 1. Parse JSON body
}
```

**Note:** Middlewares execute in reverse order (LIFO - Last In, First Out).

### 5. Custom Loader Pattern

The **CustomLoader** pattern allows extending any class via the `custom/` directory without modifying core files.

```php
// utils/CustomLoader.php
public static function getObject($class, ...$args)
{
    // Check if custom version exists
    $custom_class = str_replace('MintHCM', 'MintHCM\Custom', $class);
    
    if (class_exists($custom_class) && is_subclass_of($custom_class, $class)) {
        $class = $custom_class;
    }
    
    return new $class(...$args);
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
// utils/LegacyConnector.php
class LegacyConnector
{
    public function __construct($class_name, $link = null, $params = [])
    {
        chdir('../legacy/');  // Switch to legacy directory
        
        if (isset($link)) {
            require_once $link;
        }
        
        $this->class = new $class_name(...$params);
        
        chdir('../api/');     // Switch back to API directory
    }
    
    public function __call($name, $arguments)
    {
        chdir('../legacy/');
        $response = $this->class->$name(...$arguments);
        chdir('../api/');
        
        return $response;
    }
}
```

**Usage:**

```php
// Call legacy code from API
$legacyBean = new LegacyConnector('SugarBean');
$legacyBean->retrieve($id);
```

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
$routes = [
    'module.list' => [
        'method' => 'GET',
        'path' => '/module/{module}',
        'class' => ModuleController::class,
        'function' => 'list',
    ],
];
```

The `RouteManager` automatically:
1. Scans route directories
2. Loads route definitions
3. Registers routes with Slim
4. Handles custom route overrides

See [Routing System](./05-routing.md) for details.

## Application Flow

### Request Lifecycle

1. **HTTP Request** arrives at `index.php`
2. **Legacy Bootstrap** initializes SuiteCRM environment
3. **Composer Autoloader** loads classes
4. **Slim App** created with Doctrine container
5. **ApiManager** configures middlewares and routes
6. **Middleware Pipeline** processes request:
   - Parse JSON body
   - Authenticate user
   - Check route access
   - Validate parameters
7. **Router** matches request to route
8. **Controller** executes business logic:
   - May call repositories
   - May call legacy code
   - May process data
9. **Response** formatted and returned as JSON
10. **Middlewares** process response (in reverse order)
11. **HTTP Response** sent to client

### Example Request Flow

```
GET /api/Employees/123

1. index.php receives request
2. Legacy environment initialized
3. Slim app created
4. Middlewares process request:
   - JSON parser (no body for GET)
   - Auth middleware validates JWT token
   - Route access checks user permissions
   - Params middleware validates "123"
5. RouteManager finds route: Employees___detail
6. ModuleController::detail() called
7. Controller queries database via Doctrine:
   $employee = $repository->find(123)
8. Response formatted as JSON
9. Response sent: {"data": {"id": "123", "name": "..."}}
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
- Supports module-specific routes
- Handles custom overrides

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
    public function __construct(MyRepository $repo) { }
}

// ❌ Bad - don't create dependencies manually
class MyController
{
    public function __construct()
    {
        $this->repo = new MyRepository();
    }
}
```

### 2. Keep Controllers Thin

```php
// ✅ Good - delegate to services/repositories
public function create(Request $request, Response $response): Response
{
    $data = $request->getParsedBody();
    $employee = $this->employeeService->create($data);
    return $response->withJson($employee);
}

// ❌ Bad - too much logic in controller
public function create(Request $request, Response $response): Response
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

### SOLID Principles

- **Single Responsibility**: Each class has one clear purpose
- **Open/Closed**: Extensible via `custom/` without modifying core
- **Liskov Substitution**: Custom classes can replace core classes
- **Interface Segregation**: Slim interfaces, specific contracts
- **Dependency Inversion**: Depend on abstractions (DI container)

### DRY (Don't Repeat Yourself)

- Shared utilities in `utils/`
- Base controllers for common functionality
- Reusable middlewares

### KISS (Keep It Simple)

- Clear, readable code over clever tricks
- Standard patterns over custom solutions
- Simple route definitions

## Next Steps

- Understand [Configuration vs Constants](./04-config-vs-constants.md)
- Learn about [Routing System](./05-routing.md)
- Explore [Database Communication](./06-database.md)
- Read about [Extending the API](./08-extending-api.md)

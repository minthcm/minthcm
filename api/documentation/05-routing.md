# Routing System

The MintHCM API uses a flexible routing system built on Slim Framework that supports automatic route discovery, module-specific routes, and custom extensions.

## Route Basics

Routes map HTTP requests to controller methods. Each route defines:

- **Method**: HTTP method (GET, POST, PUT, DELETE, etc.)
- **Path**: URL pattern (e.g., `/employees/{id}`)
- **Class**: Controller class to handle the request
- **Function**: Method name to invoke (optional if using `__invoke`)

## Route Definition Format

Routes are defined as PHP arrays in route files:

```php
<?php

use MintHCM\Api\Controllers\ModuleController;

$routes = [
    'route.name' => [
        'method' => 'GET',                    // HTTP method(s)
        'path' => '/employees/{id}',          // URL pattern
        'class' => ModuleController::class,   // Controller class
        'function' => 'detail',               // Method to call
    ],
];
```



### Route Properties

| Property | Required | Type | Description |
|----------|----------|------|-------------|
| `method` | ✅ | string or array | HTTP method(s): `'GET'`, `'POST'`, `['GET', 'POST']` |
| `path` | ✅ | string | URL pattern with optional placeholders: `/module/{id}` |
| `class` | ✅ | string | Fully qualified controller class name |
| `function` | ❌ | string | Method name. If omitted, `__invoke` is used |
| `desc` | ❌ | string | Route description (for documentation/API specs) |
| `options` | ❌ | array | Route options (e.g., `['auth' => false]`) |
| `pathParams` | ❌ | array | URL parameter definitions and validation |
| `queryParams` | ❌ | array | Query string parameter definitions and validation |
| `bodyParams` | ❌ | array | Request body parameter definitions and validation |

#### OAuth Authentication Flag

By default, all routes require authentication. To create a public route (no authentication required), set the `auth` option to `false`:

```php
$routes = [
    'public.route' => [
        'method' => 'GET',
        'path' => '/public/data',
        'class' => PublicController::class,
        'function' => 'getData',
        'options' => [
            'auth' => false,  // This route doesn't require authentication
        ],
    ],
];
```

**Important:** Routes with `'auth' => false` are publicly accessible without JWT token. Use carefully!

### Route Name

The array key becomes the route name, used for:
- Route identification
- Debugging
- URL generation (if needed)

**Convention:** Use dot notation: `module.action` (e.g., `employees.list`, `auth.login`)

## Route Locations

The `RouteManager` automatically discovers routes from multiple locations:

### Global Routes

```
app/Routes/routes/               # Core global routes
custom/app/Routes/routes/        # Custom global routes
```

**Example files:**
- `base.php` - Base application routes
- `auth.php` - Authentication routes
- `comments.php` - Comments routes

### Module-Specific Routes

```
app/Routes/modules/{ModuleName}/           # Core module routes
custom/app/Routes/modules/{ModuleName}/    # Custom module routes
modules/{ModuleName}/api/routes/           # Module-owned routes
custom/modules/{ModuleName}/api/routes/    # Custom module-owned routes
```

Module routes automatically get prefixed with `/{ModuleName}`.

## How Routes Are Loaded

The `RouteManager` class handles route discovery and registration:

```php
// app/Routes/RouteManager.php

protected $routes_locations = [
    'app/Routes/routes/',
    'custom/app/Routes/routes/',
];

protected $modules_locations = [
    'app/Routes/modules/',
    'custom/app/Routes/modules/',
    'modules/{module_name}/api/routes/',
    'custom/modules/{module_name}/api/routes/',
];
```

**Loading process:**

1. Scan all route locations
2. Include all `.php` files
3. Expect each file to define `$routes` array
4. Merge all routes
5. For module routes, add module prefix
6. Register with Slim router

## Adding a New Route

### Example 1: Simple Global Route

**File:** `custom/app/Routes/routes/hello.php`

```php
<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

$routes = [
    'hello.world' => [
        'method' => 'GET',
        'path' => '/hello',
        'class' => HelloController::class,
        'function' => 'sayHello',
    ],
];
```

**Controller:** `custom/app/Controllers/HelloController.php`

```php
<?php

namespace MintHCM\Custom\Api\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

class HelloController
{
    public function sayHello(Request $request, Response $response): Response
    {
        $response->getBody()->write(json_encode(['message' => 'Hello, World!']));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
```

**Access:** `GET /api/hello`

### Example 2: Route with URL Parameters

**Route definition:**

```php
$routes = [
    'user.detail' => [
        'method' => 'GET',
        'path' => '/users/{id}',
        'class' => UserController::class,
        'function' => 'getUser',
    ],
];
```

**Controller:**

```php
public function getUser(Request $request, Response $response, array $args): Response
{
    $userId = $args['id'];  // URL parameter
    
    // Fetch user...
    $user = $this->repository->find($userId);
    
    return $response->withJson(['data' => $user]);
}
```

**Access:** `GET /api/users/123` → `$args['id'] = '123'`

### Example 3: Multiple HTTP Methods

```php
$routes = [
    'resource.handle' => [
        'method' => ['GET', 'POST', 'PUT'],
        'path' => '/resource/{id}',
        'class' => ResourceController::class,
        'function' => 'handle',
    ],
];
```

### Example 4: Using `__invoke` (Callable Controller)

```php
$routes = [
    'action.process' => [
        'method' => 'POST',
        'path' => '/action/process',
        'class' => ProcessAction::class,
        // No 'function' specified - uses __invoke
    ],
];
```

**Controller:**

```php
class ProcessAction
{
    public function __invoke(Request $request, Response $response): Response
    {
        // Handle request
        return $response->withJson(['status' => 'processed']);
    }
}
```

## Defining Route Parameters

Routes can define parameters with validation rules and documentation. This helps with automatic validation and API documentation generation.

### Parameter Definition Structure

Each parameter is defined as an array with the following properties:

| Property | Required | Type | Description |
|----------|----------|------|-------------|
| `type` | ✅ | string | Parameter type class (e.g., `StringType::class`, `IntType::class`) |
| `required` | ✅ | boolean | Whether the parameter is mandatory |
| `desc` | ❌ | string | Parameter description for documentation |
| `example` | ❌ | mixed | Example value for documentation |
| `default` | ❌ | mixed | Default value if not provided |

### Available Parameter Types

- `StringType::class` - String values
- `IntType::class` - Integer values
- `BoolType::class` - Boolean values
- `ArrayType::class` - Array values
- `EmailType::class` - Email addresses (validated)
- `DateType::class` - Date values
- `FloatType::class` - Floating point numbers

### Example: Complete Route with Parameters

```php
<?php

use MintHCM\Api\Controllers\EmployeeController;
use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;
use MintHCM\Api\Middlewares\Params\ParamTypes\IntType;
use MintHCM\Api\Middlewares\Params\ParamTypes\EmailType;
use MintHCM\Api\Middlewares\Params\ParamTypes\BoolType;

$routes = [
    'employee.create' => [
        'method' => 'POST',
        'path' => '/employees',
        'class' => EmployeeController::class,
        'function' => 'create',
        'desc' => 'Create a new employee',
        'options' => [
            'auth' => true,  // Requires authentication (default)
        ],
        'pathParams' => [],  // No URL parameters
        'queryParams' => [],  // No query string parameters
        'bodyParams' => [
            'first_name' => [
                'type' => StringType::class,
                'required' => true,
                'desc' => 'Employee first name',
                'example' => 'John',
            ],
            'last_name' => [
                'type' => StringType::class,
                'required' => true,
                'desc' => 'Employee last name',
                'example' => 'Doe',
            ],
            'email' => [
                'type' => EmailType::class,
                'required' => true,
                'desc' => 'Employee email address',
                'example' => 'john.doe@example.com',
            ],
            'age' => [
                'type' => IntType::class,
                'required' => false,
                'desc' => 'Employee age',
                'example' => 30,
            ],
            'is_manager' => [
                'type' => BoolType::class,
                'required' => false,
                'desc' => 'Whether employee is a manager',
                'example' => false,
                'default' => false,
            ],
        ],
    ],
];
```

### Example: Route with Path Parameters

```php
$routes = [
    'employee.update' => [
        'method' => 'PUT',
        'path' => '/employees/{id}',
        'class' => EmployeeController::class,
        'function' => 'update',
        'desc' => 'Update an existing employee',
        'pathParams' => [
            'id' => [
                'type' => StringType::class,
                'required' => true,
                'desc' => 'Employee ID (UUID)',
                'example' => '123e4567-e89b-12d3-a456-426614174000',
            ],
        ],
        'bodyParams' => [
            'first_name' => [
                'type' => StringType::class,
                'required' => false,
                'desc' => 'Updated first name',
                'example' => 'Jane',
            ],
            'last_name' => [
                'type' => StringType::class,
                'required' => false,
                'desc' => 'Updated last name',
                'example' => 'Smith',
            ],
        ],
    ],
];
```

### Example: Route with Query Parameters

```php
$routes = [
    'employee.list' => [
        'method' => 'GET',
        'path' => '/employees',
        'class' => EmployeeController::class,
        'function' => 'list',
        'desc' => 'List all employees with optional filtering',
        'queryParams' => [
            'page' => [
                'type' => IntType::class,
                'required' => false,
                'desc' => 'Page number for pagination',
                'example' => 1,
                'default' => 1,
            ],
            'limit' => [
                'type' => IntType::class,
                'required' => false,
                'desc' => 'Number of items per page',
                'example' => 20,
                'default' => 20,
            ],
            'status' => [
                'type' => StringType::class,
                'required' => false,
                'desc' => 'Filter by employee status',
                'example' => 'Active',
            ],
            'department' => [
                'type' => StringType::class,
                'required' => false,
                'desc' => 'Filter by department name',
                'example' => 'Engineering',
            ],
        ],
    ],
];
```

### Example: Public Login Route

```php
$routes = [
    'auth.login' => [
        'method' => 'POST',
        'path' => '/login',
        'class' => AuthController::class,
        'function' => 'login',
        'desc' => 'Authenticate user and get JWT token',
        'options' => [
            'auth' => false,  // Public route - no authentication required
        ],
        'bodyParams' => [
            'client_secret' => [
                'type' => StringType::class,
                'required' => true,
                'desc' => 'OAuth client secret',
                'example' => '12asd32131231asd23213',
            ],
            'username' => [
                'type' => StringType::class,
                'required' => true,
                'desc' => 'Username',
                'example' => 'admin',
            ],
            'password' => [
                'type' => StringType::class,
                'required' => true,
                'desc' => 'User password',
                'example' => 'p4$$w0rd',
            ],
            'login_language' => [
                'type' => StringType::class,
                'required' => false,
                'desc' => 'Preferred language code',
                'example' => 'pl_PL',
                'default' => 'en_us',
            ],
        ],
    ],
];
```

### Benefits of Parameter Definitions

1. **Automatic Validation**: The `ParamsMiddleware` validates parameters according to their definitions
2. **Type Safety**: Parameters are converted to the correct type
3. **Documentation**: Enables automatic API documentation generation
4. **Developer Experience**: Clear contract for what the endpoint expects
5. **Error Messages**: Descriptive validation errors when parameters are invalid

### Accessing Validated Parameters in Controllers

```php
public function create(Request $request, Response $response): Response
{
    // Parameters are already validated and typed
    $data = $request->getParsedBody();
    
    // Access with confidence - validation already done
    $firstName = $data['first_name'];  // Guaranteed to be a string if required
    $lastName = $data['last_name'];
    $email = $data['email'];  // Guaranteed to be valid email format
    $age = $data['age'] ?? null;  // Optional parameter
    $isManager = $data['is_manager'] ?? false;  // Has default value
    
    // Create employee...
}
```

## Module Routes

Module routes are automatically prefixed with the module name.

### Example: Employee Module Routes

**File:** `modules/Employees/api/routes/employees.php`

```php
<?php

use MintHCM\Api\Controllers\Module\EmployeeController;

$routes = [
    'detail' => [
        'method' => 'GET',
        'path' => '/{id}',                    // Becomes: /Employees/{id}
        'class' => EmployeeController::class,
        'function' => 'detail',
    ],
    'list' => [
        'method' => 'GET',
        'path' => '',                         // Becomes: /Employees
        'class' => EmployeeController::class,
        'function' => 'list',
    ],
    'create' => [
        'method' => 'POST',
        'path' => '',                         // Becomes: /Employees
        'class' => EmployeeController::class,
        'function' => 'create',
    ],
];
```

**Result:**
- `GET /api/Employees` → `list()`
- `GET /api/Employees/123` → `detail()`
- `POST /api/Employees` → `create()`

**Route names:**
- Module routes are automatically namespaced: `Employees___detail`, `Employees___list`

## Route Registration Process

### Internal Flow

```php
// RouteManager::execute()
public function execute()
{
    // Register global routes
    $this->buildRoutes($this->routes);
    
    // Register module routes
    foreach ($this->modules_routes as $module => $routes) {
        $this->buildModulesRoutes($module, $routes);
    }
}

// RouteManager::buildRoute()
protected function buildRoute(&$route_data, $route_name)
{
    $route = $this->addRoute($route_data);
    if (!$route) {
        return null;
    }
    
    $route->setName($route_name);
}
```

### Route Validation

Routes are validated before registration:

```php
protected function shouldSkipRoute($route)
{
    return empty($route['method'])
        || empty($route['path'])
        || empty($route['class'])
        || !class_exists($route['class'])
        || (
            !method_exists($route['class'], '__invoke')
            && (
                empty($route['function'])
                || !method_exists($route['class'], $route['function'])
            )
        );
}
```

**A route is skipped if:**
- No `method` specified
- No `path` specified
- No `class` specified
- Class doesn't exist
- Neither `__invoke` nor specified `function` exists

## Advanced Patterns

### Pattern: RESTful Resource Routes

```php
$routes = [
    'resource.index' => [
        'method' => 'GET',
        'path' => '/resources',
        'class' => ResourceController::class,
        'function' => 'index',
    ],
    'resource.show' => [
        'method' => 'GET',
        'path' => '/resources/{id}',
        'class' => ResourceController::class,
        'function' => 'show',
    ],
    'resource.store' => [
        'method' => 'POST',
        'path' => '/resources',
        'class' => ResourceController::class,
        'function' => 'store',
    ],
    'resource.update' => [
        'method' => ['PUT', 'PATCH'],
        'path' => '/resources/{id}',
        'class' => ResourceController::class,
        'function' => 'update',
    ],
    'resource.destroy' => [
        'method' => 'DELETE',
        'path' => '/resources/{id}',
        'class' => ResourceController::class,
        'function' => 'destroy',
    ],
];
```

### Pattern: Nested Resources

```php
$routes = [
    'employee.documents.list' => [
        'method' => 'GET',
        'path' => '/employees/{employee_id}/documents',
        'class' => EmployeeDocumentsController::class,
        'function' => 'list',
    ],
    'employee.documents.show' => [
        'method' => 'GET',
        'path' => '/employees/{employee_id}/documents/{id}',
        'class' => EmployeeDocumentsController::class,
        'function' => 'show',
    ],
];
```

### Pattern: Versioned Routes

```php
$routes = [
    'api.v1.users' => [
        'method' => 'GET',
        'path' => '/v1/users',
        'class' => V1\UserController::class,
        'function' => 'list',
    ],
    'api.v2.users' => [
        'method' => 'GET',
        'path' => '/v2/users',
        'class' => V2\UserController::class,
        'function' => 'list',
    ],
];
```

## Route Middleware

While routes are registered globally, you can add route-specific middleware in the controller:

```php
class SecureController
{
    public function __construct()
    {
        // Add middleware logic
        if (!$this->isAuthorized()) {
            throw new UnauthorizedException();
        }
    }
}
```

Or use global middlewares that check route attributes (see [Middlewares](./11-middlewares.md)).

## URL Parameters

### Simple Parameters

```php
// Route: /users/{id}
$routes = [
    'user.show' => [
        'path' => '/users/{id}',
        // ...
    ],
];

// Controller
public function show(Request $request, Response $response, array $args)
{
    $id = $args['id'];  // Access parameter
}
```

### Multiple Parameters

```php
// Route: /departments/{dept_id}/employees/{id}
$routes = [
    'dept.employee.show' => [
        'path' => '/departments/{dept_id}/employees/{id}',
        // ...
    ],
];

// Controller
public function show(Request $request, Response $response, array $args)
{
    $deptId = $args['dept_id'];
    $employeeId = $args['id'];
}
```

### Optional Parameters

Slim doesn't support optional parameters directly. Use separate routes:

```php
$routes = [
    'search.all' => [
        'path' => '/search',
        'class' => SearchController::class,
        'function' => 'search',
    ],
    'search.module' => [
        'path' => '/search/{module}',
        'class' => SearchController::class,
        'function' => 'search',
    ],
];
```

### Regular Expression Constraints

Use Slim's route patterns:

```php
// In controller or route setup
$route->setPattern('/users/{id:[0-9]+}');  // Only numeric IDs
```

## Query Parameters

Query parameters (e.g., `?page=1&limit=10`) are accessed via the request object:

```php
public function list(Request $request, Response $response): Response
{
    $queryParams = $request->getQueryParams();
    
    $page = $queryParams['page'] ?? 1;
    $limit = $queryParams['limit'] ?? 20;
    
    // Use parameters...
}
```

## Debugging Routes

### List All Routes

Create a debug route:

```php
$routes = [
    'debug.routes' => [
        'method' => 'GET',
        'path' => '/debug/routes',
        'class' => DebugController::class,
        'function' => 'listRoutes',
    ],
];
```

```php
public function listRoutes(Request $request, Response $response): Response
{
    $routeCollector = $this->app->getRouteCollector();
    $routes = $routeCollector->getRoutes();
    
    $routeList = [];
    foreach ($routes as $route) {
        $routeList[] = [
            'name' => $route->getName(),
            'pattern' => $route->getPattern(),
            'methods' => $route->getMethods(),
        ];
    }
    
    return $response->withJson($routeList);
}
```

### Check Route Errors

If routes aren't loading, check:

```bash
# Verify file syntax
php -l custom/app/Routes/routes/myroute.php

# Check if route array is defined
php -r "include 'custom/app/Routes/routes/myroute.php'; var_dump(\$routes);"
```

## Best Practices

### 1. Consistent Naming

```php
// ✅ Good - clear, descriptive
'employee.list'
'employee.detail'
'employee.create'

// ❌ Bad - unclear
'emp_l'
'get_emp'
```

### 2. RESTful Conventions

```php
// ✅ Good - follows REST conventions
GET    /employees        → list
GET    /employees/{id}   → detail
POST   /employees        → create
PUT    /employees/{id}   → update
DELETE /employees/{id}   → delete

// ❌ Bad - non-standard
GET /getEmployees
POST /createEmployee
```

### 3. Use Custom Directory

```php
// ✅ Good - custom route
custom/app/Routes/routes/myroutes.php

// ❌ Bad - modifying core
app/Routes/routes/base.php  // Don't modify!
```

### 4. Group Related Routes

```php
// ✅ Good - one file per feature
custom/app/Routes/routes/analytics.php  // All analytics routes
custom/app/Routes/routes/reports.php    // All report routes

// ❌ Bad - mixed concerns
custom/app/Routes/routes/misc.php  // Everything thrown together
```

### 5. Define Parameters Properly

```php
// ✅ Good - complete parameter definition
'bodyParams' => [
    'email' => [
        'type' => EmailType::class,
        'required' => true,
        'desc' => 'User email address',
        'example' => 'user@example.com',
    ],
],

// ❌ Bad - missing important info
'bodyParams' => [
    'email' => [
        'type' => StringType::class,  // Should use EmailType
        'required' => true,
        // Missing description and example
    ],
],
```

### 6. Use Appropriate Parameter Types

```php
// ✅ Good - correct type for email
'email' => [
    'type' => EmailType::class,
    'required' => true,
],

// ❌ Bad - generic string type for email
'email' => [
    'type' => StringType::class,
    'required' => true,
],
```

### 7. Document Required vs Optional Parameters

```php
// ✅ Good - clear which are required
'bodyParams' => [
    'name' => [
        'type' => StringType::class,
        'required' => true,  // Must be provided
        'desc' => 'User name',
    ],
    'nickname' => [
        'type' => StringType::class,
        'required' => false,  // Optional
        'desc' => 'User nickname',
        'default' => null,
    ],
],

// ❌ Bad - unclear if required
'bodyParams' => [
    'name' => [
        'type' => StringType::class,
        // Missing required flag!
    ],
],
```

### 8. Use `auth: false` Only for Public Routes

```php
// ✅ Good - public login endpoint
'auth.login' => [
    'path' => '/login',
    'options' => ['auth' => false],  // Makes sense - can't auth before login
],

// ❌ Bad - sensitive data without auth
'user.salary' => [
    'path' => '/users/{id}/salary',
    'options' => ['auth' => false],  // Dangerous! Salary should require auth
],
```

## Next Steps

- Learn about [Controllers & Actions](./09-controllers.md)
- Understand [Database Communication](./06-database.md)
- Explore [Middlewares](./11-middlewares.md)
- Read about [Extending the API](./08-extending-api.md)

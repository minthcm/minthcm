# Routing System

The MintHCM API uses a flexible routing system built on Slim Framework that supports automatic route discovery, module-specific routes, and custom extensions.

## Route Basics

Routes map HTTP requests to controller methods. Each route defines:

- **Method**: HTTP method (GET, POST, PUT, DELETE, etc.)
- **Path**: URL pattern with optional placeholders (e.g., `/subpanel/{relation_name}/{id}`)
- **Class**: Controller class to handle the request
- **Function**: Method name to invoke (optional if using `__invoke`)

## Route Definition Format

Routes are defined as PHP arrays in route files:

```php
<?php

use MintHCM\Api\Controllers\GlobalSearchController;

$routes = [
    'global_search' => [
        'method' => 'GET',                          // HTTP method(s)
        'path' => '/global_search',                 // URL pattern
        'class' => GlobalSearchController::class,   // Controller class
        'function' => 'getData',                    // Method to call
    ],
];
```

The controller is instantiated per request; its constructor parameters are resolved from the DI container by type (`RouteManager::prepareConstructorArguments()`). The method is called with `(Request $request, Response $response, array $args)`.


### Route Properties

| Property | Required | Type | Description |
|----------|----------|------|-------------|
| `method` | ✅ | string or array | HTTP method(s): `'GET'`, `'POST'`, `['GET', 'POST']` |
| `path` | ✅ | string | URL pattern with optional placeholders: `/module/{id}` |
| `class` | ✅ | string | Fully qualified controller class name |
| `function` | ❌ | string | Method name. If omitted, `__invoke` is used |
| `desc` | ❌ | string | Route description (for documentation/API specs) |
| `options` | ❌ | array | Route options (see below) |
| `pathParams` | ❌ | array | URL parameter definitions and validation |
| `queryParams` | ❌* | array | Query string parameter definitions and validation |
| `bodyParams` | ❌* | array | Request body parameter definitions and validation |

\* Every query/body parameter the client sends must be declared here - see [Undeclared Parameters](#undeclared-parameters-are-rejected).

#### Route Options

| Option | Default | Checked by | Description |
|--------|---------|------------|-------------|
| `auth` | `true` | `AuthMiddleware` | Require a valid OAuth2 bearer token or logged-in session |
| `optional_auth` | `false` | `AuthMiddleware` | Try to authenticate but tolerate a failed session (see below) |
| `admin_only` | `false` | `AdminMiddleware` | Only administrators may call the route |
| `access` | `'all'` | `RouteAccessMiddleware` | Which API client may call the route: `'all'`, `'frontend'`, `'mobile'`, `'external'`, or an array of these. Other clients get `404` |

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

**Important:** Routes with `'auth' => false` are publicly accessible without an OAuth2 token or session. Use carefully!

#### Optional Authentication Flag

Some endpoints need to work both with and without authentication — for example, an endpoint that returns different data depending on whether the user is logged in, or a logout endpoint that should not fail if the session already expired.

Set `optional_auth` to `true` (alongside `auth => false`) to enable this behavior:

```php
$routes = [
    'public.with.context' => [
        'method' => 'GET',
        'path' => '/data',
        'class' => DataController::class,
        'function' => 'getData',
        'options' => [
            'auth' => false,
            'optional_auth' => true,  // Try to authenticate, but don't fail if it can't
        ],
    ],
];
```

When `optional_auth` is `true`, the `AuthMiddleware` **attempts** to validate the token or session. If validation succeeds, `$current_user` is set as usual. A failed **session** check (request with a `PHPSESSID` cookie) is tolerated and the request proceeds without a `401`. Without a session cookie the bearer token is still validated, so a missing or invalid token returns the OAuth2 error (`401`) even with `optional_auth`.

**Summary of `auth` / `optional_auth` combinations:**

| `auth` | `optional_auth` | Behavior |
|--------|-----------------|----------|
| `true` (default) | `false` (default) | **Required** — valid token/session mandatory; `401` on failure |
| `false` | `false` | **Public** — authentication is skipped entirely |
| `false` | `true` | **Optional** — authentication is attempted; a failed session is allowed (a request without a session cookie still needs a valid token) |

**Use cases:**
- `/logout` — should cleanly terminate a session if one exists, but must not break if the session is already gone
- `/languages` — returns language data for anyone, but may personalize the response for authenticated users

### Route Name

The array key becomes the route name, used for:
- Route identification
- Debugging
- URL generation (if needed)

**Convention:** Core route files mostly use snake_case keys (e.g., `get_login`, `global_search`, `list_data`); some module files use dot notation (e.g., `employees.sidepanel.tenure`). Either works - pick one style per file and keep keys descriptive. Module routes are registered as `{Module}___{key}` (e.g., `Employees___detail`).

**Key rules** (`RouteManager::getRoutesData()`):
- Two files in the **same** location defining the same key throw an exception (`Duplicate route key ...`).
- A later location overrides an earlier one with the same key (`array_merge`), so a file in `custom/app/Routes/routes/` can replace a core route by reusing its key. For modules, a key in `modules/{Module}/api/routes/` overrides the generic route with the same key from `app/Routes/modules/module.php`.

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
app/Routes/modules/*.php                   # Generic routes registered for EVERY module (module.php)
custom/app/Routes/modules/*.php            # Custom generic routes for every module
modules/{ModuleName}/api/routes/           # Module-owned routes
custom/modules/{ModuleName}/api/routes/    # Custom module-owned routes
```

Module routes are built for every module in `$beanList`, get prefixed with `/{ModuleName}` and are named `{ModuleName}___{key}`. The generic routes in `app/Routes/modules/module.php` include e.g. `list` (`GET /{Module}`), `detail` (`GET /{Module}/Detail/{id}`), `create` (`POST /{Module}/Create`), `update` (`PATCH /{Module}/Update[/{id}]`), `get_record` (`POST /{Module}/Get[/{id}]`), `delete` (`DELETE /{Module}/{id}`), `list_data`, `subpanel_records`, `mass_actions`, `link`, `unlink`.

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
4. Merge all routes (later locations override earlier ones by key)
5. For module routes, add module prefix
6. Register with Slim router - routes that fail validation are skipped **silently** (see [Route Validation](#route-validation))

## Adding a New Route

### Example 1: Simple Global Route

**File:** `custom/app/Routes/routes/hello.php`

```php
<?php

use MintHCM\Custom\Api\Controllers\HelloController;

$routes = [
    'hello_world' => [
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
use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;
use MintHCM\Custom\Api\Controllers\MyUsersController;

$routes = [
    'my_user_detail' => [
        'method' => 'GET',
        'path' => '/my_users/{id}',
        'class' => MyUsersController::class,
        'function' => 'getUser',
        'pathParams' => [
            'id' => ['type' => StringType::class, 'required' => true],
        ],
    ],
];
```

**Controller** (`custom/app/Controllers/MyUsersController.php`):

```php
namespace MintHCM\Custom\Api\Controllers;

use Doctrine\ORM\EntityManagerInterface;
use MintHCM\Api\Entities\Users;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MyUsersController
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function getUser(Request $request, Response $response, array $args): Response
    {
        $userId = $args['id'];  // raw URL parameter; validated value: $request->getAttribute('id')

        $user = $this->entityManager->getRepository(Users::class)->find($userId);
        if (!$user) {
            $response->getBody()->write(json_encode(['error' => 'Not found']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $response->getBody()->write(json_encode(['data' => ['id' => $user->id, 'user_name' => $user->user_name]]));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
```

**Access:** `GET /api/my_users/{uuid}` → `$args['id'] = '{uuid}'`

### Example 3: Multiple HTTP Methods

```php
$routes = [
    'logout' => [
        'method' => ['POST', 'GET'],   // see app/Routes/routes/auth.php
        'path' => '/logout',
        'class' => AuthController::class,
        'function' => 'logout',
    ],
];
```

### Example 4: Using `__invoke` (Callable Controller)

```php
// app/Routes/routes/base.php
$routes = [
    'get_login' => [
        'method' => 'GET',
        'path' => '/login',
        'class' => LoginAction::class,   // MintHCM\Api\Controllers\Actions\LoginAction
        // No 'function' specified - uses __invoke
        'options' => ['auth' => false],
    ],
];
```

**Controller** (pattern used by `app/Controllers/Actions/`):

```php
class MyAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $response->getBody()->write(json_encode(['status' => 'processed']));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
```

## Defining Route Parameters

Routes can define parameters with validation rules and documentation. This helps with automatic validation and API documentation generation.

### Parameter Definition Structure

Each parameter is defined as an array with the following properties:

| Property | Required | Type | Description |
|----------|----------|------|-------------|
| `type` | ✅ | string | Parameter type class (e.g., `StringType::class`, `IntType::class`); a missing/unknown class → `400` |
| `required` | ❌ | boolean | Whether the parameter is mandatory. Default: `true` for `pathParams`, `false` for `queryParams`/`bodyParams` |
| `desc` | ❌ | string | Parameter description for documentation |
| `example` | ❌ | mixed | Example value for documentation |

There is **no `default` support**: a missing optional parameter yields `null` (`ParamType::getValue()`). Apply defaults in the controller (`$request->getAttribute('page') ?? 1`).

### Available Parameter Types

Namespace `MintHCM\Api\Middlewares\Params\ParamTypes` (`app/Middlewares/Params/ParamTypes/`):

- `StringType::class` - String values
- `IntType::class` - Integer values
- `BoolType::class` - Boolean values
- `ArrayType::class` - Array values
- `EmailType::class` - Email addresses (validated)
- `FileType::class` - Uploaded files

Custom types extend `MintHCM\Api\Middlewares\Params\ParamType` and implement `validate($value): bool` and `parseValue($value)`.

### Undeclared Parameters Are Rejected

`ParamsMiddleware` validates query and body parameters strictly:
- If the route declares no `queryParams` (or `bodyParams`) but the request sends any, the response is `400`.
- Every sent parameter must be declared in the route, otherwise the response is `400`.
- Only the global parameters from `app/Constants/global_params.php` are always accepted (currently the query parameter `XDEBUG_SESSION_START`).

Declare every parameter your clients send, even optional ones.

### Example: Complete Route with Parameters

```php
<?php

use MintHCM\Custom\Api\Controllers\MyEmployeesController;
use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;
use MintHCM\Api\Middlewares\Params\ParamTypes\IntType;
use MintHCM\Api\Middlewares\Params\ParamTypes\EmailType;
use MintHCM\Api\Middlewares\Params\ParamTypes\BoolType;

$routes = [
    'my_employee_create' => [
        'method' => 'POST',
        'path' => '/my_employees',
        'class' => MyEmployeesController::class,
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
            ],
        ],
    ],
];
```

### Example: Route with Path Parameters

```php
$routes = [
    'my_employee_update' => [
        'method' => 'PUT',
        'path' => '/my_employees/{id}',
        'class' => MyEmployeesController::class,
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
    'my_employee_list' => [
        'method' => 'GET',
        'path' => '/my_employees',
        'class' => MyEmployeesController::class,
        'function' => 'list',
        'desc' => 'List all employees with optional filtering',
        'queryParams' => [
            'page' => [
                'type' => IntType::class,
                'required' => false,
                'desc' => 'Page number for pagination',
                'example' => 1,
            ],
            'limit' => [
                'type' => IntType::class,
                'required' => false,
                'desc' => 'Number of items per page',
                'example' => 20,
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
    'login' => [   // app/Routes/routes/auth.php
        'method' => 'POST',
        'path' => '/login',
        'class' => AuthController::class,
        'function' => 'login',
        'desc' => 'Auth user in MintHCM',
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
            ],
        ],
    ],
];
```

### Benefits of Parameter Definitions

1. **Automatic Validation**: The `ParamsMiddleware` validates parameters according to their definitions; an invalid, missing required or undeclared parameter returns `400 Bad Request`
2. **Type Safety**: Parsed values are stored as request attributes in the declared type
3. **Documentation**: `desc` and `example` describe the endpoint contract
4. **Developer Experience**: Clear contract for what the endpoint expects

### Accessing Validated Parameters in Controllers

`ParamsMiddleware` stores each parsed value (path, query and body) as a **request attribute** (`$request->withAttribute($name, $parsed_value)`). `getParsedBody()` / `getQueryParams()` still return the raw, unconverted data.

```php
public function create(Request $request, Response $response, array $args): Response
{
    // Validated and parsed values
    $firstName = $request->getAttribute('first_name');   // string (required)
    $email = $request->getAttribute('email');            // valid email (required)
    $age = $request->getAttribute('age');                // int or null (optional)
    $isManager = $request->getAttribute('is_manager') ?? false;  // no 'default' support - apply it here

    // Create employee...
}
```

## Module Routes

Module routes are automatically prefixed with the module name.

### Example: Employee Module Routes

**File:** `modules/Employees/api/routes/sidepanel.php` (shortened)

```php
<?php

use MintHCM\Api\Controllers\EmployeeSidepanelController;
use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;

$routes = [
    'employees.sidepanel.tenure' => [
        'method' => 'GET',
        'path' => '/{id}/sidepanel/tenure',   // Becomes: /Employees/{id}/sidepanel/tenure
        'class' => EmployeeSidepanelController::class,
        'function' => 'getTenureSummary',
        'options' => ['auth' => true],
        'pathParams' => [
            'id' => ['type' => StringType::class, 'required' => true, 'desc' => 'Employee ID'],
        ],
    ],
];
```

**Result:**
- `GET /api/Employees/{id}/sidepanel/tenure` → `EmployeeSidepanelController::getTenureSummary()`

**Route names:**
- Module routes are automatically namespaced: `Employees___employees.sidepanel.tenure`
- The generic routes from `app/Routes/modules/module.php` are named the same way: `Employees___detail`, `Employees___list`, ...
- A module file that reuses a generic key (e.g. `detail`) replaces that generic route for this module only.

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

### Pattern: Generic Module Routes

The core does not use classic REST paths for records. Every module gets the generic routes from `app/Routes/modules/module.php`, e.g.:

```
GET    /api/{Module}                 → list (ListInitController)
GET    /api/{Module}/Detail/{id}     → ModuleController::detail
POST   /api/{Module}/Create          → ModuleController::create
PATCH  /api/{Module}/Update[/{id}]   → ModuleController::update
POST   /api/{Module}/Get[/{id}]      → ModuleController::getRecord
DELETE /api/{Module}/{id}            → ModuleController::delete
```

Add a new module-specific endpoint in `modules/{Module}/api/routes/` (or `custom/modules/{Module}/api/routes/`) instead of duplicating these.

### Pattern: Nested Resources

```php
// Several placeholders in one path
$routes = [
    'subpanel_records' => [   // app/Routes/modules/module.php
        'method' => 'POST',
        'path' => '/subpanel/{relation_name}/{id}',
        'class' => ModuleController::class,
        'function' => 'subpanelRecords',
        // pathParams: relation_name, id; queryParams: paginate_by, page, ...
    ],
];
```

## Route-Level Access Control

There is no per-route middleware registration. Restrict routes with options checked by the global middlewares (see [Route Options](#route-options) and [Middlewares](./11-middlewares.md)):

```php
'options' => [
    'auth' => true,
    'admin_only' => true,        // AdminMiddleware: administrators only
    'access' => ['frontend'],    // RouteAccessMiddleware: only the frontend client
],
```

For record-level checks inside a controller, throw a Slim HTTP exception, e.g. `throw new \Slim\Exception\HttpForbiddenException($request);`.

## URL Parameters

### Simple Parameters

```php
// Route
$routes = [
    'my_user_show' => [
        'method' => 'GET',
        'path' => '/my_users/{id}',
        // class, function, pathParams ...
    ],
];

// Controller
public function show(Request $request, Response $response, array $args): Response
{
    $id = $args['id'];                      // raw value
    $id = $request->getAttribute('id');     // value validated by pathParams
    // ...
}
```

### Multiple Parameters

```php
// Route: /subpanel/{relation_name}/{id}
public function subpanelRecords(Request $request, Response $response, array $args): Response
{
    $relationName = $args['relation_name'];
    $id = $args['id'];
    // ...
}
```

### Optional Parameters

Slim 4 supports optional segments in square brackets. The core uses them, e.g. `'path' => '/Update[/{id}]'` (`app/Routes/modules/module.php`). An optional path parameter should be declared with `'required' => false` in `pathParams`.

### Regular Expression Constraints

Put the FastRoute regex directly in the `path`:

```php
'path' => '/my_users/{id:[0-9a-f-]{36}}',  // only UUID-like IDs
```

## Query Parameters

Query parameters (e.g., `?page=1&paginate_by=10`) must be declared in `queryParams` (otherwise `400`, see [Undeclared Parameters](#undeclared-parameters-are-rejected)). Read the validated values from the request attributes:

```php
public function list(Request $request, Response $response, array $args): Response
{
    $page = $request->getAttribute('page') ?? 1;
    $paginateBy = $request->getAttribute('paginate_by') ?? 20;

    // Use parameters...
}
```

## Debugging Routes

### List All Routes

All loaded route definitions are available from the `RouteManager` singleton; registered Slim routes from the app's route collector:

```php
use MintHCM\Api\Routes\RouteManager;

$definitions = RouteManager::getInstance()->getRoutes();   // global + per-module definitions

global $mint_app;
foreach ($mint_app->getRouteCollector()->getRoutes() as $route) {
    $routeList[] = [
        'name' => $route->getName(),
        'pattern' => $route->getPattern(),
        'methods' => $route->getMethods(),
    ];
}
```

A route missing from the collector was skipped by `shouldSkipRoute()` (missing `method`/`path`/`class`, class not found, or method not found).

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
'global_search'
'list_data'
'employees.sidepanel.tenure'

// ❌ Bad - unclear
'emp_l'
'get_emp'
```

### 2. Follow the Existing URL Conventions

```php
// ✅ Good - reuse the generic module routes, add module-specific ones under the module prefix
POST /api/{Module}/Create
GET  /api/Employees/{id}/sidepanel/tenure

// ❌ Bad - duplicating generic record operations in a new global route
GET /api/getEmployee/{id}
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
    ],
],

// ❌ Bad - unclear if required (body/query params default to optional,
// path params to required)
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
'login' => [
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

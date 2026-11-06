# Extending the API

The MintHCM API is designed for extensibility. You can add new features, override existing functionality, and customize behavior without modifying core files.

## The `custom/` Directory

All customizations should be placed in the `custom/` directory, which mirrors the structure of the core API.

```
api/
├── app/               # Core code
│   ├── Controllers/
│   ├── Entities/
│   └── Routes/
└── custom/            # Your customizations
    ├── app/           # MintHCM\Custom\Api\     (PSR-4 autoloaded)
    │   ├── Controllers/
    │   ├── Middlewares/
    │   └── Routes/
    ├── modules/       # MintHCM\Custom\Modules\ (PSR-4 autoloaded)
    ├── data/          # MintHCM\Custom\Data\    (PSR-4 autoloaded)
    ├── lib/MintLogic/ # logicdefs files only (included, not autoloaded)
    └── constants/
```

**Autoloading:** `api/composer.json` maps only `MintHCM\Custom\Api\` → `custom/app/`, `MintHCM\Custom\Modules\` → `custom/modules/` and `MintHCM\Custom\Data\` → `custom/data/`. There is **no** mapping for `custom/lib/` or `custom/utils/` — PHP classes placed there will not be found. Put new classes (services, helpers, validators) under `custom/app/` or `custom/modules/`.

**Benefits:**
- ✅ Upgrades don't overwrite your changes
- ✅ Clear separation between core and custom code
- ✅ Easy to track what's been customized
- ✅ Can be version-controlled separately

## Extension Mechanisms

The API provides several ways to extend functionality:

1. **Custom Routes** - Add new endpoints
2. **Class Extension** - Override/extend core classes
3. **Constants Extension** - Add/override constants
4. **Module Routes** - Add module-specific endpoints
5. **Middlewares** - Add request/response processing
6. **Duplicate Detection** - Add record deduplication queries
7. **MintLogic** - Add form logic in `custom/lib/MintLogic/{Module}/*logicdefs.php` (see [MintLogic](./13-mintlogic.md))
8. **Mass / record / subpanel actions** - `custom/data/MassActions/Actions/` and custom action definitions (see [Mass Actions](./15-mass-actions.md), [RecordView Extensions](./18-recordview-extensions.md))
9. **Doctrine event subscribers** - `custom/data/ORM/Doctrine/MintEventManager/Subscribers/` (namespace `MintHCM\Custom\Data\ORM\Doctrine\MintEventManager\Subscribers`, loaded by `data/ORM/Doctrine/MintEventManager/MintEventManager.php`)

## Adding Custom Routes

### Global Custom Routes

Create route files in `custom/app/Routes/routes/`:

**File:** `custom/app/Routes/routes/myfeature.php`

```php
<?php

use MintHCM\Api\Middlewares\Params\ParamTypes\BoolType;
use MintHCM\Api\Middlewares\Params\ParamTypes\IntType;
use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;
use MintHCM\Custom\Api\Controllers\MyFeatureController;

$routes = [
    'myfeature.list' => [
        'method' => 'GET',
        'path' => '/myfeature',
        'class' => MyFeatureController::class,
        'function' => 'list',
        'desc' => 'List all features',
        'options' => [
            'auth' => false,  // Public endpoint (no authentication required)
        ],
    ],
    'myfeature.detail' => [
        'method' => 'GET',
        'path' => '/myfeature/{id}',
        'class' => MyFeatureController::class,
        'function' => 'detail',
        'desc' => 'Get feature details',
        'pathParams' => [
            'id' => [
                'type' => StringType::class,
                'desc' => 'Feature ID',
            ],
        ],
    ],
    'myfeature.search' => [
        'method' => 'GET',
        'path' => '/myfeature/search',
        'class' => MyFeatureController::class,
        'function' => 'search',
        'desc' => 'Search features',
        'queryParams' => [
            'query' => [
                'type' => StringType::class,
                'required' => true,
                'desc' => 'Search query',
            ],
            'limit' => [
                'type' => IntType::class,
                'required' => false,
                'desc' => 'Results limit',
                'example' => 20,
            ],
        ],
    ],
    'myfeature.create' => [
        'method' => 'POST',
        'path' => '/myfeature',
        'class' => MyFeatureController::class,
        'function' => 'create',
        'desc' => 'Create new feature',
        'bodyParams' => [
            'name' => [
                'type' => StringType::class,
                'required' => true,
                'desc' => 'Feature name',
            ],
            'description' => [
                'type' => StringType::class,
                'required' => false,
                'desc' => 'Feature description',
            ],
            'enabled' => [
                'type' => BoolType::class,
                'required' => false,
                'desc' => 'Is feature enabled',
            ],
        ],
    ],
];
```

**Route Definition Properties:**

| Property | Required | Description | Example |
|----------|----------|-------------|---------|
| `method` | ✅ Yes | HTTP method (string or array of methods) | `'GET'`, `['GET', 'POST']` |
| `path` | ✅ Yes | URL path (can include `{param}` placeholders) | `'/myfeature/{id}'` |
| `class` | ✅ Yes | Controller class (must exist, or the route is skipped) | `MyController::class` |
| `function` | ✅ Yes* | Controller method (*omit only for an `__invoke` controller) | `'list'`, `'detail'` |
| `desc` | ❌ No | Route description (for documentation) | `'Get feature details'` |
| `options` | ❌ No | Route options read by the global middlewares (see below) | `['auth' => false]` |
| `pathParams` | ❌ No | URL path parameters definition | See below |
| `queryParams` | ❌ No | Query string parameters definition | See below |
| `bodyParams` | ❌ No | Request body parameters definition | See below |

**Route keys and overriding:** keys must be unique within one location (a duplicate throws an exception), but files from later locations override earlier ones with `array_merge()` (`app/Routes/RouteManager.php`). Order for global routes: `app/Routes/routes/` → `custom/app/Routes/routes/`. A custom file that redefines a core route key therefore replaces that route.

**Route options (`options`):**

| Option | Default | Read by | Effect |
|--------|---------|---------|--------|
| `auth` | `true` | `AuthMiddleware` | Require a valid session / Bearer token (401 otherwise) |
| `optional_auth` | `false` | `AuthMiddleware` | Try to authenticate, but continue without a user if it fails (see [Routing](./05-routing.md)) |
| `admin_only` | `false` | `AdminMiddleware` | Only admins (`$current_user->isAdmin()`), others get 403 |
| `access` | `'all'` | `RouteAccessMiddleware` | Client restriction: `'all'`, `'frontend'`, `'mobile'`, `'external'` or an array of them; other clients get 404 |

**Authentication Control (`options.auth`):**

```php
// Default: authentication required
'myfeature.private' => [
    'method' => 'GET',
    'path' => '/myfeature/private',
    'class' => MyFeatureController::class,
    'function' => 'private',
    // auth => true is default, no need to specify
],

// Public endpoint: no authentication
'myfeature.public' => [
    'method' => 'GET',
    'path' => '/myfeature/public',
    'class' => MyFeatureController::class,
    'function' => 'public',
    'options' => [
        'auth' => false,  // ⚠️ Accessible without login!
    ],
],
```

⚠️ **Security Warning:** Setting `'auth' => false` makes the endpoint publicly accessible. Only use for login, health checks, or truly public endpoints.

**Parameter Definitions:**

Parameters can be defined in three locations:

1. **`pathParams`** - URL path parameters (`/users/{id}`)
2. **`queryParams`** - Query string (`?search=john&limit=10`)
3. **`bodyParams`** - Request body (JSON POST/PUT data)

Each parameter definition supports:

```php
'param_name' => [
    'type' => StringType::class,  // ParamType class (required)
    'required' => true,           // Default: true for pathParams, false for query/body params
    'desc' => 'Description for docs',
    'example' => 'value',         // Optional, documentation only
],
```

**Available Parameter Types** (namespace `MintHCM\Api\Middlewares\Params\ParamTypes`):

- `StringType::class` - Text value
- `IntType::class` - Integer number
- `BoolType::class` - Boolean (true/false)
- `EmailType::class` - Email address (validated)
- `ArrayType::class` - Array of values
- `FileType::class` - Uploaded file

**Validation rules enforced by `ParamsMiddleware`:**

- `type` must be an existing class — a plain string such as `'string'` makes every request to the route fail with **400 Bad Request**.
- Any query or body parameter that is **not declared** in the route results in **400** (the only globally accepted parameter is `XDEBUG_SESSION_START`, see `app/Constants/global_params.php`). Declare every parameter your endpoint accepts.
- A missing required parameter or a value that fails type validation results in **400**.
- Every declared parameter (path, query and body) is stored as a **request attribute** with its parsed value — read it with `$request->getAttribute('name')`. This is the pattern used by the core controllers.

For more on routing, see [Routing Documentation](./05-routing.md#defining-route-parameters).

**Controller:** `custom/app/Controllers/MyFeatureController.php`

```php
<?php

namespace MintHCM\Custom\Api\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

class MyFeatureController
{
    public function list(Request $request, Response $response): Response
    {
        $data = ['items' => []];
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // Path parameters: request attribute (also available in $args)
    public function detail(Request $request, Response $response, array $args): Response
    {
        $id = $request->getAttribute('id');  // From /myfeature/{id}
        $data = ['id' => $id, 'name' => 'Example'];
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // Query parameters: validated and parsed by ParamsMiddleware
    public function search(Request $request, Response $response): Response
    {
        $query = $request->getAttribute('query');
        $limit = $request->getAttribute('limit') ?? 20;

        $results = ['query' => $query, 'limit' => $limit];
        $response->getBody()->write(json_encode($results));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // Body parameters: validated and parsed by ParamsMiddleware
    public function create(Request $request, Response $response): Response
    {
        $name = $request->getAttribute('name');
        $description = $request->getAttribute('description') ?? '';
        $enabled = $request->getAttribute('enabled') ?? true;

        // Create logic here...

        $response->getBody()->write(json_encode(['id' => 'new-id', 'name' => $name]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    // Public endpoint (auth => false)
    public function public(Request $request, Response $response): Response
    {
        $response->getBody()->write(json_encode(['public' => true, 'message' => 'No auth required']));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
```

> **JSON responses:** the response object is a plain `Slim\Psr7\Response` — it has no `withJson()` method. Write the encoded body and set the header as shown above. (`utils/MintResponse.php` is a legacy class that is not autoloadable and is not used by the application.)

### Module-Specific Custom Routes

Add routes for a specific module in `custom/modules/{Module}/api/routes/`. The route path is prefixed with `/{Module}` and the route is named `{Module}___{key}`. Module routes are built only for modules registered in legacy `$beanList`.

Module route locations, in loading order (later ones override earlier keys):

1. `app/Routes/modules/*.php` - core routes added to **every** module (e.g. `module.php`)
2. `custom/app/Routes/modules/*.php` - custom routes added to **every** module (files only; subdirectories are ignored)
3. `modules/{Module}/api/routes/*.php` - core routes of one module
4. `custom/modules/{Module}/api/routes/*.php` - custom routes of one module

**File:** `custom/modules/Employees/api/routes/custom.php`

```php
<?php

use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;
use MintHCM\Custom\Api\Controllers\CustomEmployeeController;

$routes = [
    'export' => [
        'method' => 'GET',
        'path' => '/export',  // Becomes: /Employees/export
        'class' => CustomEmployeeController::class,
        'function' => 'export',
        'desc' => 'Export employees to CSV',
        'queryParams' => [
            'format' => [
                'type' => StringType::class,
                'required' => false,
                'desc' => 'Export format (csv, xlsx)',
            ],
        ],
    ],
    'statistics' => [
        'method' => 'GET',
        'path' => '/statistics/{department_id}',
        'class' => CustomEmployeeController::class,
        'function' => 'statistics',
        'desc' => 'Get department statistics',
        'pathParams' => [
            'department_id' => [
                'type' => StringType::class,
                'desc' => 'Department ID',
            ],
        ],
    ],
];
```

## Extending Classes

Use `CustomLoader` to extend core classes without modifying them.

### How CustomLoader Works

`CustomLoader::getObject()` (`utils/CustomLoader.php`) checks for a custom version of a class by inserting `\Custom` after `MintHCM`:

```php
// Core class
MintHCM\Api\ApiManager

// Custom class (used if it exists AND extends the core class)
MintHCM\Custom\Api\ApiManager
```

Rules:
- The custom class is used only if it **extends** the core class (`is_subclass_of`). A class with the same name that does not extend the core class is silently ignored.
- The custom class must be autoloadable (`custom/app/`, `custom/modules/` or `custom/data/`, see above).
- Constructor arguments are resolved from the DI container by type; unresolvable ones are taken from the extra arguments passed to `getObject()`.
- Only classes that the core creates through `CustomLoader::getObject()` can be overridden this way, e.g. `ApiManager`, `RouteManager`, the global middlewares, `AppConfig`, `LoginThrottle`, `SessionTokenRevoker`, `RecordViewActionsMerger`.

### Example: Overriding a Route Controller

Route controllers are **not** created through `CustomLoader` — `RouteManager` instantiates `$route['class']` directly (constructor dependencies come from the DI container). A subclass in `custom/app/Controllers/` alone has no effect: you must also redefine the route so that it points to your class.

**Custom controller:** `custom/app/Controllers/ModuleController.php`

```php
<?php

namespace MintHCM\Custom\Api\Controllers;

use MintHCM\Api\Controllers\ModuleController as BaseModuleController;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

class ModuleController extends BaseModuleController
{
    // Override method
    public function getRecord(Request $request, Response $response, array $args): Response
    {
        // Custom logic before/after the core implementation
        return parent::getRecord($request, $response, $args);
    }
}
```

**Route override:** `custom/app/Routes/modules/module_overrides.php`

Files in `custom/app/Routes/modules/` are loaded after `app/Routes/modules/` and override routes with the same key for every module:

```php
<?php

use MintHCM\Custom\Api\Controllers\ModuleController;

// Reuse the core definition (params, options) and only swap the controller class
require __DIR__ . '/../../../../app/Routes/modules/module.php';
$core_routes = $routes;

$routes = [
    'get_record' => array_merge($core_routes['get_record'], ['class' => ModuleController::class]),
];
```

For global routes do the same in `custom/app/Routes/routes/` with the key from `app/Routes/routes/*.php`.

### Example: Extending ApiManager

`ApiManager` is created through `CustomLoader`, so a subclass in `custom/app/ApiManager.php` is picked up automatically — see [Creating Custom Middlewares](#creating-custom-middlewares).

### Repositories and Entities

Custom repository or entity subclasses are **not** picked up automatically:
- Doctrine takes the repository class from the entity annotation (`repositoryClass`), and `MintRepositoryFactory` resolves short names (`getRepository('Employees')`) in `MintHCM\Api\Entities` first.
- Entity files in `app/Entities/` are generated from vardefs (see [Entities & Repositories](./10-entities-repositories.md)).

Recommended alternatives:
- **New fields:** add them as custom fields (Studio / vardefs with `source => custom_fields`). Quick Repair generates a `{Module}_cstm` entity linked to the main entity, and the fields are reachable through the main entity's magic accessors.
- **Reusable entity methods:** an Entity Trait declared in vardefs (`doctrineEntity.traits`).
- **Custom queries:** a service class in `custom/app/` that receives `EntityManagerInterface` and uses `$em->getRepository('Module')` or a query builder.

## Extending Constants

Add or override constants via the `custom/constants/` directory.

### Structure

```
custom/constants/
└── {constant_name}/
    ├── 01-my-additions.php
    ├── 02-more-additions.php
    └── 99-overrides.php
```

### Example: Adding Module Icons

**File:** `custom/constants/module_icons/custom_modules.php`

```php
<?php

return [
    'MyCustomModule' => 'briefcase',
    'AnotherModule' => 'chart-line',
    'Employees' => 'account-tie',  // Override default
];
```

Icon names are [Material Design Icons](https://pictogrammers.com/library/mdi/) names without the `mdi-` prefix (see `constants/module_icons.php`).

When `ConstantsLoader::getConstants('module_icons')` is called (`utils/ConstantsLoader.php`):
1. Base constants loaded from `constants/module_icons.php` — if this file does not exist, the call returns `false` and custom files are not read
2. Custom constants merged from all `*.php` files in `custom/constants/module_icons/`
3. Later files override earlier ones (alphabetically)
4. The custom result is merged over the base array

`getConstants()` has a second parameter, `$only_custom_if_exist`. When it is `true` and at least one custom file returns entries, the custom entries **replace** the base array instead of being merged into it.

### Example: Configuring Quick Create

Quick create is the "+" menu in the top navigation that allows quick creation of records. The constant is a simple array mapping module names to their display labels:

```php
// Base: constants/quick_create.php (excerpt)
return array(
    "Ideas" => translate("LBL_LIST_TITLE", "Ideas"),
    "Kudos" => translate("LBL_LIST_TITLE", "Kudos"),
    "Tasks" => translate("LBL_LIST_TITLE", "Tasks"),
    // ...
);
```

⚠️ `quick_create` is loaded with `getConstants('quick_create', true)` (`app/Controllers/Init/Init.php`), so custom files **replace** the whole base list — they are not merged. List every module that should appear in the menu, including the core ones you want to keep:

**File:** `custom/constants/quick_create/custom.php`

```php
<?php

return [
    'Ideas' => translate('LBL_LIST_TITLE', 'Ideas'),
    'Tasks' => translate('LBL_LIST_TITLE', 'Tasks'),
    'MyCustomModule' => translate('LBL_LIST_TITLE', 'MyCustomModule'),
    'CustomModule' => 'Quick Create Custom',  // Static label
];
```

When a user clicks the "+" icon in the navigation, they'll see options to create records for these modules (subject to ACL permissions).

## Creating Custom Middlewares

Add custom request/response processing:

**File:** `custom/app/Middlewares/CustomAuthMiddleware.php`

```php
<?php

namespace MintHCM\Custom\Api\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class CustomAuthMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        // Pre-processing
        $customHeader = $request->getHeaderLine('X-Custom-Auth');

        if (empty($customHeader)) {
            $response = new Response();
            $response->getBody()->write(json_encode(['error' => 'Custom auth required']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }

        // Continue to next middleware
        $response = $handler->handle($request);

        // Post-processing
        return $response->withHeader('X-Processed-By', 'CustomAuth');
    }
}
```

**Register middleware:**

`ApiManager` is created through `CustomLoader`, so the subclass below replaces it automatically.

**File:** `custom/app/ApiManager.php`

```php
<?php

namespace MintHCM\Custom\Api;

use MintHCM\Api\ApiManager as BaseApiManager;
use MintHCM\Custom\Api\Middlewares\CustomAuthMiddleware;

class ApiManager extends BaseApiManager
{
    protected function addBeforeRouteMiddlewares()
    {
        parent::addBeforeRouteMiddlewares();

        // Added last = executed first (before AuthMiddleware), for EVERY route
        $this->app->add(new CustomAuthMiddleware());
    }
}
```

⚠️ A middleware added this way runs for every route, including `/login` and other public endpoints. The example above would block login for clients that do not send `X-Custom-Auth`. To limit a middleware to some routes, read the matched route's definition (e.g. a custom key in `options`) by extending `MintHCM\Api\Middlewares\Middleware` and calling `$this->getRouteData($request)` — see [Middlewares](./11-middlewares.md).

## Creating Custom Services

Encapsulate business logic in service classes. Put them in an autoloadable custom namespace (here `custom/app/Services/` → `MintHCM\Custom\Api\Services`). Classes in `custom/lib/` are **not** autoloaded.

**File:** `custom/app/Services/EmployeeStatsService.php`

```php
<?php

namespace MintHCM\Custom\Api\Services;

use Doctrine\ORM\EntityManagerInterface;
use MintHCM\Api\Entities\Employees;

class EmployeeStatsService
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function countByStatus(string $status): int
    {
        // Rows with deleted = 1 are hidden by the global soft_delete filter
        return $this->entityManager->getRepository(Employees::class)
            ->count(['employee_status' => $status, 'show_on_employees' => 1]);
    }

    public function changeStatus(string $employeeId, string $status): void
    {
        $repository = $this->entityManager->getRepository(Employees::class);
        $employee = $repository->find($employeeId);
        if (!$employee) {
            throw new \InvalidArgumentException('Employee not found'); // see note below
        }
        $employee->employee_status = $status;

        // MintEntityRepository::save() goes through the legacy SugarBean save (logic hooks etc.)
        $repository->save($employee, true);
    }
}
```

**Use in controller:**

Constructor dependencies of route controllers are resolved from the PHP-DI container by type. `EntityManagerInterface` is defined explicitly; concrete classes such as the service above are autowired. Repositories and interfaces that are not defined in the container (e.g. `LoggerInterface`) cannot be injected.

```php
<?php

namespace MintHCM\Custom\Api\Controllers;

use MintHCM\Custom\Api\Services\EmployeeStatsService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

class EmployeeStatsController
{
    public function __construct(private EmployeeStatsService $statsService)
    {
    }

    public function count(Request $request, Response $response): Response
    {
        $count = $this->statsService->countByStatus($request->getAttribute('status'));
        $response->getBody()->write(json_encode(['count' => $count]));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
```

Note: Slim's `Http*Exception` classes need the request (`new HttpNotFoundException($request)`). In a service without the request, throw your own exception and map it in the controller, or let `MintExceptionHandler` turn it into a 500 (see [Error Handling](./19-error-handling.md)).

### Example: Implementing Duplicate Detection

The `DuplicateDetectionService` (`lib/DuplicateDetection/Service/DuplicateDetectionService.php`) uses class-based autodiscovery to find duplicate detection queries by module name. This allows you to add duplicate detection support for any module using a simple naming convention.

**How it works:**

When checking for duplicates in a module (e.g., "Candidates"), the service looks for a query class using this convention:
1. **Custom first:** `MintHCM\Custom\Modules\{Module}\Custom{Module}DuplicateQuery` (file `custom/modules/{Module}/Custom{Module}DuplicateQuery.php`)
2. **Core fallback:** `MintHCM\Modules\{Module}\{Module}DuplicateQuery` (file `modules/{Module}/{Module}DuplicateQuery.php`)

The first class that exists is used. It must implement `DuplicateQueryInterface`, otherwise a `LogicException` is thrown.

**Integration with saving:** `ModuleController::create()`/`update()` call the service before saving. When duplicates are found the API answers **409** with:

```json
{
  "duplicates_info": {
    "records": [ { "id": "...", "first_name": "...", "last_name": "...", "module": "Candidates" } ],
    "count": 1
  }
}
```

Sending the body parameter `force_save` skips the check.

**Core example:** `modules/Candidates/CandidatesDuplicateQuery.php` searches candidates and employees by first + last name combined with e-mail or mobile phone. Each sub-query is built on a fresh `clone $query_builder`.

**Example: Adding duplicate detection for a custom module**

**File:** `custom/modules/MyModule/CustomMyModuleDuplicateQuery.php`

```php
<?php

namespace MintHCM\Custom\Modules\MyModule;

use Doctrine\DBAL\Query\QueryBuilder;
use MintHCM\Lib\DuplicateDetection\DuplicateArrayQuery;
use MintHCM\Lib\DuplicateDetection\DuplicateQueryInterface;

class CustomMyModuleDuplicateQuery implements DuplicateQueryInterface
{
    public function buildQueries(QueryBuilder $query_builder, array $record_data): DuplicateArrayQuery
    {
        $duplicate_array_query = new DuplicateArrayQuery();

        // Check if minimum required data exists
        if (empty($record_data['name'])) {
            return $duplicate_array_query;
        }

        // Clone the pristine builder for every independent query
        $by_name = clone $query_builder;
        $by_name->select('m.id', 'm.name', "'MyModule' AS module")
            ->from('my_module', 'm')
            ->where('m.name = :name')
            ->andWhere('m.deleted = 0')
            ->andWhere('m.id != :id')
            ->setParameters([
                'name' => $record_data['name'],
                'id' => $record_data['id'] ?? '',
            ]);

        $duplicate_array_query->addQuery('by_name', $by_name);

        return $duplicate_array_query;
    }
}
```

**Usage in a service or controller:**

```php
use MintHCM\Lib\DuplicateDetection\Service\DuplicateDetectionService;

// Injected via the constructor (autowired), or: new DuplicateDetectionService($entityManager)
if ($duplicateDetectionService->shouldProcessModule('MyModule')) {
    $duplicates = $duplicateDetectionService->getDuplicates('MyModule', [
        'id' => 'record-123',
        'name' => 'Example',
    ]);
    // Rows from all sub-queries; [] when nothing is found
}
```

**Key points:**

- If no query class exists for a module, `getDuplicates()` returns `[]` (empty array) and processing is skipped
- Implement `DuplicateQueryInterface` — it requires one method: `buildQueries(QueryBuilder $query_builder, array $record_data): DuplicateArrayQuery`
- Return `DuplicateArrayQuery` to support multiple sub-queries (by name, by email, etc.); `addQuery()` ignores `null`/non-QueryBuilder values
- Always check `$record_data` contents before building queries
- Clone the **unmodified** `$query_builder` for each independent query — a clone of an already configured builder keeps its FROM, WHERE and parameters

## Custom Utilities

Add helper classes in an autoloadable custom namespace. `custom/utils/` is **not** autoloaded (`MintHCM\Utils\` maps only to `utils/`).

**File:** `custom/app/Utils/StringHelper.php`

```php
<?php

namespace MintHCM\Custom\Api\Utils;

class StringHelper
{
    public static function slugify(string $text): string
    {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9-]/', '-', $text);
        $text = preg_replace('/-+/', '-', $text);
        return trim($text, '-');
    }

    public static function truncate(string $text, int $length = 100): string
    {
        if (strlen($text) <= $length) {
            return $text;
        }
        return substr($text, 0, $length) . '...';
    }
}
```

**Usage:**

```php
use MintHCM\Custom\Api\Utils\StringHelper;

$slug = StringHelper::slugify('Hello World!');  // "hello-world"
```

## Directory Structure for Custom Code

Recommended structure for custom extensions:

```
custom/
├── app/                                  # MintHCM\Custom\Api\
│   ├── ApiManager.php                    # Extended API manager
│   ├── Controllers/
│   │   ├── MyController.php
│   │   └── ModuleController.php          # Extended core controller (+ route override)
│   ├── Middlewares/
│   │   └── CustomMiddleware.php
│   ├── Services/
│   │   └── EmployeeStatsService.php
│   ├── Utils/
│   │   └── StringHelper.php
│   └── Routes/
│       ├── routes/
│       │   └── custom.php                # Custom global routes / overrides
│       └── modules/
│           └── module_overrides.php      # Routes added to / overriding EVERY module
├── constants/
│   ├── module_icons/
│   │   └── custom.php
│   └── quick_create/
│       └── custom.php
├── data/                                 # MintHCM\Custom\Data\
│   ├── MassActions/Actions/              # Custom mass actions
│   └── ORM/Doctrine/MintEventManager/Subscribers/
├── lib/
│   └── MintLogic/
│       └── MyModule/
│           └── custom.logicdefs.php      # MintLogic rules (included, not autoloaded)
└── modules/                              # MintHCM\Custom\Modules\
    └── MyModule/
        ├── api/routes/custom.php         # Routes of one module (/MyModule/...)
        └── CustomMyModuleDuplicateQuery.php
```

## Complete Example: Custom Feature

Let's create a complete custom feature - an endpoint returning employee counts per status.

### Step 1: Add Route

**File:** `custom/app/Routes/routes/employee_stats.php`

```php
<?php

use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;
use MintHCM\Custom\Api\Controllers\EmployeeStatsController;

$routes = [
    'employee_stats.count' => [
        'method' => 'GET',
        'path' => '/employee-stats/count',
        'class' => EmployeeStatsController::class,
        'function' => 'count',
        'desc' => 'Count employees with the given status',
        'queryParams' => [
            'status' => [
                'type' => StringType::class,
                'required' => true,
                'desc' => 'Employee status',
                'example' => 'Active',
            ],
        ],
    ],
];
```

### Step 2: Create Controller

Use `EmployeeStatsController` from [Creating Custom Services](#creating-custom-services) in `custom/app/Controllers/EmployeeStatsController.php`.

### Step 3: Create Service

Use `EmployeeStatsService` from [Creating Custom Services](#creating-custom-services) in `custom/app/Services/EmployeeStatsService.php`.

### Step 4: Test

The route requires authentication (`auth` defaults to `true`), so send a Bearer token or the session cookie:

```bash
curl -H "Authorization: Bearer <access_token>" \
  "http://your-instance/api/employee-stats/count?status=Active"
```

## Best Practices

### 1. Always Use `custom/` Directory

```php
// ✅ Good
custom/app/Controllers/MyController.php

// ❌ Bad - will be overwritten on upgrade
app/Controllers/MyController.php
```

### 2. Use Proper Namespacing

```php
// ✅ Good - custom namespace
namespace MintHCM\Custom\Api\Controllers;

// ❌ Bad - core namespace
namespace MintHCM\Api\Controllers;  // Don't use in custom/
```

### 3. Extend, Don't Replace

```php
// ✅ Good - extend core class
class ModuleController extends BaseModuleController
{
    public function getRecord(Request $request, Response $response, array $args): Response
    {
        // Add custom logic
        return parent::getRecord($request, $response, $args);  // Call parent if needed
    }
}

// ❌ Bad - completely replace
class ModuleController
{
    // No parent, no upgrade path. CustomLoader ignores custom classes
    // that do not extend the core class.
}
```

### 4. Document Custom Code

```php
/**
 * Custom extension of ModuleController.
 * 
 * Adds support for custom filtering and performance tracking.
 * 
 * @author Your Name <your.email@example.com>
 * @since 2024-01-15
 */
class ModuleController extends BaseModuleController
{
    // ...
}
```

### 5. Version Control Custom Code

Keep `api/custom/` under version control together with the rest of the instance repository. Since the core repository already tracks files there (e.g. `custom/data/MassActions/Actions/.gitkeep`), do not create a nested repository inside it.

## Troubleshooting

### Custom Class Not Loading

**Problem:** CustomLoader not finding custom class

**Solution:**
```text
# Check that the class is created through CustomLoader at all
# (route controllers are not - override the route instead, see "Overriding a Route Controller")

# Check that the directory is autoloaded: custom/app, custom/modules, custom/data only

# Check namespace
# Core:   namespace MintHCM\Api\Controllers;
# Custom: namespace MintHCM\Custom\Api\Controllers;

# Check file location
# Core:   app/Controllers/MyController.php
# Custom: custom/app/Controllers/MyController.php

# Verify class extends core
class MyController extends \MintHCM\Api\Controllers\MyController
```

### Routes Not Registering

**Problem:** Custom routes not appearing

**Solution:**
```bash
# Check file location
ls -la custom/app/Routes/routes/myroutes.php

# Verify $routes array
php -r "include 'custom/app/Routes/routes/myroutes.php'; var_dump(\$routes);"

# Check for syntax errors
php -l custom/app/Routes/routes/myroutes.php
```

Also check that the controller class and method exist (otherwise the route is skipped silently), that the route key is not duplicated within the same directory (exception), and - for module routes - that the module is in `$beanList`. If the route registers but answers 400, check the parameter definitions (`type` must be a ParamType class, all accepted parameters must be declared).

### Constants Not Merging

**Problem:** Custom constants not appearing

**Solution:**
```bash
# Check directory structure
ls -la custom/constants/module_icons/

# File must end in .php
mv custom_icons.txt custom_icons.php

# The base file constants/module_icons.php must exist

# Test loading (run from api/)
php -r "require 'vendor/autoload.php'; var_dump(\MintHCM\Utils\ConstantsLoader::getConstants('module_icons'));"
```

Remember that some constants are loaded with `$only_custom_if_exist = true` (e.g. `quick_create`) - custom files replace the base list instead of being merged.

## Next Steps

- Review [Routing System](./05-routing.md)
- Learn about [Controllers & Actions](./09-controllers.md)
- Understand [Database Communication](./06-database.md)
- Explore [Testing](./12-testing.md)

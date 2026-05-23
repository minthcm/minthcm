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
    ├── app/
    │   ├── Controllers/
    │   ├── Entities/
    │   └── Routes/
    └── constants/
```

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

## Adding Custom Routes

### Global Custom Routes

Create route files in `custom/app/Routes/routes/`:

**File:** `custom/app/Routes/routes/myfeature.php`

```php
<?php

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
                'type' => 'string',
                'description' => 'Feature ID',
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
                'type' => 'string',
                'required' => true,
                'description' => 'Search query',
            ],
            'limit' => [
                'type' => 'int',
                'required' => false,
                'description' => 'Results limit',
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
                'type' => 'string',
                'required' => true,
                'description' => 'Feature name',
            ],
            'description' => [
                'type' => 'string',
                'required' => false,
                'description' => 'Feature description',
            ],
            'enabled' => [
                'type' => 'bool',
                'required' => false,
                'description' => 'Is feature enabled',
            ],
        ],
    ],
];
```

**Route Definition Properties:**

| Property | Required | Description | Example |
|----------|----------|-------------|---------|
| `method` | ✅ Yes | HTTP method | `'GET'`, `'POST'`, `'PUT'`, `'DELETE'` |
| `path` | ✅ Yes | URL path (can include `{param}` placeholders) | `'/myfeature/{id}'` |
| `class` | ✅ Yes | Controller class | `MyController::class` |
| `function` | ✅ Yes | Controller method | `'list'`, `'detail'` |
| `desc` | ❌ No | Route description (for documentation) | `'Get feature details'` |
| `options` | ❌ No | Route options (auth, etc.) | `['auth' => false]` |
| `pathParams` | ❌ No | URL path parameters definition | See below |
| `queryParams` | ❌ No | Query string parameters definition | See below |
| `bodyParams` | ❌ No | Request body parameters definition | See below |

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
    'type' => 'string',        // string, int, bool, email, etc.
    'required' => true,        // Is parameter required?
    'description' => 'Description for docs',
    'validation' => [...],     // Custom validation rules (optional)
],
```

**Available Parameter Types:**

- `string` - Text value
- `int` - Integer number
- `bool` - Boolean (true/false)
- `email` - Email address
- `date` - Date value
- `datetime` - Date and time
- `array` - Array of values

For complete parameter type reference, see [Routing Documentation](./05-routing.md#defining-route-parameters).

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
        return $response->withJson($data);
    }

    // Path parameters accessed via $args
    public function detail(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];  // From /myfeature/{id}
        $data = ['id' => $id, 'name' => 'Example'];
        return $response->withJson($data);
    }

    // Query parameters accessed via $request->getQueryParams()
    public function search(Request $request, Response $response): Response
    {
        $queryParams = $request->getQueryParams();
        $query = $queryParams['query'] ?? '';
        $limit = (int)($queryParams['limit'] ?? 20);
        
        $results = ['query' => $query, 'limit' => $limit];
        return $response->withJson($results);
    }

    // Body parameters accessed via $request->getParsedBody()
    public function create(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();
        $name = $body['name'];
        $description = $body['description'] ?? '';
        $enabled = $body['enabled'] ?? true;
        
        // Create logic here...
        
        return $response->withJson(['id' => 'new-id', 'name' => $name], 201);
    }

    // Public endpoint (auth => false)
    public function public(Request $request, Response $response): Response
    {
        return $response->withJson(['public' => true, 'message' => 'No auth required']);
    }
}
```

### Module-Specific Custom Routes

Add routes for specific modules in `custom/modules/{Module}/api/routes/`:

**File:** `custom/modules/Employees/api/routes/custom.php`

```php
<?php

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
                'type' => 'string',
                'required' => false,
                'description' => 'Export format (csv, xlsx)',
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
                'type' => 'string',
                'description' => 'Department ID',
            ],
        ],
    ],
];
```

## Extending Classes

Use `CustomLoader` to extend core classes without modifying them.

### How CustomLoader Works

`CustomLoader::getObject()` automatically checks for a custom version of a class:

```php
// Core class
MintHCM\Api\Controllers\ModuleController

// Custom class (loaded if exists)
MintHCM\Custom\Api\Controllers\ModuleController
```

### Example: Extending a Controller

**Core:** `app/Controllers/ModuleController.php`

```php
namespace MintHCM\Api\Controllers;

class ModuleController
{
    public function list(Request $request, Response $response): Response
    {
        // Core implementation
    }
}
```

**Custom:** `custom/app/Controllers/ModuleController.php`

```php
<?php

namespace MintHCM\Custom\Api\Controllers;

use MintHCM\Api\Controllers\ModuleController as BaseModuleController;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

class ModuleController extends BaseModuleController
{
    // Override method
    public function list(Request $request, Response $response): Response
    {
        // Custom implementation
        $customData = $this->getCustomData();
        
        // Or call parent
        $response = parent::list($request, $response);
        
        return $response;
    }
    
    // Add new method
    public function customAction(Request $request, Response $response): Response
    {
        return $response->withJson(['custom' => true]);
    }
}
```

**Usage:**

```php
use MintHCM\Utils\CustomLoader;

// Automatically uses custom version if it exists
$controller = CustomLoader::getObject(ModuleController::class);
```

### Example: Extending a Repository

**Custom:** `custom/app/Repositories/EmployeesRepository.php`

```php
<?php

namespace MintHCM\Custom\Api\Repositories;

use MintHCM\Api\Repositories\EmployeesRepository as BaseRepository;

class EmployeesRepository extends BaseRepository
{
    public function findTopPerformers(int $limit = 10): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.performance_score >= :threshold')
            ->setParameter('threshold', 90)
            ->orderBy('e.performance_score', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
```

### Example: Extending an Entity

**Custom:** `custom/app/Entities/Employees.php`

```php
<?php

namespace MintHCM\Custom\Api\Entities;

use MintHCM\Api\Entities\Employees as BaseEmployees;

class Employees extends BaseEmployees
{
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    public $custom_field;

    // Add custom methods
    public function getFullNameWithTitle(): string
    {
        return "{$this->title} {$this->first_name} {$this->last_name}";
    }
}
```

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
    'Employees' => 'user-tie',  // Override default
];
```

When `ConstantsLoader::getConstants('module_icons')` is called:
1. Base constants loaded from `constants/module_icons.php`
2. Custom constants merged from all files in `custom/constants/module_icons/`
3. Later files override earlier ones (alphabetically)

### Example: Adding Quick Create Fields

**File:** `custom/constants/quick_create/employees.php`

```php
### Example: Adding Module Icons

**File:** `custom/constants/module_icons/custom_modules.php`

```php
<?php

return [
    'MyCustomModule' => 'briefcase',
    'AnotherModule' => 'chart-line',
    'Employees' => 'user-tie',  // Override default
];
```

When `ConstantsLoader::getConstants('module_icons')` is called:
1. Base constants loaded from `constants/module_icons.php`
2. Custom constants merged from all files in `custom/constants/module_icons/`
3. Later files override earlier ones (alphabetically)

### Example: Adding Modules to Quick Create

Quick create is the "+" menu in the top navigation that allows quick creation of records.

**File:** `custom/constants/quick_create/custom.php`

```php
<?php

return [
    'MyCustomModule' => translate('LBL_LIST_TITLE', 'MyCustomModule'),
    'Projects' => translate('LBL_LIST_TITLE', 'Projects'),
    'Ideas' => translate('LBL_CUSTOM_TITLE', 'Ideas'),  // Override default label
];
```

**How it works:**

The quick create constant is a simple array mapping module names to their display labels:

```php
// Base: constants/quick_create.php
return [
    "Ideas" => translate("LBL_LIST_TITLE", "Ideas"),
    "Kudos" => translate("LBL_LIST_TITLE", "Kudos"),
    "Tasks" => translate("LBL_LIST_TITLE", "Tasks"),
];
```

**Custom additions:**

```php
// custom/constants/quick_create/my_modules.php
return [
    "MyModule" => translate("LBL_LIST_TITLE", "MyModule"),
    "CustomModule" => "Quick Create Custom",  // Static label
];
```

The merged result will include all modules from both base and custom files. When a user clicks the "+" icon in the navigation, they'll see options to create records for these modules (subject to ACL permissions).
```

## Creating Custom Middlewares

Add custom request/response processing:

**File:** `custom/app/Middlewares/CustomAuthMiddleware.php`

```php
<?php

namespace MintHCM\Custom\Api\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class CustomAuthMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        // Pre-processing
        $customHeader = $request->getHeaderLine('X-Custom-Auth');
        
        if (empty($customHeader)) {
            $response = new Response();
            return $response->withStatus(401)->withJson(['error' => 'Custom auth required']);
        }
        
        // Continue to next middleware
        $response = $handler->handle($request);
        
        // Post-processing
        $response = $response->withHeader('X-Processed-By', 'CustomAuth');
        
        return $response;
    }
}
```

**Register middleware:**

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
        
        // Add custom middleware
        $this->app->add(new CustomAuthMiddleware());
    }
}
```

## Creating Custom Services

Encapsulate business logic in service classes:

**File:** `custom/lib/Services/EmployeeService.php`

```php
<?php

namespace MintHCM\Custom\Lib\Services;

use MintHCM\Api\Repositories\EmployeesRepository;

class EmployeeService
{
    private $repository;

    public function __construct(EmployeesRepository $repository)
    {
        $this->repository = $repository;
    }

    public function calculateYearlySalary(string $employeeId): float
    {
        $employee = $this->repository->find($employeeId);
        
        if (!$employee) {
            throw new \Exception('Employee not found');
        }

        // Custom business logic
        $baseSalary = $employee->base_salary ?? 0;
        $bonus = $employee->annual_bonus ?? 0;
        
        return $baseSalary * 12 + $bonus;
    }

    public function promoteEmployee(string $employeeId, string $newPosition): void
    {
        $employee = $this->repository->find($employeeId);
        $employee->position = $newPosition;
        $employee->promotion_date = new \DateTime();
        
        // Use EntityManager to save
        $this->repository->getEntityManager()->flush();
    }
}
```

**Use in controller:**

```php
use MintHCM\Custom\Lib\Services\EmployeeService;

class EmployeeController
{
    private $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    public function calculateSalary(Request $request, Response $response, array $args): Response
    {
        $salary = $this->employeeService->calculateYearlySalary($args['id']);
        return $response->withJson(['yearly_salary' => $salary]);
    }
}
```

## Custom Utilities

Add helper functions and utilities:

**File:** `custom/utils/StringHelper.php`

```php
<?php

namespace MintHCM\Custom\Utils;

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
use MintHCM\Custom\Utils\StringHelper;

$slug = StringHelper::slugify('Hello World!');  // "hello-world"
```

## Directory Structure for Custom Code

Recommended structure for custom extensions:

```
custom/
├── app/
│   ├── ApiManager.php              # Extended API manager
│   ├── Controllers/
│   │   ├── MyController.php
│   │   └── ModuleController.php    # Extended core controller
│   ├── Entities/
│   │   └── Employees.php           # Extended entity
│   ├── Middlewares/
│   │   └── CustomMiddleware.php
│   ├── Repositories/
│   │   └── EmployeesRepository.php # Extended repository
│   └── Routes/
│       ├── routes/
│       │   └── custom.php          # Custom global routes
│       └── modules/
│           └── Employees/
│               └── custom.php      # Custom module routes
├── constants/
│   ├── module_icons/
│   │   └── custom.php
│   └── quick_create/
│       └── custom.php
├── lib/
│   ├── Services/
│   │   └── EmployeeService.php
│   └── MintLogic/
│       └── CustomLogic.php
└── utils/
    └── StringHelper.php
```

## Complete Example: Custom Feature

Let's create a complete custom feature - an employee performance review system.

### Step 1: Add Route

**File:** `custom/app/Routes/routes/performance.php`

```php
<?php

use MintHCM\Custom\Api\Controllers\PerformanceController;

$routes = [
    'performance.list' => [
        'method' => 'GET',
        'path' => '/performance/reviews',
        'class' => PerformanceController::class,
        'function' => 'listReviews',
    ],
    'performance.create' => [
        'method' => 'POST',
        'path' => '/performance/reviews',
        'class' => PerformanceController::class,
        'function' => 'createReview',
    ],
];
```

### Step 2: Create Controller

**File:** `custom/app/Controllers/PerformanceController.php`

```php
<?php

namespace MintHCM\Custom\Api\Controllers;

use MintHCM\Custom\Lib\Services\PerformanceService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

class PerformanceController
{
    private $performanceService;

    public function __construct(PerformanceService $performanceService)
    {
        $this->performanceService = $performanceService;
    }

    public function listReviews(Request $request, Response $response): Response
    {
        $reviews = $this->performanceService->getAllReviews();
        return $response->withJson(['data' => $reviews]);
    }

    public function createReview(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $review = $this->performanceService->createReview($data);
        return $response->withJson(['data' => $review], 201);
    }
}
```

### Step 3: Create Service

**File:** `custom/lib/Services/PerformanceService.php`

```php
<?php

namespace MintHCM\Custom\Lib\Services;

use MintHCM\Api\Repositories\PerformanceReviewsRepository;
use MintHCM\Api\Entities\PerformanceReviews;

class PerformanceService
{
    private $repository;

    public function __construct(PerformanceReviewsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllReviews(): array
    {
        return $this->repository->findAll();
    }

    public function createReview(array $data): PerformanceReviews
    {
        $review = new PerformanceReviews();
        $review->employee_id = $data['employee_id'];
        $review->score = $data['score'];
        $review->comments = $data['comments'];
        $review->review_date = new \DateTime();

        $em = $this->repository->getEntityManager();
        $em->persist($review);
        $em->flush();

        return $review;
    }
}
```

### Step 4: Test

```bash
# List reviews
curl http://your-instance/api/performance/reviews

# Create review
curl -X POST http://your-instance/api/performance/reviews \
  -H "Content-Type: application/json" \
  -d '{"employee_id": "123", "score": 85, "comments": "Great work!"}'
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
    public function list(...) {
        // Add custom logic
        parent::list(...);  // Call parent if needed
    }
}

// ❌ Bad - completely replace
class ModuleController
{
    // No parent, no upgrade path
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

```bash
# Initialize git in custom/
cd custom
git init
git add .
git commit -m "Initial custom code"
```

## Troubleshooting

### Custom Class Not Loading

**Problem:** CustomLoader not finding custom class

**Solution:**
```bash
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

### Constants Not Merging

**Problem:** Custom constants not appearing

**Solution:**
```bash
# Check directory structure
ls -la custom/constants/module_icons/

# File must end in .php
mv custom_icons.txt custom_icons.php

# Test loading
php -r "require 'utils/ConstantsLoader.php'; var_dump(ConstantsLoader::getConstants('module_icons'));"
```

## Next Steps

- Review [Routing System](./05-routing.md)
- Learn about [Controllers & Actions](./09-controllers.md)
- Understand [Database Communication](./06-database.md)
- Explore [Testing](./12-testing.md)

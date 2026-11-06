# Controllers & Actions

Controllers are the heart of the API - they handle HTTP requests, coordinate business logic, and return responses.

## Controller Basics

A controller is a PHP class that processes HTTP requests and returns responses. Each controller method (action) handles a specific endpoint defined in a route file (see [Routing](./05-routing.md)).

Route controllers are instantiated by `RouteManager` (`app/Routes/RouteManager.php`) with `new $route['class'](...)`; every constructor parameter is resolved from the PHP-DI container by its type. They are **not** created through `CustomLoader` — to replace a core controller, redefine its route in `custom/` (see [Extending the API](./08-extending-api.md#example-overriding-a-route-controller)).

### Anatomy of a Controller

```php
<?php

namespace MintHCM\Custom\Api\Controllers;

use Doctrine\ORM\EntityManagerInterface;
use MintHCM\Api\Entities\Employees;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

class EmployeeController
{
    // Dependencies injected via constructor
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    // Action method
    public function list(Request $request, Response $response): Response
    {
        // 1. Get request data (declared in the route, validated by ParamsMiddleware)
        $status = $request->getAttribute('status');

        // 2. Process request
        $employees = $this->entityManager->getRepository(Employees::class)
            ->findBy(['employee_status' => $status], ['last_name' => 'ASC'], 50);

        // 3. Return response (entities must be serialized, see below)
        $data = array_map(fn(Employees $employee) => $employee->getSerialized(), $employees);
        $response->getBody()->write(json_encode(['data' => $data]));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
```

## Request Object

The `Request` object (PSR-7 `ServerRequestInterface`) provides access to all request data.

### Validated Parameters (Request Attributes)

`ParamsMiddleware` validates the request against the `pathParams`, `queryParams` and `bodyParams` declared in the route and stores every declared parameter as a **request attribute** with its parsed value (e.g. `IntType` gives an `int`). This is how the core controllers read input:

```php
public function list(Request $request, Response $response): Response
{
    // GET /api/MyModule/items?page=1&limit=20  (page/limit declared as IntType queryParams)
    $page = $request->getAttribute('page') ?? 1;
    $limit = $request->getAttribute('limit') ?? 20;

    // Use parameters...
}
```

Important:
- A query or body parameter that is not declared in the route causes **400 Bad Request** before the controller runs.
- A missing required parameter or an invalid value also causes **400**.
- Declared but absent optional parameters are `null`.

### Query Parameters

The raw values are still available:

```php
$queryParams = $request->getQueryParams();
$page = $queryParams['page'] ?? 1;   // string, not validated
```

### Request Body

```php
public function create(Request $request, Response $response): Response
{
    // POST /api/...
    // Body: {"first_name": "John", "last_name": "Doe"}
    $firstName = $request->getAttribute('first_name');  // declared bodyParam
    $lastName = $request->getAttribute('last_name');

    // Raw parsed JSON body (parsed by JsonBodyParserMiddleware)
    $data = $request->getParsedBody();

    // Create record...
}
```

### URL Parameters

```php
public function detail(Request $request, Response $response, array $args): Response
{
    // GET /api/MyModule/item/123
    $id = $request->getAttribute('id');  // "123" - declared pathParam
    $id = $args['id'];                   // also available from Slim's route arguments

    // Fetch record...
}
```

### Headers

```php
public function process(Request $request, Response $response): Response
{
    // Get single header
    $contentType = $request->getHeaderLine('Content-Type');
    $authToken = $request->getHeaderLine('Authorization');
    
    // Get all headers
    $headers = $request->getHeaders();
    
    // Check if header exists
    if ($request->hasHeader('X-Custom-Header')) {
        $value = $request->getHeaderLine('X-Custom-Header');
    }
}
```

### Current User and Route Context

The authenticated user is **not** a request attribute. `AuthMiddleware` sets the legacy globals:

```php
public function action(Request $request, Response $response): Response
{
    global $current_user, $api_client;   // User bean; 'frontend', 'mobile' or another client id

    // Matched route (name, pattern, arguments)
    $route = \Slim\Routing\RouteContext::fromRequest($request)->getRoute();

    // Module routes are named "{Module}___{key}" and their pattern starts with "/{Module}"
    $module = explode('/', $route->getPattern())[1] ?? null;
}
```

### HTTP Method

```php
public function handle(Request $request, Response $response): Response
{
    $method = $request->getMethod();  // GET, POST, PUT, DELETE, etc.
    
    if ($method === 'POST') {
        // Handle POST
    } elseif ($method === 'PUT') {
        // Handle PUT
    }
}
```

### URI Information

```php
public function info(Request $request, Response $response): Response
{
    $uri = $request->getUri();
    
    $path = $uri->getPath();        // /api/Employees/Get/123
    $query = $uri->getQuery();      // page=1&limit=20
    $scheme = $uri->getScheme();    // http or https
    $host = $uri->getHost();        // example.com
}
```

## Response Object

The `Response` object is a plain `Slim\Psr7\Response` (PSR-7 `ResponseInterface`). PSR-7 responses are immutable: every `with*()` call returns a new object, so always use the returned value.

### JSON Responses

The response has **no** `withJson()` method. Encode the data, write it to the body and set the header:

```php
public function list(Request $request, Response $response): Response
{
    $data = ['employees' => [/* ... */]];

    $response->getBody()->write(json_encode($data));
    return $response->withHeader('Content-Type', 'application/json');
}
```

With a custom status code:

```php
$response->getBody()->write(json_encode($data));
return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
```

(`utils/MintResponse.php` contains an old `withJson()` helper; it is not autoloadable and not used by the application.)

### Serializing Entities

Doctrine entities extend `MintEntity` and keep their fields in `protected` properties, so `json_encode($entity)` returns `{}`. Use `getSerialized()`:

```php
$response->getBody()->write(json_encode(['data' => $entity->getSerialized()]));
return $response->withHeader('Content-Type', 'application/json');
```

### Status Codes

```php
// Success
$response->getBody()->write(json_encode($data));
return $response->withHeader('Content-Type', 'application/json')->withStatus(201);  // Created

// Client errors
$response->getBody()->write(json_encode(['error' => 'Not found']));
return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
```

Common codes: `400` bad request, `401` unauthorized, `403` forbidden, `404` not found, `409` conflict (duplicates), `422` validation failed (MintLogic).

Alternatively throw a Slim HTTP exception and let `MintExceptionHandler` build the error response (see [Error Handling](./19-error-handling.md)):

```php
use Slim\Exception\HttpNotFoundException;

throw new HttpNotFoundException($request);
```

### Headers

```php
public function action(Request $request, Response $response): Response
{
    $response = $response->withHeader('X-Custom-Header', 'value');
    $response = $response->withHeader('Content-Type', 'application/json');
    
    // Add header to existing
    $response = $response->withAddedHeader('Set-Cookie', 'name=value');
    
    return $response;
}
```

`SecurityHeadersMiddleware` adds `Cache-Control: no-store` to every response that does not set its own `Cache-Control` (e.g. set it explicitly for file downloads).

### Body

```php
public function custom(Request $request, Response $response): Response
{
    // Write to response body
    $response->getBody()->write('Plain text response');
    
    return $response->withHeader('Content-Type', 'text/plain');
}
```

## Common Controller Patterns

The examples below assume a controller with `private EntityManagerInterface $entityManager` injected in the constructor and use the `Employees` entity. Repositories are obtained with `$this->entityManager->getRepository(...)`; they extend `MintEntityRepository` (see [Entities & Repositories](./10-entities-repositories.md)).

### CRUD Operations

#### List (Index)

```php
public function list(Request $request, Response $response): Response
{
    $page = $request->getAttribute('page') ?? 1;     // IntType queryParam
    $limit = $request->getAttribute('limit') ?? 20;  // IntType queryParam

    $repository = $this->entityManager->getRepository(Employees::class);
    $records = $repository->createQueryBuilder('e')
        ->orderBy('e.date_entered', 'DESC')
        ->setFirstResult(($page - 1) * $limit)
        ->setMaxResults($limit)
        ->getQuery()
        ->getResult();

    $response->getBody()->write(json_encode([
        'data' => array_map(fn($record) => $record->getSerialized(), $records),
        'total' => $repository->count([]),
        'page' => $page,
        'limit' => $limit,
    ]));
    return $response->withHeader('Content-Type', 'application/json');
}
```

Deleted rows (`deleted = 1`) are excluded automatically by the global `soft_delete` Doctrine filter.

For module list views the core already provides `MintEntityRepository::getList()` and the `/{Module}/...` list routes (see [List View Panels & Meta](./21-list-view-panels-and-meta.md)).

#### Detail (Show)

```php
public function detail(Request $request, Response $response, array $args): Response
{
    $record = $this->entityManager->getRepository(Employees::class)->find($request->getAttribute('id'));

    if (!$record) {
        $response->getBody()->write(json_encode(['error' => 'Not found']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }
    if (!$record->hasAccess('view')) {
        return $response->withStatus(403);
    }

    $response->getBody()->write(json_encode(['data' => $record->getSerialized()]));
    return $response->withHeader('Content-Type', 'application/json');
}
```

#### Create (Store)

```php
public function create(Request $request, Response $response): Response
{
    $repository = $this->entityManager->getRepository(Employees::class);

    $entity = $repository->getNewEntity();
    $entity->first_name = $request->getAttribute('first_name');  // required StringType bodyParam
    $entity->last_name = $request->getAttribute('last_name');

    // Saves through the legacy SugarBean (logic hooks, ACL-related fields, custom fields)
    $repository->save($entity, true);

    $response->getBody()->write(json_encode(['data' => $entity->getSerialized()]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
}
```

#### Update

```php
public function update(Request $request, Response $response, array $args): Response
{
    $repository = $this->entityManager->getRepository(Employees::class);
    $entity = $repository->find($request->getAttribute('id'));

    if (!$entity) {
        $response->getBody()->write(json_encode(['error' => 'Not found']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }
    if (!$entity->hasAccess('edit')) {
        return $response->withStatus(403);
    }

    $lastName = $request->getAttribute('last_name');
    if ($lastName !== null) {
        $entity->last_name = $lastName;
    }

    $repository->save($entity, true);

    $response->getBody()->write(json_encode(['data' => $entity->getSerialized()]));
    return $response->withHeader('Content-Type', 'application/json');
}
```

#### Delete

```php
public function delete(Request $request, Response $response, array $args): Response
{
    $repository = $this->entityManager->getRepository(Employees::class);
    $entity = $repository->find($request->getAttribute('id'));

    if (!$entity) {
        $response->getBody()->write(json_encode(['error' => 'Not found']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }

    // Legacy mark_deleted() (soft delete), not a SQL DELETE
    $repository->delete($entity);

    $response->getBody()->write(json_encode(['message' => 'Deleted']));
    return $response->withHeader('Content-Type', 'application/json');
}
```

⚠️ Do not use `$entityManager->persist()/flush()` or `remove()` directly for module records: that bypasses the legacy save (logic hooks, related data) and `remove()` deletes the row physically. The core `ModuleController` (`app/Controllers/ModuleController.php`) already implements generic create/update/delete/get routes for every module (`app/Routes/modules/module.php`).

### Search

```php
public function search(Request $request, Response $response): Response
{
    $query = $request->getAttribute('q');

    if (empty($query)) {
        $response->getBody()->write(json_encode(['data' => []]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    $results = $this->entityManager->getRepository(Employees::class)->createQueryBuilder('e')
        ->where('e.first_name LIKE :query')
        ->orWhere('e.last_name LIKE :query')
        ->setParameter('query', '%' . $query . '%')
        ->setMaxResults(50)
        ->getQuery()
        ->getResult();

    $response->getBody()->write(json_encode(['data' => array_map(fn($e) => $e->getSerialized(), $results)]));
    return $response->withHeader('Content-Type', 'application/json');
}
```

For full-text search across modules use the existing global search (`GlobalSearchController`, ElasticSearch).

### Filtering

```php
public function list(Request $request, Response $response): Response
{
    $qb = $this->entityManager->getRepository(Employees::class)->createQueryBuilder('e');

    // Filter by status
    if ($request->getAttribute('status') !== null) {
        $qb->andWhere('e.employee_status = :status')
           ->setParameter('status', $request->getAttribute('status'));
    }

    // Filter by date range
    if ($request->getAttribute('from') !== null) {
        $qb->andWhere('e.date_entered >= :from')
           ->setParameter('from', new \DateTime($request->getAttribute('from')));
    }

    $results = $qb->getQuery()->getResult();

    $response->getBody()->write(json_encode(['data' => array_map(fn($e) => $e->getSerialized(), $results)]));
    return $response->withHeader('Content-Type', 'application/json');
}
```

### Sorting

```php
public function list(Request $request, Response $response): Response
{
    $sortBy = $request->getAttribute('sort') ?? 'date_entered';
    $sortDir = strtoupper($request->getAttribute('dir') ?? 'DESC');

    // Validate - never put user input into DQL unchecked
    $allowedFields = ['first_name', 'last_name', 'date_entered'];
    if (!in_array($sortBy, $allowedFields, true)) {
        $sortBy = 'date_entered';
    }

    if (!in_array($sortDir, ['ASC', 'DESC'], true)) {
        $sortDir = 'DESC';
    }

    $results = $this->entityManager->getRepository(Employees::class)->createQueryBuilder('e')
        ->orderBy("e.{$sortBy}", $sortDir)
        ->getQuery()
        ->getResult();

    $response->getBody()->write(json_encode(['data' => array_map(fn($e) => $e->getSerialized(), $results)]));
    return $response->withHeader('Content-Type', 'application/json');
}
```

## Dependency Injection

Controllers receive dependencies through constructor injection. `RouteManager` asks the PHP-DI container for every constructor parameter type:

- `EntityManagerInterface` is defined explicitly (`data/ORM/Doctrine/DoctrineContainerBuilder.php`).
- Concrete classes whose own dependencies can be resolved are **autowired** (e.g. `DuplicateDetectionService` in `ModuleController`).
- Interfaces and classes that are not defined in the container cannot be injected — e.g. `LoggerInterface`, or repositories (their constructor needs `ClassMetadata`). Get repositories from the entity manager instead.

### Injecting the Entity Manager

```php
use Doctrine\ORM\EntityManagerInterface;
use MintHCM\Api\Entities\Users;

class UserInfoController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function info(Request $request, Response $response): Response
    {
        $repository = $this->entityManager->getRepository(Users::class); // UsersRepository
        // ...
    }
}
```

### Injecting Services

```php
use MintHCM\Custom\Api\Services\EmployeeStatsService;   // custom/app/Services/

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

### Multiple Dependencies

```php
class EmployeeController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DuplicateDetectionService $duplicateDetectionService,
        private EmployeeStatsService $statsService
    ) {
    }
}
```

### Calling Legacy Code

Legacy `BeanFactory` and other SuiteCRM code expect the `legacy/` working directory. Core controllers switch it around such calls:

```php
chdir('../legacy');
$bean = \BeanFactory::getBean('Kudos', $id);
$bean->save(false);
chdir('../api');
```

See [Legacy Integration](./07-legacy-integration.md).

## Error Handling

### Validation Errors

Basic presence and type validation is done by `ParamsMiddleware` from the route definition (400). Business validation of module records belongs in [MintLogic](./13-mintlogic.md) validators — `ModuleController` returns **422** with `{"isValid": false, "error": "ERR_..."}` when they fail. For custom endpoints:

```php
public function create(Request $request, Response $response): Response
{
    $email = $request->getAttribute('email');   // EmailType already validated the format

    $errors = [];
    if ($this->emailIsTaken($email)) {
        $errors['email'] = 'LBL_EMAIL_TAKEN';
    }

    if (!empty($errors)) {
        $response->getBody()->write(json_encode(['errors' => $errors]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(422);
    }

    // Continue with creation...
}
```

### Exception Handling

Let unexpected exceptions propagate: `MintExceptionHandler` logs them and returns a uniform JSON error (details only in developer mode). For expected client errors throw Slim HTTP exceptions:

```php
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpForbiddenException;

public function process(Request $request, Response $response): Response
{
    try {
        $result = $this->service->doSomething();
    } catch (\InvalidArgumentException $e) {
        throw new HttpBadRequestException($request, $e->getMessage());
    }

    $response->getBody()->write(json_encode(['data' => $result]));
    return $response->withHeader('Content-Type', 'application/json');
}
```

See [Error Handling](./19-error-handling.md).

## Best Practices

### 1. Keep Controllers Thin

```php
// ✅ Good - delegate to service
public function process(Request $request, Response $response): Response
{
    $result = $this->service->processEmployee($request->getAttribute('employee_id'));
    $response->getBody()->write(json_encode($result));
    return $response->withHeader('Content-Type', 'application/json');
}

// ❌ Bad - too much logic
public function process(Request $request, Response $response): Response
{
    // 100+ lines of business logic...
}
```

### 2. Use Type Hints

```php
// ✅ Good
public function list(Request $request, Response $response): Response
{
    // ...
}

// ❌ Bad
public function list($request, $response)
{
    // ...
}
```

### 3. Declare and Validate Input

```php
// ✅ Good - parameter declared in the route ('required' => true), read as attribute
$name = $request->getAttribute('name');

// ❌ Bad - undeclared parameter: ParamsMiddleware rejects the request with 400
$data = $request->getParsedBody();
$entity->name = $data['name'];
```

### 4. Return Consistent Response Format

```php
// ✅ Good - consistent structure
$response->getBody()->write(json_encode([
    'data' => $records,
    'meta' => ['total' => $count, 'page' => $page],
]));
return $response->withHeader('Content-Type', 'application/json');

// ❌ Bad - inconsistent: sometimes array, sometimes object
$response->getBody()->write(json_encode($records));
```

### 5. Use Proper HTTP Status Codes

```php
// ✅ Good
return $response->withHeader('Content-Type', 'application/json')->withStatus(201);  // Created
return $response->withHeader('Content-Type', 'application/json')->withStatus(404);  // Not found
return $response->withHeader('Content-Type', 'application/json')->withStatus(400);  // Bad request

// ❌ Bad - error body with status 200
$response->getBody()->write(json_encode(['error' => 'Not found']));
return $response->withHeader('Content-Type', 'application/json');  // Still 200!
```

## Next Steps

- Learn about [Routing System](./05-routing.md)
- Understand [Middlewares](./11-middlewares.md)
- Explore [Database Communication](./06-database.md)
- Review [Testing](./12-testing.md)

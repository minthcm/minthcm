# Controllers & Actions

Controllers are the heart of the API - they handle HTTP requests, coordinate business logic, and return responses.

## Controller Basics

A controller is a PHP class that processes HTTP requests and returns responses. Each controller method (action) handles a specific endpoint.

### Anatomy of a Controller

```php
<?php

namespace MintHCM\Api\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

class EmployeeController
{
    // Dependencies injected via constructor
    private $repository;

    public function __construct(EmployeeRepository $repository)
    {
        $this->repository = $repository;
    }

    // Action method
    public function list(Request $request, Response $response): Response
    {
        // 1. Get request data
        $params = $request->getQueryParams();
        
        // 2. Process request
        $employees = $this->repository->findAll();
        
        // 3. Return response
        return $response->withJson(['data' => $employees]);
    }
}
```

## Request Object

The `Request` object (PSR-7 `ServerRequestInterface`) provides access to all request data.

### Query Parameters

```php
public function list(Request $request, Response $response): Response
{
    // GET /api/employees?page=1&limit=20
    $queryParams = $request->getQueryParams();
    
    $page = $queryParams['page'] ?? 1;
    $limit = $queryParams['limit'] ?? 20;
    
    // Use parameters...
}
```

### Request Body

```php
public function create(Request $request, Response $response): Response
{
    // POST /api/employees
    // Body: {"first_name": "John", "last_name": "Doe"}
    $data = $request->getParsedBody();
    
    $firstName = $data['first_name'] ?? null;
    $lastName = $data['last_name'] ?? null;
    
    // Create employee...
}
```

### URL Parameters

```php
public function detail(Request $request, Response $response, array $args): Response
{
    // GET /api/employees/123
    $id = $args['id'];  // "123"
    
    // Fetch employee...
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

### Request Attributes

Attributes are set by middlewares:

```php
public function action(Request $request, Response $response): Response
{
    // Attributes set by middlewares
    $currentUser = $request->getAttribute('current_user');
    $module = $request->getAttribute('module');
    
    // Use attributes...
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
    
    $path = $uri->getPath();        // /api/employees/123
    $query = $uri->getQuery();      // page=1&limit=20
    $scheme = $uri->getScheme();    // http or https
    $host = $uri->getHost();        // example.com
}
```

## Response Object

The `Response` object (PSR-7 `ResponseInterface`) represents the HTTP response.

### JSON Responses

```php
use MintHCM\Api\utils\MintResponse;

public function list(Request $request, Response $response): Response
{
    $data = ['employees' => [...]];
    
    // Using MintResponse::withJson()
    return $response->withJson($data);
    
    // With custom status code
    return $response->withJson($data, 201);
}
```

### Status Codes

```php
public function create(Request $request, Response $response): Response
{
    // Success
    return $response->withJson($data, 201);  // Created
    
    // Client errors
    return $response->withJson(['error' => 'Not found'], 404);
    return $response->withJson(['error' => 'Unauthorized'], 401);
    return $response->withJson(['error' => 'Forbidden'], 403);
    return $response->withJson(['error' => 'Bad request'], 400);
    
    // Server errors
    return $response->withJson(['error' => 'Internal error'], 500);
}
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

### CRUD Operations

#### List (Index)

```php
public function list(Request $request, Response $response): Response
{
    $queryParams = $request->getQueryParams();
    $page = $queryParams['page'] ?? 1;
    $limit = $queryParams['limit'] ?? 20;
    
    $offset = ($page - 1) * $limit;
    
    $qb = $this->repository->createQueryBuilder('e')
        ->orderBy('e.date_entered', 'DESC')
        ->setFirstResult($offset)
        ->setMaxResults($limit);
    
    $records = $qb->getQuery()->getResult();
    $total = $this->repository->count([]);
    
    return $response->withJson([
        'data' => $records,
        'total' => $total,
        'page' => $page,
        'limit' => $limit,
    ]);
}
```

#### Detail (Show)

```php
public function detail(Request $request, Response $response, array $args): Response
{
    $id = $args['id'];
    
    $record = $this->repository->find($id);
    
    if (!$record) {
        return $response->withJson(['error' => 'Not found'], 404);
    }
    
    return $response->withJson(['data' => $record]);
}
```

#### Create (Store)

```php
public function create(Request $request, Response $response): Response
{
    $data = $request->getParsedBody();
    
    // Validate
    if (empty($data['name'])) {
        return $response->withJson(['error' => 'Name is required'], 400);
    }
    
    // Create entity
    $entity = new Employee();
    $entity->name = $data['name'];
    $entity->email = $data['email'] ?? null;
    $entity->date_entered = new \DateTime();
    
    // Save
    $em = $this->repository->getEntityManager();
    $em->persist($entity);
    $em->flush();
    
    return $response->withJson(['data' => $entity], 201);
}
```

#### Update

```php
public function update(Request $request, Response $response, array $args): Response
{
    $id = $args['id'];
    $data = $request->getParsedBody();
    
    $entity = $this->repository->find($id);
    
    if (!$entity) {
        return $response->withJson(['error' => 'Not found'], 404);
    }
    
    // Update fields
    if (isset($data['name'])) {
        $entity->name = $data['name'];
    }
    if (isset($data['email'])) {
        $entity->email = $data['email'];
    }
    
    $entity->date_modified = new \DateTime();
    
    // Save
    $em = $this->repository->getEntityManager();
    $em->flush();
    
    return $response->withJson(['data' => $entity]);
}
```

#### Delete

```php
public function delete(Request $request, Response $response, array $args): Response
{
    $id = $args['id'];
    
    $entity = $this->repository->find($id);
    
    if (!$entity) {
        return $response->withJson(['error' => 'Not found'], 404);
    }
    
    $em = $this->repository->getEntityManager();
    $em->remove($entity);
    $em->flush();
    
    return $response->withJson(['message' => 'Deleted'], 200);
}
```

### Search

```php
public function search(Request $request, Response $response): Response
{
    $queryParams = $request->getQueryParams();
    $query = $queryParams['q'] ?? '';
    
    if (empty($query)) {
        return $response->withJson(['data' => []]);
    }
    
    $qb = $this->repository->createQueryBuilder('e')
        ->where('e.name LIKE :query')
        ->orWhere('e.email LIKE :query')
        ->setParameter('query', '%' . $query . '%')
        ->setMaxResults(50);
    
    $results = $qb->getQuery()->getResult();
    
    return $response->withJson(['data' => $results]);
}
```

### Filtering

```php
public function list(Request $request, Response $response): Response
{
    $queryParams = $request->getQueryParams();
    
    $qb = $this->repository->createQueryBuilder('e');
    
    // Filter by status
    if (isset($queryParams['status'])) {
        $qb->andWhere('e.status = :status')
           ->setParameter('status', $queryParams['status']);
    }
    
    // Filter by department
    if (isset($queryParams['department'])) {
        $qb->andWhere('e.department_id = :dept')
           ->setParameter('dept', $queryParams['department']);
    }
    
    // Filter by date range
    if (isset($queryParams['from'])) {
        $qb->andWhere('e.date_entered >= :from')
           ->setParameter('from', new \DateTime($queryParams['from']));
    }
    
    $results = $qb->getQuery()->getResult();
    
    return $response->withJson(['data' => $results]);
}
```

### Sorting

```php
public function list(Request $request, Response $response): Response
{
    $queryParams = $request->getQueryParams();
    
    $sortBy = $queryParams['sort'] ?? 'date_entered';
    $sortDir = strtoupper($queryParams['dir'] ?? 'DESC');
    
    // Validate
    $allowedFields = ['name', 'email', 'date_entered'];
    if (!in_array($sortBy, $allowedFields)) {
        $sortBy = 'date_entered';
    }
    
    if (!in_array($sortDir, ['ASC', 'DESC'])) {
        $sortDir = 'DESC';
    }
    
    $qb = $this->repository->createQueryBuilder('e')
        ->orderBy("e.{$sortBy}", $sortDir);
    
    $results = $qb->getQuery()->getResult();
    
    return $response->withJson(['data' => $results]);
}
```

## Dependency Injection

Controllers receive dependencies through constructor injection.

### Injecting Repositories

```php
use MintHCM\Api\Repositories\EmployeesRepository;

class EmployeeController
{
    private $repository;

    public function __construct(EmployeesRepository $repository)
    {
        $this->repository = $repository;
    }
}
```

### Injecting Services

```php
use MintHCM\Lib\Services\EmployeeService;

class EmployeeController
{
    private $service;

    public function __construct(EmployeeService $service)
    {
        $this->service = $service;
    }

    public function calculateBonus(Request $request, Response $response, array $args): Response
    {
        $bonus = $this->service->calculateBonus($args['id']);
        return $response->withJson(['bonus' => $bonus]);
    }
}
```

### Multiple Dependencies

```php
class EmployeeController
{
    private $repository;
    private $logger;
    private $validator;

    public function __construct(
        EmployeesRepository $repository,
        LoggerInterface $logger,
        ValidatorInterface $validator
    ) {
        $this->repository = $repository;
        $this->logger = $logger;
        $this->validator = $validator;
    }
}
```

## Error Handling

### Validation Errors

```php
public function create(Request $request, Response $response): Response
{
    $data = $request->getParsedBody();
    
    $errors = [];
    
    if (empty($data['name'])) {
        $errors['name'] = 'Name is required';
    }
    
    if (empty($data['email'])) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }
    
    if (!empty($errors)) {
        return $response->withJson(['errors' => $errors], 400);
    }
    
    // Continue with creation...
}
```

### Exception Handling

```php
public function process(Request $request, Response $response): Response
{
    try {
        $result = $this->service->doSomething();
        return $response->withJson(['data' => $result]);
    } catch (\InvalidArgumentException $e) {
        return $response->withJson(['error' => $e->getMessage()], 400);
    } catch (\Exception $e) {
        // Log error
        error_log($e->getMessage());
        return $response->withJson(['error' => 'Internal server error'], 500);
    }
}
```

## Best Practices

### 1. Keep Controllers Thin

```php
// ✅ Good - delegate to service
public function process(Request $request, Response $response): Response
{
    $data = $request->getParsedBody();
    $result = $this->service->processEmployee($data);
    return $response->withJson($result);
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

### 3. Validate Input

```php
// ✅ Good
public function create(Request $request, Response $response): Response
{
    $data = $request->getParsedBody();
    
    if (empty($data['name'])) {
        return $response->withJson(['error' => 'Name required'], 400);
    }
    
    // Continue...
}

// ❌ Bad - no validation
public function create(Request $request, Response $response): Response
{
    $data = $request->getParsedBody();
    $entity->name = $data['name'];  // May be undefined!
}
```

### 4. Return Consistent Response Format

```php
// ✅ Good - consistent structure
return $response->withJson([
    'data' => $records,
    'meta' => ['total' => $count, 'page' => $page]
]);

// ❌ Bad - inconsistent
return $response->withJson($records);  // Sometimes array, sometimes object
```

### 5. Use Proper HTTP Status Codes

```php
// ✅ Good
return $response->withJson($data, 201);  // Created
return $response->withJson(['error' => '...'], 404);  // Not found
return $response->withJson(['error' => '...'], 400);  // Bad request

// ❌ Bad - always 200
return $response->withJson(['error' => 'Not found']);  // Still 200!
```

## Next Steps

- Learn about [Routing System](./05-routing.md)
- Understand [Middlewares](./11-middlewares.md)
- Explore [Database Communication](./06-database.md)
- Review [Testing](./12-testing.md)

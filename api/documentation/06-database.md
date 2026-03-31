# Database Communication

The MintHCM API uses **Doctrine ORM** for database operations, providing an object-oriented interface to the MySQL database.

## Overview

**Doctrine ORM** (Object-Relational Mapping) maps PHP objects (Entities) to database tables, allowing you to work with data using objects instead of SQL queries.

### Key Components

```
┌──────────────┐
│  Controller  │
└──────┬───────┘
       │ uses
       ▼
┌──────────────┐
│  Repository  │ ← Query builder, data access
└──────┬───────┘
       │ returns/persists
       ▼
┌──────────────┐
│   Entity     │ ← PHP object representing DB row
└──────┬───────┘
       │ maps to
       ▼
┌──────────────┐
│ Database Row │
└──────────────┘
```

- **Entity**: PHP class representing a database table
- **Repository**: Class for querying and retrieving entities
- **EntityManager**: Doctrine service for persisting/removing entities

## Entities

Entities are PHP classes that map to database tables using annotations.

**Note:** For details on how entities are generated from vardefs, see [Entities & Repositories](./10-entities-repositories.md#entity-generation-from-vardefs).

### Example Entity

**File:** `app/Entities/Employees.php`

```php
<?php

namespace MintHCM\Api\Entities;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;

/**
 * @ORM\Entity(repositoryClass="MintHCM\Api\Repositories\EmployeesRepository")
 * @ORM\Table(name="users")
 */
class Employees
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     * @ORM\Column(type="string", length="36")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length="60")
     */
    public $user_name;

    /**
     * @ORM\Column(type="string", length="255")
     */
    public $first_name;

    /**
     * @ORM\Column(type="string", length="255")
     */
    public $last_name;

    /**
     * @ORM\Column(type="datetime")
     */
    public $date_entered;

    /**
     * @ORM\Column(type="boolean")
     */
    public $is_admin;
}
```

### Entity Annotations

#### Class-Level Annotations

```php
/**
 * @ORM\Entity(repositoryClass="RepositoryClassName")
 * @ORM\Table(name="table_name", indexes={...})
 */
```

- `@ORM\Entity`: Marks class as Doctrine entity
- `repositoryClass`: Custom repository class (optional)
- `@ORM\Table`: Specifies table name and indexes

#### Property Annotations

```php
/**
 * @ORM\Id
 * @ORM\GeneratedValue(strategy="CUSTOM")
 * @ORM\CustomIdGenerator(class=UuidGenerator::class)
 * @ORM\Column(type="string", length="36")
 */
public $id;
```

**Common annotations:**

- `@ORM\Id`: Primary key
- `@ORM\Column`: Maps property to column
  - `type`: Column type (string, integer, datetime, boolean, text, etc.)
  - `length`: Max length (for strings)
  - `nullable`: Allow NULL values
  - `unique`: Unique constraint
- `@ORM\GeneratedValue`: Auto-generate value
- `@ORM\CustomIdGenerator`: Custom ID generator (e.g., UUID)

### Column Types

| Doctrine Type | MySQL Type | PHP Type | Example |
|---------------|------------|----------|---------|
| `string` | VARCHAR | string | `'John'` |
| `text` | TEXT | string | `'Long text...'` |
| `integer` | INT | int | `42` |
| `boolean` | TINYINT(1) | bool | `true` |
| `datetime` | DATETIME | \DateTime | `new \DateTime()` |
| `date` | DATE | \DateTime | `new \DateTime()` |
| `decimal` | DECIMAL | string | `'123.45'` |
| `json` | JSON | array | `['key' => 'value']` |

### Relationships

Doctrine supports associations between entities:

#### One-to-Many

```php
/**
 * @ORM\OneToMany(targetEntity="Document", mappedBy="employee")
 */
public $documents;
```

#### Many-to-One

```php
/**
 * @ORM\ManyToOne(targetEntity="Employee", inversedBy="documents")
 * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
 */
public $employee;
```

#### Many-to-Many

```php
/**
 * @ORM\ManyToMany(targetEntity="Role")
 * @ORM\JoinTable(name="user_roles")
 */
public $roles;
```

## Repositories

Repositories handle data retrieval and custom queries.

### Basic Repository

Every entity can have a repository class:

**File:** `app/Repositories/EmployeesRepository.php`

```php
<?php

namespace MintHCM\Api\Repositories;

use Doctrine\ORM\EntityRepository;

class EmployeesRepository extends EntityRepository
{
    // Custom query methods go here
}
```

### Using Repositories

#### In Controllers (via Dependency Injection)

```php
use MintHCM\Api\Repositories\EmployeesRepository;

class EmployeeController
{
    private $repository;

    public function __construct(EmployeesRepository $repository)
    {
        $this->repository = $repository;
    }

    public function list(Request $request, Response $response): Response
    {
        $employees = $this->repository->findAll();
        return $response->withJson(['data' => $employees]);
    }
}
```

#### Manual Repository Access

```php
global $mint_app;
$container = $mint_app->getContainer();
$entityManager = $container->get(\Doctrine\ORM\EntityManager::class);

$repository = $entityManager->getRepository(\MintHCM\Api\Entities\Employees::class);
```

### Built-In Repository Methods

All repositories inherit these methods from `EntityRepository`:

```php
// Find by primary key
$employee = $repository->find($id);

// Find all records
$employees = $repository->findAll();

// Find by criteria
$employees = $repository->findBy(['is_admin' => true]);
$employee = $repository->findOneBy(['user_name' => 'admin']);

// Count records
$count = $repository->count(['is_admin' => true]);
```

### Custom Repository Methods

Add custom query methods to repositories:

```php
class EmployeesRepository extends EntityRepository
{
    public function findActiveEmployees(): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.status = :status')
            ->setParameter('status', 'Active')
            ->orderBy('e.last_name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByDepartment(string $deptId): array
    {
        return $this->createQueryBuilder('e')
            ->join('e.department', 'd')
            ->where('d.id = :deptId')
            ->setParameter('deptId', $deptId)
            ->getQuery()
            ->getResult();
    }

    public function countAdmins(): int
    {
        return (int) $this->createQueryBuilder('e')
            ->select('COUNT(e.id)')
            ->where('e.is_admin = :isAdmin')
            ->setParameter('isAdmin', true)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
```

## Query Builder

The Query Builder provides a fluent interface for building queries.

### Basic Query Builder

```php
$qb = $repository->createQueryBuilder('e');  // 'e' is the alias

$qb->where('e.is_admin = :admin')
   ->setParameter('admin', true)
   ->orderBy('e.last_name', 'ASC')
   ->setMaxResults(10);

$results = $qb->getQuery()->getResult();
```

### Common Methods

#### Selection

```php
// Select all fields (default)
$qb->select('e');

// Select specific fields
$qb->select('e.id, e.first_name, e.last_name');

// Count
$qb->select('COUNT(e.id)');
```

#### Filtering

```php
// WHERE
$qb->where('e.status = :status')
   ->setParameter('status', 'Active');

// AND
$qb->where('e.status = :status')
   ->andWhere('e.is_admin = :admin')
   ->setParameter('status', 'Active')
   ->setParameter('admin', true);

// OR
$qb->where('e.is_admin = :admin')
   ->orWhere('e.status = :status')
   ->setParameter('admin', true)
   ->setParameter('status', 'Active');

// IN
$qb->where($qb->expr()->in('e.id', [1, 2, 3]));

// LIKE
$qb->where('e.first_name LIKE :name')
   ->setParameter('name', '%John%');
```

#### Joining

```php
// INNER JOIN
$qb->join('e.department', 'd')
   ->where('d.name = :dept')
   ->setParameter('dept', 'HR');

// LEFT JOIN
$qb->leftJoin('e.documents', 'doc');
```

#### Ordering

```php
$qb->orderBy('e.last_name', 'ASC');
$qb->addOrderBy('e.first_name', 'ASC');
```

#### Pagination

```php
$qb->setFirstResult(0)     // Offset
   ->setMaxResults(20);    // Limit
```

#### Grouping

```php
$qb->groupBy('e.department_id');
$qb->having('COUNT(e.id) > 10');
```

### Executing Queries

```php
// Get all results
$results = $qb->getQuery()->getResult();

// Get single result (throws exception if not found)
$result = $qb->getQuery()->getSingleResult();

// Get single result or null
$result = $qb->getQuery()->getOneOrNullResult();

// Get array result
$results = $qb->getQuery()->getArrayResult();

// Get scalar result (for COUNT, SUM, etc.)
$count = $qb->getQuery()->getSingleScalarResult();
```

## EntityManager

The EntityManager handles entity persistence (create, update, delete).

### Getting EntityManager

```php
global $mint_app;
$container = $mint_app->getContainer();
$em = $container->get(\Doctrine\ORM\EntityManager::class);
```

### Creating Records

```php
use MintHCM\Api\Entities\Employees;

$employee = new Employees();
$employee->user_name = 'jdoe';
$employee->first_name = 'John';
$employee->last_name = 'Doe';
$employee->is_admin = false;
$employee->date_entered = new \DateTime();

$em->persist($employee);  // Mark for insert
$em->flush();             // Execute INSERT
```

### Updating Records

```php
$employee = $repository->find($id);

$employee->first_name = 'Jane';
$employee->last_name = 'Smith';

$em->flush();  // Execute UPDATE (no need to call persist)
```

### Deleting Records

```php
$employee = $repository->find($id);

$em->remove($employee);  // Mark for delete
$em->flush();            // Execute DELETE
```

### Transactions

```php
$em->beginTransaction();

try {
    $employee = new Employees();
    $employee->user_name = 'jdoe';
    $em->persist($employee);
    
    // More operations...
    
    $em->flush();
    $em->commit();
} catch (\Exception $e) {
    $em->rollback();
    throw $e;
}
```

## DQL (Doctrine Query Language)

DQL is an SQL-like language for querying entities.

### Basic DQL

```php
$dql = "SELECT e FROM MintHCM\Api\Entities\Employees e WHERE e.is_admin = :admin";
$query = $em->createQuery($dql);
$query->setParameter('admin', true);
$results = $query->getResult();
```

### Complex DQL

```php
$dql = "
    SELECT e, d
    FROM MintHCM\Api\Entities\Employees e
    JOIN e.department d
    WHERE d.name = :dept
    AND e.status = :status
    ORDER BY e.last_name ASC
";

$query = $em->createQuery($dql);
$query->setParameter('dept', 'HR');
$query->setParameter('status', 'Active');
$results = $query->getResult();
```

## Native SQL Queries

For complex queries, you can use native SQL:

```php
$sql = "
    SELECT u.id, u.first_name, u.last_name
    FROM users u
    WHERE u.is_admin = 1
";

$stmt = $em->getConnection()->executeQuery($sql);
$results = $stmt->fetchAllAssociative();
```

## Best Practices

### 1. Use Repositories for Queries

```php
// ✅ Good - encapsulated in repository
$employees = $repository->findActiveEmployees();

// ❌ Bad - query logic in controller
$employees = $em->createQuery("SELECT e FROM ...")->getResult();
```

### 2. Always Use Parameters

```php
// ✅ Good - prevents SQL injection
$qb->where('e.id = :id')->setParameter('id', $id);

// ❌ Bad - vulnerable to SQL injection
$qb->where("e.id = " . $id);
```

### 3. Batch Operations

```php
// ✅ Good - batch flush
foreach ($employees as $employee) {
    $employee->status = 'Active';
    $em->persist($employee);
    
    if (++$i % 20 === 0) {
        $em->flush();
        $em->clear();
    }
}
$em->flush();

// ❌ Bad - flush in loop
foreach ($employees as $employee) {
    $employee->status = 'Active';
    $em->persist($employee);
    $em->flush();  // Slow!
}
```

### 4. Clear EntityManager for Large Operations

```php
$em->clear();  // Detach all entities from memory
```

### 5. Use Query Result Caching

```php
$query->useResultCache(true, 3600, 'my_cache_key');
```

## Common Patterns

### Pattern: Pagination

```php
public function getPaginatedEmployees(int $page = 1, int $limit = 20): array
{
    $offset = ($page - 1) * $limit;
    
    $qb = $this->createQueryBuilder('e')
        ->orderBy('e.last_name', 'ASC')
        ->setFirstResult($offset)
        ->setMaxResults($limit);
    
    return [
        'data' => $qb->getQuery()->getResult(),
        'total' => $this->count([]),
        'page' => $page,
        'limit' => $limit,
    ];
}
```

### Pattern: Search

```php
public function search(string $query): array
{
    return $this->createQueryBuilder('e')
        ->where('e.first_name LIKE :query')
        ->orWhere('e.last_name LIKE :query')
        ->orWhere('e.user_name LIKE :query')
        ->setParameter('query', '%' . $query . '%')
        ->getQuery()
        ->getResult();
}
```

### Pattern: Soft Delete

```php
// Entity
/**
 * @ORM\Column(type="boolean")
 */
public $deleted = false;

// Repository
public function findActive(): array
{
    return $this->findBy(['deleted' => false]);
}

// Delete method
public function softDelete($id): void
{
    $employee = $this->find($id);
    $employee->deleted = true;
    $this->em->flush();
}
```

## Troubleshooting

### Entity Not Found

```bash
# Clear Doctrine cache
rm -rf cache/doctrine/*
```

### Entity Out of Sync with Database

If your entity doesn't reflect recent database changes:

1. **Run Quick Repair and Rebuild** in Admin Panel
   - Navigate to: **Admin** → **Repair** → **Quick Repair and Rebuild**
   - Click "Execute" to regenerate entities from current vardefs
   - See [Entities & Repositories](./10-entities-repositories.md#entity-generation-from-vardefs) for details
   
2. **Clear Doctrine cache**
   ```bash
   rm -rf cache/doctrine/*
   ```

3. **Regenerate Doctrine proxies**
   ```bash
   vendor/bin/doctrine orm:generate-proxies
   ```

### Custom Entity Changes Lost After Rebuild

**Problem:** Your custom entity modifications disappeared after Quick Repair

**Solution:** Never modify auto-generated entities directly. Instead, create custom entity extensions.

See [Extending Entities](./10-entities-repositories.md#extending-entities-and-repositories) for the complete guide on safely customizing entities via the `custom/` directory.

### Metadata Errors

```bash
# Regenerate Doctrine proxies
vendor/bin/doctrine orm:generate-proxies
```

### Query Performance

```php
// Enable SQL logging (development only)
$em->getConnection()->getConfiguration()->setSQLLogger(
    new \Doctrine\DBAL\Logging\EchoSQLLogger()
);
```

## Next Steps

- Learn about [Entities & Repositories](./10-entities-repositories.md)
- Understand [Controllers & Actions](./09-controllers.md)
- Explore [Legacy Integration](./07-legacy-integration.md)

# Entities & Repositories

Entities and Repositories form the data layer of the MintHCM API, using Doctrine ORM to interact with the database.

## Entities

Entities are PHP classes that represent database tables. Each property maps to a column.

### Entity Generation from Vardefs

**Important:** Entity classes in MintHCM are **automatically generated** from vardefs (variable definitions) during the "Quick Repair and Rebuild" process in the Administration Panel.

> **Entities are NOT part of the repository.** As of the entity-generation rework, `api/app/Entities/*.php` files are **git-ignored** (see `.gitignore`) and produced locally during Quick Repair / build. Do not commit them. Reusable, hand-written entity behaviour now lives in **Traits** under `api/app/EntityTraits/` (see [Entity Traits](#entity-traits) below), which *are* versioned and referenced from vardefs. The only entity file still tracked is the OAuth2 entity.

**How it works:**

1. **Vardefs** define field structures in legacy MintHCM modules (located in `modules/{ModuleName}/vardefs.php`)
2. Navigate to **Admin Panel** → **Repair** → **Quick Repair and Rebuild**
3. The system scans all module vardefs
4. **Entity Creator** automatically generates Doctrine Entity classes in `api/app/Entities/`
5. Each module's vardefs are converted to Doctrine annotations, and any Traits declared in the vardefs are wired in via `use` statements

**Example vardefs to Entity conversion:**

```php
// Legacy: modules/Employees/vardefs.php
$dictionary['Employee']['fields']['first_name'] = [
    'name' => 'first_name',
    'type' => 'varchar',
    'len' => 255,
];

// Generated: api/app/Entities/Employees.php
/**
 * @ORM\Column(type="string", length="255")
 */
public $first_name;
```

**When to regenerate entities:**
- After adding/modifying fields in Studio
- After changing vardefs manually
- After installing/upgrading modules
- When database schema changes

**Important notes:**
- ⚠️ **Do not manually edit generated entity files** - they will be overwritten during next rebuild
- ✅ To customize entities, use the `custom/` directory (see below)
- ✅ Always run Quick Repair after schema changes to keep entities in sync

### Entity Structure

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

    // Helper methods
    public function getFullName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
```

### Annotations Reference

#### @ORM\Entity

Marks the class as a Doctrine entity.

```php
/**
 * @ORM\Entity
 */
class SimpleEntity { }

/**
 * @ORM\Entity(repositoryClass="MyRepository")
 */
class EntityWithRepo { }
```

#### @ORM\Table

Specifies the database table.

```php
/**
 * @ORM\Table(name="users")
 */

/**
 * @ORM\Table(
 *     name="users",
 *     indexes={
 *         @ORM\Index(name="idx_name", columns={"first_name", "last_name"}),
 *         @ORM\Index(name="idx_email", columns={"email"})
 *     }
 * )
 */
```

#### @ORM\Id and @ORM\Column

Define the primary key and columns.

```php
/**
 * @ORM\Id
 * @ORM\Column(type="string", length="36")
 */
public $id;

/**
 * @ORM\Column(type="string", length="255", nullable=true)
 */
public $optional_field;

/**
 * @ORM\Column(type="integer", options={"default": 0})
 */
public $count;

/**
 * @ORM\Column(type="text")
 */
public $description;

/**
 * @ORM\Column(type="boolean")
 */
public $is_active;

/**
 * @ORM\Column(type="datetime")
 */
public $created_at;

/**
 * @ORM\Column(type="decimal", precision=10, scale=2)
 */
public $price;
```

### Relationships

#### One-to-Many

One employee has many documents:

```php
// Employee.php
/**
 * @ORM\OneToMany(targetEntity="Document", mappedBy="employee")
 */
public $documents;

public function __construct()
{
    $this->documents = new ArrayCollection();
}

// Document.php
/**
 * @ORM\ManyToOne(targetEntity="Employee", inversedBy="documents")
 * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
 */
public $employee;
```

#### Many-to-One

Many employees belong to one department:

```php
// Employee.php
/**
 * @ORM\ManyToOne(targetEntity="Department", inversedBy="employees")
 * @ORM\JoinColumn(name="department_id", referencedColumnName="id")
 */
public $department;

// Department.php
/**
 * @ORM\OneToMany(targetEntity="Employee", mappedBy="department")
 */
public $employees;

public function __construct()
{
    $this->employees = new ArrayCollection();
}
```

#### Many-to-Many

Employees can have multiple roles, roles can have multiple employees:

```php
// Employee.php
/**
 * @ORM\ManyToMany(targetEntity="Role", inversedBy="employees")
 * @ORM\JoinTable(
 *     name="employee_roles",
 *     joinColumns={@ORM\JoinColumn(name="employee_id", referencedColumnName="id")},
 *     inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
 * )
 */
public $roles;

public function __construct()
{
    $this->roles = new ArrayCollection();
}

// Role.php
/**
 * @ORM\ManyToMany(targetEntity="Employee", mappedBy="roles")
 */
public $employees;

public function __construct()
{
    $this->employees = new ArrayCollection();
}
```

### Entity Methods

Entities can have helper methods:

```php
class Employee
{
    public function getFullName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }

    public function getAge(): ?int
    {
        if (!$this->birth_date) {
            return null;
        }
        return $this->birth_date->diff(new \DateTime())->y;
    }

    public function addDocument(Document $document): void
    {
        if (!$this->documents->contains($document)) {
            $this->documents->add($document);
            $document->employee = $this;
        }
    }
}
```

## Repositories

Repositories handle data retrieval and custom queries.

### Basic Repository

```php
<?php

namespace MintHCM\Api\Repositories;

use Doctrine\ORM\EntityRepository;

class EmployeesRepository extends EntityRepository
{
    // Custom methods go here
}
```

### Built-In Methods

All repositories inherit these from `EntityRepository`:

```php
// Find by ID
$employee = $repository->find($id);

// Find all
$employees = $repository->findAll();

// Find by criteria
$active = $repository->findBy(['status' => 'Active']);
$admin = $repository->findOneBy(['is_admin' => true]);

// Count
$total = $repository->count([]);
$activeCount = $repository->count(['status' => 'Active']);
```

### Custom Query Methods

Add methods for complex queries:

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
            ->where('e.department_id = :deptId')
            ->setParameter('deptId', $deptId)
            ->orderBy('e.last_name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function searchByName(string $query): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.first_name LIKE :query')
            ->orWhere('e.last_name LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->setMaxResults(50)
            ->getQuery()
            ->getResult();
    }

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

    public function getTopPerformers(int $limit = 10): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.performance_score >= :threshold')
            ->setParameter('threshold', 90)
            ->orderBy('e.performance_score', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function countByStatus(): array
    {
        $results = $this->createQueryBuilder('e')
            ->select('e.status, COUNT(e.id) as count')
            ->groupBy('e.status')
            ->getQuery()
            ->getResult();

        $formatted = [];
        foreach ($results as $result) {
            $formatted[$result['status']] = $result['count'];
        }

        return $formatted;
    }
}
```

### Joining Related Entities

```php
public function findWithDepartments(): array
{
    return $this->createQueryBuilder('e')
        ->select('e, d')
        ->join('e.department', 'd')
        ->getQuery()
        ->getResult();
}

public function findByDepartmentName(string $deptName): array
{
    return $this->createQueryBuilder('e')
        ->join('e.department', 'd')
        ->where('d.name = :name')
        ->setParameter('name', $deptName)
        ->getQuery()
        ->getResult();
}
```

### Aggregations

```php
public function getTotalSalary(): float
{
    return (float) $this->createQueryBuilder('e')
        ->select('SUM(e.salary)')
        ->getQuery()
        ->getSingleScalarResult();
}

public function getAverageSalaryByDepartment(): array
{
    return $this->createQueryBuilder('e')
        ->select('d.name, AVG(e.salary) as avg_salary')
        ->join('e.department', 'd')
        ->groupBy('d.id')
        ->getQuery()
        ->getResult();
}
```

### Complex Queries

```php
public function findByCriteria(array $criteria): array
{
    $qb = $this->createQueryBuilder('e');

    if (!empty($criteria['status'])) {
        $qb->andWhere('e.status = :status')
           ->setParameter('status', $criteria['status']);
    }

    if (!empty($criteria['department_id'])) {
        $qb->andWhere('e.department_id = :dept')
           ->setParameter('dept', $criteria['department_id']);
    }

    if (!empty($criteria['hired_after'])) {
        $qb->andWhere('e.hire_date >= :hired_after')
           ->setParameter('hired_after', new \DateTime($criteria['hired_after']));
    }

    if (!empty($criteria['search'])) {
        $qb->andWhere(
            $qb->expr()->orX(
                $qb->expr()->like('e.first_name', ':search'),
                $qb->expr()->like('e.last_name', ':search'),
                $qb->expr()->like('e.email', ':search')
            )
        )->setParameter('search', '%' . $criteria['search'] . '%');
    }

    if (!empty($criteria['sort'])) {
        $qb->orderBy('e.' . $criteria['sort'], $criteria['dir'] ?? 'ASC');
    }

    if (!empty($criteria['limit'])) {
        $qb->setMaxResults($criteria['limit']);
    }

    return $qb->getQuery()->getResult();
}
```

## Using EntityManager

The EntityManager handles entity persistence.

### Creating Entities

```php
use MintHCM\Api\Entities\Employee;

public function createEmployee(array $data)
{
    $employee = new Employee();
    $employee->first_name = $data['first_name'];
    $employee->last_name = $data['last_name'];
    $employee->email = $data['email'];
    $employee->date_entered = new \DateTime();

    $em = $this->getEntityManager();
    $em->persist($employee);
    $em->flush();

    return $employee;
}
```

### Updating Entities

```php
public function updateEmployee(string $id, array $data)
{
    $employee = $this->find($id);

    if (!$employee) {
        throw new \Exception('Employee not found');
    }

    if (isset($data['first_name'])) {
        $employee->first_name = $data['first_name'];
    }

    if (isset($data['last_name'])) {
        $employee->last_name = $data['last_name'];
    }

    $employee->date_modified = new \DateTime();

    $this->getEntityManager()->flush();

    return $employee;
}
```

### Deleting Entities

```php
public function deleteEmployee(string $id): void
{
    $employee = $this->find($id);

    if (!$employee) {
        throw new \Exception('Employee not found');
    }

    $em = $this->getEntityManager();
    $em->remove($employee);
    $em->flush();
}
```

### Soft Delete

```php
public function softDelete(string $id): void
{
    $employee = $this->find($id);

    if (!$employee) {
        throw new \Exception('Employee not found');
    }

    $employee->deleted = true;
    $employee->date_deleted = new \DateTime();

    $this->getEntityManager()->flush();
}

public function findActive(): array
{
    return $this->findBy(['deleted' => false]);
}
```

## Extending Entities and Repositories

Because the generated `api/app/Entities/*.php` files are git-ignored and regenerated on every build, **hand-written code must not live directly in an entity file** — it would not be versioned and would be lost. Reusable entity behaviour is instead placed in **Traits** (`api/app/EntityTraits/`) and referenced from the module's vardefs. New database-mapped properties still go through the `custom/` directory (see [Extending via Custom Directory](#extending-via-custom-directory)).

### Entity Traits

Traits hold hand-written, reusable methods that the Entity Creator wires into the generated entity. Unlike inline entity code, Traits are committed to the repository and survive regeneration.

**How it works:**

1. Create a trait in `api/app/EntityTraits/` under the `MintHCM\Api\EntityTraits` namespace. Name it `{ShortName}Trait` (e.g. `UsersTrait`, `PersonTrait`).
2. Declare which traits a module uses in its vardefs under `doctrineEntity.traits`, using the **short name** (without the `Trait` suffix).
3. On Quick Repair, the Entity Creator resolves each short name via `TraitNameResolver` (appends `Trait`, prepends the namespace), adds the `use` statement, and emits `use {Trait};` inside the generated class.

**Declaring traits in vardefs:**

```php
// legacy/modules/Users/vardefs.php
$dictionary['User'] = array(
    'table' => 'users',
    'doctrineEntity' => array(
        'repository' => 'UsersRepository',
        'traits' => array('Users'),   // -> MintHCM\Api\EntityTraits\UsersTrait
    ),
    // ...
);
```

**Defining a trait:**

```php
// api/app/EntityTraits/UsersTrait.php
namespace MintHCM\Api\EntityTraits;

trait UsersTrait
{
    public function getIdentifier(): string
    {
        return $this->id;
    }

    public function checkPassword(string $password): bool
    {
        // ...
    }
}
```

**The `person` template shortcut:** modules whose vardefs include `'templates' => array('person', ...)` automatically receive `PersonTrait` (providing `getFullName()`, `getName()`, `getEmail1()`, `getSerialized()`) — no explicit `traits` entry is needed. When `PersonTrait` is applied, the Entity Creator skips generating its own `getEmail1()`/`getSerialized()` to avoid duplicate declarations.

**Note on Employees:** the `Employee` module reuses the `User` vardefs but overrides `doctrineEntity` to an empty array, so the `Employees` entity does not inherit the `UsersRepository` or `UsersTrait`.

### Understanding Protected Sections

The Entity Creator marks auto-generated sections with special comments:

```php
// Auto-generated SectionUse section start
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
// Auto-generated SectionUse section end

// Auto-generated SectionProperties section start
/**
 * @ORM\Column(type="string", length="255")
 */
public $first_name;
// Auto-generated SectionProperties section end

// Auto-generated SectionMethods section start
public function __construct()
{
    $this->documents = new ArrayCollection();
}
// Auto-generated SectionMethods section end
```

**Protected sections:**
- `// Auto-generated Section{Name} section start` to `// Auto-generated Section{Name} section end`
- Everything **inside** these sections will be regenerated
- Everything **outside** these sections is safe to modify

### Adding Custom Code Directly to Generated Entities

> ⚠️ **Discouraged.** Since generated entity files are git-ignored and rebuilt from scratch, code added directly to a `*.php` entity file (even outside the auto-generated sections) is **not versioned and is lost on regeneration**. Use an [Entity Trait](#entity-traits) for reusable methods, or the [custom directory](#extending-via-custom-directory) for new mapped properties. The pattern below is retained only to explain the protected-section markers the generator emits.

You can technically place code **outside** the auto-generated sections, but prefer Traits:

**File:** `app/Entities/Users.php`

```php
<?php
// Generated by the Entity Creator

namespace MintHCM\Api\Entities;

// Auto-generated SectionUse section start
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
// Auto-generated SectionUse section end

// ✅ SAFE: Add custom imports here
use App\Utils\PasswordHasher;

/**
 * @ORM\Entity(repositoryClass="MintHCM\Api\Repositories\UsersRepository")
 * @ORM\Table(name="users")
 */
class Users
{
    // Auto-generated SectionProperties section start
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length="36")
     */
    public $id;
    
    public $first_name;
    public $last_name;
    // Auto-generated SectionProperties section end
    
    // ✅ SAFE: Add custom properties here
    private $cached_full_name;

    // Auto-generated SectionMethods section start
    public function __construct()
    {
        // Auto-generated initialization
    }
    // Auto-generated SectionMethods section end
    
    // ✅ SAFE: Add custom methods here
    /**
     * Get the full name with caching
     */
    public function getFullName(): string
    {
        if ($this->cached_full_name === null) {
            $names = [];
            if (!empty($this->first_name)) {
                $names[] = $this->first_name;
            }
            if (!empty($this->last_name)) {
                $names[] = $this->last_name;
            }
            $this->cached_full_name = implode(' ', $names);
        }
        
        return $this->cached_full_name;
    }
    
    /**
     * Check if user is administrator
     */
    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }
    
    /**
     * Hash and set password
     */
    public function setPassword(string $plainPassword): void
    {
        $this->user_hash = PasswordHasher::hash($plainPassword);
        $this->pwd_last_changed = new \DateTime();
    }
}
```

### Example: Real Users Entity with Custom Methods

Methods such as `getIdentifier()`, `getFullName()` and `checkPassword()` used to be added directly to `app/Entities/Users.php`. They now live in `api/app/EntityTraits/UsersTrait.php` and `PersonTrait.php`, and the generator emits `use UsersTrait;` / `use PersonTrait;` into the `Users` entity based on its vardefs. The equivalent generated result looks like:

```php
// Auto-generated SectionMethods section start
public function __construct()
{
    $this->reports_to_link = new ArrayCollection();
    $this->email_addresses = new ArrayCollection();
    // ... other collections
}

public function getIdentifier(): string
{
    return $this->id;
}
// Auto-generated SectionMethods section end

// ✅ Custom methods added here are safe from regeneration:

/**
 * Get the fullname 
 *
 * @return string
 */
public function getFullName(): string
{
    $names = [];
    if (!empty($this->first_name)) {
        $names[] = $this->first_name;
    }
    if (!empty($this->last_name)) {
        $names[] = $this->last_name;
    }

    return !empty($names) ? implode(' ', $names) : '';
}

/**
 * Check that password matches existing hash
 * @param string $password Plaintext password
 */
public function checkPassword(string $password): bool
{
    if (empty($this->user_hash)) {
        return false;
    }

    $passwordMd5 = md5($password);
    if ($this->user_hash[0] !== '$' && strlen($this->user_hash) === 32) {
        // Legacy md5 password
        return strtolower($passwordMd5) === $this->user_hash;
    }

    return password_verify(strtolower($passwordMd5), $this->user_hash);
}
```

### When to Use a Trait vs Custom Directory

**Use an Entity Trait (`api/app/EntityTraits/`, declared in vardefs):**
- ✅ Adding helper methods (getFullName, isActive, etc.)
- ✅ Adding business logic methods
- ✅ Sharing behaviour across several entities (e.g. `PersonTrait`)
- ✅ Any hand-written method that must survive regeneration and be versioned

**Use custom directory extension:**
- ✅ Adding new Doctrine-mapped properties (@ORM\Column)
- ✅ Adding new relationships (@ORM\ManyToOne, etc.)
- ✅ When you need to override existing auto-generated properties
- ✅ For complex customizations that require namespace separation

### Extending via Custom Directory

For adding new database-mapped properties, use the `custom/` directory:

**File:** `custom/app/Entities/Users.php`

```php
<?php

namespace MintHCM\Custom\Api\Entities;

use MintHCM\Api\Entities\Users as BaseUsers;
use Doctrine\ORM\Mapping as ORM;

class Users extends BaseUsers
{
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    public $custom_field;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    public $bonus_amount;

    public function getTotalCompensation(): float
    {
        return ($this->salary ?? 0) + ($this->bonus_amount ?? 0);
    }
}
```

**Using the custom entity:**

```php
use MintHCM\Utils\CustomLoader;
use MintHCM\Api\Entities\Users;

// CustomLoader automatically loads MintHCM\Custom\Api\Entities\Users if it exists
$user = CustomLoader::getObject(Users::class);
```

### Best Practices for Entity Extension

```php
// ✅ GOOD: Add methods outside auto-generated sections
class Users
{
    // Auto-generated SectionMethods section start
    public function __construct() { }
    // Auto-generated SectionMethods section end
    
    // Safe custom method
    public function getDisplayName(): string
    {
        return $this->getFullName() ?: $this->user_name;
    }
}

// ❌ BAD: Modifying inside auto-generated sections
class Users
{
    // Auto-generated SectionProperties section start
    public $first_name;
    public $custom_field;  // ❌ Will be removed on regeneration!
    // Auto-generated SectionProperties section end
}

// ✅ GOOD: Adding new properties via custom directory
// custom/app/Entities/Users.php
class Users extends BaseUsers
{
    /**
     * @ORM\Column(type="string", length=100)
     */
    public $custom_field;  // ✅ Safe in custom directory
}
```

### Protected Section Reference

The Entity Creator uses these protected sections:

| Section Name | Purpose | Safe to Edit After? |
|-------------|---------|---------------------|
| `SectionUse` | Import statements (use ...) | Outside only |
| `SectionRepository` | @ORM\Entity and @ORM\Table annotations | Outside only |
| `SectionProperties` | Property declarations (@ORM\Column, etc.) | Outside only |
| `SectionMethods` | Constructor and auto-generated methods | Outside only |

**Rule of thumb:** 
- Inside `// Auto-generated Section...` comments = ❌ Will be overwritten
- Outside `// Auto-generated Section...` comments = ✅ Safe to modify

### Extending a Repository

**File:** `custom/app/Repositories/EmployeesRepository.php`

```php
<?php

namespace MintHCM\Custom\Api\Repositories;

use MintHCM\Api\Repositories\EmployeesRepository as BaseRepository;

class EmployeesRepository extends BaseRepository
{
    public function findEligibleForBonus(): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.performance_score >= :threshold')
            ->andWhere('e.bonus_amount IS NULL')
            ->setParameter('threshold', 80)
            ->getQuery()
            ->getResult();
    }

    public function calculateAverageBonus(): float
    {
        return (float) $this->createQueryBuilder('e')
            ->select('AVG(e.bonus_amount)')
            ->where('e.bonus_amount IS NOT NULL')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
```

## Best Practices

### 1. Keep Entities Simple

```php
// ✅ Good - entity is data structure + simple methods
class Employee
{
    public $id;
    public $name;
    
    public function getFullName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}

// ❌ Bad - business logic in entity
class Employee
{
    public function sendWelcomeEmail(): void
    {
        // Complex email sending logic...
    }
}
```

### 2. Complex Logic in Repositories

```php
// ✅ Good - complex queries in repository
public function findByCriteria(array $criteria): array
{
    // Complex query builder logic
}

// ❌ Bad - complex queries in controller
$employees = $em->createQuery("...")->getResult();
```

### 3. Use Type Hints

```php
// ✅ Good
public function findActiveEmployees(): array
{
    return $this->findBy(['status' => 'Active']);
}

// ❌ Bad
public function findActiveEmployees()
{
    return $this->findBy(['status' => 'Active']);
}
```

### 4. Consistent Naming

```php
// ✅ Good - clear, descriptive names
public function findActiveEmployees(): array
public function countByDepartment(string $deptId): int
public function getTopPerformers(int $limit): array

// ❌ Bad - unclear names
public function getStuff(): array
public function doQuery(): array
```

### 5. Optimize Queries

```php
// ✅ Good - single query with join
public function findWithDepartments(): array
{
    return $this->createQueryBuilder('e')
        ->select('e, d')
        ->join('e.department', 'd')
        ->getQuery()
        ->getResult();
}

// ❌ Bad - N+1 queries
$employees = $this->findAll();
foreach ($employees as $employee) {
    $department = $employee->department;  // Separate query!
}
```

## Next Steps

- Learn about [Database Communication](./06-database.md)
- Understand [Controllers & Actions](./09-controllers.md)
- Explore [Extending the API](./08-extending-api.md)

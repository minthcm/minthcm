# Entities & Repositories

Entities and Repositories form the data layer of the MintHCM API, using Doctrine ORM to interact with the database.

## Entities

Entities are PHP classes that represent database tables. Each property maps to a column.

### Entity Generation from Vardefs

**Important:** Entity classes in MintHCM are **automatically generated** from vardefs (variable definitions) during the "Quick Repair and Rebuild" process in the Administration Panel.

> **Entities are NOT part of the repository.** As of the entity-generation rework, `api/app/Entities/*.php` files are **git-ignored** (see `.gitignore`) and produced locally during Quick Repair / build. Do not commit them. Reusable, hand-written entity behaviour now lives in **Traits** under `api/app/EntityTraits/` (see [Entity Traits](#entity-traits) below), which *are* versioned and referenced from vardefs. The only entity files still tracked are `app/Entities/OAuth2/*`, `Schedulers.php` and `UsersPasswordLink.php` (see the exceptions in `.gitignore`).

**How it works:**

1. **Vardefs** define field structures in legacy MintHCM modules (located in `legacy/modules/{ModuleName}/vardefs.php`)
2. Navigate to **Admin Panel** → **Repair** → **Quick Repair and Rebuild** (`legacy/modules/Administration/QuickRepairAndRebuild.php` calls `EntityCreatorManager::createEntities()`)
3. The system loads the vardefs of all modules in `$beanList` and generates an entity **only for dictionaries that contain a `doctrineEntity` key**
4. **Entity Creator** (`legacy/include/EntityCreator/`) generates Doctrine Entity classes in `api/app/Entities/{Module}.php`
5. Each module's vardefs are converted to Doctrine annotations, and any Traits declared in the vardefs are wired in via `use` statements
6. If the module has a `_cstm` table with custom fields (`'source' => 'custom_fields'`, e.g. added in Studio), an additional `{Module}_cstm` entity is generated in `api/app/Entities/{Module}_cstm_custom.php` (see [Custom Fields (`_cstm`) Entities](#custom-fields-_cstm-entities))

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
protected $first_name;
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

Generated entities extend `MintHCM\Data\ORM\Doctrine\MintEntity\MintEntity` and declare their fields as `protected` properties. Reading and writing works through `MintEntity::__get()` / `__set()` (`$employee->first_name = 'John'`); `__set()` also converts values to the PHP type of the column and turns IDs assigned to association properties into Doctrine references. Fields of the `_cstm` entity are reachable the same way.

```php
<?php

namespace MintHCM\Api\Entities;

// Auto-generated SectionUse section start
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use MintHCM\Data\ORM\Doctrine\MintEntity\MintEntity;
use MintHCM\Api\EntityTraits\PersonTrait;
// Auto-generated SectionUse section end

/**
 * @ORM\Entity
 * @ORM\Table(name="users", indexes={...})
 * @property mixed $first_name
 */
class Employees extends MintEntity
{
    // Auto-generated SectionTraits section start
    use PersonTrait;   // provides getFullName(), getName(), getEmail1(), getSerialized()
    // Auto-generated SectionTraits section end

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     * @ORM\Column(type="id", length="36")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length="255")
     */
    protected $first_name;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $date_entered;
}
```

A module with a repository declared in vardefs gets `@ORM\Entity(repositoryClass="MintHCM\Api\Repositories\{Name}")` (e.g. `Users` → `UsersRepository`); otherwise the default `MintEntityRepository` is used.

Mint-specific column types (`id`, `date`, `datetime`, `multienum`) are registered by `MintTypeManager` (`data/ORM/Doctrine/MintTypes/`).

### Useful `MintEntity` Methods

| Method | Purpose |
|--------|---------|
| `getSerialized(bool $json = false)` | Array (or JSON) of the entity's fields — use this for API output; `json_encode($entity)` gives `{}` because properties are protected |
| `hasAccess(string $view)` | Legacy ACL check (`'view'`, `'edit'`, `'delete'`, ...) |
| `getMintBean()` | Legacy bean (`MintBean`) for the entity, e.g. for MintLogic |
| `getId()`, `getName()`, `getModuleName()` | Basic identity helpers |
| `getRelatedPropertiesDefs()` / `getRelatedPropertiesNames()` | Association properties of the entity |
| `hasCustomEntity()` / `isCustomEntity()` | Whether a `_cstm` entity is attached / this is a `_cstm` entity |

### Annotations Reference

The Entity Creator writes these annotations from vardefs (relationships come from the vardefs `relationships`/`link` definitions). The snippets below are a Doctrine reference for reading generated files — do not hand-edit generated entities.

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

Hand-written helper methods must not be added to generated entity files (they are git-ignored and regenerated). Put them in an [Entity Trait](#entity-traits) declared in vardefs, e.g. `PersonTrait`:

```php
// api/app/EntityTraits/PersonTrait.php (excerpt)
trait PersonTrait
{
    public function getFullName(): string
    {
        // first_name + last_name
    }

    public function getEmail1(): string
    {
        // primary e-mail address
    }
}
```

## Repositories

Repositories handle data retrieval and custom queries. Get them from the entity manager — repositories cannot be injected into controllers directly:

```php
use MintHCM\Api\Entities\Employees;

$repository = $entityManager->getRepository(Employees::class);
$repository = $entityManager->getRepository('Employees');   // short names are resolved too
```

`MintRepositoryFactory` (`data/ORM/Doctrine/MintRepository/MintRepositoryFactory.php`) resolves short names in `MintHCM\Api\Entities` first, then in `MintHCM\Custom\Api\Entities`.

### Basic Repository

Every entity uses `MintEntityRepository` (`data/ORM/Doctrine/MintRepository/MintEntityRepository.php`) unless its vardefs declare a dedicated repository (`doctrineEntity.repository`, class in `MintHCM\Api\Repositories\`, e.g. `UsersRepository`, `PositionsRepository`). Dedicated repositories extend `MintEntityRepository`:

```php
<?php

namespace MintHCM\Api\Repositories;

use MintHCM\Data\ORM\Doctrine\MintRepository\MintEntityRepository;

class PositionsRepository extends MintEntityRepository
{
    // Custom methods go here
}
```

### Built-In Methods

All repositories inherit these from Doctrine's `EntityRepository`:

```php
// Find by ID
$employee = $repository->find($id);

// Find all
$employees = $repository->findAll();

// Find by criteria
$active = $repository->findBy(['employee_status' => 'Active']);
$admin = $repository->findOneBy(['is_admin' => true]);

// Count
$total = $repository->count([]);
$activeCount = $repository->count(['employee_status' => 'Active']);
```

…and these from `MintEntityRepository`:

| Method | Purpose |
|--------|---------|
| `getNewEntity()` | New, empty instance of the repository's entity |
| `save($entity, bool $should_flush = false)` | Persist; for entities with legacy actions (default) the record is saved through the legacy SugarBean (logic hooks, custom fields), then refreshed and detached |
| `delete($entity, bool $should_flush = false)` | Delete; for entities with legacy actions this is the legacy `mark_deleted()` (soft delete) |
| `findWithRelatedFields(string $id, bool $with_deleted = false)` | Find with all association properties joined |
| `getList(array $search_params = [], bool $check_acl = true)` | List data used by the list views (`EntitiesListTrait`) |
| `getEntityShortName()` | Short class name (= module name) |

### Custom Query Methods

Add query methods to a dedicated repository (core) or to a service class (customizations, see [Extending the API](./08-extending-api.md#creating-custom-services)):

```php
public function findActiveEmployees(): array
{
    return $this->createQueryBuilder('e')
        ->where('e.employee_status = :status')
        ->setParameter('status', 'Active')
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

public function getPaginated(int $page = 1, int $limit = 20): array
{
    $qb = $this->createQueryBuilder('e')
        ->orderBy('e.last_name', 'ASC')
        ->setFirstResult(($page - 1) * $limit)
        ->setMaxResults($limit);

    return [
        'data' => $qb->getQuery()->getResult(),
        'total' => $this->count([]),
        'page' => $page,
        'limit' => $limit,
    ];
}

public function countByStatus(): array
{
    $results = $this->createQueryBuilder('e')
        ->select('e.employee_status AS status, COUNT(e.id) AS count')
        ->groupBy('e.employee_status')
        ->getQuery()
        ->getResult();

    $formatted = [];
    foreach ($results as $result) {
        $formatted[$result['status']] = $result['count'];
    }

    return $formatted;
}
```

### Joining Related Entities

Association property names come from the generated entity (e.g. `reports_to_id` on `Employees`):

```php
public function findWithManagers(): array
{
    return $this->createQueryBuilder('e')
        ->select('e, m')
        ->leftJoin('e.reports_to_id', 'm')
        ->getQuery()
        ->getResult();
}

public function findByManagerLastName(string $lastName): array
{
    return $this->createQueryBuilder('e')
        ->join('e.reports_to_id', 'm')
        ->where('m.last_name = :name')
        ->setParameter('name', $lastName)
        ->getQuery()
        ->getResult();
}
```

### Aggregations

```php
public function countAll(): int
{
    return (int) $this->createQueryBuilder('e')
        ->select('COUNT(e.id)')
        ->getQuery()
        ->getSingleScalarResult();
}
```

### Complex Queries

```php
public function findByCriteria(array $criteria): array
{
    $qb = $this->createQueryBuilder('e');

    if (!empty($criteria['status'])) {
        $qb->andWhere('e.employee_status = :status')
           ->setParameter('status', $criteria['status']);
    }

    if (!empty($criteria['entered_after'])) {
        $qb->andWhere('e.date_entered >= :entered_after')
           ->setParameter('entered_after', new \DateTime($criteria['entered_after']));
    }

    if (!empty($criteria['search'])) {
        $qb->andWhere(
            $qb->expr()->orX(
                $qb->expr()->like('e.first_name', ':search'),
                $qb->expr()->like('e.last_name', ':search')
            )
        )->setParameter('search', '%' . $criteria['search'] . '%');
    }

    // Whitelist sort fields - never pass user input into DQL unchecked
    if (in_array($criteria['sort'] ?? null, ['first_name', 'last_name', 'date_entered'], true)) {
        $qb->orderBy('e.' . $criteria['sort'], ($criteria['dir'] ?? 'ASC') === 'DESC' ? 'DESC' : 'ASC');
    }

    if (!empty($criteria['limit'])) {
        $qb->setMaxResults((int) $criteria['limit']);
    }

    return $qb->getQuery()->getResult();
}
```

## Saving and Deleting Entities

Use the repository's `save()` / `delete()` for module records. They route through the legacy SugarBean (logic hooks, `_cstm` fields, relationships, soft delete), as `ModuleController` does.

### Creating Entities

```php
use MintHCM\Api\Entities\Employees;

$repository = $entityManager->getRepository(Employees::class);

$employee = $repository->getNewEntity();
$employee->first_name = $data['first_name'];
$employee->last_name = $data['last_name'];

$repository->save($employee, true);
```

### Updating Entities

```php
$employee = $repository->find($id);

if (!$employee) {
    throw new \InvalidArgumentException('Employee not found');
}

if (isset($data['first_name'])) {
    $employee->first_name = $data['first_name'];
}

$repository->save($employee, true);
```

### Deleting Entities

```php
$employee = $repository->find($id);

if ($employee) {
    $repository->delete($employee);   // legacy mark_deleted(): sets deleted = 1
}
```

⚠️ `$entityManager->remove($entity); $entityManager->flush();` issues a SQL `DELETE` and bypasses legacy logic — do not use it for module records. Plain `persist()/flush()` likewise skips logic hooks. Entities can opt out of legacy actions by overriding the `LEGACY_ACTIONS` constant (default `true` in `MintEntity`).

### Soft Delete

Records are soft-deleted (`deleted = 1`). The `soft_delete` Doctrine filter (`data/ORM/Doctrine/Filter/SoftDeleteFilter.php`) is enabled globally in `DoctrineContainerBuilder` and adds `deleted = 0` to every query on an entity that has a `deleted` field — `find()`, `findBy()` and query builders never return deleted rows, so you do not need a `deleted` condition.

To read deleted rows, disable the filter temporarily:

```php
$entityManager->getFilters()->disable('soft_delete');
$all = $repository->findAll();
$entityManager->getFilters()->enable('soft_delete');
```

## Extending Entities and Repositories

Because the generated `api/app/Entities/*.php` files are git-ignored and regenerated on every build, **hand-written code must not live directly in an entity file** — it would not be versioned and would be lost. Reusable entity behaviour is instead placed in **Traits** (`api/app/EntityTraits/`) and referenced from the module's vardefs. New database-mapped fields are added as **custom fields** in vardefs/Studio, which produces a `_cstm` entity (see [Custom Fields (`_cstm`) Entities](#custom-fields-_cstm-entities)).

### Entity Traits

Traits hold hand-written, reusable methods that the Entity Creator wires into the generated entity. Unlike inline entity code, Traits are committed to the repository and survive regeneration. Existing traits: `PersonTrait`, `UsersTrait`, `UserPreferencesTrait`.

**How it works:**

1. Create a trait in `api/app/EntityTraits/` under the `MintHCM\Api\EntityTraits` namespace. Name it `{ShortName}Trait` (e.g. `UsersTrait`, `PersonTrait`).
2. Declare which traits a module uses in its vardefs under `doctrineEntity.traits`, using the **short name** (without the `Trait` suffix).
3. On Quick Repair, the Entity Creator resolves each short name via `TraitNameResolver` (`legacy/include/EntityCreator/TraitNameResolver.php`: appends `Trait`, prepends the namespace for the `use` import), adds the import to `SectionUse`, and emits `use {Trait};` inside the class in `SectionTraits`.

**Declaring traits in vardefs:**

```php
// legacy/modules/Users/vardefs.php
$dictionary['User'] = array(
    'table' => 'users',
    'doctrineEntity' => array(
        'repository' => 'UsersRepository',     // -> MintHCM\Api\Repositories\UsersRepository
        'traits' => array('Users', 'Person'),  // -> UsersTrait, PersonTrait
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
        // Delegates to MintHCM\Lib\Services\Auth\PasswordHash (md5 + bcrypt policy)
    }

    public function passwordNeedsRehash(): bool
    {
        // ...
    }
}
```

**The `person` template shortcut:** modules whose vardefs include `'templates' => array('person', ...)` automatically receive `PersonTrait` (providing `getFullName()`, `getName()`, `getEmail1()`, `getSerialized()`) — no explicit `traits` entry is needed. When `PersonTrait` is applied, the Entity Creator skips generating its own `getEmail1()`/`getSerialized()` to avoid duplicate declarations.

**Note on Employees:** the `Employee` module reuses the `User` vardefs but overrides `doctrineEntity` with `array('traits' => array('Person'))` (`legacy/modules/Employees/vardefs.php`), so the `Employees` entity uses `PersonTrait` but not the `UsersRepository` or `UsersTrait`.

### Understanding Protected Sections

The Entity Creator marks auto-generated sections with special comments:

```php
// Auto-generated SectionUse section start
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use MintHCM\Data\ORM\Doctrine\MintEntity\MintEntity;
use MintHCM\Api\EntityTraits\PersonTrait;
// Auto-generated SectionUse section end

// Auto-generated SectionTraits section start
use PersonTrait;
// Auto-generated SectionTraits section end

// Auto-generated SectionProperties section start
/**
 * @ORM\Column(type="string", length="255")
 */
protected $first_name;
// Auto-generated SectionProperties section end

// Auto-generated SectionMethods section start
public function __construct()
{
    $this->email_addresses = new ArrayCollection();
}
// Auto-generated SectionMethods section end
```

When an entity file already exists, the generator replaces only the content of these sections (`EntityCreator::updateExistingEntity()`); otherwise it writes the whole file from the templates in `legacy/include/EntityCreator/tpls/` (module-specific templates exist for `Users` and `UserPreferences`).

> ⚠️ Code placed outside the sections survives a local regeneration, but entity files are git-ignored, so such code is **not versioned** and is lost on a fresh build or install. Use an Entity Trait instead.

### Protected Section Reference

| Section Name | Purpose |
|-------------|---------|
| `SectionUse` | Import statements (`use ...`), including trait imports |
| `SectionRepository` | `@ORM\Entity` / `@ORM\Table` annotations and `@property` hints |
| `SectionTraits` | `use {Trait};` statements inside the class |
| `SectionProperties` | Property declarations (`@ORM\Column`, associations) |
| `SectionMethods` | Constructor and auto-generated methods |

Everything inside `// Auto-generated Section...` comments is overwritten on every Quick Repair.

### Custom Fields (`_cstm`) Entities

Custom fields (vardefs with `'source' => 'custom_fields'`, typically created in Studio) live in the legacy `{table}_cstm` table. When such a table and fields exist, Quick Repair generates a second entity, `{Module}_cstm` (file `api/app/Entities/{Module}_cstm_custom.php`, `CustomEntityCreator`), mapped to the `_cstm` table:

- The `_cstm` entity has `id_c` as its ID and a `main_entity` one-to-one association to the main entity.
- The main entity gets a `custom_entity` one-to-one association (`cascade={"persist", "remove"}`, `fetch="EAGER"`).
- `MintEntity::__get()` / `__set()` fall back to the custom entity, so `$employee->my_custom_field_c` works like a regular field.
- `CustomEntitySubscriber` (`data/ORM/Doctrine/MintEventManager/Subscribers/`) persists the custom entity together with the main one and copies the main ID into it.

This is the supported way to add mapped fields to an entity. A hand-written subclass in `custom/app/Entities/` is **not** used by Doctrine for existing modules (repository lookup and annotations point to the core class).

### When to Use What

**Use an Entity Trait (`api/app/EntityTraits/`, declared in vardefs):**
- ✅ Adding helper methods (getFullName, isActive, etc.)
- ✅ Sharing behaviour across several entities (e.g. `PersonTrait`)
- ✅ Any hand-written method that must survive regeneration and be versioned

**Use custom fields (vardefs / Studio → `_cstm` entity):**
- ✅ Adding new database-mapped fields to an existing module

**Use a service class (`custom/app/...`, see [Extending the API](./08-extending-api.md#creating-custom-services)):**
- ✅ Custom queries and business logic for customizations

### Extending a Repository

Dedicated repositories are selected by the `doctrineEntity.repository` vardefs key and must live in `MintHCM\Api\Repositories\` (`EntityCreator::REPOSITORY_FOLDER_PATH`). A subclass in `custom/app/Repositories/` is **not** picked up — neither Doctrine nor `MintRepositoryFactory` uses `CustomLoader`. For customizations, put query methods in a service class that receives `EntityManagerInterface` and builds queries with `$entityManager->getRepository('Module')->createQueryBuilder(...)`.

## Best Practices

### 1. Keep Entities Simple

```php
// ✅ Good - entity trait with simple, data-related methods
trait PersonTrait
{
    public function getFullName(): string
    {
        // first_name + last_name
    }
}

// ❌ Bad - business logic in an entity trait
trait EmployeeTrait
{
    public function sendWelcomeEmail(): void
    {
        // Complex email sending logic... -> belongs in a service
    }
}
```

### 2. Complex Logic in Repositories

```php
// ✅ Good - complex queries in a repository (core) or a service (custom)
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
    return $this->findBy(['employee_status' => 'Active']);
}

// ❌ Bad
public function findActiveEmployees()
{
    return $this->findBy(['employee_status' => 'Active']);
}
```

### 4. Consistent Naming

```php
// ✅ Good - clear, descriptive names
public function findActiveEmployees(): array
public function countByStatus(): array
public function searchByName(string $query): array

// ❌ Bad - unclear names
public function getStuff(): array
public function doQuery(): array
```

### 5. Optimize Queries

```php
// ✅ Good - single query with join
public function findWithManagers(): array
{
    return $this->createQueryBuilder('e')
        ->select('e, m')
        ->leftJoin('e.reports_to_id', 'm')
        ->getQuery()
        ->getResult();
}

// ❌ Bad - N+1 queries
$employees = $this->findAll();
foreach ($employees as $employee) {
    $manager = $employee->reports_to_id?->last_name;  // Separate query per employee!
}
```

## Next Steps

- Learn about [Database Communication](./06-database.md)
- Understand [Controllers & Actions](./09-controllers.md)
- Explore [Extending the API](./08-extending-api.md)

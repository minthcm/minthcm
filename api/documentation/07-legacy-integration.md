# Legacy Integration

The MintHCM API integrates seamlessly with the legacy SuiteCRM/SugarCRM codebase, allowing you to leverage existing business logic, beans, and utilities.

## Overview

MintHCM is built on top of SuiteCRM (a fork of SugarCRM), so the API layer coexists with the legacy PHP codebase. `MintHCM\Data\BeanFactory` (records) and the **LegacyConnector** / **LegacyStaticConnector** classes (any legacy class or function) provide the bridge between modern API code and legacy code. The legacy environment itself is already initialized by `index.php` (`include/entryPoint.php`), so globals such as `$current_user`, `$db`, `$beanList` and classes such as `SugarBean` and the legacy `BeanFactory` are loaded.

```
┌─────────────────────────────────┐
│         MintHCM API             │
│   (Modern Slim/Doctrine)        │
└───────────┬─────────────────────┘
            │
            │ BeanFactory / LegacyConnector
            ▼
┌─────────────────────────────────┐
│      Legacy SuiteCRM            │
│   (SugarCRM-based code)         │
└─────────────────────────────────┘
```

## The LegacyConnector Class

The `LegacyConnector` allows you to instantiate and use legacy classes from the API.

### How It Works

**File:** `utils/LegacyConnector.php` (simplified)

```php
<?php

namespace MintHCM\Utils;

#[\AllowDynamicProperties]
class LegacyConnector
{
    protected $class;
    protected static $static_class;

    public function __construct($class_name, $link = null, $params = array())
    {
        $old_cwd = getcwd();
        chdir('../legacy/');  // Switch to legacy directory
        try {
            if (isset($link)) {
                require_once $link;  // Include legacy file (relative to legacy/)
            }
            $this->class = !empty($params) ? new $class_name(...$params) : new $class_name();
            static::$static_class = !empty($params) ? new $class_name(...$params) : new $class_name();
        } finally {
            chdir($old_cwd);  // Restore the previous working directory
        }
    }

    // __get, __set, __call and __callStatic do the same chdir + try/finally around
    // $this->class->$name, $this->class->$name = $value, $this->class->$name(...$arguments)
    // and static::$static_class::$name(...$arguments)

    public static function callFunction(string $function_name, ...$arguments): mixed { /* ... */ }

    public static function runCallable(callable $fn): mixed { /* ... */ }
}
```

**Key features:**
- **Directory switching**: Changes to `../legacy/` when calling legacy code and restores the previous directory afterwards (also on exceptions)
- **Magic methods**: Proxies property access, method calls and static calls
- **Auto-include**: Can require legacy files before instantiation
- **Helpers**: `callFunction()` for global functions, `runCallable()` for any block of legacy code

**Note:** Values returned through the connector are raw legacy objects. Calling methods on them later runs with the API working directory - use `MintHCM\Data\BeanFactory` for beans, or `runCallable()`.

## Using LegacyConnector

### Basic Usage

```php
use MintHCM\Utils\LegacyConnector;

// Instantiate a legacy class
$timeDate = new LegacyConnector('TimeDate', 'include/TimeDate.php');

// Call methods (executed inside legacy/)
$now = $timeDate->nowDb();

// Access properties
$timeDate->allow_cache = false;
```

### With File Include

Some legacy classes require specific files to be included:

```php
$timeDate = new LegacyConnector(
    'TimeDate',                      // Class name
    'include/TimeDate.php'           // File to include
);

$formattedDate = $timeDate->to_display_date_time($datetime);
```

### With Constructor Parameters

```php
$user = new LegacyConnector(
    'User',                          // Class name
    null,                            // No file to include
    [$userId]                        // Constructor parameters
);
```

### Calling Legacy Global Functions

Some legacy functionality is exposed as standalone global functions rather than class methods (e.g. `translate()`). Use the static `LegacyConnector::callFunction()` helper to call them without instantiating a class:

```php
use MintHCM\Utils\LegacyConnector;

$label = LegacyConnector::callFunction('translate', 'LBL_START_DATE', 'Delegations');
```

It switches to `../legacy/` for the duration of the call (so the function can resolve legacy includes/globals) and restores the previous working directory afterward — the same isolation `LegacyConnector` provides for instance methods.

### Running a Block of Legacy Code

`LegacyConnector::runCallable()` runs any closure inside `legacy/` and returns its result (used e.g. in `app/Controllers/Admin/AdminConfigController.php`, `ElasticSearchAdminController.php`):

```php
use MintHCM\Utils\LegacyConnector;

$result = LegacyConnector::runCallable(function () use ($id) {
    $bean = \BeanFactory::getBean('Employees', $id);
    $bean->description = 'Updated from API';
    return $bean->save();
});
```

## The LegacyStaticConnector Class

Some legacy classes use the Singleton pattern with a private constructor (e.g. `TrackerManager`) and can only be obtained through a static factory method such as `getInstance()`. `LegacyConnector` cannot wrap these — it always calls `new $class_name()` directly, which fails against a private constructor. Use `LegacyStaticConnector` instead.

**File:** `utils/LegacyStaticConnector.php`

Unlike `LegacyConnector`, it does not instantiate the class directly — it obtains the instance via a static factory method and proxies property access and method calls to it, with the same `chdir` isolation.

```php
use MintHCM\Utils\LegacyStaticConnector;

$trackerManager = new LegacyStaticConnector(
    'TrackerManager',                     // Class name
    'modules/Trackers/TrackerManager.php' // File to include
);

$monitor = $trackerManager->getMonitor('tracker');
```

The factory method defaults to `getInstance()`; pass a different method name (and its arguments) as the 3rd/4th constructor arguments if the legacy class exposes a different singleton accessor.

## Common Legacy Classes

### BeanFactory

Use the API's `MintHCM\Data\BeanFactory` (`api/data/BeanFactory.php`) to retrieve and create beans (records). It calls the legacy `BeanFactory` inside `legacy/` and wraps the result in `MintHCM\Data\MintBean`, which proxies property access and method calls (`save()`, `mark_deleted()`, `ACLAccess()`, `load_relationship()`, ...) with the same `chdir` isolation:

```php
use MintHCM\Data\BeanFactory;

// Retrieve a bean (MintBean wrapper)
$employee = BeanFactory::getBean('Employees', $id);

// Create new bean
$newEmployee = BeanFactory::newBean('Employees');
$newEmployee->first_name = 'John';
$newEmployee->last_name = 'Doe';
$newEmployee->save();
```

### Legacy BeanFactory

The legacy global `\BeanFactory` is also loaded (by `include/entryPoint.php` in `index.php`), but it returns raw beans and runs with the API working directory, so code that includes legacy files by relative path may fail. Use it only inside `LegacyConnector::runCallable()` or prefer `MintHCM\Data\BeanFactory`:

```php
// index.php
chdir('../legacy/');
require_once 'include/entryPoint.php';  // Initializes legacy environment
chdir('../api/');
```

### SugarBean Methods

Common methods available on legacy beans:

```php
use MintHCM\Data\BeanFactory;

// Retrieve record
$bean = BeanFactory::getBean('Employees', $id);

// Properties
$firstName = $bean->first_name;
$bean->last_name = 'Doe';

// Save
$bean->save();

// Delete
$bean->mark_deleted($id);

// Load relationships (link names come from the module vardefs)
if ($bean->load_relationship('contracts')) {
    $contracts = $bean->contracts->getBeans();
}

// Check ACL
if ($bean->ACLAccess('edit')) {
    $bean->save();
}
```

## Legacy Utilities

### TimeDate

Date and time utilities from legacy code:

```php
use MintHCM\Utils\LegacyConnector;

$timeDate = new LegacyConnector('TimeDate', 'include/TimeDate.php');

// Format dates
$displayDate = $timeDate->to_display_date_time($dbDate);
$dbDate = $timeDate->to_db($userDate);

// Get current time
$now = $timeDate->nowDb();
```

### User

Access user information:

```php
global $current_user;

// Current user is already available
$userName = $current_user->user_name;
$isAdmin = $current_user->is_admin;

// Check preferences
$dateFormat = $current_user->getPreference('datef');
```

### ACL (Access Control)

Check permissions:

```php
$bean = \MintHCM\Data\BeanFactory::getBean('Employees', $id);

// Check specific permission
if ($bean->ACLAccess('edit')) {
    // User can edit
}

if ($bean->ACLAccess('delete')) {
    // User can delete
}
```

### App Strings

Access language strings:

```php
global $app_strings, $app_list_strings, $current_language;

// Load if not already loaded
if (!$app_list_strings) {
    $app_list_strings = return_app_list_strings_language($current_language);
}

// Get dropdown options
$statusOptions = $app_list_strings['employee_status_dom'];

// Get translated string
$label = $app_strings['LBL_EMPLOYEES'];
```

## Global Variables

Legacy code relies on global variables. Key globals available in the API:

```php
global $current_user;        // Current logged-in user
global $db;                  // Database connection
global $app_strings;         // Application strings
global $app_list_strings;    // Dropdown lists
global $beanList;            // List of all modules
global $beanFiles;           // Bean file paths
global $sugar_config;        // Configuration array
```

### Using Globals in Controllers

```php
class MyModulesController
{
    public function list(Request $request, Response $response, array $args): Response
    {
        global $current_user, $beanList;

        // Check if user is admin (or use the route option 'admin_only' => true)
        if (!$current_user->is_admin) {
            return $response->withStatus(403);
        }

        // Get all modules
        $modules = array_keys($beanList);

        $response->getBody()->write(json_encode(['modules' => $modules]));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
```

## Legacy Database Access

While the API uses Doctrine, you can still access the legacy database connection:

```php
global $db;

// Run query - always quote user input ($db->quoted() adds the quotes)
$result = $db->query("SELECT * FROM users WHERE id = " . $db->quoted($id));

// Fetch row
$row = $db->fetchByAssoc($result);

// Escaping without quotes (legacy style)
$result = $db->query(sprintf(
    "SELECT * FROM users WHERE id = '%s'",
    $db->quote($id)
));
```

**Warning:** Prefer Doctrine for new code. Use legacy DB only when necessary.

## Working with Legacy Classes That Need the Legacy Directory

Legacy controllers and views (`SugarController`, `SugarView`) depend on the full legacy request cycle (`$_REQUEST`, output buffering, Smarty) and are not meant to be called from the API. Call the underlying business logic (beans, utility classes, global functions) through `MintHCM\Data\BeanFactory`, `LegacyConnector` or `LegacyConnector::runCallable()` instead.

## Legacy vs API Comparison

| Feature | Legacy Code | API Code |
|---------|-------------|----------|
| **Data Access** | SugarBean | Doctrine Entities |
| **Routing** | `index.php?module=X&action=Y` | Slim routes |
| **Responses** | HTML/Smarty | JSON |
| **Authentication** | Session-based | OAuth2 bearer token or the legacy PHP session (frontend) |
| **Database** | Direct SQL via `$db` | Doctrine ORM |
| **Autoloading** | `require_once` + SuiteCRM autoloader | Composer PSR-4 |

## Integration Patterns

### Pattern: Using Legacy Logic in API

```php
use MintHCM\Data\BeanFactory;

class MyEmployeesController
{
    public function create(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();

        // Use legacy bean for business logic
        $employee = BeanFactory::newBean('Employees');
        foreach (['first_name', 'last_name'] as $field) {   // whitelist fields
            $employee->$field = $data[$field] ?? null;
        }

        // Legacy save (logic hooks, workflows, audit)
        $employee->save();

        $response->getBody()->write(json_encode([
            'id' => $employee->id,
            'name' => $employee->name,
        ]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }
}
```

### Pattern: Doctrine Entities With Legacy Writes

Doctrine entities already delegate writes to legacy beans: `MintEntityRepository::save()` / `delete()` call `$bean->save()` / `$bean->mark_deleted()` for entities with legacy actions (`MintEntity::LEGACY_ACTIONS`, `true` by default). Read with Doctrine and write through the repository - logic hooks still run:

```php
use MintHCM\Api\Entities\Employees;

$repository = $entityManager->getRepository(Employees::class);
foreach ($repository->findBy(['status' => 'Active']) as $employee) {
    $employee->description = 'Reviewed';
    $repository->save($employee, true);   // legacy save, triggers logic hooks
}
```

See [Database Communication](./06-database.md#creating-records).

## Best Practices

### 1. Prefer Doctrine for Reads

```php
// ✅ Good - Doctrine for reads; writes via $repository->save() still run legacy hooks
$employee = $repository->find($id);

// OK when you need bean-only features (ACLAccess, relationships, legacy methods)
$employee = \MintHCM\Data\BeanFactory::getBean('Employees', $id);
```

### 2. Use LegacyConnector for Isolation

```php
// ✅ Good - encapsulated, working directory restored even on exceptions
$timeDate = new LegacyConnector('TimeDate', 'include/TimeDate.php');
$now = $timeDate->nowDb();

// ❌ Bad - manual chdir, not restored if an exception is thrown
chdir('../legacy/');
require_once 'include/TimeDate.php';
$now = (new TimeDate())->nowDb();
chdir('../api/');
```

### 3. Wrap Legacy Logic in Services

```php
// ✅ Good - abstracted in service
class EmployeeService
{
    public function calculateBonus($employeeId): float
    {
        $bean = \MintHCM\Data\BeanFactory::getBean('Employees', $employeeId);
        return $this->legacyBonusCalculation($bean);
    }
    
    private function legacyBonusCalculation($bean): float
    {
        // Legacy logic here
    }
}
```

### 4. Document Legacy Dependencies

```php
/**
 * Calculate employee bonus using legacy SugarCRM logic.
 * 
 * @deprecated This uses legacy code. Migrate to EmployeeService::calculateBonus()
 * @param string $employeeId Employee ID
 * @return float Calculated bonus
 */
public function legacyBonusCalculation(string $employeeId): float
{
    // ...
}
```

## Common Pitfalls

### 1. Directory Context

**Problem:** Files not found

```php
// ❌ Wrong - in API directory
require_once 'modules/Employees/Employee.php';  // Not found!

// ✅ Correct - LegacyConnector includes the file relative to legacy/
$employee = new LegacyConnector('Employee', 'modules/Employees/Employee.php');

// ✅ Also fine for beans - MintHCM\Data\BeanFactory handles the directory
$employee = \MintHCM\Data\BeanFactory::newBean('Employees');
```

### 2. Global Variable Scope

**Problem:** Globals not available in functions

```php
// ❌ Wrong
function myFunction() {
    if ($current_user->is_admin) { }  // Undefined variable
}

// ✅ Correct
function myFunction() {
    global $current_user;
    if ($current_user->is_admin) { }
}
```

### 3. Legacy Classes Not Loaded

**Problem:** Class not found

Core legacy classes (`SugarBean`, `BeanFactory`, `User`, ...) are loaded by `include/entryPoint.php`. Module-specific or include files are not:

```php
// ❌ Class from a file that entryPoint does not load
$obj = new SomeLegacyHelper();  // Error!

// ✅ Solution 1: Use BeanFactory for beans
$bean = \MintHCM\Data\BeanFactory::newBean('Employees');

// ✅ Solution 2: Use LegacyConnector with the file to include
$timeDate = new LegacyConnector('TimeDate', 'include/TimeDate.php');
```

## Debugging Legacy Integration

### Enable Legacy Error Logging

```php
// In legacy code
$GLOBALS['log']->fatal('Debug message');
$GLOBALS['log']->error('Error message');
```

```bash
# View logs (logger name 'minthcm' in legacy/config.php)
tail -f legacy/minthcm.log
```

### Check Global State

```php
var_dump($GLOBALS['current_user']);
var_dump($GLOBALS['beanList']);
var_dump($GLOBALS['sugar_config']);
```

### Test Legacy Functions

```php
// Test legacy code directly
$emp = LegacyConnector::runCallable(function () {
    require_once 'modules/Employees/Employee.php';
    return new Employee();
});
var_dump($emp);
```

## Next Steps

- Understand [Database Communication](./06-database.md)
- Learn about [Extending the API](./08-extending-api.md)
- Explore [Controllers & Actions](./09-controllers.md)

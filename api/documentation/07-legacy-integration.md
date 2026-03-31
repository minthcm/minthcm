# Legacy Integration

The MintHCM API integrates seamlessly with the legacy SuiteCRM/SugarCRM codebase, allowing you to leverage existing business logic, beans, and utilities.

## Overview

MintHCM is built on top of SuiteCRM (a fork of SugarCRM), so the API layer coexists with the legacy PHP codebase. The **LegacyConnector** class provides a bridge between modern API code and legacy code.

```
┌─────────────────────────────────┐
│         MintHCM API             │
│   (Modern Slim/Doctrine)        │
└───────────┬─────────────────────┘
            │
            │ LegacyConnector
            ▼
┌─────────────────────────────────┐
│      Legacy SuiteCRM            │
│   (SugarCRM-based code)         │
└─────────────────────────────────┘
```

## The LegacyConnector Class

The `LegacyConnector` allows you to instantiate and use legacy classes from the API.

### How It Works

**File:** `utils/LegacyConnector.php`

```php
<?php

namespace MintHCM\Utils;

class LegacyConnector
{
    protected $class;

    public function __construct($class_name, $link = null, $params = [])
    {
        chdir('../legacy/');  // Switch to legacy directory
        
        if (isset($link)) {
            require_once $link;  // Include legacy file
        }
        
        if (!empty($params)) {
            $this->class = new $class_name(...$params);
        } else {
            $this->class = new $class_name();
        }
        
        chdir('../api/');  // Return to API directory
    }

    public function __get($name)
    {
        chdir('../legacy/');
        $response = $this->class->$name;
        chdir('../api/');
        return $response;
    }

    public function __set($name, $value)
    {
        chdir('../legacy/');
        $this->class->$name = $value;
        chdir('../api/');
    }

    public function __call($name, $arguments)
    {
        chdir('../legacy/');
        $response = $this->class->$name(...$arguments);
        chdir('../api/');
        return $response;
    }
}
```

**Key features:**
- **Directory switching**: Changes to `../legacy/` when calling legacy code
- **Magic methods**: Proxies property access and method calls
- **Auto-include**: Can require legacy files before instantiation

## Using LegacyConnector

### Basic Usage

```php
use MintHCM\Utils\LegacyConnector;

// Instantiate legacy class
$bean = new LegacyConnector('SugarBean');

// Call methods
$bean->retrieve($id);
$bean->save();

// Access properties
$name = $bean->name;
$bean->status = 'Active';
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

## Common Legacy Classes

### BeanFactory

Use `BeanFactory` to retrieve and create beans (records):

```php
use MintHCM\Utils\LegacyConnector;

// Get BeanFactory
$beanFactory = new LegacyConnector('BeanFactory', 'data/BeanFactory.php');

// Retrieve a bean
$employee = $beanFactory->getBean('Employees', $id);

// Create new bean
$newEmployee = $beanFactory->newBean('Employees');
$newEmployee->first_name = 'John';
$newEmployee->last_name = 'Doe';
$newEmployee->save();
```

### Direct Bean Usage

The API provides its own `BeanFactory` in the `data/` directory:

```php
use BeanFactory;

// No need for LegacyConnector - already available
$employee = BeanFactory::getBean('Employees', $id);
```

**Note:** `BeanFactory` is already included in `index.php` during initialization:

```php
// index.php
chdir('../legacy/');
require_once 'include/entryPoint.php';  // Initializes legacy environment
chdir('../api/');
```

### SugarBean Methods

Common methods available on legacy beans:

```php
// Retrieve record
$bean = BeanFactory::getBean('Employees', $id);

// Properties
$firstName = $bean->first_name;
$bean->last_name = 'Doe';

// Save
$bean->save();

// Delete
$bean->mark_deleted($id);

// Load relationships
$bean->load_relationship('documents');
$documents = $bean->documents->getBeans();

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
$bean = BeanFactory::getBean('Employees', $id);

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
$statusOptions = $app_list_strings['employee_status_list'];

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
class EmployeeController
{
    public function list(Request $request, Response $response): Response
    {
        global $current_user, $beanList;
        
        // Check if user is admin
        if (!$current_user->is_admin) {
            return $response->withStatus(403);
        }
        
        // Get all modules
        $modules = array_keys($beanList);
        
        return $response->withJson(['modules' => $modules]);
    }
}
```

## Legacy Database Access

While the API uses Doctrine, you can still access the legacy database connection:

```php
global $db;

// Run query
$result = $db->query("SELECT * FROM users WHERE id = '{$id}'");

// Fetch row
$row = $db->fetchByAssoc($result);

// Prepared statement (legacy style)
$result = $db->query(sprintf(
    "SELECT * FROM users WHERE id = '%s'",
    $db->quote($id)
));
```

**Warning:** Prefer Doctrine for new code. Use legacy DB only when necessary.

## Working with Legacy Controllers

### Calling Legacy Controller Actions

```php
use MintHCM\Utils\LegacyConnector;

// Instantiate legacy controller
$controller = new LegacyConnector(
    'EmployeesController',
    'modules/Employees/controller.php'
);

// Call action
$controller->action_detail();
```

### Using Legacy Views

```php
chdir('../legacy/');

require_once 'include/MVC/View/SugarView.php';

$view = new SugarView();
$view->display();

chdir('../api/');
```

## Legacy vs API Comparison

| Feature | Legacy Code | API Code |
|---------|-------------|----------|
| **Data Access** | SugarBean | Doctrine Entities |
| **Routing** | `index.php?module=X&action=Y` | Slim routes |
| **Responses** | HTML/Smarty | JSON |
| **Authentication** | Session-based | JWT/OAuth2 |
| **Database** | Direct SQL via `$db` | Doctrine ORM |
| **Autoloading** | Manual `require_once` | Composer PSR-4 |

## Integration Patterns

### Pattern: Using Legacy Logic in API

```php
class EmployeeController
{
    public function create(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        
        // Use legacy bean for business logic
        $employee = BeanFactory::newBean('Employees');
        
        foreach ($data as $field => $value) {
            $employee->$field = $value;
        }
        
        // Legacy save (includes hooks, logic hooks, etc.)
        $employee->save();
        
        // Return modern JSON response
        return $response->withJson([
            'id' => $employee->id,
            'name' => $employee->name,
        ]);
    }
}
```

### Pattern: Migrating from Legacy to API

**Step 1:** Wrap legacy logic in API endpoint

```php
public function legacyAction(Request $request, Response $response): Response
{
    chdir('../legacy/');
    
    // Call legacy function
    require_once 'modules/Employees/legacy_function.php';
    $result = legacy_process_employee($id);
    
    chdir('../api/');
    
    return $response->withJson($result);
}
```

**Step 2:** Gradually refactor to modern code

```php
public function modernAction(Request $request, Response $response): Response
{
    // New Doctrine-based implementation
    $employee = $this->repository->find($id);
    $result = $this->service->processEmployee($employee);
    
    return $response->withJson($result);
}
```

### Pattern: Hybrid Data Access

```php
// Use Doctrine for reads (faster)
$employees = $repository->findBy(['status' => 'Active']);

// Use legacy beans for writes (preserves hooks)
foreach ($employees as $employeeEntity) {
    $bean = BeanFactory::getBean('Employees', $employeeEntity->id);
    $bean->bonus_calculated = true;
    $bean->save();  // Triggers logic hooks
}
```

## Best Practices

### 1. Prefer API Code Over Legacy

```php
// ✅ Good - use Doctrine when possible
$employee = $repository->find($id);

// ❌ Avoid - use legacy only when necessary
$employee = BeanFactory::getBean('Employees', $id);
```

### 2. Use LegacyConnector for Isolation

```php
// ✅ Good - encapsulated
$connector = new LegacyConnector('MyLegacyClass');
$result = $connector->doSomething();

// ❌ Bad - direct legacy access
chdir('../legacy/');
require_once 'include/MyLegacyClass.php';
$obj = new MyLegacyClass();
chdir('../api/');
```

### 3. Wrap Legacy Logic in Services

```php
// ✅ Good - abstracted in service
class EmployeeService
{
    public function calculateBonus($employeeId): float
    {
        $bean = BeanFactory::getBean('Employees', $employeeId);
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

// ✅ Correct - switch to legacy directory
chdir('../legacy/');
require_once 'modules/Employees/Employee.php';
chdir('../api/');

// ✅ Better - use LegacyConnector
$employee = new LegacyConnector('Employee', 'modules/Employees/Employee.php');
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

### 3. Autoloading Conflicts

**Problem:** Class not found

```php
// Legacy class not autoloaded by Composer
$bean = new SugarBean();  // Error!

// ✅ Solution 1: Use BeanFactory
$bean = BeanFactory::newBean('Employees');

// ✅ Solution 2: Use LegacyConnector
$bean = new LegacyConnector('SugarBean');
```

## Debugging Legacy Integration

### Enable Legacy Error Logging

```php
// In legacy code
$GLOBALS['log']->fatal('Debug message');
$GLOBALS['log']->error('Error message');

// View logs
tail -f legacy/sugarcrm.log
```

### Check Global State

```php
var_dump($GLOBALS['current_user']);
var_dump($GLOBALS['beanList']);
var_dump($GLOBALS['sugar_config']);
```

### Test Legacy Functions

```php
chdir('../legacy/');

// Test legacy code directly
require_once 'modules/Employees/Employee.php';
$emp = new Employee();
var_dump($emp);

chdir('../api/');
```

## Migration Strategy

### Phase 1: Wrapper Approach

Wrap legacy code in API endpoints:
- Keep business logic in legacy beans
- Expose via JSON API
- Minimal code changes

### Phase 2: Hybrid Approach

Mix legacy and modern code:
- Read with Doctrine (performance)
- Write with beans (preserve hooks)
- Gradual refactoring

### Phase 3: Full Migration

Replace legacy code entirely:
- Reimplement business logic in API
- Use Doctrine exclusively
- Deprecate legacy endpoints

## Next Steps

- Understand [Database Communication](./06-database.md)
- Learn about [Extending the API](./08-extending-api.md)
- Explore [Controllers & Actions](./09-controllers.md)

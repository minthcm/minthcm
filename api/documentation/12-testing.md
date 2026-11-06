# Testing

Testing ensures your API works correctly and prevents regressions. The MintHCM API uses **PHPUnit 11** (`phpunit/phpunit ^11.5` in `api/composer.json`, PHP 8.2) for unit tests.

Run the unit tests after every implementation and after manual testing, before committing.

## Testing Setup

### PHPUnit Configuration

**File:** `api/phpunit.xml`

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit
    colors="true"
    testdox="true"
    bootstrap="tests/bootstrap.php"
>
    <testsuites>
        <testsuite name="MintApi Test Suite">
            <directory>tests</directory>
        </testsuite>
    </testsuites>
</phpunit>
```

`tests/bootstrap.php` only loads `vendor/autoload.php`. The legacy application, the database, the Slim app (`$mint_app`) and the DI container are **not** initialized — the suite contains pure unit tests.

### Test Directory Layout

```
api/tests/
├── bootstrap.php
├── unit/                  # main location for new tests
│   ├── Controllers/       # e.g. AuthControllerTest, MetaControllerTest, GlobalSearchControllerTest
│   ├── ExceptionHandlers/
│   ├── Legacy/
│   ├── Lib/Services/      # Auth (PasswordHash, LoginThrottle, ...), Http
│   ├── MassActions/
│   ├── Middlewares/       # Auth, Params (ParamTypesTest), Security
│   ├── RecordView/
│   ├── Repositories/      # UsersRepository, CurrenciesRepository, OAuth2/*
│   └── utils/             # LuxonMapperTest, ConstantsLoaderTest
└── Unit/                  # older tests (Comments, Search, DynamicPropertiesTest)
```

Both directories are part of the suite (`<directory>tests</directory>`). Test namespaces follow the path: `MintHCM\Tests\Unit\{Area}` (e.g. `MintHCM\Tests\Unit\Middlewares\Params`). There is no `autoload-dev` mapping; PHPUnit discovers test files by directory.

### Running Tests

Run from `api/`:

```bash
# Run all tests (canonical)
composer test
# equivalent:
php vendor/phpunit/phpunit/phpunit --configuration phpunit.xml

# Run specific test file
./vendor/bin/phpunit tests/unit/Controllers/AuthControllerTest.php

# Run specific test method
./vendor/bin/phpunit --filter testLoginFailsWithInvalidPassword tests/unit/Controllers/AuthControllerTest.php

# Run with coverage (requires the pcov or xdebug extension)
./vendor/bin/phpunit --coverage-html coverage/
```

`api/runTests.php` is a legacy runner: it boots legacy and only picks up `tests/*Test.php` files at the top level of `tests/`, where there are none — do not use it.

Functional/E2E coverage (real database, legacy, browser) is provided by the Playwright tests in the repository root `tests/` directory, not by PHPUnit.

## Test Structure

### Basic Test Class

```php
<?php

namespace MintHCM\Tests\Unit\MyFeature;

use PHPUnit\Framework\TestCase;

final class MyFeatureTest extends TestCase
{
    protected function setUp(): void
    {
        // Runs before each test
        parent::setUp();
    }

    protected function tearDown(): void
    {
        // Runs after each test
        parent::tearDown();
    }

    public function testSomething(): void
    {
        // Arrange
        $expected = 'value';
        
        // Act
        $actual = strtolower('VALUE');
        
        // Assert
        $this->assertSame($expected, $actual);
    }
}
```

## Testing Controllers

Controllers are tested by calling the action directly with a mocked PSR-7 request and a real `Slim\Psr7\Response`. Mock `getAttribute()` — that is how controllers read the parameters validated by `ParamsMiddleware`. Code paths that need legacy (`BeanFactory`, database) cannot run under `tests/bootstrap.php`; cover them with E2E tests.

**Real example:** `tests/unit/Controllers/CloseMeetingTest.php`

```php
<?php

namespace MintHCM\Tests\Unit\Controllers;

use MintHCM\Modules\Meetings\api\controllers\CloseMeeting;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Psr7\Response;

final class CloseMeetingTest extends TestCase
{
    public function testThrowsBadRequestWhenIdIsMissing(): void
    {
        $this->expectException(HttpBadRequestException::class);
        (new CloseMeeting())($this->requestWithId(null), new Response());
    }

    private function requestWithId(?string $id): ServerRequestInterface
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getAttribute')->with('id')->willReturn($id);
        return $request;
    }
}
```

Asserting a JSON response:

```php
$response = $controller->list($request, new Response());

$this->assertSame(200, $response->getStatusCode());
$this->assertSame('application/json', $response->getHeaderLine('Content-Type'));

$data = json_decode((string) $response->getBody(), true);
$this->assertArrayHasKey('data', $data);
```

## Testing Repositories and Services

Repositories and services receive their dependencies through the constructor, so they can be tested with mocks of `EntityManagerInterface`, repositories or Doctrine query objects. See `tests/unit/Repositories/` (e.g. `CurrenciesRepositoryTest`, `OAuth2/AccessTokenRepositoryTest`).

Example for the `EmployeeStatsService` from [Extending the API](./08-extending-api.md#creating-custom-services):

```php
<?php

namespace MintHCM\Tests\Unit\Custom;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use MintHCM\Api\Entities\Employees;
use MintHCM\Custom\Api\Services\EmployeeStatsService;
use PHPUnit\Framework\TestCase;

final class EmployeeStatsServiceTest extends TestCase
{
    public function testCountByStatusQueriesActiveEmployees(): void
    {
        $repository = $this->createMock(EntityRepository::class);
        $repository->expects($this->once())
            ->method('count')
            ->with(['employee_status' => 'Active', 'show_on_employees' => 1])
            ->willReturn(42);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->method('getRepository')->with(Employees::class)->willReturn($repository);

        $service = new EmployeeStatsService($entityManager);

        $this->assertSame(42, $service->countByStatus('Active'));
    }
}
```

⚠️ Do not instantiate generated entities and set their properties in unit tests: `MintEntity::__set()` looks up the entity manager through the global `$mint_app`, which does not exist under `tests/bootstrap.php`. Mock the entity or test the logic that does not touch entities.

## Mocking

### Mocking Dependencies

```php
use PHPUnit\Framework\TestCase;

final class MyTest extends TestCase
{
    public function testWithMock(): void
    {
        // Create mock
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $repository = $this->createMock(EntityRepository::class);

        // Set expectations
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->willReturn($repository);

        // Use mock
        $service = new EmployeeStatsService($entityManager);
        // ...
    }
}
```

### Mocking Multiple Calls

```php
public function testMultipleCalls(): void
{
    $repository = $this->createMock(EntityRepository::class);
    
    $repository->expects($this->exactly(2))
        ->method('count')
        ->willReturn(1);
    
    // ... code under test calls count() twice
}
```

### Mocking with Different Return Values

```php
public function testDifferentReturns(): void
{
    $repository = $this->createMock(EntityRepository::class);
    
    $repository->expects($this->exactly(2))
        ->method('count')
        ->willReturnOnConsecutiveCalls(5, 0);
    
    $this->assertSame(5, $repository->count([]));
    $this->assertSame(0, $repository->count([]));
}
```

### Mocking Request Attributes

```php
$request = $this->createMock(ServerRequestInterface::class);
$request->method('getAttribute')->willReturnMap([
    ['username', null, 'john'],
    ['email', null, 'john@example.com'],
]);
```

(The `null` is the `$default` argument of `getAttribute()`; see `tests/unit/Controllers/AuthControllerTest.php`.)

## Assertions

### Common Assertions

```php
// Equality
$this->assertEquals($expected, $actual);
$this->assertNotEquals($expected, $actual);
$this->assertSame($expected, $actual);  // Strict comparison

// Boolean
$this->assertTrue($value);
$this->assertFalse($value);

// Null
$this->assertNull($value);
$this->assertNotNull($value);

// Type
$this->assertIsArray($value);
$this->assertIsString($value);
$this->assertIsInt($value);
$this->assertIsBool($value);
$this->assertInstanceOf(ClassName::class, $object);

// Array
$this->assertArrayHasKey('key', $array);
$this->assertContains('value', $array);
$this->assertCount(5, $array);
$this->assertEmpty($array);
$this->assertNotEmpty($array);

// String
$this->assertStringContainsString('needle', 'haystack');
$this->assertStringStartsWith('prefix', 'prefixString');
$this->assertStringEndsWith('suffix', 'stringSuffix');

// Comparison
$this->assertGreaterThan(10, $value);
$this->assertLessThan(100, $value);
$this->assertGreaterThanOrEqual(10, $value);
```

### Custom Assertions

```php
public function testCustomAssertion(): void
{
    $data = ['first_name' => 'John', 'last_name' => 'Doe'];

    $this->assertPersonName($data, 'John', 'Doe');
}

private function assertPersonName(array $data, string $first, string $last): void
{
    $this->assertSame($first, $data['first_name'], 'First name mismatch');
    $this->assertSame($last, $data['last_name'], 'Last name mismatch');
}
```

## Data Providers

Test the same logic with different data. In PHPUnit 11 data provider methods **must be `public static`** (a non-static provider throws `InvalidDataProviderException`), and they are attached with the `#[DataProvider]` attribute (the `@dataProvider` doc-comment annotation is deprecated):

```php
use PHPUnit\Framework\Attributes\DataProvider;

#[DataProvider('salaryDataProvider')]
public function testCalculateYearlySalary(float $monthly, float $bonus, float $expected): void
{
    $this->assertEquals($expected, $monthly * 12 + $bonus);
}

public static function salaryDataProvider(): array
{
    return [
        'standard salary' => [5000, 10000, 70000],
        'no bonus' => [5000, 0, 60000],
        'high bonus' => [5000, 50000, 110000],
    ];
}
```

Real example: `tests/unit/Middlewares/Params/ParamTypesTest.php`.

## Testing Exceptions

```php
public function testThrowsBadRequestForInvalidValue(): void
{
    $this->expectException(\Slim\Exception\HttpBadRequestException::class);

    (new \MintHCM\Api\Middlewares\Params\ParamTypes\IntType())($this->createMock(ServerRequestInterface::class), 'abc');
}
```

## Integration and E2E Tests

There is no PHPUnit harness that boots the Slim application, legacy and the database. Verify endpoints end-to-end with:

- the Playwright E2E tests in the repository root `tests/` directory,
- manual requests against a running instance, e.g. `curl -H "Authorization: Bearer <token>" http://your-instance/api/...` (module routes are `/{Module}/...`, the login endpoint is `POST /api/login`).

## Best Practices

### 1. Test One Thing Per Test

```php
// ✅ Good - one behaviour per test
public function testIntTypeRejectsNonNumericString(): void
{
    $this->expectException(HttpBadRequestException::class);
    (new IntType())($this->request(), 'abc');
}

// ❌ Bad - several unrelated behaviours in one test
public function testParamTypes(): void
{
    // checks Int, Bool and Email parsing and error handling at once...
}
```

### 2. Use Descriptive Test Names

```php
// ✅ Good
public function testLoginFailsWithInvalidPassword(): void

// ❌ Bad
public function testLogin(): void
```

### 3. Arrange-Act-Assert Pattern

```php
public function testSomething(): void
{
    // Arrange - set up test data
    $monthly = 5000;
    
    // Act - perform action
    $yearly = $monthly * 12;
    
    // Assert - verify result
    $this->assertEquals(60000, $yearly);
}
```

### 4. Keep Tests Independent

Unit tests must not depend on a database, legacy globals or execution order. Restore any global state (`$GLOBALS`, `$sugar_config`) you change in `tearDown()`.

### 5. Don't Test Framework Code

```php
// ✅ Good - test your logic
public function testCountByStatusQueriesActiveEmployees(): void { /* ... */ }

// ❌ Bad - testing Doctrine / PHPUnit itself
public function testMockReturnsValue(): void
{
    $repository = $this->createMock(EntityRepository::class);
    $repository->method('count')->willReturn(1);
    $this->assertSame(1, $repository->count([]));
}
```

## Continuous Integration

The repository CI (`.gitlab-ci.yml`) does not run the API unit tests. To run them in a pipeline, use PHP 8.2 and run the suite from `api/`:

```bash
cd api
composer install
composer test
```

## Coverage Reports

Generate code coverage (requires the pcov or xdebug PHP extension):

```bash
# HTML coverage report
./vendor/bin/phpunit --coverage-html coverage/

# Open coverage/index.html in a browser
```

## Debugging Tests

```php
public function testDebug(): void
{
    $data = ['id' => 'abc'];

    // Debug output
    var_dump($data);

    // Write to stderr (visible in PHPUnit output)
    fwrite(STDERR, "Debug: " . $data['id'] . "\n");

    $this->assertTrue(true);
}
```

## Next Steps

- Review [Controllers & Actions](./09-controllers.md)
- Learn about [Entities & Repositories](./10-entities-repositories.md)
- Understand [Extending the API](./08-extending-api.md)

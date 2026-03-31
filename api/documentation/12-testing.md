# Testing

Testing ensures your API works correctly and prevents regressions. The MintHCM API uses PHPUnit for testing.

## Testing Setup

### PHPUnit Configuration

**File:** `phpunit.xml`

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit bootstrap="vendor/autoload.php"
         colors="true"
         convertErrorsToExceptions="true"
         convertNoticesToExceptions="true"
         convertWarningsToExceptions="true">
    <testsuites>
        <testsuite name="API Tests">
            <directory>./tests</directory>
        </testsuite>
    </testsuites>
</phpunit>
```

### Running Tests

```bash
# Run all tests
./vendor/bin/phpunit

# Run specific test file
./vendor/bin/phpunit tests/AuthTest.php

# Run specific test method
./vendor/bin/phpunit --filter testLogin tests/AuthTest.php

# Run with coverage
./vendor/bin/phpunit --coverage-html coverage/
```

Using the test runner script:

```bash
php runTests.php
```

## Test Structure

### Basic Test Class

```php
<?php

namespace MintHCM\Api\Tests;

use PHPUnit\Framework\TestCase;

class MyFeatureTest extends TestCase
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
        $actual = $this->doSomething();
        
        // Assert
        $this->assertEquals($expected, $actual);
    }
}
```

## Testing Routes and Controllers

### Example: Testing Authentication

**File:** `tests/AuthTest.php`

```php
<?php

namespace MintHCM\Api\Tests;

use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
    private $app;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Initialize Slim app for testing
        $this->app = $this->createApplication();
    }

    public function testLoginSuccess(): void
    {
        $request = $this->createRequest('POST', '/auth/login', [
            'username' => 'admin',
            'password' => 'password123',
        ]);
        
        $response = $this->app->handle($request);
        
        $this->assertEquals(200, $response->getStatusCode());
        
        $body = (string) $response->getBody();
        $data = json_decode($body, true);
        
        $this->assertArrayHasKey('token', $data);
        $this->assertNotEmpty($data['token']);
    }

    public function testLoginInvalidCredentials(): void
    {
        $request = $this->createRequest('POST', '/auth/login', [
            'username' => 'admin',
            'password' => 'wrongpassword',
        ]);
        
        $response = $this->app->handle($request);
        
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testLoginMissingUsername(): void
    {
        $request = $this->createRequest('POST', '/auth/login', [
            'password' => 'password123',
        ]);
        
        $response = $this->app->handle($request);
        
        $this->assertEquals(400, $response->getStatusCode());
        
        $body = (string) $response->getBody();
        $data = json_decode($body, true);
        
        $this->assertArrayHasKey('error', $data);
    }

    private function createApplication()
    {
        // Create Slim app instance for testing
        // Implementation depends on your setup
    }

    private function createRequest(string $method, string $path, array $data = [])
    {
        // Create PSR-7 request for testing
        // Implementation depends on your setup
    }
}
```

## Testing Repositories

### Example: Testing Employee Repository

**File:** `tests/EmployeeRepositoryTest.php`

```php
<?php

namespace MintHCM\Api\Tests;

use MintHCM\Api\Entities\Employees;
use MintHCM\Api\Repositories\EmployeesRepository;
use PHPUnit\Framework\TestCase;

class EmployeeRepositoryTest extends TestCase
{
    private $repository;
    private $entityManager;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Get entity manager
        global $mint_app;
        $container = $mint_app->getContainer();
        $this->entityManager = $container->get(\Doctrine\ORM\EntityManager::class);
        
        $this->repository = $this->entityManager->getRepository(Employees::class);
    }

    public function testFindActiveEmployees(): void
    {
        $employees = $this->repository->findBy(['status' => 'Active']);
        
        $this->assertIsArray($employees);
        
        foreach ($employees as $employee) {
            $this->assertEquals('Active', $employee->status);
        }
    }

    public function testSearchByName(): void
    {
        $results = $this->repository->searchByName('John');
        
        $this->assertIsArray($results);
        
        foreach ($results as $employee) {
            $this->assertStringContainsStringIgnoringCase(
                'john',
                $employee->first_name . ' ' . $employee->last_name
            );
        }
    }

    public function testCreateEmployee(): void
    {
        $employee = new Employees();
        $employee->first_name = 'Test';
        $employee->last_name = 'User';
        $employee->user_name = 'testuser_' . time();
        $employee->date_entered = new \DateTime();
        
        $this->entityManager->persist($employee);
        $this->entityManager->flush();
        
        $this->assertNotNull($employee->id);
        
        // Cleanup
        $this->entityManager->remove($employee);
        $this->entityManager->flush();
    }

    public function testCount(): void
    {
        $total = $this->repository->count([]);
        
        $this->assertIsInt($total);
        $this->assertGreaterThan(0, $total);
    }
}
```

## Testing Services

### Example: Testing Employee Service

**File:** `tests/EmployeeServiceTest.php`

```php
<?php

namespace MintHCM\Api\Tests;

use MintHCM\Lib\Services\EmployeeService;
use PHPUnit\Framework\TestCase;

class EmployeeServiceTest extends TestCase
{
    private $service;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Initialize service with dependencies
        $this->service = new EmployeeService(
            $this->createMock(EmployeesRepository::class)
        );
    }

    public function testCalculateYearlySalary(): void
    {
        $employeeId = '123';
        
        // Mock repository
        $repository = $this->createMock(EmployeesRepository::class);
        
        $employee = new Employees();
        $employee->base_salary = 5000;
        $employee->annual_bonus = 10000;
        
        $repository->expects($this->once())
            ->method('find')
            ->with($employeeId)
            ->willReturn($employee);
        
        $service = new EmployeeService($repository);
        
        $yearlySalary = $service->calculateYearlySalary($employeeId);
        
        $this->assertEquals(70000, $yearlySalary); // 5000 * 12 + 10000
    }

    public function testCalculateYearlySalaryEmployeeNotFound(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Employee not found');
        
        $employeeId = 'nonexistent';
        
        $repository = $this->createMock(EmployeesRepository::class);
        $repository->expects($this->once())
            ->method('find')
            ->with($employeeId)
            ->willReturn(null);
        
        $service = new EmployeeService($repository);
        $service->calculateYearlySalary($employeeId);
    }
}
```

## Mocking

### Mocking Dependencies

```php
use PHPUnit\Framework\TestCase;

class MyTest extends TestCase
{
    public function testWithMock(): void
    {
        // Create mock
        $repository = $this->createMock(EmployeesRepository::class);
        
        // Set expectations
        $repository->expects($this->once())
            ->method('find')
            ->with('123')
            ->willReturn(new Employees());
        
        // Use mock
        $service = new EmployeeService($repository);
        $employee = $service->getEmployee('123');
        
        $this->assertInstanceOf(Employees::class, $employee);
    }
}
```

### Mocking Multiple Calls

```php
public function testMultipleCalls(): void
{
    $repository = $this->createMock(EmployeesRepository::class);
    
    $repository->expects($this->exactly(2))
        ->method('find')
        ->willReturn(new Employees());
    
    $service = new EmployeeService($repository);
    $service->getEmployee('123');
    $service->getEmployee('456');
}
```

### Mocking with Different Return Values

```php
public function testDifferentReturns(): void
{
    $repository = $this->createMock(EmployeesRepository::class);
    
    $repository->expects($this->exactly(2))
        ->method('find')
        ->willReturnOnConsecutiveCalls(
            new Employees(),
            null
        );
    
    $service = new EmployeeService($repository);
    
    $first = $service->getEmployee('123');
    $this->assertInstanceOf(Employees::class, $first);
    
    $second = $service->getEmployee('456');
    $this->assertNull($second);
}
```

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
    $employee = new Employees();
    $employee->first_name = 'John';
    $employee->last_name = 'Doe';
    
    $this->assertEmployeeName($employee, 'John', 'Doe');
}

private function assertEmployeeName(Employees $employee, string $first, string $last): void
{
    $this->assertEquals($first, $employee->first_name, 'First name mismatch');
    $this->assertEquals($last, $employee->last_name, 'Last name mismatch');
}
```

## Data Providers

Test the same logic with different data:

```php
/**
 * @dataProvider salaryDataProvider
 */
public function testCalculateSalary(float $base, float $bonus, float $expected): void
{
    $employee = new Employees();
    $employee->base_salary = $base;
    $employee->annual_bonus = $bonus;
    
    $result = $this->service->calculateYearlySalary($employee);
    
    $this->assertEquals($expected, $result);
}

public function salaryDataProvider(): array
{
    return [
        'standard salary' => [5000, 10000, 70000],
        'no bonus' => [5000, 0, 60000],
        'high bonus' => [5000, 50000, 110000],
    ];
}
```

## Testing Exceptions

```php
public function testThrowsException(): void
{
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('Invalid employee ID');
    
    $this->service->getEmployee('invalid');
}
```

## Integration Tests

Test multiple components together:

```php
class EmployeeIntegrationTest extends TestCase
{
    public function testCreateAndRetrieveEmployee(): void
    {
        // Create employee
        $data = [
            'first_name' => 'Integration',
            'last_name' => 'Test',
            'user_name' => 'inttest_' . time(),
        ];
        
        $request = $this->createRequest('POST', '/employees', $data);
        $response = $this->app->handle($request);
        
        $this->assertEquals(201, $response->getStatusCode());
        
        $body = json_decode((string) $response->getBody(), true);
        $employeeId = $body['data']['id'];
        
        // Retrieve employee
        $request = $this->createRequest('GET', "/employees/{$employeeId}");
        $response = $this->app->handle($request);
        
        $this->assertEquals(200, $response->getStatusCode());
        
        $body = json_decode((string) $response->getBody(), true);
        
        $this->assertEquals('Integration', $body['data']['first_name']);
        $this->assertEquals('Test', $body['data']['last_name']);
        
        // Cleanup
        $this->deleteEmployee($employeeId);
    }
}
```

## Test Database

### Using Transactions

Roll back changes after each test:

```php
class DatabaseTest extends TestCase
{
    private $entityManager;

    protected function setUp(): void
    {
        parent::setUp();
        
        global $mint_app;
        $container = $mint_app->getContainer();
        $this->entityManager = $container->get(\Doctrine\ORM\EntityManager::class);
        
        // Start transaction
        $this->entityManager->beginTransaction();
    }

    protected function tearDown(): void
    {
        // Rollback transaction
        $this->entityManager->rollback();
        
        parent::tearDown();
    }
}
```

### Test Fixtures

Create test data:

```php
class EmployeeTest extends TestCase
{
    private function createTestEmployee(): Employees
    {
        $employee = new Employees();
        $employee->first_name = 'Test';
        $employee->last_name = 'User';
        $employee->user_name = 'testuser_' . uniqid();
        $employee->date_entered = new \DateTime();
        
        $this->entityManager->persist($employee);
        $this->entityManager->flush();
        
        return $employee;
    }

    public function testSomethingWithEmployee(): void
    {
        $employee = $this->createTestEmployee();
        
        // Test logic...
        
        // Cleanup
        $this->entityManager->remove($employee);
        $this->entityManager->flush();
    }
}
```

## Best Practices

### 1. Test One Thing Per Test

```php
// ✅ Good - one assertion
public function testEmployeeHasFirstName(): void
{
    $employee = new Employees();
    $employee->first_name = 'John';
    
    $this->assertEquals('John', $employee->first_name);
}

// ❌ Bad - multiple unrelated assertions
public function testEmployee(): void
{
    $employee = new Employees();
    $this->assertNotNull($employee);
    $this->assertEquals('Active', $employee->status);
    $this->assertTrue($employee->save());
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
    $employee = new Employees();
    $employee->salary = 5000;
    
    // Act - perform action
    $yearlySalary = $employee->salary * 12;
    
    // Assert - verify result
    $this->assertEquals(60000, $yearlySalary);
}
```

### 4. Clean Up Test Data

```php
public function testCreateEmployee(): void
{
    $employee = $this->createEmployee();
    
    $this->assertNotNull($employee->id);
    
    // Clean up
    $this->entityManager->remove($employee);
    $this->entityManager->flush();
}
```

### 5. Don't Test Framework Code

```php
// ✅ Good - test your logic
public function testCalculateBonus(): void
{
    $bonus = $this->service->calculateBonus($employee);
    $this->assertEquals(5000, $bonus);
}

// ❌ Bad - testing Doctrine
public function testEntityManagerPersist(): void
{
    $this->entityManager->persist($employee);
    $this->assertTrue(true);
}
```

## Continuous Integration

### Running Tests Automatically

Add to your CI pipeline (e.g., GitHub Actions):

```yaml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    
    steps:
      - uses: actions/checkout@v2
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '7.4'
      
      - name: Install dependencies
        run: composer install
      
      - name: Run tests
        run: ./vendor/bin/phpunit
```

## Coverage Reports

Generate code coverage:

```bash
# HTML coverage report
./vendor/bin/phpunit --coverage-html coverage/

# Open report
open coverage/index.html
```

## Debugging Tests

```php
public function testDebug(): void
{
    $employee = $this->createEmployee();
    
    // Debug output
    var_dump($employee);
    print_r($employee);
    
    // Write to stderr (visible in PHPUnit output)
    fwrite(STDERR, "Debug: " . $employee->id . "\n");
    
    $this->assertTrue(true);
}
```

## Next Steps

- Review [Controllers & Actions](./09-controllers.md)
- Learn about [Entities & Repositories](./10-entities-repositories.md)
- Understand [Extending the API](./08-extending-api.md)

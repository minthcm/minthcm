<?php

use MintHCM\Lib\Search\Base\SearchQuery;
use PHPUnit\Framework\TestCase;

/**
 * Verifies that SearchQuery's property declarations have correct types and defaults.
 * Uses ReflectionClass to avoid instantiating the abstract class.
 */
class SearchQueryTest extends TestCase
{
    private \ReflectionClass $reflection;

    protected function setUp(): void
    {
        $this->reflection = new \ReflectionClass(SearchQuery::class);
    }

    public function testQueryPropertyIsArrayWithEmptyDefault(): void
    {
        $defaults = $this->reflection->getDefaultProperties();
        $this->assertArrayHasKey('query', $defaults);
        $this->assertSame([], $defaults['query']);
    }

    public function testSortPropertyDefaultIsNull(): void
    {
        $defaults = $this->reflection->getDefaultProperties();
        $this->assertArrayHasKey('sort', $defaults);
        $this->assertNull($defaults['sort']);
    }

    public function testSizeDefaultIsZero(): void
    {
        $defaults = $this->reflection->getDefaultProperties();
        $this->assertArrayHasKey('size', $defaults);
        $this->assertSame(0, $defaults['size']);
    }

    public function testFromDefaultIsZero(): void
    {
        $defaults = $this->reflection->getDefaultProperties();
        $this->assertArrayHasKey('from', $defaults);
        $this->assertSame(0, $defaults['from']);
    }

    public function testAddAclFiltersDefaultIsFalse(): void
    {
        $defaults = $this->reflection->getDefaultProperties();
        $this->assertArrayHasKey('add_acl_filters', $defaults);
        $this->assertFalse($defaults['add_acl_filters']);
    }

    public function testAllPropertiesAreDeclaredExplicitly(): void
    {
        $properties = ['params', 'query', 'sort', 'size', 'from', 'add_acl_filters'];
        foreach ($properties as $name) {
            $this->assertTrue(
                $this->reflection->hasProperty($name),
                "Property \$$name is not declared in SearchQuery"
            );
            $this->assertTrue(
                $this->reflection->getProperty($name)->isProtected(),
                "Property \$$name should be protected"
            );
        }
    }
}

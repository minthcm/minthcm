<?php

use MintHCM\Lib\Search\ElasticSearch\ElasticOperator;
use PHPUnit\Framework\TestCase;

/**
 * Verifies that ElasticOperator's property declarations have correct types and defaults.
 * Uses ReflectionClass to avoid instantiating the abstract class.
 */
class ElasticOperatorTest extends TestCase
{
    private \ReflectionClass $reflection;

    protected function setUp(): void
    {
        $this->reflection = new \ReflectionClass(ElasticOperator::class);
    }

    public function testFieldDefaultIsNull(): void
    {
        $defaults = $this->reflection->getDefaultProperties();
        $this->assertNull($defaults['field']);
    }

    public function testValueDefaultIsNull(): void
    {
        $defaults = $this->reflection->getDefaultProperties();
        $this->assertNull($defaults['value']);
    }

    public function testNotDefaultIsFalse(): void
    {
        $defaults = $this->reflection->getDefaultProperties();
        $this->assertFalse($defaults['not']);
    }

    public function testBoostDefaultIsOne(): void
    {
        $defaults = $this->reflection->getDefaultProperties();
        $this->assertSame(1.0, $defaults['boost']);
    }

    public function testDataDefaultIsNull(): void
    {
        $defaults = $this->reflection->getDefaultProperties();
        $this->assertNull($defaults['data']);
    }

    public function testAllPropertiesAreDeclaredProtected(): void
    {
        $properties = ['field', 'value', 'not', 'boost', 'data'];
        foreach ($properties as $name) {
            $this->assertTrue(
                $this->reflection->hasProperty($name),
                "Property \$$name is not declared in ElasticOperator"
            );
            $this->assertTrue(
                $this->reflection->getProperty($name)->isProtected(),
                "Property \$$name should be protected"
            );
        }
    }
}

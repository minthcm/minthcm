<?php

use MintHCM\Lib\Search\Base\SearchResult;
use PHPUnit\Framework\TestCase;

/**
 * Verifies that SearchResult's property declarations have correct types and defaults.
 * Uses ReflectionClass to avoid instantiating the abstract class.
 */
class SearchResultTest extends TestCase
{
    private \ReflectionClass $reflection;

    protected function setUp(): void
    {
        $this->reflection = new \ReflectionClass(SearchResult::class);
    }

    /**
     * @dataProvider arrayPropertiesProvider
     */
    public function testArrayPropertiesDefaultToEmptyArray(string $propName): void
    {
        $defaults = $this->reflection->getDefaultProperties();
        $this->assertArrayHasKey($propName, $defaults, "Property \$$propName should exist in SearchResult");
        $this->assertSame([], $defaults[$propName], "Property \$$propName should default to []");
    }

    public static function arrayPropertiesProvider(): array
    {
        return [
            ['result'],
            ['grouped_ids'],
            ['hits'],
            ['indice_module_map'],
        ];
    }

    public function testBeansDefaultsToNull(): void
    {
        $defaults = $this->reflection->getDefaultProperties();
        $this->assertArrayHasKey('beans', $defaults);
        $this->assertNull($defaults['beans'], 'Property $beans should default to null');
    }

    public function testHandleAclDefaultIsFalse(): void
    {
        $defaults = $this->reflection->getDefaultProperties();
        $this->assertFalse($defaults['handle_acl']);
    }

    /**
     * @dataProvider intPropertiesProvider
     */
    public function testIntPropertiesDefaultToZero(string $propName): void
    {
        $defaults = $this->reflection->getDefaultProperties();
        $this->assertArrayHasKey($propName, $defaults, "Property \$$propName should exist in SearchResult");
        $this->assertSame(0, $defaults[$propName], "Property \$$propName should default to 0");
    }

    public static function intPropertiesProvider(): array
    {
        return [
            ['size'],
            ['current_offset'],
            ['total'],
        ];
    }

    public function testAllPropertiesAreDeclaredProtected(): void
    {
        $properties = ['result', 'grouped_ids', 'beans', 'hits', 'handle_acl',
                       'indice_module_map', 'size', 'current_offset', 'total'];
        foreach ($properties as $name) {
            $this->assertTrue(
                $this->reflection->hasProperty($name),
                "Property \$$name is not declared in SearchResult"
            );
            $this->assertTrue(
                $this->reflection->getProperty($name)->isProtected(),
                "Property \$$name should be protected"
            );
        }
    }
}

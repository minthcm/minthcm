<?php

use MintHCM\Modules\Comments\AccessChecker\AccessChecker;
use PHPUnit\Framework\TestCase;

/**
 * Verifies that AccessChecker's $parent property is declared correctly
 * and that the constructor assigns it to the declared property (not a dynamic one).
 */
class AccessCheckerTest extends TestCase
{
    public function testParentPropertyIsDeclared(): void
    {
        $reflection = new \ReflectionClass(AccessChecker::class);
        $this->assertTrue(
            $reflection->hasProperty('parent'),
            'Property $parent should be declared in AccessChecker'
        );
        $this->assertTrue(
            $reflection->getProperty('parent')->isProtected(),
            'Property $parent should be protected'
        );
    }

    public function testConstructorAssignsParentToTypedProperty(): void
    {
        $parentObject = new \stdClass();
        $checker = new AccessChecker($parentObject);

        $reflection = new \ReflectionClass($checker);
        $property = $reflection->getProperty('parent');
        $property->setAccessible(true);

        $this->assertSame(
            $parentObject,
            $property->getValue($checker),
            'Constructor should assign $parent to the declared property'
        );
    }
}

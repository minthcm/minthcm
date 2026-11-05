<?php

namespace MintHCM\Tests\Unit\Legacy;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use SugarBean;
use SugarRelationship;

/**
 * Regression for #197083: deleting a record with parent_type/parent_id children (e.g. Kudos with
 * Reactions) ended with "Call to a member function save() on bool", because the already deleted
 * parent (BeanFactory::getBean() === false) was added to the resave list.
 *
 * Loads the legacy classes directly — no legacy bootstrap / database is needed for the resave list.
 */
final class SugarRelationshipResaveListTest extends TestCase
{
    private const LEGACY_DIR = __DIR__ . '/../../../../legacy';

    private static string $previous_cwd;

    public static function setUpBeforeClass(): void
    {
        self::$previous_cwd = getcwd();
        // Legacy files include their dependencies with paths relative to legacy/
        chdir(self::LEGACY_DIR);
        if (!defined('sugarEntry')) {
            define('sugarEntry', true);
        }
        require_once 'data/Relationships/SugarRelationship.php';
        require_once 'data/SugarBean.php';
    }

    public static function tearDownAfterClass(): void
    {
        chdir(self::$previous_cwd);
    }

    protected function setUp(): void
    {
        $this->setResaveList([]);
    }

    protected function tearDown(): void
    {
        $this->setResaveList([]);
    }

    #[DataProvider('nonBeanProvider')]
    public function testIgnoresValueThatIsNotABean(mixed $not_a_bean): void
    {
        SugarRelationship::addToResaveList($not_a_bean);

        $this->assertSame([], $this->getResaveList());
    }

    public function testResaveDoesNotFailAfterAddingDeletedParent(): void
    {
        // BeanFactory::getBean() returns false for a parent already marked as deleted
        SugarRelationship::addToResaveList(false);

        // The actual oracle: before the fix this call ended with "Call to a member function save() on bool"
        SugarRelationship::resaveRelatedBeans();

        $this->assertSame([], $this->getResaveList());
    }

    public function testAddsBeanUnderModuleAndId(): void
    {
        $bean = (new ReflectionClass(SugarBean::class))->newInstanceWithoutConstructor();
        $bean->module_dir = 'Kudos';
        $bean->id = 'kudos-id';

        SugarRelationship::addToResaveList($bean);

        $this->assertSame(['Kudos' => ['kudos-id' => $bean]], $this->getResaveList());
    }

    public static function nonBeanProvider(): array
    {
        return [
            'false (deleted record)' => [false],
            'null' => [null],
        ];
    }

    private function getResaveList(): array
    {
        $property = (new ReflectionClass(SugarRelationship::class))->getProperty('beansToResave');
        $property->setAccessible(true);

        return $property->getValue();
    }

    private function setResaveList(array $beans): void
    {
        $property = (new ReflectionClass(SugarRelationship::class))->getProperty('beansToResave');
        $property->setAccessible(true);
        $property->setValue(null, $beans);
    }
}

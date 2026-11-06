<?php

namespace MintHCM\Data\MassActions\Actions {

    use MintHCM\Data\MassActions\MassAction;

    /**
     * Core action without a custom counterpart.
     */
    class LoaderTestCoreOnly extends MassAction
    {
        public function execute()
        {
            return true;
        }

        public function hasAccess()
        {
            return true;
        }
    }

    /**
     * Core action overridden by a custom class of the same short name.
     */
    class LoaderTestOverridable extends MassAction
    {
        public function execute()
        {
            return true;
        }

        public function hasAccess()
        {
            return true;
        }
    }
}

namespace MintHCM\Custom\Data\MassActions\Actions {

    use MintHCM\Data\MassActions\Actions\LoaderTestOverridable as CoreLoaderTestOverridable;
    use MintHCM\Data\MassActions\MassAction;

    class LoaderTestOverridable extends CoreLoaderTestOverridable
    {
    }

    /**
     * Brand new custom action -- no core counterpart at all.
     */
    class LoaderTestCustomOnly extends MassAction
    {
        public function execute()
        {
            return true;
        }

        public function hasAccess()
        {
            return true;
        }
    }
}

namespace MintHCM\Tests\Unit\MassActions {

    use InvalidArgumentException;
    use MintHCM\Data\MassActions\MassActionLoader;
    use PHPUnit\Framework\TestCase;

    final class MassActionLoaderTest extends TestCase
    {
        public function testResolvesShortNameToCoreActionWhenNoCustomExists(): void
        {
            $action = MassActionLoader::getAction('LoaderTestCoreOnly', 'Accounts', []);

            self::assertSame('MintHCM\Data\MassActions\Actions\LoaderTestCoreOnly', get_class($action));
        }

        public function testShortNamePrefersCustomOverrideOfCoreAction(): void
        {
            $action = MassActionLoader::getAction('LoaderTestOverridable', 'Accounts', []);

            self::assertSame('MintHCM\Custom\Data\MassActions\Actions\LoaderTestOverridable', get_class($action));
        }

        public function testResolvesShortNameOfActionThatExistsOnlyInCustom(): void
        {
            $action = MassActionLoader::getAction('LoaderTestCustomOnly', 'Accounts', []);

            self::assertSame('MintHCM\Custom\Data\MassActions\Actions\LoaderTestCustomOnly', get_class($action));
        }

        public function testFullyQualifiedCoreNameIsReplacedByItsCustomOverride(): void
        {
            $action = MassActionLoader::getAction('MintHCM\Data\MassActions\Actions\LoaderTestOverridable', 'Accounts', []);

            self::assertSame('MintHCM\Custom\Data\MassActions\Actions\LoaderTestOverridable', get_class($action));
        }

        public function testFullyQualifiedNameWithLeadingSeparatorStillFindsCustomOverride(): void
        {
            $action = MassActionLoader::getAction('\\MintHCM\\Data\\MassActions\\Actions\\LoaderTestOverridable', 'Accounts', []);

            self::assertSame('MintHCM\\Custom\\Data\\MassActions\\Actions\\LoaderTestOverridable', get_class($action));
        }

        public function testFullyQualifiedCustomNameIsUsedAsGiven(): void
        {
            $action = MassActionLoader::getAction('MintHCM\Custom\Data\MassActions\Actions\LoaderTestCustomOnly', 'Accounts', []);

            self::assertSame('MintHCM\Custom\Data\MassActions\Actions\LoaderTestCustomOnly', get_class($action));
        }

        public function testPassesConstructorArgumentsToTheResolvedAction(): void
        {
            $action = MassActionLoader::getAction('LoaderTestCoreOnly', 'Accounts', ['id-1', 'id-2']);

            self::assertSame(
                ['icon' => '', 'label' => '', 'action' => 'LoaderTestCoreOnly'],
                $action->getFrontendData()
            );
        }

        public function testThrowsWhenActionDoesNotExist(): void
        {
            $this->expectException(InvalidArgumentException::class);

            MassActionLoader::getAction('LoaderTestMissing', 'Accounts', []);
        }

        public function testThrowsWhenResolvedClassIsNotAMassAction(): void
        {
            $this->expectException(InvalidArgumentException::class);

            MassActionLoader::getAction(self::class, 'Accounts', []);
        }
    }
}

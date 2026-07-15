<?php

declare (strict_types=1);
namespace Symplify\VendorPatches\DependencyInjection;

use VendorPatches202602\Entropy\Container\Container;
use VendorPatches202602\SebastianBergmann\Diff\Differ;
use VendorPatches202602\SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;
final class ContainerFactory
{
    public static function create() : Container
    {
        $container = new Container();
        $container->autodiscover(__DIR__ . '/../Command');
        // differ
        $container->service(UnifiedDiffOutputBuilder::class, static function () : UnifiedDiffOutputBuilder {
            return new UnifiedDiffOutputBuilder("--- Original\n+++ New\n", \true);
        });
        $container->service(Differ::class, static function (Container $container) : Differ {
            return new Differ($container->make(UnifiedDiffOutputBuilder::class));
        });
        return $container;
    }
}

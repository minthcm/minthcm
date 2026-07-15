<?php

declare (strict_types=1);
namespace Symplify\VendorPatches\Composer;

use VendorPatches202602\Entropy\Utils\FileSystem;
use Symplify\VendorPatches\Exception\ShouldNotHappenException;
use Symplify\VendorPatches\FileSystem\PathResolver;
use VendorPatches202602\Webmozart\Assert\Assert;
/**
 * @see \Symplify\VendorPatches\Tests\Composer\PackageNameResolverTest
 */
final class PackageNameResolver
{
    public function resolveFromFilePath(string $vendorFile) : string
    {
        $packageComposerJsonFilePath = $this->getPackageComposerJsonFilePath($vendorFile);
        $composerJson = FileSystem::loadFileToJson($packageComposerJsonFilePath);
        if (!isset($composerJson['name'])) {
            throw new ShouldNotHappenException();
        }
        return $composerJson['name'];
    }
    private function getPackageComposerJsonFilePath(string $vendorFilePath) : string
    {
        $vendorPackageDirectory = PathResolver::resolveVendorDirectory($vendorFilePath);
        $packageComposerJsonFilePath = $vendorPackageDirectory . '/composer.json';
        Assert::fileExists($packageComposerJsonFilePath);
        return $packageComposerJsonFilePath;
    }
}

<?php

declare (strict_types=1);
namespace Symplify\VendorPatches;

use VendorPatches202602\Composer\Autoload\ClassLoader;
use ReflectionClass;
use VendorPatches202602\Webmozart\Assert\Assert;
final class VendorDirProvider
{
    public static function provide() : string
    {
        $rootFolder = \getenv('SystemDrive', \true) . \DIRECTORY_SEPARATOR;
        $path = __DIR__;
        while (\substr_compare($path, 'vendor', -\strlen('vendor')) !== 0 && $path !== $rootFolder) {
            $path = \dirname($path);
        }
        if ($path !== $rootFolder) {
            return $path;
        }
        return self::reflectionFallback();
    }
    private static function reflectionFallback() : string
    {
        $reflectionClass = new ReflectionClass(ClassLoader::class);
        $classLoaderFileName = $reflectionClass->getFileName();
        Assert::string($classLoaderFileName);
        return \dirname($classLoaderFileName, 2);
    }
}

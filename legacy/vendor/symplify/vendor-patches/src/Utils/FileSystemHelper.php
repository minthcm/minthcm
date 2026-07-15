<?php

declare (strict_types=1);
namespace Symplify\VendorPatches\Utils;

final class FileSystemHelper
{
    /**
     * Converts backslashes to slashes.
     */
    public static function normalizePath(string $path) : string
    {
        return \str_replace('\\', '/', $path);
    }
}

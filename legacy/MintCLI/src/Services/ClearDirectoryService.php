<?php

namespace MintHCM\MintCLI\Services;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ClearDirectoryService
{
    public function run($dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        $iterator = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($files as $file) {
            $file->isDir() ? rmdir($file) : unlink($file);
        }

        rmdir($dir);
    }
}

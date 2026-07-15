<?php

use PHPUnit\Framework\TestCase;

/**
 * Guards against accidental re-introduction of #[\AllowDynamicProperties].
 *
 * Scans all PHP files in api/ (excluding vendor/) and verifies that the attribute
 * is only present on explicitly approved proxy classes.
 *
 * Run after every batch: php vendor/bin/phpunit tests/Unit/DynamicPropertiesTest.php
 */
class DynamicPropertiesTest extends TestCase
{
    /**
     * Classes intentionally keeping #[\AllowDynamicProperties].
     * These use the proxy pattern (__get/__set/__call) to forward access to legacy objects.
     *
     * @var array<string>
     */
    private const ALLOWED_FILES = [
        'utils/LegacyConnector.php', // proxy to legacy Sugar object via __get/__set/__call
        'data/MintBean.php',         // proxy to legacy SugarBean via __get/__set/__call
    ];

    public function testNoDisallowedAllowDynamicProperties(): void
    {
        $apiDir = dirname(__DIR__, 2); // api/
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($apiDir, FilesystemIterator::SKIP_DOTS)
        );

        $violations = [];

        /** @var SplFileInfo $file */
        foreach ($iterator as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $realPath = $file->getRealPath();

            // Skip vendor
            if (strpos($realPath, DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR) !== false) {
                continue;
            }

            $relativePath = ltrim(str_replace($apiDir, '', $realPath), DIRECTORY_SEPARATOR);

            // Skip whitelisted proxy files
            if (in_array(str_replace(DIRECTORY_SEPARATOR, '/', $relativePath), self::ALLOWED_FILES, true)) {
                continue;
            }

            $contents = file_get_contents($realPath);
            if ($contents === false) {
                continue;
            }

            // Match the attribute as PHP code construct (not inside strings or comments)
            if (preg_match('/^\s*#\[\\\AllowDynamicProperties\]/m', $contents)) {
                $violations[] = $relativePath;
            }
        }

        sort($violations);

        $this->assertEmpty(
            $violations,
            sprintf(
                "#[\\AllowDynamicProperties] found in %d non-whitelisted file(s):\n  - %s\n\n"
                . "Either declare explicit properties or add the file to ALLOWED_FILES if it "
                . "intentionally uses the proxy pattern.",
                count($violations),
                implode("\n  - ", $violations)
            )
        );
    }
}

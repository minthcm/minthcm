<?php

/**
 * Merges a module's recordviewdefs source definition with all recordview extension files
 * registered for that module.
 *
 * Each extension file is a plain PHP script that mutates the same $viewdefs[$module] array
 * already populated by the source file -- full access to the array, no partial-merge semantics,
 * applied strictly in the order given by the caller (plain filename sort, no `_override`-style
 * special casing). Extension files should stick to mutating $viewdefs -- declaring a function or
 * class here is not safe: a "Cannot redeclare" fatal (e.g. the same file built twice in one
 * request, or two files picking the same name) is a compile error, not a Throwable, so it is not
 * caught below and can still abort the whole Quick Repair & Rebuild run.
 *
 * Pure by design -- takes file paths in, returns an array out. No cache reads/writes and no
 * required Sugar globals, so it stays unit-testable without bootstrapping the framework; the
 * one exception is a guarded, best-effort log call on the failure path below.
 */
class RecordViewDefsBuilder
{
    /**
     * @param string $module Module name the definition belongs to (e.g. 'Employees').
     * @param string $source_file Path to the base or custom-overridden recordviewdefs.php.
     * @param string[] $extension_files Paths to recordview extension files, in application order.
     * @return array The merged $viewdefs[$module] definition, or [] if the source file is missing/unreadable
     *               or a fatal error is thrown while including it or one of the extension files.
     */
    public static function build(string $module, string $source_file, array $extension_files): array
    {
        if (!is_file($source_file) || !is_readable($source_file)) {
            return [];
        }

        try {
            $viewdefs = [];
            include $source_file;

            foreach ($extension_files as $extension_file) {
                if (!is_file($extension_file) || !is_readable($extension_file)) {
                    continue;
                }
                include $extension_file;
            }
        } catch (\Throwable $e) {
            if (isset($GLOBALS['log'])) {
                $GLOBALS['log']->fatal("RecordViewDefsBuilder: failed to build recordviewdefs for module '$module': " . $e->getMessage());
            }
            return [];
        }

        return $viewdefs[$module] ?? [];
    }
}

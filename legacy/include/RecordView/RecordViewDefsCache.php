<?php

require_once __DIR__ . '/RecordViewDefsBuilder.php';

/**
 * Cache for merged recordviewdefs definitions (source file + recordview extensions), one file
 * per module under cache/modules/<module>/recordviewdefs.php.
 *
 * Mirrors the read/lazy-build/refresh contract VardefManager already uses for vardefs, applied
 * to the recordview extension mechanism:
 * - get() is called by the metadata reader on every front-end init; it builds the file on first
 *   read if it is missing, so a fresh install or a package dropped in without a rebuild never
 *   sees an empty view.
 * - refreshAll() is called from Quick Repair & Rebuild (ModuleInstaller::rebuild_all()).
 * - refresh($module) lets a package regenerate just its own module's cache at runtime -- e.g.
 *   right after it writes a new recordview extension file -- without triggering a full instance
 *   rebuild, and tells the frontend to pick up the change on its next init.
 *
 * All paths here are relative, like the rest of legacy -- callers must run with cwd = legacy/
 * (already true for Quick Repair & Rebuild and ModuleInstaller; MetaController chdir()s there
 * before calling in).
 */
class RecordViewDefsCache
{
    /** sprintf pattern for the directory holding a module's recordview extension files. */
    private const EXTENSION_DIR = 'custom/Extension/modules/%s/recordview';

    /**
     * Returns the merged recordviewdefs definition for a module, building and caching it first
     * if the cache file does not exist yet.
     *
     * @return array The $viewdefs[$module] definition, or [] if the module has no recordviewdefs source.
     */
    public static function get(string $module): array
    {
        $cache_file = self::cacheFile($module);
        if (!is_file($cache_file)) {
            self::rebuildAndSave($module);
        }

        if (!is_file($cache_file)) {
            return [];
        }

        $viewdefs = [];
        include $cache_file;

        return $viewdefs[$module] ?? [];
    }

    /**
     * Forces a rebuild of a single module's cache and signals the frontend that metadata
     * changed, without running a full instance rebuild. Intended for packages that add or
     * change a recordview extension file programmatically at runtime.
     */
    public static function refresh(string $module): void
    {
        self::rebuildAndSave($module);
        updateMintRebuildFile(['reload_module_menu']);
    }

    /**
     * Rebuilds the cache for every installed module. Called from Quick Repair & Rebuild.
     *
     * A module whose source or extension files throw while being built (see
     * RecordViewDefsBuilder::build()) does not stop the loop -- the rest of the instance still
     * gets rebuilt. That module's own cache is overwritten with an empty definition (the same
     * "a broken extension file breaks the module's view" contract documented for packages), not
     * left at its previous, possibly-working state.
     */
    public static function refreshAll(): void
    {
        foreach (get_module_dir_list() as $module) {
            self::rebuildAndSave($module);
        }
    }

    /**
     * Deletes the cache file for a module, or for every module when none is given.
     */
    public static function clear(?string $module = null): void
    {
        if (null !== $module) {
            self::deleteFile(self::cacheFile($module));
            return;
        }

        foreach (get_module_dir_list() as $module_name) {
            self::deleteFile(self::cacheFile($module_name));
        }
    }

    /**
     * Builds the merged definition and writes it to the module's cache file. Deletes a stale
     * cache file when the module no longer has a recordviewdefs source at all.
     */
    private static function rebuildAndSave(string $module): void
    {
        $source_file = self::sourceFile($module);
        if (null === $source_file) {
            self::deleteFile(self::cacheFile($module));
            return;
        }

        $definition = RecordViewDefsBuilder::build($module, $source_file, self::extensionFiles($module));
        self::save($module, $definition);
    }

    /**
     * Resolves the source recordviewdefs.php for a module -- the custom/ override when present,
     * otherwise the base module file. Reuses the same resolution SugarView uses for legacy views.
     */
    private static function sourceFile(string $module): ?string
    {
        $view = new ViewRecord();
        $view->module = $module;
        $file = $view->getMetaDataFile();

        return ($file && is_file($file)) ? $file : null;
    }

    /**
     * @return string[] Paths to the module's recordview extension files, sorted by filename so
     *                   merging is deterministic and multiple packages apply in a stable order.
     */
    private static function extensionFiles(string $module): array
    {
        $files = glob(sprintf(self::EXTENSION_DIR, $module) . '/*.php') ?: [];
        sort($files);

        return $files;
    }

    private static function cacheFile(string $module): string
    {
        return sugar_cached('modules/' . $module . '/recordviewdefs.php');
    }

    private static function save(string $module, array $definition): void
    {
        $file = create_cache_directory('modules/' . $module . '/recordviewdefs.php');
        $contents = "<?php \n\$viewdefs[\"" . $module . "\"] = " . var_export($definition, true) . ";\n";
        sugar_file_put_contents_atomic($file, $contents);
    }

    private static function deleteFile(string $file): void
    {
        if (is_file($file)) {
            unlink($file);
        }
    }
}

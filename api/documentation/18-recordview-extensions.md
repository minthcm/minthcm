# RecordView Extensions

`recordviewdefs.php` (see [Record View Definitions](../../vue/documentation/12-recordviewdefs.md)) is the single source of a module's record-view layout — panels, sidepanel widgets, and record-level actions. Historically the only way for a package to change it without editing the module's own file was to override the whole `recordviewdefs.php` in `custom/modules/{Module}/metadata/` — destructive, and unusable by two packages at once. This document covers the additive alternative: a dedicated Extension type for recordview, plus the cache that makes reading it cheap.

## Writing an extension file

Drop a PHP file into:

```
legacy/custom/Extension/modules/{Module}/recordview/
```

Any filename ending in `.php` is picked up. The file has full read/write access to the same `$viewdefs['{Module}']` array the source `recordviewdefs.php` populates — add a panel, append or remove an action, replace a whole section, drop the sidepanel entirely. There is no declarative "patch" format; you mutate the array directly, the same contract as `Extension/Vardefs`:

```php
<?php
// legacy/custom/Extension/modules/Meetings/recordview/close_button.php
$viewdefs['Meetings']['panels']['basicInfo']['data']['actions'][] = [
    'key' => 'CloseMeeting',
    'label' => 'LBL_CLOSE_BUTTON_TITLE',
    'icon' => 'mdi-check',
    'acl' => 'edit',
];
```

```php
<?php
// legacy/custom/Extension/modules/Employees/recordview/hide_sidepanel.php
unset($viewdefs['Employees']['sidepanel']);
```

**Merge order.** When a module has multiple extension files (from one package or several), they are applied in ascending filename order (plain `sort()`, no special-cased names), after the source definition. If two files touch the same key, the one that sorts later wins. Resolving conflicts between packages that touch the same fragment on purpose is out of scope; name your files so the order you need is the order you get — a numeric prefix like `01-`, `02-` is a common way to pin it.

**A broken extension file breaks the module's view.** There is no validation or isolation — a fatal error in an extension file surfaces exactly like a fatal error in `recordviewdefs.php` itself. Keep extension files small and test them.

**Stick to mutating `$viewdefs` — don't declare functions or classes.** The builder wraps the merge in a `try`/`catch` so one module's broken extension can't abort the rebuild for every other module, but a "Cannot redeclare" fatal (the same file built twice in one request, or two extension files picking the same function/class name) is a PHP compile error, not a catchable exception — it isn't covered by that `try`/`catch` and can still take down the whole run.

## Where the merged result lives: the cache

The merge (source file + all extensions, in order) is written to:

```
legacy/cache/modules/{Module}/recordviewdefs.php
```

The frontend metadata reader (`MetaController::getRecordViewMeta()`, called from `POST /init`) reads **only** this cache file — never the module's `recordviewdefs.php` or its extensions directly. This is what makes reading metadata cheap: no `glob()`, no re-merging, on every request.

Two things write this file:

- **Quick Repair & Rebuild.** `ModuleInstaller::rebuild_all()` calls `rebuild_recordviewdefs()`, which rebuilds the cache for every module. This also runs whenever `ModuleInstaller` installs or removes a package, so a package that ships a recordview extension file gets it merged in automatically as part of its own install step.
- **Lazy-build on read.** If a module's cache file doesn't exist yet — a fresh install, or a package dropped onto the filesystem without running a rebuild — `RecordViewDefsCache::get()` builds it on the first read and saves it, so a missing rebuild never means a broken or empty view.

**The cache is not invalidated automatically.** Editing `recordviewdefs.php` or an extension file does not do anything by itself — the existing cache file keeps being served until something rebuilds it. During development this means an edit needs a Quick Repair & Rebuild (or the `refresh()` call below) before it shows up, unlike the pre-cache behavior where the file was read fresh on every request.

## Regenerating one module's cache from code

A package that changes its own recordview extension file at runtime — for example, generating a button definition programmatically — doesn't need to trigger a full instance rebuild just to see it. `RecordViewDefsCache::refresh($module)` rebuilds the cache for that one module and marks the frontend's metadata as stale, so the next `POST /init` picks up the change:

```php
\SugarAutoLoader::requireWithCustom('include/RecordView/RecordViewDefsCache.php');
\RecordViewDefsCache::refresh('Meetings');
```

This is a plain PHP call, not an HTTP endpoint — call it from legacy code (a logic hook, a scheduler, an install step), with the working directory set to `legacy/` like the rest of the legacy layer expects. There is no route exposing this over HTTP; if a future need requires triggering it from outside PHP, that's a separate addition.

`RecordViewDefsCache` also exposes `refreshAll()` (rebuild every module — what Quick Repair & Rebuild calls) and `clear($module = null)` (delete a module's cache file, or all of them, without rebuilding).

## Related

- [Record View Definitions](../../vue/documentation/12-recordviewdefs.md) — the `recordviewdefs.php` format itself (panels, components, actions).
- `legacy/include/RecordView/RecordViewDefsBuilder.php` — the pure merge function (source + extension files → array), unit-tested in `api/tests/unit/RecordView/RecordViewDefsBuilderTest.php`.
- `legacy/include/RecordView/RecordViewDefsCache.php` — the cache: `get()`, `refresh()`, `refreshAll()`, `clear()`.

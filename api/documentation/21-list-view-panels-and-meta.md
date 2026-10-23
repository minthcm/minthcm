# List View Panels, Record View Function Options and Schedulers

This page describes the API-side pieces introduced when the **Schedulers** module was moved from the legacy view to the native Vue views. Most of them are generic and can be reused by other modules.

## Switching a Module to Vue Views

Whether a module is rendered by a native Vue view or by the `LegacyView` iframe is controlled by `api/constants/legacy_views.php`:

```php
'Schedulers' => [
    'list' => false,
    'record' => false,
],
```

For a module rendered by Vue the following must exist:

- `metadata/recordviewdefs.php` and `metadata/eslistviewdefs.php` in the module (see the Vue [Record View Definitions](../../vue/documentation/12-recordviewdefs.md)),
- a Doctrine entity - enabled by `'doctrineEntity' => []` in the module's `vardefs.php` and generated into `api/app/Entities/` (see [Entities and Repositories](./10-entities-repositories.md)),
- optionally, MintLogic rules in `api/lib/MintLogic/Modules/{Module}/logicdefs.php` (see [MintLogic](./13-mintlogic.md)).

`Schedulers` uses a MintLogic file to make `last_run` read-only, to default `date_time_start` for new records and to show `job_url` only when `job` equals `url::`.

## List View Panels (`panels` in `eslistviewdefs.php`)

A module can attach extra Vue components under its list view. They are declared by name in `eslistviewdefs.php`:

```php
$ESListViewDefs['Schedulers'] = [
    // ...
    'panels' => [
        'Schedulers',
    ],
];
```

`ListInitController::preparePanels()` passes the list unchanged to the frontend as `panels` inside the list view defs. Resolving the names into components is done on the Vue side - see [List View Panels](../../vue/documentation/16-list-view-panels.md).

## Function-Based Options in Record View (`MetaController`)

An `enum` or `multienum` field in `recordviewdefs.php` can take its options from a function instead of a dropdown list:

```php
['name' => 'job', 'type' => 'enum', 'function' => ['name' => ['Scheduler', 'getJobsList'], 'params' => []]],
```

`MetaController::resolveRecordViewFunctionOptions()` runs while the record view metadata is built:

- it loads the module language (`$mod_strings`) first, so the function can return translated labels,
- `function.name` is either a `[class, method]` pair or the name of a global function, and `function.params` are passed as arguments,
- a non-empty result is stored as the field's `options` (key => label map),
- the `function` key is always removed, so no callable is exposed to the client.

Only `enum` and `multienum` fields are processed. This is the record view counterpart of the list view columns, where options of fields using a vardef `function` are resolved separately.

## Server Information for Admins (`Init`)

For administrators only, `Init::getFullData()` adds two entries to the init response:

| Key | Value |
|---|---|
| `document_root` | absolute path of the `legacy` directory (the directory with `cron.php`) |
| `web_server_user` | user running the web server (`exec('whoami')`, falling back to `posix_getpwuid`) |

They are used by the Schedulers list view panel to show a ready-to-copy crontab line. Non-admin users do not receive them.

## Scheduler Job Names

`Scheduler::getJobsList()` now also lists class-based jobs (`RunnableSchedulerJob`) as `class::{ClassName}`. The label is taken from `LBL_{CLASSNAME}` in the Schedulers language file and falls back to the class name. `Scheduler::rebuildDefaultSchedulers()` creates the default schedulers, including `indexMissingElasticsearchRecords`.

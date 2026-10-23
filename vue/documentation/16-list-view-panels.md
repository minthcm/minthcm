# List View Panels and the `crontab` Field

## List View Panels

A module can show extra components below its list. The module only lists panel names in `eslistviewdefs.php` (see the API [List View Panels](../../api/documentation/21-list-view-panels-and-meta.md)), and `ListView.vue` renders them:

```php
$ESListViewDefs['Schedulers'] = [
    'panels' => ['Schedulers'],
];
```

Resolution of a name `X` (`ListView.vue`, `resolvePanel()`):

1. `vue/src/custom/components/ListViewPanels/X.vue` - customizations win,
2. `vue/src/components/ListViewPanels/X.vue` - core panels,
3. otherwise the name is ignored.

Both directories are loaded with `import.meta.glob` (eager). A panel receives one prop, `module`, and is rendered after the list table and the mass update panel. The panel list is available as `panels` in the `Defs` of `ListViewStore.ts`.

Example: `ListViewPanels/Schedulers.vue` shows the instruction for configuring the system crontab. It reads `document_root` and `web_server_user` from `backend.initData`, which the API sends to administrators only. A panel that needs other data should read it from a store or fetch it itself.

To add a panel to a custom module, create `vue/src/custom/components/ListViewPanels/MyPanel.vue` and add `'MyPanel'` to the module's `panels`.

## `crontab` Field Type

`crontab` edits and displays a scheduler interval stored as `minutes::hours::day of month::month::day of week` (for example `*/5::*::*::*::*`). Use it in `recordviewdefs.php` and `eslistviewdefs.php`:

```php
['name' => 'job_interval', 'type' => 'crontab'],
```

Components in `vue/src/components/Fields/crontab/`:

| Component | View | Behavior |
|---|---|---|
| `crontab.edit.vue` | edit | `Basic` mode with dropdowns for each part and `Advanced` mode with a raw text input; emits the `::`-separated value |
| `crontab.detail.vue` | detail | human-readable description built from `Schedulers` language labels (`LBL_ON_THE`, `LBL_MINUTES`, ...) |
| `crontab.list.vue` | list | the raw value with `::` replaced by spaces |

Labels are read from the `Schedulers` module language, so the field depends on it being loaded. See [Field System](./09-fields.md) for how field components are discovered by type and view.

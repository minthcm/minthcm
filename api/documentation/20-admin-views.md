# Admin Views

MintHCM provides a generic, metadata-driven mechanism for adding new pages under **Administration**. Instead of writing a bespoke controller and Vue page for every admin setting screen, a view is described as metadata and rendered by a shared `AdminView` — the same idea `recordviewdefs.php` applies to record pages, applied to admin config screens.

This mechanism was introduced for the Elasticsearch settings screen and is now used by every view in `legacy/modules/Administration/metadata/admins/`:

| File | View name | Panels |
|---|---|---|
| `elasticsearchdefs.php` | `ElasticsearchSettings` | `MintPanelAdminConfig` + `MintPanelElasticsearchTools` |
| `configuratordefs.php` | `ConfiguratorSettings` (System Settings) | `MintPanelAdminConfig` |
| `languagesdefs.php` | `LanguageSettings` | `MintPanelLanguages` (custom panel, own controller `LanguagesAdminController`) |

## Restricting Routes to Admins

The `AdminMiddleware` (`app/Middlewares/Admin/AdminMiddleware.php`) restricts any route marked with `'admin_only' => true` in its `options` to users where `$current_user->isAdmin()` is true; everyone else gets a `403`. In `ApiManager` it is `add()`ed just before `AuthMiddleware`; since Slim runs middlewares in reverse order of registration, it executes *after* `AuthMiddleware`, so `$current_user` is already populated when it runs.

```php
$routes = [
    'admin_config_get' => [
        'method' => 'GET',
        'path' => '/admin/config/{viewName}',
        'class' => AdminConfigController::class,
        'function' => 'getConfig',
        'options' => [
            'auth' => true,
            'admin_only' => true,
        ],
        // ...
    ],
];
```

See [Routing System](./05-routing.md) for the general route option format and [Middlewares](./11-middlewares.md) for how middlewares are wired up.

## Admin View Metadata

Each admin page is described by a PHP file in `legacy/modules/Administration/metadata/admins/` that sets `$viewdefs['Administration']['{viewName}']` (the file name itself is free, e.g. `elasticsearchdefs.php`), structurally similar to `recordviewdefs.php`. There is **no** `custom/` override for this directory — neither the API nor the init payload reads `legacy/custom/modules/Administration/metadata/admins/`.

```php
$viewdefs['Administration']['ElasticsearchSettings'] = [
    'order' => ['connection', 'tools'],
    'panels' => [
        'connection' => [
            'component' => 'MintPanelAdminConfig',
            'data' => [
                'title' => 'LBL_ELASTIC_SEARCH_SETTINGS',
                'sections' => [
                    'connection' => [
                        'title' => 'LBL_ES_CONNECTION_SETTINGS',
                        'fields' => [
                            [
                                ['name' => 'host', 'type' => 'varchar', 'label' => 'LBL_ELASTIC_SEARCH_HOST', 'config_key' => 'search:ElasticSearch'],
                                ['name' => 'elasticsearch_index_prefix', 'type' => 'varchar', 'label' => 'LBL_ES_INDEX_PREFIX'],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'tools' => [
            'component' => 'MintPanelElasticsearchTools',
        ],
    ],
];
```

- `order` — panel render order.
- `panels` — one entry per panel; `component` names a Vue component under `vue/src/components/MintPanel/{component}/` (resolved dynamically by `MintPanel.vue`, the same registry used by record views).
- A panel's `data` is passed straight through to its component as props. `MintPanelAdminConfig` (the generic field-editing panel) expects `sections` of field rows, laid out like `recordviewdefs.php` sections. Unlike record views, each field is a **complete inline definition** (`name`, `type`, `label`, ...) — it is not merged with module vardefs — and an empty cell is written as `[]`. `data.title` is the panel title and `data.label_module` the module whose language file resolves labels. A panel can instead be a fully custom component (e.g. `MintPanelElasticsearchTools`, `MintPanelLanguages`) that ignores the generic field/config wiring entirely and manages its own API calls.

Field keys understood by `AdminConfigController` / `MintPanelAdminConfig` (see also the header comment of `configuratordefs.php`):

| Key | Meaning |
|---|---|
| `config_key` | Colon-separated path into `$sugar_config` (e.g. `'search:ElasticSearch'` → `$sugar_config['search']['ElasticSearch'][name]`). Without it the value lives at `$sugar_config[name]`. |
| `settings_key` | `'category:name'` — the value is stored in the DB `config` table via `Administration::saveSetting()` instead of `$sugar_config`. |
| `inputType` => `'password'` | Value masked in `GET` responses and skipped on `PUT` while still masked. |
| `len` | Max length applied on save (values are also passed through `strip_tags`). |
| `options` | `app_list_strings` key for `enum` fields. |
| `additional_info` | Label key shown as an info tooltip next to the field. |
| `upload_handler` | For `type => 'image'`: `Configurator` method called by `POST /admin/config/{viewName}/image/{fieldName}`. |
| `static_value` | Fixed, display-only value; never read from or written to the config. |
| `visible_when` | `[['field' => ..., 'value' => ...]]` — the field is shown only when the listed fields have those values; hidden fields are cleared. |

All `legacy/modules/Administration/metadata/admins/*.php` files are scanned at two points:

- **API**: `AdminConfigController::getFieldDefs()` (`app/Controllers/Admin/AdminConfigController.php`) — finds the file defining the requested view and flattens its field defs to serve `GET`/`PUT /admin/config/{viewName}`.
- **Init payload**: `Init::loadAdminViewsMeta()` (`app/Controllers/Init/Init.php`) — merges every `$viewdefs['Administration']` entry into `initData.adminViews`, which the frontend consumes to render the view without an extra round-trip. It is included **for admin users only**.

## Generic Config Controller

`AdminConfigController` (`app/Controllers/Admin/AdminConfigController.php`) provides generic `GET`/`PUT /admin/config/{viewName}` endpoints driven entirely by the metadata above — no per-view controller code is needed as long as a view's fields map onto `$sugar_config`:

- `getConfig()` resolves each field's current value from `$sugar_config` (nested via `config_key` if present) and masks `password` fields.
- `saveConfig()` sanitizes each submitted value (`strip_tags` + max length from `len`), skips masked passwords left untouched, and writes back via `Configurator` (loaded through `LegacyConnector`, see [Legacy Integration](./07-legacy-integration.md)).

A view whose logic goes beyond flat config get/save (e.g. `ElasticSearchAdminController`'s connection test, module indexing and toggling) adds its own controller and routes instead of — or alongside — the generic one; code that needs the legacy context runs through `LegacyConnector::runCallable()`, which switches the working directory to `legacy/` for the duration of the callable.

## Adding a New Admin View

1. Create a file in `legacy/modules/Administration/metadata/admins/` defining `$viewdefs['Administration']['{viewName}']` with panels/sections/fields as above (a `custom/` location is not supported).
2. Add an entry to `legacy/modules/Administration/metadata/adminpaneldefs.php` linking to `javascript:parent.location.hash="/Administration/{viewName}"` so it appears on the legacy Administration page and opens inside the Vue shell.
3. If the view only needs config get/save, no new controller/routes are required — `AdminConfigController` + the metadata above are enough.
4. For anything beyond flat config (external calls, batch jobs, custom validation), add a controller under `app/Controllers/Admin/` and routes under `app/Routes/routes/admin.php` (or `custom/`) with `'admin_only' => true`.
5. For a fully custom panel UI, add a Vue component under `vue/src/components/MintPanel/{ComponentName}/` and reference its name as a panel's `component`.

See [Admin Views (frontend)](../../vue/documentation/15-admin-views.md) for the corresponding frontend pieces (`AdminView`, `AdminViewStore`, `MintPanelAdminConfig`).

## Next Steps

- Learn about [Routing System](./05-routing.md)
- Understand [Middlewares](./11-middlewares.md)
- Read about [Legacy Integration](./07-legacy-integration.md)

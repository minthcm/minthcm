# Admin Views

## Overview

`AdminView` is a generic, metadata-driven page for **Administration** screens — the admin-page equivalent of `RecordView` for record pages (see [Record View Definitions](12-recordviewdefs.md)). A new admin page is added by writing metadata rather than a bespoke Vue view.

It was introduced for the Elasticsearch settings screen and is meant to be reused for future admin pages.

## Routing

```
/#/Administration/{viewName}   → AdminView.vue
```

Registered in `router/routes.ts`:

```ts
{
    path: '/Administration/:viewName',
    name: 'admin-view',
    component: () => import('@/views/AdminView/AdminView.vue'),
    meta: {
        auth: true,
        adminOnly: true,
    },
},
```

The `adminOnly` route meta flag is checked in the global navigation guard (`router/index.ts`) — non-admin users are redirected to the dashboard. This mirrors the API's `admin_only` route option; see [Admin Views](../../api/documentation/20-admin-views.md) on the API side.

Legacy Administration panel entries link into it via a hash URL, e.g.:

```php
// legacy/modules/Administration/metadata/adminpaneldefs.php
$admin_option_defs['Administration']['elastic_search'] = [
    'ElasticSearchIndexerSettings',
    'LBL_ELASTIC_SEARCH_SETTINGS',
    'LBL_ELASTIC_SEARCH_SETTINGS_DESC',
    'javascript:parent.location.hash="/Administration/ElasticsearchSettings"',
    'global-search',
];
```

## Metadata

`AdminView` reads its layout from `backend.initData.adminViews[viewName]` — populated server-side by merging every `legacy/modules/Administration/metadata/admins/*.php` file (see the API-side [Admin Views](../../api/documentation/20-admin-views.md) doc for the metadata format). Structure mirrors `recordviewdefs.php`:

```php
$viewdefs['Administration']['ElasticsearchSettings'] = [
    'order' => ['connection', 'tools'],
    'panels' => [
        'connection' => [
            'component' => 'MintPanelAdminConfig',
            'data' => [
                'title' => 'LBL_ELASTIC_SEARCH_SETTINGS',
                'sections' => [ /* ... field rows, same shape as recordviewdefs sections ... */ ],
            ],
        ],
        'tools' => [
            'component' => 'MintPanelElasticsearchTools',
        ],
    ],
];
```

`order` and `panels[].component` behave exactly like `recordviewdefs.php` — `component` is resolved dynamically by the shared `MintPanel.vue` from `vue/src/components/MintPanel/{component}/{component}.vue`.

## AdminView / AdminViewStore

- **`AdminView.vue`** (`views/AdminView/`) — reads `viewName` from the route, loads the matching metadata into `AdminViewStore`, fetches current config values, and renders each panel via `MintPanel`.
- **`AdminViewStore`** (Pinia, `views/AdminView/AdminViewStore.ts`) — holds `meta` (the view's metadata), `record` (raw config values from the API), and `fieldStates` (one `MintField` per field, built with the existing `useField` composable used by record views). `loadConfig()`/`saveConfig()` call `GET`/`PUT /admin/config/{viewName}` via `api/adminApi.ts`.

## MintPanelAdminConfig

The generic config-editing panel component (`components/MintPanel/MintPanelAdminConfig/`). Given a panel's `data.sections` (field rows, same shape as record view sections), it renders each field with the standard `Field.vue` component bound to `AdminViewStore`'s `fieldStates`/`record`, and a Save button that calls `AdminViewStore.saveConfig()`.

Use it whenever an admin screen is a flat form over `$sugar_config` values. For anything else (external calls, batch actions, custom UI), write a dedicated panel component instead — as done for the Elasticsearch screen's `MintPanelElasticsearchTools` — and reference its name in the metadata's `component` key.

## Adding a New Admin View

1. Add `legacy/modules/Administration/metadata/admins/{viewName}.php` describing panels/sections/fields.
2. Link to it from `legacy/modules/Administration/metadata/adminpaneldefs.php` (hash URL as shown above).
3. If the view is a flat config form, no new Vue component is needed — `MintPanelAdminConfig` handles it.
4. For custom behavior, add a new component under `components/MintPanel/{ComponentName}/` and reference it in the metadata.
5. For anything beyond generic config get/save, add a backend controller/routes — see the API-side [Admin Views](../../api/documentation/20-admin-views.md) doc.

---

**Related Documentation**:
- [Admin Views (API)](../../api/documentation/20-admin-views.md)
- [Record View Definitions](12-recordviewdefs.md)
- [Field System](09-fields.md)

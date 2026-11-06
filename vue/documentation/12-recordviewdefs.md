# Record View Definitions

## Overview

`recordviewdefs.php` files define the structure of record views (detail/edit) in the new Vue-based system, replacing the legacy detailviewdefs.php and editviewdefs.php files.

## Location

```
legacy/modules/{Module}/metadata/recordviewdefs.php
legacy/custom/modules/{Module}/metadata/recordviewdefs.php   # full-file override (takes precedence)
```

The frontend reads the merged, cached result (`legacy/cache/modules/{Module}/recordviewdefs.php`), not these files directly — after editing them run Quick Repair & Rebuild (see [RecordView Extensions](../../api/documentation/18-recordview-extensions.md)).

## Extending recordviewdefs from a package

A package (e.g. a client deployment) doesn't have to override a module's whole `recordviewdefs.php` in `custom/` to add a panel, a button, or a sidepanel widget. It can instead drop a small extension file that only touches the part it cares about — additive, coexists with other packages' extensions, no full-file override. The frontend reads the already-merged result from a cache file, built by Quick Repair & Rebuild.

Full contract (extension file format, merge order, cache lazy-build, and how to force a single module's cache to refresh from PHP code) is documented on the backend side, since that's where the mechanism lives: see [RecordView Extensions](../../api/documentation/18-recordview-extensions.md).

## Basic Structure

```php
<?php

$viewdefs['{Module}'] = [
    'sidepanel' => ['MintWidgetTenure'],    // Optional, see Sidepanel & Widgets
    'order' => ['basicInfo', 'subpanels'],  // Panel order
    'panels' => [
        'basicInfo' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'sections' => [
                    'sectionName' => [
                        'title' => 'LBL_SECTION_TITLE',
                        'fields' => [
                            ['field1', 'field2'],  // Row with 2 columns
                            ['field3'],            // Row with 1 column (full width)
                            ['field4', ''],        // Row with 1 field (left) and empty column
                        ],
                    ],
                ],
            ],
        ],
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
        ],
    ],
];
```

**Panel titles.** `MintPanel.vue` passes only a panel's `data` to its component, so a panel-level `'title'` key is not rendered by itself. Components that show a heading read `data.title` (e.g. `MintPanelFiles`, `MintPanelChecklist`, `MintPanelRecordPanel`, `MintPanelAdminConfig`). `MetaController` copies a panel-level `title` into `data.title` only for panels that use `data.fields` (such as `MintPanelRecordPanel`). `MintPanelRecordDetails` shows **section** titles only.

## Available Components

### MintPanelRecordDetails
Standard form fields panel. Most commonly used component for displaying and editing record fields.

**Usage**:
```php
'basicInfo' => [
    'component' => 'MintPanelRecordDetails',
    'data' => [
        'sections' => [
            'basic' => [
                'title' => 'LBL_BASIC',
                'fields' => [
                    ['name', 'status'],
                    ['description'],
                ],
            ],
        ],
    ],
],
```

**Automatic `other` section.** `MetaController::getRecordViewMeta()` appends a collapsed section `other` (title `LBL_OTHER`) with `assigned_user_name`, `date_entered` and `date_modified` (`MetaController::FIELDS_IN_OTHER_SECTION`) to every `MintPanelRecordDetails` panel. Define your own section with the key `other` to replace it.

#### Header Components

`data.headerComponents` renders arbitrary Vue components in the panel header (next to the actions menu), in addition to the standard `data.actions` bean actions — useful for record-level widgets that aren't fields, like reactions.

**Usage**:
```php
'basicInfo' => [
    'component' => 'MintPanelRecordDetails',
    'data' => [
        'actions' => ['PublishNews', 'ArchiveNews'],
        'headerComponents' => ['MintNewsReactions'],
        'sections' => [ /* ... */ ],
    ],
],
```

Each entry is a component name, dynamically imported from `@/components/{name}/{name}.vue` by `MintPanelRecordDetails.vue`. `MetaController` merges `headerComponents` from the module's `recordviewdefs.php` into the recordview meta payload sent to the frontend, so no additional backend registration is required beyond declaring it in the panel's `data`.

#### Actions

`data.actions` renders a menu of record-level actions (Audit, Delete, Duplicate, ...). Each entry is either:

- a **string** — the class name of a `BeanAction` subclass in `vue/src/business/BeanActions/Actions/` (e.g. `'Audit'`), or an object `{ name, ...options }` passed to that class's constructor (e.g. `skipFields` for `Duplicate`);
- a **generic action config** (object containing `api_route`) — recognized automatically and handled by `GenericApiAction` (`vue/src/business/BeanActions/GenericApiAction.ts`) without writing any TS code. Use this for simple "POST to an endpoint on click" actions with optional confirmation, a single text prompt, and visibility conditions.

**Where the class lives (named actions only).** Core actions live in `vue/src/business/BeanActions/Actions/<Name>.ts`; a deployment adds its own — or overrides a core one — in `vue/src/custom/business/BeanActions/Actions/<Name>.ts`. Both directories are scanned at build time, core first and custom second, so a custom file with the same name deterministically replaces the core action. The **file name** is the action name used here in `actions`. Named actions require a frontend rebuild (`npm run build`); generic actions (`api_route`) do not, and are never looked up by class name.

**Default actions and merging.** The backend merges the declared `actions` on top of the default ones (`Audit`, `Delete`, `Duplicate`) — declaring your own action never silently removes them:

- no `actions` key at all, or `actions => []` → the three default actions;
- declared actions are appended **after** the defaults;
- a declaration reusing a default action's identifier replaces that default **in its original position** — e.g. `['name' => 'Audit', 'icon' => 'mdi-history']` stays first in the menu;
- the identifier is `name` for a named action (the string itself for a plain string entry) and the **required** `key` for a generic action; a generic action declared without `key` is skipped;
- when the same identifier appears twice, the **last** declaration wins;
- `includeDefaultActions => false`, placed next to `actions` in the same panel `data`, drops the defaults and keeps only what the panel declares (without `actions` it yields an empty menu):

```php
'data' => [
    'includeDefaultActions' => false,
    'actions' => [ /* only these */ ],
],
```

**Generic action config fields:**

| Field | Type | Required | Description |
|---|---|---|---|
| `key` | string | yes | Unique key, used to track in-flight requests (disables the button while pending) |
| `label` | string | yes | i18n key for the menu item label |
| `icon` | string | no | MDI icon name (default: `mdi-circle-medium`) |
| `acl` | string | yes | Required ACL action (e.g. `'edit'`) — checked both client-side (hides the button) and must be re-checked server-side |
| `type` | `'confirm' \| 'prompt' \| 'default'` | no | `'confirm'` shows a confirmation popup before calling the API; `'prompt'` shows a popup with the same descriptive text plus a single text field the user fills in before the API is called |
| `confirm.title` / `confirm.body` / `confirm.confirmLabel` / `confirm.cancelLabel` | string | `body` required if `type: 'confirm'` or `type: 'prompt'` | i18n keys for the confirm/prompt popup; `title`/`confirmLabel`/`cancelLabel` fall back to the default popup labels when omitted. For `type: 'prompt'`, `body` is rendered as **Markdown** (via the same renderer used by the `markdown` field type, `vue/src/components/Fields/markdown/markdown.detail.vue`) instead of plain text |
| `required` | boolean | no | Only relevant for `type: 'prompt'` (default `false`). When `true`, the text field must be non-empty after `trim()` for the request to be sent — the popup shows inline validation and blocks submission otherwise. When `false`, the request can be sent with an empty field |
| `api_route` | string | yes | Route called via `mintApi.post()`, e.g. `'Meetings/closeMeeting'` |
| `customVisibility` | object | no | `{ operator: 'AND' \| 'OR', conditions: [{ field, operator, value? }] }` — flat list of conditions evaluated against the bean's current `attributes` (reactive: re-evaluates on inline-edit). `operator` per condition: `=`, `!=`, `in`, `not_in`, `empty`, `not_empty` |
| `onSuccess` | `'reload' \| 'refresh_subpanels' \| 'redirect' \| 'toast'` | no | Fallback client behavior after a successful response, used only if the backend response doesn't include `action` |
| `loader` | boolean | no | Whether to show blocking loading UI while `api_route` is in flight (default `true`). Set `false` to skip it — the button itself still shows a loading/disabled state via `pendingKeys` either way |
| `loaderLabel` | string | no | i18n key for the text shown under the spinner while loading. Only relevant when `loader` is not `false`; shows no text below the spinner when omitted |

**Request/response contract:**

- Request: `POST {api_route}` with body `{ id, module }` (the current bean's id and module — no other client data is trusted), **plus `value` (string) when `type: 'prompt'`** — the text field's content after `trim()`, or an empty string when the field was left empty (allowed when `required` is not `true`). `value` is a fixed, documented field name — not configurable per button.
- Response: `{ success: boolean, message: string, action?: string }` — `message` is an i18n key, translated client-side; `action` (if present) overrides the configured `onSuccess`.
- Closing the popup without confirming (`type: 'confirm'` or `'prompt'`) never sends the request, regardless of `required`.

**Loader while the request is in flight:**

- For `type: 'confirm'` / `type: 'prompt'`: clicking confirm doesn't close the popup. Its content (text/textarea, buttons) dims and gets disabled, and a spinner overlay (with `loaderLabel`'s text underneath, if set) covers it *in place* — the popup keeps its size and position, it doesn't shrink/re-center or get replaced. Only once the response comes back does it close, immediately followed by the success/error popup (`popupsStore.alert()` with the response's `message`). This all happens inside the same popup component (`MintPopupConfirm.vue` / `MintPopupPrompt.vue`, driven by an `onSubmit` callback passed from `GenericApiAction` into `popupsStore.confirm()`/`prompt()`) — no second popup is involved.
- For `type: 'default'` (no confirm/prompt popup to begin with): a standalone blocking popup (`MintPopupLoader.vue`, via `popupsStore.showLoader()`) covers the screen instead, closing the same way once the response arrives.
- Set `loader: false` on the button to skip all of the above — the popup (if any) then closes immediately on confirm like before, and the request fires with no blocking UI (the menu item still shows its own loading/disabled state via `pendingKeys` regardless).

**Security:** the button being hidden (ACL / `customVisibility`) is a UI convenience only — the endpoint **must** independently re-check ACL server-side (e.g. `$bean->ACLAccess('edit')`) and load the record by `id` itself; never trust field values from the request body beyond `id`/`module` — including the free-text `value` field sent for `type: 'prompt'`, which the endpoint must independently validate, length-limit and (if persisted or rendered elsewhere) sanitize/escape server-side before use.

**Example — "Close" button on Meetings** (`legacy/modules/Meetings/metadata/recordviewdefs.php`), a reference end-to-end implementation (metadata → generic frontend action → dedicated API endpoint). The panel lists its whole menu explicitly, so it opts out of the defaults — otherwise the default `Duplicate` would be shown next to `DuplicateMeetings`:

```php
'includeDefaultActions' => false,
'actions' => [
    'Audit',
    'Delete',
    [
        'name' => 'DuplicateMeetings',
        'skipFields' => ['repeat', 'status'],
    ],
    [
        'key' => 'CloseMeeting',
        'label' => 'LBL_CLOSE_BUTTON_TITLE',
        'icon' => 'mdi-check',
        'acl' => 'edit',
        'type' => 'confirm',
        'confirm' => [
            'body' => 'LBL_CLOSE_MEETING_CONFIRM_BODY',
        ],
        'api_route' => 'Meetings/closeMeeting',
        'customVisibility' => [
            'operator' => 'AND',
            'conditions' => [
                ['field' => 'status', 'operator' => '!=', 'value' => 'Held'],
            ],
        ],
        'onSuccess' => 'reload',
    ],
],
```

The button is hidden once `status` is `Held`. On click, it confirms, then `POST Meetings/closeMeeting` with `{ id, module }`; the controller (`api/modules/Meetings/api/controllers/CloseMeeting.php`) re-checks `edit` ACL, loads the meeting by `id`, sets `status = 'Held'` and saves — triggering normal `before_save`/`after_save` hooks.

**Example — `type: 'prompt'` config** (collects one required text value before calling the API):

```php
[
    'key' => 'AddComment',
    'label' => 'LBL_ADD_COMMENT_BUTTON_TITLE',
    'icon' => 'mdi-comment-plus-outline',
    'acl' => 'edit',
    'type' => 'prompt',
    'confirm' => [
        'body' => 'LBL_ADD_COMMENT_PROMPT_BODY', // supports Markdown
    ],
    'required' => true,
    'api_route' => 'Meetings/addComment',
    'onSuccess' => 'reload',
    'loaderLabel' => 'LBL_ADD_COMMENT_LOADER_LABEL',
],
```

On click, the popup shows `LBL_ADD_COMMENT_PROMPT_BODY` (rendered as Markdown) above a textarea. Because `required: true`, submitting with an empty (or whitespace-only) field is blocked with inline validation. On confirm, the form dims and a spinner overlay (showing `LBL_ADD_COMMENT_LOADER_LABEL` underneath) covers it in place while `POST Meetings/addComment` is called with `{ id, module, value }`, where `value` is the trimmed textarea content; once the response comes back, the popup closes and the success/error popup opens in its place.

### MintPanelSubpanels
Displays related records (relationships).

**Usage**:
```php
'subpanels' => [
    'component' => 'MintPanelSubpanels',
],
```

#### Subpanel Inline Action Buttons

Each row in a subpanel can display action buttons (Edit, Remove, Delete). These come from the legacy subpanel definition's `_buttons` property (e.g. `subpaneldefs.php`) and are mapped to Vue action classes in `vue/src/business/SubpanelActions/InlineActions/` (extensible from `vue/src/custom/business/SubpanelActions/InlineActions/`).

**Available inline actions:**

| `widget_class` (legacy) | Vue class | Behavior |
|---|---|---|
| `SubPanelEditButton` | `Edit` | Redirects to the related record's EditView |
| `SubPanelDeleteButton` | `Delete` | Deletes the related record after confirmation |
| `SubPanelRemoveButton` | `Remove` | Unlinks (removes relationship to) the related record after confirmation |

**ACL requirements:**
- `Edit` — requires `edit` access on the related module
- `Delete` — requires `delete` + `edit` access on the related module
- `Remove` — requires `delete` + `edit` access on **both** the related module and the parent record's module

**Extending with a custom inline action:**

Create a new class extending `SubpanelAction` — in `vue/src/business/SubpanelActions/InlineActions/` for core, or `vue/src/custom/business/SubpanelActions/InlineActions/` for a deployment — then register its `widget_class` mapping in the `inlineActionMap` of `MintSubpanelsInlineButtons.vue`. Note that this map lives in a core file, so a brand-new `widget_class` requires a core change; overriding an existing inline action (same file name) does not.

```typescript
// vue/src/custom/business/SubpanelActions/InlineActions/MyAction.ts
import { SubpanelAction } from '@/business/SubpanelActions/SubpanelAction'

export class MyAction extends SubpanelAction {
    public static readonly TITLE = 'LBL_MY_ACTION'
    public static readonly ICON = 'mdi-star'
    public static readonly ACL = ['edit']

    public async execute(): Promise<boolean> {
        // custom logic here
        return true
    }
}
```

The file is auto-discovered by `index.ts` via `import.meta.glob`, which scans the core directory first and the custom one second — so a custom file with the same name as a core inline action overrides it. No registration is needed beyond adding the `widget_class` mapping in `MintSubpanelsInlineButtons.vue`. Inline actions and regular subpanel actions share one registry keyed by file name, so their names must not collide. The same applies to non-inline subpanel actions (`vue/src/custom/business/SubpanelActions/Actions/`). See [Customization Guide](11-customization.md), section *Custom Business Logic*.

### MintPanelFiles
Displays file attachments.

**Usage**:
```php
'files' => [
    'component' => 'MintPanelFiles',
    'data' => [
        'title' => 'LBL_FILES',  // Optional, defaults to LBL_FILES
    ],
],
```

### MintPanelScheduler
Displays calendar/scheduler for time-based records (used by `Meetings`).

**Usage**:
```php
'scheduler' => [
    'component' => 'MintPanelScheduler',
],
```

### MintPanelChecklist
Displays checklist functionality (used by `Tasks`).

**Usage**:
```php
'checklist' => [
    'component' => 'MintPanelChecklist',
    'data' => [
        'title' => 'LBL_CHECKLIST',  // Optional, defaults to LBL_DETAILS
    ],
],
```

### MintPanelPositionCard
Displays position card information (HR-specific, used by `Positions` and `EmployeeRoles`).

**Usage**:
```php
'positionCard' => [
    'component' => 'MintPanelPositionCard',
],
```

### MintPanelComments
Displays the record's comment thread (used by `News`).

**Usage**:
```php
'comments' => [
    'component' => 'MintPanelComments',
    'data' => [],
],
```

### MintPanelRecordPanel
Simple field panel without sections or actions (not used by core modules). It requires `data.fields` (rows, same shape as section `fields`); a panel-level `title` is copied into `data.title` by `MetaController`.

**Usage**:
```php
'custom' => [
    'component' => 'MintPanelRecordPanel',
    'title' => 'LBL_CUSTOM',
    'data' => [
        'fields' => [
            ['name', 'status'],
        ],
    ],
],
```

Admin screens use further panel components (`MintPanelAdminConfig`, `MintPanelElasticsearchTools`, `MintPanelLanguages`) — see [Admin Views](15-admin-views.md).

## Field Layout

Fields are organized in rows of at most two columns:

```php
'fields' => [
    ['field1', 'field2'],      // Row 1: 2 columns (50% width each)
    ['field3'],                // Row 2: 1 column (100% width)
    ['field4', ''],            // Row 3: Left column only (empty right)
    ['', 'field5'],            // Row 4: Right column only (empty left)
]
```

**Tips**:
- Empty string (`''`) creates empty column
- Single field in array spans full width
- `MintPanelRecordDetails` renders at most **two** columns — a third entry in a row is silently ignored
- On small screens (`mdAndDown`) every field is rendered in its own row

### Field entries

A cell is either a vardef field name (string) or an array. Array entries are merged with the field's vardef by `MetaController::mergeModuleFields()`, so they can override vardef properties such as `type` or `label`:

```php
'fields' => [
    [
        ['name' => 'email1', 'type' => 'email'],     // override the field type
        ['name' => 'birthdate', 'type' => 'age'],     // render a date as age
    ],
    [
        [
            'name' => 'primary_address',
            'type' => 'fieldset',                     // several fields shown as one
            'label' => 'LBL_PRIMARY_ADDRESS',
            'properties' => [
                'fields' => [
                    'primary_address_street',
                    'primary_address_city',
                    'primary_address_postalcode',
                    'primary_address_country',
                ],
                'separator' => ', ',
            ],
        ],
        '',
    ],
],
```

An `enum`/`multienum` entry can also take its options from a PHP function (`'function' => ['name' => [Class, method], 'params' => []]`), resolved server-side — see [List View Panels, Record View Function Options and Schedulers](../../api/documentation/21-list-view-panels-and-meta.md). The real `legacy/modules/Employees/metadata/recordviewdefs.php` is a good reference for all of the above.

## Multiple Sections

Organize fields into multiple sections within a panel:

```php
'data' => [
    'sections' => [
        'basic' => [
            'title' => 'LBL_BASIC_INFO',
            'fields' => [
                ['name', 'status'],
            ],
        ],
        'additional' => [
            'title' => 'LBL_ADDITIONAL_INFO',
            'fields' => [
                ['category', 'type'],
            ],
        ],
        'dates' => [
            'title' => 'LBL_DATES',
            'fields' => [
                ['start_date', 'end_date'],
            ],
        ],
    ],
],
```

## Panel Order

Control the order panels appear:

```php
'order' => ['basicInfo', 'customPanel', 'subpanels', 'files'],
```

## Complete Example

A shortened version of `legacy/modules/Employees/metadata/recordviewdefs.php` (the `files` panel is added for illustration):

```php
<?php

$viewdefs['Employees'] = [
    'sidepanel' => ['MintWidgetTenure', 'MintWidgetLeave', 'MintWidgetCalendar', 'MintWidgetTrainings', 'MintWidgetKudos'],
    'order' => ['basicInfo', 'subpanels', 'files'],
    'panels' => [
        'basicInfo' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'sections' => [
                    'basic' => [
                        'title' => 'LBL_BASIC',
                        'fields' => [
                            ['first_name', 'last_name'],
                            [['name' => 'email1', 'type' => 'email'], 'phone_mobile'],
                            ['employee_status', ['name' => 'birthdate', 'type' => 'age']],
                            ['position_name', ['name' => 'photo', 'type' => 'image']],
                            ['securitygroup_name', 'reports_to_name'],
                            ['description'],
                        ],
                    ],
                ],
            ],
        ],
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
        ],
        'files' => [
            'component' => 'MintPanelFiles',
            'data' => [
                'title' => 'LBL_FILES',
            ],
        ],
    ],
];
```

## Dynamic Field Behavior

Field behavior (visibility, required, readonly) is controlled by MintLogic on the backend, not in recordviewdefs. The recordviewdefs only defines structure and layout.

For dynamic field behavior, see backend documentation: `api/documentation/13-mintlogic.md`

## Migration from Legacy Views

When migrating from legacy views:
1. Analyze existing detailviewdefs.php and editviewdefs.php
2. Create recordviewdefs.php with equivalent structure
3. Migrate vt_* View Tools to MintLogic logicdefs.php (see: `api/documentation/13-mintlogic.md`)
4. Make sure `api/constants/legacy_views.php` does not force the legacy view: the record view switches to Vue automatically once `recordviewdefs.php` exists (and the list view once `eslistviewdefs.php` exists), unless that file sets `'record' => true` / `'list' => true` for the module (`Init::getLegacyViews()` in `api/app/Controllers/Init/Init.php`)
5. Run Quick Repair & Rebuild so the recordviewdefs cache is built, then test thoroughly

## Best Practices

**DO**:
- ✅ Group related fields in sections
- ✅ Use meaningful section titles (LBL_* constants)
- ✅ Keep field layout consistent across modules
- ✅ Use appropriate panel components for functionality
- ✅ Test in both detail and edit modes

**DON'T**:
- ❌ Define field logic in recordviewdefs (use MintLogic)
- ❌ Create too many sections (3-5 is optimal)
- ❌ Mix different types of fields without clear organization
- ❌ Forget to add subpanels panel if module has relationships

## Troubleshooting

**Panel not showing**:
- Run Quick Repair & Rebuild — the frontend reads the cached `legacy/cache/modules/{Module}/recordviewdefs.php`
- Check component name spelling
- Verify panel key exists in 'order' array
- Check panel structure matches component requirements

**Fields not displaying**:
- Verify field names match vardefs
- Check if fields are hidden by MintLogic
- Ensure fields exist in module vardefs

**Layout broken**:
- Check field array structure (nested arrays for rows)
- Verify empty strings for empty columns
- Test with different screen sizes

---

**Related Documentation**:
- [MintLogic System](../../api/documentation/13-mintlogic.md)
- [Working with Beans](10-working-with-beans.md)
- [Field System](09-fields.md)
- [RecordView Extensions](../../api/documentation/18-recordview-extensions.md)

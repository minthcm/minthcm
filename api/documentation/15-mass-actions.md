# Mass Actions

Mass actions execute an operation on multiple records at once. The system supports selecting individual records by ID or selecting all records matching the current list view filters.

## Endpoint

```
POST /{module}/MassActions/{action}
```

| Parameter | Type | Description |
|-----------|------|-------------|
| `{module}` | path | Module name (e.g., `Employees`) |
| `{action}` | path | Action short class name (e.g., `Delete`, `Export`, `Update`) |

### Body Parameters

| Parameter | Required | Type | Description |
|-----------|----------|------|-------------|
| `ids` | ✅ | array | Record IDs to act on, or `["all"]` to act on all filtered records |
| `filter` | ❌ | object | Active list view filters — required when `ids` is `["all"]` |
| `update_fields` | ❌ | object | Fields to update — required (non-empty, else `400`) for any action that defines `setArrayFields()`, i.e. `Update` |

## Select All Records

When the user selects all records matching the current filters (not just the current page), the frontend sends `ids: ["all"]` along with the current `filter` state. The API resolves the full list of matching IDs via ElasticSearch before executing the action.

### Frontend (Vue)

```typescript
// allSelected = user clicked "Select all X records for current filters"
const selectedIds = allSelected.value ? ['all'] : selected.value.map(item => item.id)

await new actionClass(module, selectedIds, {
    searchPhrase: searchPhrase.value,
    filters: filters.value,
    activeFilter: activeFilter.value,
    myObjects: myObjects.value
}).execute()
```

### API (PHP)

`MassAction::__construct()` detects `['all']` in `$ids` and resolves the actual IDs:

```php
// api/data/MassActions/MassAction.php
$this->ids = in_array('all', $ids)
    ? MassActionDataHelper::getRecordIds($module_name, $filter)
    : $ids;
```

`MassActionDataHelper::getRecordIds()` pages through ElasticSearch results (10 000 per page) and returns all matching record IDs respecting ACL.

### Filter Object Structure

The `filter` body parameter mirrors the ListView filter state:

```json
{
    "searchPhrase": "john",
    "filters": {
        "filter": [...],
        "must_not": [...],
        "must": [...]
    },
    "activeFilter": "my_saved_filter",
    "myObjects": false
}
```

`MassActionDataHelper::parseFilters()` converts this into an ElasticSearch query.

## Built-in Actions

| Action class | File | Description |
|---|---|---|
| `Delete` | `api/data/MassActions/Actions/Delete.php` | Soft-delete records (`mark_deleted()`) |
| `Update` | `api/data/MassActions/Actions/Update.php` | Update fields on all selected records (mass update) |
| `Export` | `api/data/MassActions/Actions/Export.php` | Export to CSV via legacy `export.php` |
| `Merge` | `api/data/MassActions/Actions/Merge.php` | Merge records via legacy `MergeRecords` |
| `MassConfirmation` | `api/data/MassActions/Actions/MassConfirmation.php` | WorkSchedules mass confirmation |
| `MassAcceptance` | `api/data/MassActions/Actions/MassAcceptance.php` | WorkSchedules mass supervisor-acceptance (batched, with progress UI) |

### Export and Merge: Session Bridge

`Export` and `Merge` delegate to legacy PHP pages that cannot receive a large list of IDs via URL. The API stores resolved IDs in the PHP session before redirecting:

- `Export` sets `$_SESSION['uids']` → `legacy/export.php` reads it
- `Merge` sets `$_SESSION['merge_uids']` → `legacy/modules/MergeRecords/index.php` reads it

## Creating a Custom Action

1. Extend `MassAction` in `api/data/MassActions/Actions/` (core) or `api/custom/data/MassActions/Actions/` (customization — see [Custom actions in `api/custom/`](#custom-actions-in-apicustom) below):

```php
<?php

namespace MintHCM\Data\MassActions\Actions;

use MintHCM\Data\MassActions\MassAction;

class Archive extends MassAction
{
    const ICON = 'mdi-archive';
    const LABEL = 'LBL_MASS_ARCHIVE';

    public function execute()
    {
        $beans = $this->getBeans();
        chdir('../legacy'); // bean saves expect the legacy working directory
        foreach ($beans as $bean) {
            if (!$bean->ACLAccess('edit')) {
                continue;
            }
            $bean->status = 'Archived';
            $bean->save();
        }
        chdir('../api');
        return ['success' => true];
    }

    public function hasAccess()
    {
        return true; // add ACL check as needed
    }
}
```

2. List the action's short name in the `massActions` key of the module's `eslistviewdefs.php` (`legacy/modules/{Module}/metadata/` or its `custom/` override) so the frontend shows it in the mass actions menu:

```php
$ESListViewDefs['WorkSchedules'] = [
    // ...
    'massActions' => ['Delete', 'Export', 'Merge', 'MassConfirmation', 'Update', 'MassAcceptance'],
];
```

The key **replaces** the default list (`ListInitController::DEFAULT_MASS_ACTIONS`: `Delete`, `Export`, `Merge`, `Update`) — list the defaults again if you want to keep them. Each listed action is shown only when its `hasAccess()` returns `true`; the menu entry carries `icon`, `label` and `action` (the PHP short class name) from `MassAction::getFrontendData()`.

3. Add a matching `actionName` static property on the **frontend** action class. The Vue `MassAction` base uses `static readonly actionName` (not `this.constructor.name`) to build the request URL — production builds minify class names, so `this.constructor.name` becomes unreliable:

```typescript
// vue/src/business/MassActions/Actions/Archive.ts
import { MassAction } from '../MassAction'

export class Archive extends MassAction {
    protected static readonly actionName = 'Archive'

    public async execute(): Promise<boolean> {
        // ...
    }
}
```

The string MUST equal the PHP short class name on the backend (no namespace), because the backend routes by `POST /{module}/MassActions/{actionName}`. The **file name** must match it too: the frontend registry (`vue/src/business/MassActions/index.ts` via `registerActionModules()`) keys classes by the file's basename, and the menu looks them up by the `action` name sent from the backend.

`$this->ids` is always a resolved array of IDs — the `all` expansion happens in the base constructor before `execute()` is called.

## Custom actions in `api/custom/`

`MassActionLoader::getAction()` resolves an action by its **short name** (e.g. `'Archive'`), checking the custom namespace first and the core namespace second. Whichever class it picks must be a subclass of `MintHCM\Data\MassActions\MassAction` — otherwise it throws `InvalidArgumentException`. The same custom-wins rule applies when a fully qualified core class name is passed in.

Custom actions live in `api/custom/data/MassActions/Actions/` under the `MintHCM\Custom\Data\MassActions\Actions` namespace, mapped in `api/composer.json`:

```json
"MintHCM\\Custom\\Data\\": "custom/data/"
```

Run `cd api && composer dump-autoload` after adding the first class there (and on every instance the customization is deployed to).

### Overriding a core action

Same short name as the core action, extending the core class — here restricting the built-in `Export`
to admins:

```php
<?php

namespace MintHCM\Custom\Data\MassActions\Actions;

use MintHCM\Data\MassActions\Actions\Export as CoreExport;

class Export extends CoreExport
{
    public function hasAccess()
    {
        return $GLOBALS['current_user']->isAdmin();
    }
}
```

`'Export'` now resolves to the custom class everywhere, with no change to core files. The frontend action class keeps `actionName = 'Export'` — routing is unchanged. When a **fully qualified core class name** is passed instead of the short name, the custom class is used only if it actually extends that core class; a short name picks the custom class whenever one exists.

### Adding a brand new action

No core counterpart — extend `MassAction` directly:

```php
<?php

namespace MintHCM\Custom\Data\MassActions\Actions;

use MintHCM\Data\MassActions\MassAction;

class SendToPayroll extends MassAction
{
    const ICON = 'mdi-cash-sync';
    const LABEL = 'LBL_MASS_SEND_TO_PAYROLL';

    public function execute()
    {
        // ...
    }

    public function hasAccess()
    {
        return true; // add ACL check as needed
    }
}
```

It is resolved by its short name `'SendToPayroll'`; the core namespace does not need a matching class. The matching frontend class goes in `vue/src/custom/business/MassActions/Actions/SendToPayroll.ts` with `protected static readonly actionName = 'SendToPayroll'` — custom frontend actions are picked up by the registry without touching core files (see [Customization Guide](../../vue/documentation/11-customization.md), section *Custom Business Logic*).

## Batched Actions with Progress UI

For long-running actions (e.g., processing dozens or hundreds of records), send the IDs to the backend in **batches** instead of a single request. The backend processes each batch and returns per-batch statistics; the frontend aggregates them and shows a progress popup.

Pattern (`MassAcceptance` is the reference implementation):

**Backend** returns per-batch stats — counts of accepted, skipped, and IDs that errored:

```php
// api/data/MassActions/Actions/MassAcceptance.php
public function execute()
{
    $accepted = 0; $skipped = 0; $errors = [];
    foreach ($this->ids as $id) {
        $bean = BeanFactory::getBean($this->module_name, $id);
        if (empty($bean) || !$bean->canBeAccepted()) { $skipped++; continue; }
        try { $bean->accept(); $accepted++; }
        catch (Exception $e) {
            $GLOBALS['log']->error("Mass acceptance failed for {$bean->id}: " . $e->getMessage());
            $errors[] = $bean->id;
            $skipped++;
        }
    }
    return ['accepted' => $accepted, 'skipped' => $skipped, 'errors' => $errors];
}
```

**Frontend** iterates in batches and pushes per-batch results into a reactive state bound to a `v-progress-linear` popup. After all batches complete, show a summary with `{accepted}` / `{skipped}` placeholders resolved from `languages.label(...)`. Reference component: `vue/src/components/MintPopups/MintPopupMassAcceptance.vue`.

Limitations of the current implementation (`vue/src/business/MassActions/Actions/MassAcceptance.ts`): batching slices the frontend `ids` array, so with "select all" (`ids: ['all']`) everything is sent in a single request and resolved on the backend — the progress bar then shows a single step. The per-batch `errors` array returned by the backend is not used by the frontend.

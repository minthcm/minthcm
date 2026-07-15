# Mass Actions

Mass actions execute an operation on multiple records at once. The system supports selecting individual records by ID or selecting all records matching the current list view filters.

## Endpoint

```
POST /{module}/MassActions/{action}
```

| Parameter | Type | Description |
|-----------|------|-------------|
| `{module}` | path | Module name (e.g., `Employees`) |
| `{action}` | path | Action class name (e.g., `Delete`, `Export`, `MassUpdate`) |

### Body Parameters

| Parameter | Required | Type | Description |
|-----------|----------|------|-------------|
| `ids` | ✅ | array | Record IDs to act on, or `["all"]` to act on all filtered records |
| `filter` | ❌ | object | Active list view filters — required when `ids` is `["all"]` |
| `update_fields` | ❌ | object | Fields to update (used by `MassUpdate` action) |

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
| `Delete` | `api/data/MassActions/Actions/Delete.php` | Hard-delete records |
| `MassUpdate` | `api/data/MassActions/Actions/MassUpdate.php` | Update fields on all selected records |
| `Export` | `api/data/MassActions/Actions/Export.php` | Export to CSV via legacy `export.php` |
| `Merge` | `api/data/MassActions/Actions/Merge.php` | Merge records via legacy `MergeRecords` |
| `MassConfirmation` | `api/data/MassActions/Actions/MassConfirmation.php` | WorkSchedules mass confirmation |

### Export and Merge: Session Bridge

`Export` and `Merge` delegate to legacy PHP pages that cannot receive a large list of IDs via URL. The API stores resolved IDs in the PHP session before redirecting:

- `Export` sets `$_SESSION['uids']` → `legacy/export.php` reads it
- `Merge` sets `$_SESSION['merge_uids']` → `legacy/modules/MergeRecords/index.php` reads it

## Creating a Custom Action

1. Extend `MassAction` in `api/data/MassActions/Actions/` (or `api/custom/data/MassActions/Actions/`):

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
        foreach ($this->getBeans() as $bean) {
            $bean->status = 'Archived';
            $bean->save();
        }
        return ['success' => true];
    }

    public function hasAccess()
    {
        return true; // add ACL check as needed
    }
}
```

2. Register the action in the module's `massActions` config so the frontend shows it in the mass actions menu.

`$this->ids` is always a resolved array of IDs — the `all` expansion happens in the base constructor before `execute()` is called.

# Sidepanel & Widgets

The **sidepanel** is a resizable, collapsible column that `RecordView` renders alongside the record panels. It hosts self-contained **widgets** showing read-only, at-a-glance HR context for the record being viewed — for Employees: tenure, upcoming leave, today's calendar, kudos, trainings, competencies; for Candidatures: recruitment stage, candidate score, application timeline. Each widget fetches and renders its own data independently; the panel itself only handles layout, ordering, and collapse.

All components live in `src/components/MintSidepanel/`.

## Enabling the sidepanel for a module

The panel is driven by the module's `recordviewdefs.php` metadata (see [Record View Definitions](./12-recordviewdefs.md)). Add a `sidepanel` key holding an ordered array of widget **component names**:

```php
// legacy/modules/Employees/metadata/recordviewdefs.php
$viewdefs['Employees'] = [
    'sidepanel' => ['MintWidgetTenure', 'MintWidgetLeave', 'MintWidgetCalendar', 'MintWidgetTrainings', 'MintWidgetCompetencies', 'MintWidgetKudos'],
    'order'  => [/* ... */],
    'panels' => [/* ... */],
];
```

`RecordViewStore` reads this from `defs.value.sidepanel` and exposes it as `store.sidepanelWidgets`, plus a `store.hasSidepanel` guard. The panel renders only when the array is non-empty **and** the record already exists — it is hidden on new/unsaved records, because every widget keys its data off `store.bean.id`.

## Registering a widget

Component names in the metadata are resolved to actual components through the `widgetComponents` map in `MintSidepanel.vue`. Adding a widget therefore takes three steps: (1) create the component under `MintSidepanel/`, (2) import it and add it to that map, and (3) list its name in the relevant module's `sidepanel` array. Widgets are not module-scoped — the same component can be reused across modules by naming it in each module's metadata.

## Building a widget

Most widgets follow the same two-part pattern: wrap the body in `MintWidgetFrame` and pull data with `useWidgetFetch`.

`MintWidgetFrame` supplies the shared chrome — an uppercase title bar with icon, a loading spinner, and an error state with a refresh button. Bind its `is-loading` / `has-error` props from the composable and forward `@refresh` to a refetch:

```vue
<MintWidgetFrame
    :is-loading="isLoading"
    :has-error="hasError"
    icon="mdi-briefcase-clock-outline"
    :title="languages.label('LBL_WIDGET_TENURE_TITLE', 'Employees')"
    :error-label="languages.label('LBL_ERROR_LOADING_DATA', 'Employees')"
    :refresh-label="languages.label('LBL_REFRESH', 'Employees')"
    @refresh="fetchData(store.bean.id)"
>
    <!-- widget body; only shown once data has loaded -->
</MintWidgetFrame>
```

```ts
const { data, isLoading, hasError, fetchData } = useWidgetFetch(
    async (id) => (await mintApi.get(`Employees/${id}/sidepanel/tenure`, { rawError: true })).data,
    defaultValue,                 // rendered on error / before first load
    (id) => `mint-tenure-${id}`,  // optional cache key; omit to disable caching
)
watch(() => store.bean.id, (id) => { if (id) fetchData(id) }, { immediate: true })
```

`useWidgetFetch` gives each widget its own loading/error state and layers three behaviors worth knowing about:

- **TTL cache (5 min), stale-while-revalidate.** When a cache key is supplied, a fresh cache hit is shown immediately *without* the loading spinner while the request still runs in the background and updates the value in place. The cache is a module-level singleton `Map` shared across all widget instances.
- **Request sequencing.** Every call bumps an internal counter and responses from superseded requests are discarded. This prevents a slow response for a previously-viewed record from overwriting the current one when the user navigates between records quickly.
- **Logout invalidation.** The cache holds other people's HR data, so `auth.ts` calls `clearWidgetFetchCache()` on logout as defense-in-depth. The cache is in-memory only and never persisted.

Not every widget needs a dedicated backend endpoint. `MintWidgetRecruitmentStage` derives entirely from `store.bean.syncAttributes.status` with no fetch at all, and `MintWidgetCalendar` reuses the existing `/scheduler` endpoint rather than a sidepanel-specific one — `useWidgetFetch` accepts any async fetcher.

## Ordering, collapse, and persistence

- **Drag-to-reorder.** A widget becomes draggable only when the drag starts on its title bar. The panel detects the handle via the frame's `__title` element (attribute selector `[class*="__title"]`). A custom widget that does **not** use `MintWidgetFrame` must still expose an element whose class ends in `__title` (and one ending in `__content`) for dragging and collapse to work — see `MintWidgetRecruitmentStage.vue` for an example that reimplements both.
- **Collapse.** Collapsing hides the `__content` element via CSS. Collapsed widgets are also lazily mounted: a widget that starts collapsed is not instantiated (and does not fetch) until its first expand (`everExpanded`).
- **Persistence (per module, `localStorage`).** Widget order is stored under `mint-sidepanel-widget-order-{module}` and collapse state under `mint-sidepanel-widget-collapsed-{module}`; stale/unknown names are filtered out on load and newly-added widgets are appended. Panel width and the whole-panel collapse toggle are owned by `RecordView.vue` (`mint-sidepanel-width` / `mint-sidepanel-collapsed`).

---

**Related Documentation**:
- [Record View Definitions](./12-recordviewdefs.md)
- [Sidepanel Endpoints (API)](../../api/documentation/16-sidepanel.md)

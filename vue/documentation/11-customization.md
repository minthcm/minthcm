# Customization Guide

This guide shows you how to safely extend and customize the MintHCM frontend without modifying core files.

## The `custom/` Directory

Customizations go in `src/custom/` so that they are kept separate from core code during updates.

```
src/custom/
├── business/
│   ├── BeanActions/Actions/             # Record actions
│   ├── MassActions/Actions/             # Mass actions
│   └── SubpanelActions/
│       ├── Actions/                     # Subpanel header actions
│       └── InlineActions/               # Subpanel row actions
├── components/
│   └── ListViewPanels/                  # Panels under the list view
├── drawers/                             # Right-side drawers (*.ts)
├── router/
│   └── index.ts                         # Custom routes (default-exported array)
├── store/                               # Custom Pinia stores
└── views/                               # Custom page components
    └── ListView/Actions/                # List row actions (*.ts)
```

**Why use `custom/`?**

✅ **Update-safe** - Core updates won't overwrite your code  
✅ **Organized** - Clear separation of core vs custom  
✅ **Maintainable** - See what you've customized at a glance

## Extension Points

Only the following paths are picked up automatically (all through `import.meta.glob`, so a rebuild is required after changes). Anything else under `custom/` is used only if you import it yourself, for example from a custom route.

| What | Custom path | Loaded by | Rule |
|------|-------------|-----------|------|
| Routes | `custom/router/index.ts` (default export `RouteRecordRaw[]`) | `router/routes.ts` | Appended after core routes |
| Record actions | `custom/business/BeanActions/Actions/<Name>.ts` | `business/BeanActions/index.ts` | Same file name overrides core |
| Mass actions | `custom/business/MassActions/Actions/<Name>.ts` | `business/MassActions/index.ts` | Same file name overrides core |
| Subpanel actions | `custom/business/SubpanelActions/{Actions,InlineActions}/<Name>.ts` | `business/SubpanelActions/index.ts` | Same file name overrides core |
| List row actions | `custom/views/ListView/Actions/<Name>.ts` | `views/ListView/ListViewStore.ts` | Used for action names that core does not define |
| List view panels | `custom/components/ListViewPanels/<Name>.vue` | `views/ListView/ListView.vue` | Custom wins over core panel of the same name |
| Drawers | `custom/drawers/<name>.drawer.ts` | `bundler/drawers.bundle.ts` | Added next to core drawers (key `custom-<name>`) |
| Menu components | `custom/components/**/<Path>.vue` | `utils/componentLoader.ts` | `componentPath` of a menu action; custom checked first |

**Not supported:**

- **Overriding a core component or view by placing a file at the same path in `custom/`.** There is no such mechanism: core imports always resolve to `src/`.
- **Custom field types in `custom/components/Fields/`.** `Field.vue` and `Field.config.ts` load field components only from `src/components/Fields/`. A new field type is currently added in core as `src/components/Fields/<type>/<type>.{detail,edit,list}.vue` and is registered automatically by the glob; see [Field System](./09-fields.md#adding-a-new-field-type).

## Custom Components

Reusable components for your customizations can live anywhere under `custom/` and are imported explicitly.

**File:** `custom/components/MyCustomComponent.vue`

```vue
<template>
    <v-card class="my-custom-component">
        <v-card-title>{{ title }}</v-card-title>
        <v-card-text>
            <slot></slot>
        </v-card-text>
        <v-card-actions>
            <v-btn @click="handleAction">
                {{ actionLabel }}
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script setup lang="ts">
interface Props {
    title: string
    actionLabel?: string
}

const props = withDefaults(defineProps<Props>(), {
    actionLabel: 'OK'
})

const emit = defineEmits(['action'])

function handleAction() {
    emit('action')
}
</script>

<style scoped>
.my-custom-component {
    border-radius: 12px;
}
</style>
```

**Usage:**

```vue
<template>
    <MyCustomComponent 
        title="Hello"
        @action="onAction"
    >
        <p>Custom content here</p>
    </MyCustomComponent>
</template>

<script setup lang="ts">
import MyCustomComponent from '@/custom/components/MyCustomComponent.vue'

function onAction() {
    console.log('Action triggered!')
}
</script>
```

## Custom Routes

Add new routes without modifying the core router. `src/router/routes.ts` already imports `@/custom/router` and appends its default export after the core routes:

```typescript
// src/router/routes.ts (core - no change needed)
import customRoutes from '@/custom/router'
// ...
const routes: Array<RouteRecordRaw> = [...coreRoutes, ...customRoutes]
```

**File:** `custom/router/index.ts`

```typescript
import { RouteRecordRaw } from 'vue-router'

const customRoutes: Array<RouteRecordRaw> = [
    {
        path: '/my-custom-page',
        name: 'my-custom-page',
        component: () => import('@/custom/views/MyCustomPage/MyCustomPage.vue'),
        meta: {
            auth: true,  // Requires authentication
        },
    },
    {
        path: '/public-page',
        name: 'public-page',
        component: () => import('@/custom/views/PublicPage.vue'),
        meta: {
            auth: false,  // Public page
        },
    },
]

export default customRoutes
```

The global guards in `src/router/index.ts` (install check, `init`, authentication via `meta.auth`, `meta.adminOnly`) apply to custom routes as well. The app uses hash history, so the page is reachable at `#/my-custom-page`.

## Custom Stores

Create custom Pinia stores for your features.

**File:** `custom/store/myFeature.ts`

```typescript
import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { mintApi } from '@/api/api'

export const useMyFeatureStore = defineStore('myFeature', () => {
    // State
    const items = ref<any[]>([])
    const loading = ref(false)
    const error = ref<string | null>(null)
    
    // Getters
    const activeItems = computed(() => 
        items.value.filter(item => item.active)
    )
    
    const itemCount = computed(() => items.value.length)
    
    // Actions
    async function fetchItems() {
        loading.value = true
        error.value = null
        
        try {
            const response = await mintApi.get('/my-feature/items')
            items.value = response.data
        } catch (err) {
            error.value = err.message
        } finally {
            loading.value = false
        }
    }
    
    async function createItem(data: any) {
        const response = await mintApi.post('/my-feature/items', data)
        items.value.push(response.data)
        return response.data
    }
    
    async function updateItem(id: string, data: any) {
        const response = await mintApi.put(`/my-feature/items/${id}`, data)
        const index = items.value.findIndex(item => item.id === id)
        if (index !== -1) {
            items.value[index] = response.data
        }
        return response.data
    }
    
    async function deleteItem(id: string) {
        await mintApi.delete(`/my-feature/items/${id}`)
        items.value = items.value.filter(item => item.id !== id)
    }

    // No manual $reset() needed: the global reset-store plugin (src/plugins/reset-store.ts)
    // gives every store a $reset() that restores its initial state.

    return {
        // State
        items,
        loading,
        error,
        // Getters
        activeItems,
        itemCount,
        // Actions
        fetchItems,
        createItem,
        updateItem,
        deleteItem,
    }
})
```

**Usage in component:**

```vue
<script setup lang="ts">
import { useMyFeatureStore } from '@/custom/store/myFeature'
import { onMounted } from 'vue'

const store = useMyFeatureStore()

onMounted(() => {
    store.fetchItems()
})

async function createNew() {
    await store.createItem({ name: 'New Item' })
}
</script>

<template>
    <div v-if="store.loading">Loading...</div>
    <div v-else>
        <div v-for="item in store.activeItems" :key="item.id">
            {{ item.name }}
        </div>
        <v-btn @click="createNew">Create New</v-btn>
    </div>
</template>
```

## Custom Views

Create complete custom pages.

**File:** `custom/views/MyCustomPage/MyCustomPage.vue`

```vue
<template>
    <div class="my-custom-page">
        <h1>{{ pageTitle }}</h1>
        
        <v-card class="mt-4">
            <v-card-text>
                <MyCustomForm 
                    v-model="formData"
                    @submit="handleSubmit"
                />
            </v-card-text>
        </v-card>
        
        <v-card class="mt-4">
            <v-card-text>
                <MyCustomTable 
                    :items="items"
                    @edit="handleEdit"
                    @delete="handleDelete"
                />
            </v-card-text>
        </v-card>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useMyFeatureStore } from '@/custom/store/myFeature'
import { useLanguagesStore } from '@/store/languages'
import MyCustomForm from './MyCustomForm.vue'
import MyCustomTable from './MyCustomTable.vue'

const languages = useLanguagesStore()
const store = useMyFeatureStore()

const pageTitle = ref('My Custom Page')
const formData = ref({})
const items = ref([])

onMounted(async () => {
    await store.fetchItems()
    items.value = store.items
})

async function handleSubmit(data: any) {
    await store.createItem(data)
    formData.value = {}
}

async function handleEdit(item: any) {
    // Edit logic
}

async function handleDelete(item: any) {
    if (confirm('Are you sure?')) {
        await store.deleteItem(item.id)
    }
}
</script>

<style scoped lang="scss">
.my-custom-page {
    padding: 20px;
    
    h1 {
        font-size: 24px;
        font-weight: 600;
    }
}
</style>
```

## Custom Business Logic

Record-level actions (the menu on a record view) are `BeanAction` subclasses. Core actions live in
`vue/src/business/BeanActions/Actions/`; a deployment adds its own — or overrides a core one — in
`vue/src/custom/business/BeanActions/Actions/`. **There is no registry to call**: both directories are
scanned with `import.meta.glob` at build time, core first and custom second, so a custom file
overrides the core action of the same name deterministically. The **file name** is the action name
used in `recordviewdefs.php`.

### When you need a class at all

Only for **named** actions. An action that just POSTs to an endpoint (optionally behind a confirm or
prompt popup) is declared entirely in metadata with `api_route` and handled by `GenericApiAction` —
no TypeScript and no frontend rebuild. See
[Record View Definitions](12-recordviewdefs.md), section *Actions*. Write a class only when the action
needs real client-side behavior (opening a window, navigating, driving a custom popup).

### The `BeanAction` contract

```typescript
abstract class BeanAction {
    public static readonly TITLE: string   // i18n key for the menu label
    public static readonly ICON: string    // MDI icon, default 'mdi-circle-medium'
    public static readonly ACL: string[]   // ACL actions required to show the item, e.g. ['edit']

    protected bean: ReturnType<typeof useBean>   // current record: id, module, attributes, aclAccess
    protected options: BeanActionOptions         // the action object from recordviewdefs (a plain string declaration yields {})

    public abstract execute(): Promise<boolean>

    public isAvailable(): boolean                // override for visibility beyond ACL
    public toMenuListItem(): MenuListItem        // override to change how the item renders
}
```

`TITLE`, `ICON` and `ACL` are **static** members, and `options` (`title`, `icon`, `acl`, plus anything
else declared in metadata) overrides them per declaration.

### Adding a custom action

**File:** `vue/src/custom/business/BeanActions/Actions/SendToPayroll.ts`

```typescript
import { BeanAction } from '@/business/BeanActions/BeanAction'
import { mintApi } from '@/api/api'
import { usePopupsStore } from '@/store/popups'

export class SendToPayroll extends BeanAction {
    public static readonly TITLE = 'LBL_SEND_TO_PAYROLL'
    public static readonly ICON = 'mdi-cash-sync'
    public static readonly ACL = ['edit']

    public async execute(): Promise<boolean> {
        const response = await mintApi.post('Employees/sendToPayroll', {
            id: this.bean.id,
            module: this.bean.module,
        })

        usePopupsStore().alert(response.data.message)

        return response.data.success === true
    }

    public isAvailable(): boolean {
        return super.isAvailable() && this.bean.attributes?.status === 'Active'
    }
}
```

Export it either as a **named export matching the file name** (as above) or as the file's `default`
export — the registry accepts both.

Then reference it by file name in the module's `recordviewdefs.php`:

```php
'actions' => [
    'SendToPayroll',
    // or, with per-declaration overrides:
    ['name' => 'SendToPayroll', 'icon' => 'mdi-bank-transfer'],
],
```

Declaring it does **not** remove `Audit` / `Delete` / `Duplicate` — the backend merges declared actions
on top of the defaults. See [Record View Definitions](12-recordviewdefs.md), section *Actions*, for the
merge rules and the `includeDefaultActions` flag.

### Overriding a core action

Give the custom file the **same name** as the core one — e.g.
`vue/src/custom/business/BeanActions/Actions/Duplicate.ts` replaces
`vue/src/business/BeanActions/Actions/Duplicate.ts` everywhere, with no change to core files.

### Rebuild

Named actions are bundled at build time, so a new or changed class needs:

```bash
cd vue
npm run build   # build:repo is an alias
```

Then commit the rebuilt `vue/dist/` together with the source.

**Security:** `isAvailable()` and `customVisibility` only control what the UI shows. Any endpoint an
action calls must independently re-check ACL, the record's existence and module, and every business
precondition server-side.

### Other action registries

The same core-then-custom rule applies to the remaining action types:

| Action type | Core | Custom |
|---|---|---|
| Record actions | `vue/src/business/BeanActions/Actions/` | `vue/src/custom/business/BeanActions/Actions/` |
| Mass actions | `vue/src/business/MassActions/Actions/` | `vue/src/custom/business/MassActions/Actions/` |
| Subpanel actions | `vue/src/business/SubpanelActions/Actions/` | `vue/src/custom/business/SubpanelActions/Actions/` |
| Subpanel inline actions | `vue/src/business/SubpanelActions/InlineActions/` | `vue/src/custom/business/SubpanelActions/InlineActions/` |

**List row actions** work differently: for an action name in the list view config that has no core implementation, `ListViewStore.loadCustomAction()` lazily loads `vue/src/custom/views/ListView/Actions/<Name>.ts`. Its default export is a factory that receives `{ router, store, url, languages, popups, backend, ComponentLoader, mintApi }` and returns `{ icon, label?, onClick(item), hasAccess?(item) }`.

Mass actions additionally need a backend counterpart — see
[Mass Actions](../../api/documentation/15-mass-actions.md), section *Custom actions in `api/custom/`*.

## Custom API Endpoints

Add custom API integration. `custom/api/` is a convention, not an auto-loaded directory: import the file where you need it. The endpoints themselves must exist in the API (custom routes in `api/custom/`, see [Extending the API](../../api/documentation/08-extending-api.md)). Paths are relative to the `mintApi` base URL `api/` (a leading `/` is stripped by axios). Built-in record endpoints use `PATCH {module}/Update/{id}`; the `put`/`delete` calls below assume your custom routes define those methods.

**File:** `custom/api/myfeature.api.ts`

```typescript
import { mintApi } from '@/api/api'

export interface MyFeatureItem {
    id: string
    name: string
    description: string
    created_date: string
}

export interface MyFeatureListResponse {
    data: MyFeatureItem[]
    total: number
}

export const myFeatureApi = {
    /**
     * Get all items
     */
    async getItems(): Promise<MyFeatureListResponse> {
        const response = await mintApi.get<MyFeatureListResponse>(
            '/my-feature/items'
        )
        return response.data
    },
    
    /**
     * Get single item
     */
    async getItem(id: string): Promise<MyFeatureItem> {
        const response = await mintApi.get<MyFeatureItem>(
            `/my-feature/items/${id}`
        )
        return response.data
    },
    
    /**
     * Create new item
     */
    async createItem(data: Partial<MyFeatureItem>): Promise<MyFeatureItem> {
        const response = await mintApi.post<MyFeatureItem>(
            '/my-feature/items',
            data
        )
        return response.data
    },
    
    /**
     * Update item
     */
    async updateItem(
        id: string, 
        data: Partial<MyFeatureItem>
    ): Promise<MyFeatureItem> {
        const response = await mintApi.put<MyFeatureItem>(
            `/my-feature/items/${id}`,
            data
        )
        return response.data
    },
    
    /**
     * Delete item
     */
    async deleteItem(id: string): Promise<void> {
        await mintApi.delete(`/my-feature/items/${id}`)
    },
}
```

**Usage:**

```typescript
import { myFeatureApi } from '@/custom/api/myfeature.api'

// In component or store
const items = await myFeatureApi.getItems()
const item = await myFeatureApi.getItem('123')
await myFeatureApi.createItem({ name: 'New' })
```

## Custom Drawers

Add custom drawers to the right-side drawer bar. `bundler/drawers.bundle.ts` loads every `src/drawers/*.ts` and `src/custom/drawers/*.ts` file; a custom file `myDrawer.drawer.ts` gets the key `custom-myDrawer`.

**File:** `custom/drawers/myDrawer.drawer.ts`

```typescript
import MyDrawer from '@/custom/components/MyDrawer.vue'
import { useACL } from '@/composables/useACL'

export default {
    icon: 'mdi-star',                    // Button icon in the drawer bar
    component: MyDrawer,                 // Rendered with <component :is> - pass a component, not () => import()
    label: 'LBL_MY_DRAWER',              // Button aria-label (translated)
    descriptionLabel: 'LBL_MY_DRAWER_HELP', // Optional help text
    // Required: the button is shown only when this returns true (note the spelling used by core)
    isAvaliable: () => useACL().hasAccess('Employees', 'list', true),
    // Optional: badge content; the badge is shown when truthy
    badge: () => 0,
    // Optional: called on scroll of the drawer content (e.g. infinite loading)
    onScroll: (drawerElement: HTMLElement) => {},
}
```

To load the component lazily, wrap it: `component: defineAsyncComponent(() => import('@/custom/components/MyDrawer.vue'))`.

**File:** `custom/components/MyDrawer.vue`

The component is rendered inside the drawer container provided by `DefaultLayoutDrawer.vue`, receives no props, and must not create its own `v-navigation-drawer`:

```vue
<template>
    <div class="my-drawer">
        <h2>{{ languages.label('LBL_MY_DRAWER') }}</h2>
        <!-- Drawer content: read what you need from stores, e.g. the current route/module -->
        <p>Module: {{ route.params.module }}</p>
    </div>
</template>

<script setup lang="ts">
import { useRoute } from 'vue-router'
import { useLanguagesStore } from '@/store/languages'

const route = useRoute()
const languages = useLanguagesStore()
</script>
```

## Styling Customizations

There is no automatically loaded custom stylesheet.

- **Theme colors** (light/dark) are defined in `src/plugins/vuetify.ts` (`theme.themes.light/dark.colors`); see [User Theme System](./14-user-theme.md). Vuetify 4 does not read SASS variables such as `$primary` from an imported SCSS file.
- **Custom components** should use scoped styles and the theme CSS variables so they follow light/dark mode:

```vue
<style scoped lang="scss">
.custom-card {
    border-radius: 12px;
    background: rgb(var(--v-theme-surface));
    color: rgb(var(--v-theme-on-surface));
    border-left: 4px solid rgb(var(--v-theme-primary));
}
</style>
```

- **Global overrides** of core styles need an import in core `src/main.ts` (or `src/main.scss`). That is a core change, so keep it minimal and document it.

## Best Practices

### ✅ DO

- **Use the `custom/` extension points** listed above
- **Follow naming conventions** (PascalCase for components, camelCase for utils)
- **Type your code** with TypeScript interfaces
- **Document your customizations** with comments
- **Rebuild** (`npm run build`) and commit `vue/dist/` after changes
- **Test thoroughly** before deploying

### ❌ DON'T

- **Modify core files** unless there is no extension point (field types, global styles); keep such changes minimal
- **Duplicate core code** (extend instead)
- **Hardcode values** (use constants/config)
- **Skip TypeScript** (type safety prevents bugs)
- **Ignore ESLint** warnings (they catch issues)

## Troubleshooting

### Custom Component Not Loading

**Check:**
1. The file is in one of the auto-loaded paths (see [Extension Points](#extension-points)) or is imported explicitly
2. Component is properly exported
3. No TypeScript errors
4. Import path uses `@/` alias correctly
5. The frontend was rebuilt

### Custom Route Not Working

**Check:**
1. The route is in the default export of `custom/router/index.ts`
2. Route name is unique
3. Component path is correct
4. `meta.auth` is set correctly
5. The URL uses the hash (`#/my-custom-page`)

### Custom Store Not Updating

**Check:**
1. Using `.value` for refs inside the store
2. State is returned from the `defineStore` setup function
3. Store is imported correctly
4. Calling actions, not accessing them

## Next Steps

- Learn about [Field System](./09-fields.md) for field types
- Explore [User Theme System](./14-user-theme.md) for appearance customization
- Read [Record View Definitions](./12-recordviewdefs.md) for record view metadata and actions

---

**Customize safely, update confidently!** 🎨

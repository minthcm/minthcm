# Customization Guide

This guide shows you how to safely extend and customize the MintHCM frontend without modifying core files.

## The `custom/` Directory

All customizations should go in `src/custom/` to ensure they're not overwritten during updates.

```
src/custom/
├── drawers/        # Custom drawer definitions
├── router/         # Custom routes
├── store/          # Custom Pinia stores
└── views/          # Custom page components
```

**Why use `custom/`?**

✅ **Update-safe** - Core updates won't overwrite your code  
✅ **Organized** - Clear separation of core vs custom  
✅ **Portable** - Easy to version control separately  
✅ **Maintainable** - See what you've customized at a glance

## Customizing Components

### Extending Field Types

Create a custom field type by adding to `custom/`:

**Goal:** Add a custom "color picker" field type

**1. Create field component:**

```
src/custom/
└── components/
    └── Fields/
        └── colorpicker/
            ├── colorpicker.detail.vue
            ├── colorpicker.edit.vue
            └── colorpicker.list.vue
```

**File:** `custom/components/Fields/colorpicker/colorpicker.edit.vue`

```vue
<template>
    <div class="color-picker-field">
        <label v-if="label">{{ label }}</label>
        <input 
            type="color" 
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
        />
    </div>
</template>

<script setup lang="ts">
interface Props {
    modelValue?: string
    label?: string
    defs?: any
    state?: 'normal' | 'required' | 'error'
}

const props = defineProps<Props>()
defineEmits(['update:modelValue'])
</script>

<style scoped>
input[type="color"] {
    width: 50px;
    height: 50px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}
</style>
```

**File:** `custom/components/Fields/colorpicker/colorpicker.detail.vue`

```vue
<template>
    <div class="color-picker-detail">
        <div 
            class="color-swatch"
            :style="{ backgroundColor: modelValue }"
        ></div>
        <span>{{ modelValue }}</span>
    </div>
</template>

<script setup lang="ts">
interface Props {
    modelValue?: string
    label?: string
}

const props = defineProps<Props>()
</script>

<style scoped>
.color-picker-detail {
    display: flex;
    align-items: center;
    gap: 8px;
}

.color-swatch {
    width: 30px;
    height: 30px;
    border-radius: 4px;
    border: 1px solid #ccc;
}
</style>
```

**2. Register field type:**

Modify `src/components/Fields/Field.config.ts` to include your custom type:

```typescript
// Or better: create custom/components/Fields/Field.config.ts
export const customFieldConfig = {
    allowedTypes: {
        detail: ['colorpicker'],
        edit: ['colorpicker'],
        list: ['colorpicker'],
    },
    typeMap: {
        'color': 'colorpicker',  // Map backend type to your component
    }
}
```

**3. Use in module:**

Now any field with `type: 'colorpicker'` will use your custom component:

```typescript
// Backend vardefs
$dictionary['MyModule']['fields']['brand_color'] = [
    'name' => 'brand_color',
    'type' => 'colorpicker',
    'label' => 'Brand Color',
];
```

### Overriding Core Components

To replace a core component, create a file at the same path in `custom/`:

**Example:** Customize the Dashboard

**File:** `custom/views/DashboardView/DashboardView.vue`

```vue
<template>
    <div class="custom-dashboard">
        <h1>Custom Dashboard</h1>
        
        <!-- Your custom dashboard content -->
        <div class="widgets">
            <CustomWidget />
            <AnotherWidget />
        </div>
    </div>
</template>

<script setup lang="ts">
import CustomWidget from './CustomWidget.vue'
import AnotherWidget from './AnotherWidget.vue'
</script>

<style scoped>
.custom-dashboard {
    padding: 20px;
}

.widgets {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}
</style>
```

**Loading:** Update bundler or import system to prioritize `custom/` over core.

### Creating Reusable Components

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

Add new routes without modifying core router.

**File:** `custom/router/routes.ts`

```typescript
import { RouteRecordRaw } from 'vue-router'

const customRoutes: RouteRecordRaw[] = [
    {
        path: '/my-custom-page',
        name: 'my-custom-page',
        component: () => import('@/custom/views/MyCustomPage.vue'),
        meta: {
            auth: true,  // Requires authentication
        }
    },
    {
        path: '/public-page',
        name: 'public-page',
        component: () => import('@/custom/views/PublicPage.vue'),
        meta: {
            auth: false,  // Public page
        }
    },
]

export default customRoutes
```

**File:** `custom/router/index.ts`

```typescript
import { Router } from 'vue-router'
import customRoutes from './routes'

export function registerCustomRoutes(router: Router) {
    customRoutes.forEach(route => {
        router.addRoute(route)
    })
}
```

**File:** `src/router/index.ts` (modify to include custom routes)

```typescript
import { createRouter } from 'vue-router'
import routes from './routes'
import { registerCustomRoutes } from '@/custom/router'

const router = createRouter({
    history: createWebHashHistory(),
    routes,
})

// Register custom routes
registerCustomRoutes(router)

export default router
```

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
    
    function $reset() {
        items.value = []
        loading.value = false
        error.value = null
    }
    
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
        $reset,
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
npm run build:repo
```

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

Mass actions additionally need a backend counterpart — see
[Mass Actions](../../api/documentation/15-mass-actions.md), section *Custom actions in `api/custom/`*.

## Custom API Endpoints

Add custom API integration.

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

Add custom sidebar drawers.

**File:** `custom/drawers/myDrawer.drawer.ts`

```typescript
export default {
    name: 'myDrawer',
    component: () => import('@/custom/components/MyDrawer.vue'),
    props: {
        module: String,
        recordId: String,
    }
}
```

**File:** `custom/components/MyDrawer.vue`

```vue
<template>
    <v-navigation-drawer
        v-model="isOpen"
        temporary
        location="right"
        width="400"
    >
        <v-toolbar>
            <v-toolbar-title>My Custom Drawer</v-toolbar-title>
            <v-spacer />
            <v-btn icon @click="isOpen = false">
                <v-icon>mdi-close</v-icon>
            </v-btn>
        </v-toolbar>
        
        <v-card-text>
            <!-- Drawer content -->
            <p>Module: {{ module }}</p>
            <p>Record ID: {{ recordId }}</p>
        </v-card-text>
    </v-navigation-drawer>
</template>

<script setup lang="ts">
import { ref } from 'vue'

interface Props {
    module: string
    recordId: string
}

const props = defineProps<Props>()
const isOpen = ref(true)
</script>
```

## Styling Customizations

Override or extend styles.

**File:** `custom/styles/custom.scss`

```scss
// Override Vuetify variables
$primary: #2196F3;
$secondary: #424242;
$accent: #82B1FF;

// Custom utility classes
.custom-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

.custom-card {
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

// Override core component styles
.list-view {
    .custom-header {
        background: linear-gradient(135deg, $primary 0%, $secondary 100%);
        color: white;
        padding: 20px;
    }
}

// Add dark mode support
.v-theme--dark {
    .custom-card {
        background: #1e1e1e;
    }
}
```

**Import in main:**

```typescript
// src/main.ts
import '@/custom/styles/custom.scss'
```

## Best Practices

### ✅ DO

- **Use `custom/` directory** for all customizations
- **Follow naming conventions** (PascalCase for components, camelCase for utils)
- **Type your code** with TypeScript interfaces
- **Document your customizations** with comments
- **Test thoroughly** before deploying
- **Version control** your `custom/` directory separately

### ❌ DON'T

- **Modify core files** (they'll be overwritten)
- **Duplicate core code** (extend instead)
- **Hardcode values** (use constants/config)
- **Skip TypeScript** (type safety prevents bugs)
- **Ignore ESLint** warnings (they catch issues)

## Troubleshooting

### Custom Component Not Loading

**Check:**
1. File path matches expected pattern
2. Component is properly exported
3. No TypeScript errors
4. Import path uses `@/` alias correctly

### Custom Route Not Working

**Check:**
1. Route is registered in router
2. Route name is unique
3. Component path is correct
4. Meta auth property is set correctly

### Custom Store Not Updating

**Check:**
1. Using `.value` for refs
2. Store is returned from defineStore
3. Store is imported correctly
4. Calling actions, not accessing them

## Next Steps

- Learn about [Field System](./09-fields.md) for custom field types
- Explore [Styling & Theming](./12-styling-theming.md) for appearance customization
- Read [Best Practices](./15-best-practices.md) for code quality

---

**Customize safely, update confidently!** 🎨

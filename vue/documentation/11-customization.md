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

Add custom actions for records.

**File:** `custom/business/BeanActions/Actions/CustomAction.ts`

```typescript
import { BeanAction } from '@/business/BeanActions/BeanAction'
import { mintApi } from '@/api/api'

export class CustomAction extends BeanAction {
    name = 'custom_action'
    label = 'Custom Action'
    icon = 'mdi-star'
    
    async execute(module: string, id: string) {
        try {
            await mintApi.post(`${module}/${id}/custom-action`)
            
            this.showNotification({
                type: 'success',
                message: 'Custom action executed successfully!'
            })
            
            // Refresh the record
            await this.refresh()
        } catch (error) {
            this.showNotification({
                type: 'error',
                message: 'Custom action failed'
            })
        }
    }
    
    isAvailable(module: string, record: any): boolean {
        // Show only for specific modules
        return ['Users', 'Candidates'].includes(module)
    }
}
```

**Register action:**

```typescript
// custom/business/BeanActions/index.ts
import { CustomAction } from './Actions/CustomAction'

export function registerCustomActions() {
    // Register with action system
    BeanActionRegistry.register(new CustomAction())
}
```

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

# Project Architecture

Understanding the MintHCM frontend architecture will help you make informed decisions when developing and customizing the application.

## Technology Stack

### Core Framework: Vue 3

**Vue 3** is the progressive JavaScript framework powering MintHCM's UI.

**Key Features Used:**
- **Composition API** - Modern, flexible component logic
- **Script Setup** - Cleaner component syntax
- **Reactive System** - Efficient, automatic UI updates
- **Single File Components** - HTML, JS, CSS in one file

```vue
<template>
    <div>{{ message }}</div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const message = ref('Hello, MintHCM!')
</script>

<style scoped>
div { color: blue; }
</style>
```

**Why Vue 3?**
- ✅ Better TypeScript support
- ✅ Smaller bundle size
- ✅ Faster rendering
- ✅ Composition API for better code reuse

### Language: TypeScript

**TypeScript** adds static typing to JavaScript for safer, more maintainable code.

**Benefits:**
- **Type Safety** - Catch errors at compile time
- **IntelliSense** - Better IDE autocomplete
- **Refactoring** - Safer code changes
- **Self-Documentation** - Types explain code

```typescript
// Type-safe props
interface Props {
    userId: string
    isActive: boolean
}

const props = defineProps<Props>()

// TypeScript prevents this error:
// props.userId = 123  // ❌ Error: Type 'number' is not assignable to type 'string'
```

**Configuration:** `tsconfig.json`

### Build Tool: Vite

**Vite** is the next-generation frontend build tool.

**Advantages over Webpack:**
- ⚡ **Lightning Fast** - Native ESM for instant dev server start
- 🔥 **Hot Module Replacement** - Sub-second updates
- 📦 **Optimized Builds** - Rollup-based production bundling
- 🔌 **Plugin Ecosystem** - Rich plugin support

**Configuration:** `vite.config.ts`

```typescript
export default defineConfig({
    plugins: [
        vue(),
        vuetify({ autoImport: true }),
    ],
    server: {
        proxy: {
            '/api': { target: process.env.PROXY_URL },
        },
    },
})
```

### UI Framework: Vuetify

**Vuetify** provides Material Design components out of the box.

**Components Used:**
- `v-btn`, `v-card`, `v-dialog` - UI elements
- `v-data-table` - Enhanced tables
- `v-menu`, `v-list` - Navigation
- `v-form`, `v-text-field` - Forms
- Layout system - `v-container`, `v-row`, `v-col`

```vue
<template>
    <v-btn color="primary" @click="save">
        Save Changes
    </v-btn>
</template>
```

**Customization:** `src/plugins/vuetify.ts`

### State Management: Pinia

**Pinia** is the official Vue state management library (successor to Vuex).

**Why Pinia?**
- 🍍 **Simpler API** - Less boilerplate than Vuex
- 📝 **TypeScript First** - Excellent type inference
- 🔌 **Modular** - Independent stores
- 🛠️ **DevTools** - Great debugging experience

**Store Structure:**

```typescript
import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useUserStore = defineStore('user', () => {
    // State
    const user = ref(null)
    
    // Actions
    function setUser(newUser) {
        user.value = newUser
    }
    
    return { user, setUser }
})
```

**Main Stores:**
- `backend` - Application initialization, global config
- `auth` - User authentication
- `modules` - Module definitions and metadata
- `languages` - Translations and localization
- `preferences` - User and global preferences

### Routing: Vue Router 4

**Vue Router** handles client-side navigation.

**Features:**
- **Dynamic Routes** - `/modules/:module/:view/:id?`
- **Navigation Guards** - Authentication, permissions
- **Nested Routes** - Layouts and sub-views
- **Hash Mode** - `createWebHashHistory()` for compatibility

```typescript
const routes = [
    {
        path: '/modules/:module/list',
        name: 'list',
        component: ListView,
        meta: { auth: true }
    }
]
```

**Configuration:** `src/router/index.ts`

### HTTP Client: Axios

**Axios** manages all API communication with the backend.

**Features:**
- **Interceptors** - Global request/response handling
- **Error Handling** - Centralized error management
- **Type Safety** - TypeScript interfaces for API responses
- **Retry Logic** - Automatic retries for failed requests

```typescript
import { mintApi } from '@/api/api'

// Type-safe API call
const response = await mintApi.get<User>('user/profile')
const user: User = response.data
```

**Configuration:** `src/api/api.ts`

## Architecture Patterns

### Component-Based Architecture

The app is built from reusable, composable components:

```
App
├── DefaultLayout
│   ├── TopBar
│   ├── Sidebar
│   └── RouterView
│       └── ListView
│           ├── ListViewFilters
│           ├── ListViewHeader
│           └── ListViewTable
│               └── MintDataTable
│                   └── Field (dynamic)
```

**Benefits:**
- **Reusability** - Write once, use everywhere
- **Maintainability** - Small, focused components
- **Testability** - Isolated units to test
- **Composability** - Combine small parts into complex UIs

### Composition API Pattern

We use the Composition API with `<script setup>` for all components:

```vue
<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'

// Props
const props = defineProps<{ userId: string }>()

// Reactive state
const user = ref(null)

// Computed properties
const fullName = computed(() => 
    `${user.value?.first_name} ${user.value?.last_name}`
)

// Lifecycle hooks
onMounted(async () => {
    user.value = await fetchUser(props.userId)
})

// Methods
async function save() {
    await updateUser(user.value)
}
</script>
```

### Composables Pattern

Reusable logic is extracted into composable functions:

```typescript
// src/composables/useBean.ts
export function useBean(module: string, id: string) {
    const data = ref(null)
    const loading = ref(false)
    
    async function fetch() {
        loading.value = true
        data.value = await mintApi.get(`${module}/${id}`)
        loading.value = false
    }
    
    return { data, loading, fetch }
}

// Usage in component
const { data, loading, fetch } = useBean('Users', '123')
```

**Common Composables:**
- `useACL` - Access control checks
- `useBean` - Bean data management
- `useField` - Field rendering logic
- `useLink` - Related records
- `useLogic` - Business logic execution

### Store Pattern

Pinia stores follow a consistent structure:

```typescript
export const useMyStore = defineStore('myStore', () => {
    // 1. State (reactive references)
    const data = ref([])
    const loading = ref(false)
    
    // 2. Getters (computed properties)
    const filteredData = computed(() => 
        data.value.filter(item => item.active)
    )
    
    // 3. Actions (methods)
    async function fetchData() {
        loading.value = true
        data.value = await api.get('/data')
        loading.value = false
    }
    
    // 4. Return public API
    return {
        data,
        loading,
        filteredData,
        fetchData,
    }
})
```

### Dynamic Component Loading

Components are loaded dynamically based on configuration:

```typescript
// Field component loader
const FieldComponent = defineAsyncComponent(() => {
    const type = props.defs?.type ?? 'text'
    const view = props.view // 'detail', 'edit', 'list'
    
    return import(`@/components/Fields/${type}/${type}.${view}.vue`)
})
```

**Benefits:**
- **Code Splitting** - Smaller initial bundle
- **Lazy Loading** - Load only when needed
- **Flexibility** - Easy to extend with new types

## Data Flow

### Application Initialization

```
1. main.ts
   ↓
2. App.vue mounted
   ↓
3. Router initialized
   ↓
4. Router guard checks auth
   ↓
5. backend.init() called
   ↓
6. POST /api/init
   ↓
7. Store data populated:
   - auth.user
   - languages.languages
   - modules.modulesDefs
   - preferences.user
   ↓
8. Route to destination
```

### Typical Page Load

```
1. User clicks link
   ↓
2. Router navigation
   ↓
3. Route guard checks:
   - Authentication
   - ACL permissions
   - Legacy view redirect
   ↓
4. Component mounted
   ↓
5. Component fetches data:
   - Store actions
   - API calls
   ↓
6. Data rendered
   ↓
7. User interacts
   ↓
8. State updated (reactive)
   ↓
9. UI re-renders automatically
```

### State Update Flow

```
Component Action
   ↓
Store Action Called
   ↓
API Request (optional)
   ↓
Store State Updated
   ↓
Component Re-renders (automatic)
```

## Module System

MintHCM uses a **module-based architecture** where each business entity (Users, Candidates, etc.) is a module.

### Module Definition

Modules are defined in backend and synced to frontend:

```typescript
interface ModuleDef {
    name: string
    label: string
    icon: string
    acl: {
        access: number
        view: number
        list: number
        edit: number
        delete: number
    }
    fields: { [fieldName: string]: FieldDef }
}
```

### Generic Views

Views work with any module:

- **ListView** - `GET /api/{module}`
- **DetailView** - `GET /api/{module}/{id}`
- **EditView** - `POST /api/{module}` or `PUT /api/{module}/{id}`

```typescript
// Works for Users, Candidates, Meetings, etc.
<ListView :module="'Users'" />
<DetailView :module="'Candidates'" :id="'123'" />
```

## Performance Optimizations

### Code Splitting

```typescript
// Lazy load route components
const ListView = () => import('@/views/ListView/ListView.vue')

// Lazy load heavy libraries
const TinyMCE = defineAsyncComponent(() => 
    import('@/components/MintWysiwyg.vue')
)
```

### Caching Strategy

```typescript
// Cache init response in browser Cache API
if (typeof caches !== "undefined") {
    caches.open('mint-rebuild').then(cache => {
        cache.put('init', new Response(JSON.stringify(initData)))
    })
}

// On next init, check cache first
await caches.match('init').then(response => {
    if (response) {
        cachedConfig.value = await response.json()
    }
})
```

### Virtual Scrolling

Large lists use virtual scrolling for performance:

```vue
<v-data-table
    :items="items"
    :items-per-page="50"
    fixed-header
    height="600"
/>
```

### Debounced Search

User input is debounced to reduce API calls:

```typescript
import { watchDebounced } from '@vueuse/core'

watchDebounced(
    searchQuery,
    async (newVal) => {
        await search(newVal)
    },
    { debounce: 300 }
)
```

## Security Considerations

### Authentication

All authenticated routes require valid session:

```typescript
router.beforeEach(async (to, from) => {
    if (to.meta?.auth !== false && !auth.user?.id) {
        return { name: 'auth-login' }
    }
})
```

### ACL (Access Control List)

Component-level permission checks:

```typescript
import { useACL } from '@/composables/useACL'

const acl = useACL()

if (!acl.hasAccess('Users', 'edit')) {
    // Hide edit button
}
```

### XSS Protection

- All user input is escaped
- Vue automatically escapes `{{ }}` interpolations
- Use `v-html` only with sanitized content

### CSRF Protection

API requests include CSRF tokens from backend.

## Build Process

### Development Build

```
Source Files (TypeScript, Vue, SCSS)
   ↓
Vite Dev Server
   ↓
- No bundling
- Native ESM modules
- Fast HMR
   ↓
Browser (instant updates)
```

### Production Build

```
Source Files
   ↓
Vite Build (Rollup)
   ↓
- TypeScript → JavaScript
- Vue SFC → Render functions
- SCSS → CSS
- Tree shaking
- Minification
- Code splitting
- Asset optimization
   ↓
dist/
├── assets/
│   ├── index.[hash].js
│   └── index.[hash].css
└── index.html
```

## Next Steps

- Learn about [Directory Structure](./03-directory-structure.md)
- Understand [State Management](./06-state-management.md)
- Explore [Component System](./08-components.md)

---

**Understanding the architecture is key to effective development!** 🏗️

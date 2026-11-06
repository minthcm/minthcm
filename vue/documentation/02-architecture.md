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

**Configuration:** `vite.config.mts` (Vite 6)

```typescript
export default defineConfig({
    base: './',
    plugins: [
        vue(),
        vuetify({ autoImport: true }),
    ],
    server: {
        proxy: {
            '/api': { target: process.env.PROXY_URL ?? '', changeOrigin: true },
            '/legacy': { target: process.env.PROXY_URL ?? '', changeOrigin: true },
        },
    },
})
```

### UI Framework: Vuetify 4

**Vuetify** (version 4, pinned to `4.1.2` in `package.json`) provides Material Design components out of the box. Components are auto-imported by `vite-plugin-vuetify`.

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

**Customization:** `src/plugins/vuetify.ts` (themes and defaults; see [User Theme System](./14-user-theme.md))

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

Other stores in `src/store/`: `alerts`, `favorites`, `legacyIframe`, `localStorage`, `mcp`, `popups`, `recents`, `returnLocation`, `statusBoxes`, `theme`, `url`, `ux`.

### Routing: Vue Router 4

**Vue Router** handles client-side navigation.

**Features:**
- **Dynamic Routes** - `/modules/:module` (list), `/modules/:module/DetailView/:id` (record), `/modules/:module/:action/:record?` (legacy fallback)
- **Navigation Guards** - Installation check, authentication, ACL, legacy-view redirect
- **Nested Routes** - Layouts and sub-views
- **Hash Mode** - `createWebHashHistory(window.location.pathname)` for compatibility

```typescript
const coreRoutes: Array<RouteRecordRaw> = [
    {
        path: '/modules/:module',
        name: 'list',
        component: ListView,
        alias: ['/modules/:module/ESListView', '/modules/:module/ListView', '/modules/:module/index'],
        meta: { auth: true },
    },
    {
        path: '/modules/:module/DetailView/:id',
        alias: '/modules/:module/EditView/:id?',
        name: 'record',
        component: RecordView,
        meta: { auth: true },
    },
]
```

**Configuration:** `src/router/routes.ts` (definitions, merged with `src/custom/router/index.ts`) and `src/router/index.ts` (guards)

### HTTP Client: Axios

**Axios** manages all API communication with the backend.

**Features:**
- **Interceptors** - Global response handling (`api/interceptors/`)
- **Error Handling** - Centralized error handler registry (`api/error-manager/`); pass `{ rawError: true }` to receive the raw error instead
- **Type Safety** - Generic response types (`mintApi.get<T>()`)
- **Relative base URL** - `baseURL: 'api/'`, so requests follow the instance path

There is no automatic retry of failed requests.

```typescript
import { mintApi } from '@/api/api'

// Type-safe API call (GET api/languages?modules=Employees)
const response = await mintApi.get<{ [key: string]: string }>('languages', { params: { modules: 'Employees' } })
```

**Configuration:** `src/api/api.ts`

## Architecture Patterns

### Component-Based Architecture

The app is built from reusable, composable components:

```
App
└── <component :is="ux.layout">   (DefaultLayout, or GuestLayout / route meta.layout)
    ├── DefaultLayoutTopbar
    ├── DefaultLayoutSidebar
    ├── DefaultLayoutDrawer
    └── <slot> → v-main → RouterView (passed in by App.vue)
        └── ListView
            ├── ListViewHeader
            ├── ListViewFilters
            └── ListViewTable
                └── Field (dynamic)
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
// Simplified illustration of the pattern (not the real useBean)
export function useThing(module: string, id: string) {
    const data = ref(null)
    const loading = ref(false)

    async function fetch() {
        loading.value = true
        data.value = (await mintApi.post(`${module}/Get/${id}`)).data
        loading.value = false
    }

    return { data, loading, fetch }
}

// Usage in component
const { data, loading, fetch } = useThing('Employees', '123')
```

The real `useBean(module, id, fetch_links = [])` is described in [Working with Beans](./10-working-with-beans.md).

**Composables in `src/composables/`:**
- `useACL` - Access control checks
- `useBean` - Bean data management
- `useLink` - Related records
- `useLogic` - Business logic execution
- `useMintDate` - Date/time values in user and server format
- `useDraggable`, `useMove`, `useDataPosition` - Drag and positioning helpers
- `useEagerFetch`, `useWorkscheduleColorStyle` - Feature-specific helpers

`useField` (per-field model, dirty flag, validation and formatting) lives next to the field components in `src/components/Fields/useField.ts`; see [Field System](./09-fields.md).

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
// Field.vue (simplified): the type is resolved against the glob-built
// fieldConfig.allowedTypes / typeMap and falls back to 'varchar'
let FieldComponent = defineAsyncComponent(() => {
    return import(`@/components/Fields/${resolvedFieldType.value}/${resolvedFieldType.value}.${props.view}.vue`)
})
```

**Benefits:**
- **Code Splitting** - Smaller initial bundle
- **Lazy Loading** - Load only when needed
- **Flexibility** - Easy to extend with new types

## Data Flow

### Application Initialization

```
1. main.ts (installs router, Vuetify, Pinia; mounts App.vue)
   ↓
2. First navigation → router.beforeEach
   ↓
3. Not installed? → redirect to 'install'
   ↓
4. backend.init() called (skipped for the MCP login page)
   ↓
5. POST api/init (cached response reused via the Cache API)
   ↓
6. Store data populated:
   - auth.user
   - languages.languages
   - modules.modulesDefs
   - preferences.user
   ↓
7. Guard checks auth, admin-only routes, setup wizard, ACL
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

### Returning From a Record

Records are often opened from a legacy view that holds view state the shell cannot see - the
calendar's view type and date range being the motivating case. Two mechanisms bring the user back
to exactly that view, and they solve different halves of the problem.

**1. View state in the URL (`legacyQueryToHash`)**

A route can declare which query parameters of its legacy page belong in the app's address:

```ts
{
    path: '/Calendar',
    component: LegacyView,
    meta: {
        legacyUrl: 'legacy/index.php?module=Calendar',
        legacyQueryToHash: ['view', 'action', 'year', 'month', 'day'],
    },
}
```

The bridge is bidirectional and lives in `LegacyView.vue`:

- `legacyUrl` (computed) injects the address hash into the iframe `src`, so a refresh or a pasted
  link reopens the legacy page in the same state;
- `onIframeLoad` lifts those parameters back out of the iframe URL into the address via
  `history.pushState`, so each in-iframe navigation becomes a browser history entry.

> `onIframeLoad` deliberately writes history directly instead of calling `router.push`. `legacyUrl`
> is reactive to the route, so routing there would reload the iframe, which would fire
> `onIframeLoad` again - an endless reload loop.

**2. Return location (`store/returnLocation.ts`)**

The URL cannot carry everything. The dashboard is a single legacy page whose dashlets have no
address of their own, and `router.back()` is unavailable after a refresh on the record or an entry
from a link. So the place being left is captured explicitly:

```
legacy view --emit('CaptureReturnLocation', viewState)--> reportViewState()
legacy view --postMessage(record URL)--> LegacyView --capture({path, hash, recordPath})--> store
record: back arrow / cancel of a new record --> consume() --> router.push(origin)
```

The store is backed by `sessionStorage`: the location must survive a refresh on the record but must
not outlive the tab, otherwise a remembered view would leak into a later visit. A `router.afterEach`
guard drops the location as soon as the user navigates outside the captured journey, so entering a
module from the menu is never affected by it.

Legacy views that have extra state to preserve report it through the `CaptureReturnLocation` legacy
event; the payload is opaque to the shell and handed straight back to the legacy side on return
(the calendar dashlet is restored this way, via a `mint-restore-calendar-dashlet` message that
refreshes that one dashlet and leaves the rest of the dashboard alone).

## Module System

MintHCM uses a **module-based architecture** where each business entity (Employees, Candidates, etc.) is a module.

### Module Definition

Modules are defined in backend, delivered by `init` and stored in `useModulesStore().modulesDefs` (`src/store/modules.ts`):

```typescript
export interface ModulesDefs {
    [name: string]: {
        name: string
        icon: string
        actions: ModuleAction[]
        vardefs: unknown          // field definitions (FieldVardef per field)
        metadata: ModuleMetadata  // e.g. RecordView panels, Subpanels
        acl: { [view: string]: number }
    }
}
```

The computed `modules` getter adds a translated `label` and normalizes `icon` to an `mdi-` name.

### Generic Views

Views read the module from the route and work with any module:

- **ListView** (`#/modules/{module}`) - `GET api/{module}` (list init) and `POST api/{module}` (list data), see `src/api/modules.api.ts`
- **RecordView** (`#/modules/{module}/DetailView/{id}`, `#/modules/{module}/EditView`) - `POST api/{module}/Get/{id}` to load, `PATCH api/{module}/Update/{id}` to save, `DELETE api/{module}/{id}` to delete (all via `useBean`)

Modules whose `backend.initData.legacy_views[module]` sets `list` / `record` are redirected by the router guard to `LegacyView`. The `views/DetailView` and `views/EditView` directories contain only unused stubs.

## Performance Optimizations

### Code Splitting

Most core routes import their view eagerly (`ListView`, `RecordView`, `LegacyView`). Less frequently used views are lazy-loaded:

```typescript
// src/router/routes.ts
{
    path: '/Administration/:viewName',
    name: 'admin-view',
    component: () => import('@/views/AdminView/AdminView.vue'),
    meta: { auth: true, adminOnly: true },
},
```

Field components are always lazy-loaded by `Field.vue` (see above).

### Caching Strategy

The `init` response is cached in the browser Cache API (`src/store/backend.ts`) and its `mintRebuildID` / language are sent with the next `init` call:

```typescript
// Read the cached init response
await caches.match('init')
    .then((response) => response?.json())
    .then((cached) => {
        if (cached) {
            cachedConfig.value = cached
        }
    })

// After init, store the fresh response
caches.open('mint-rebuild').then((cache) => {
    cache.put('init', new Response(JSON.stringify(initData.value)))
})
```

### Language Batching & In-Flight Request Dedup

`useLanguagesStore().fetchModulesLanguage(modules)` fetches translations for several modules in a single `GET languages?modules=X,Y,Z` request instead of one request per module, and skips any module already present in `languages.value.modules` (the in-memory cache — not persisted across page reloads). Callers needing a single module (`fetchModuleLanguage(module)`) delegate to the batched call with a one-item array.

Concurrent calls for the same module share one in-flight promise via `pendingRequests`, so a second caller awaits the request already in progress instead of firing a duplicate:

```typescript
// RecordView's subpanels panel reserves the batch during setup, before its own template
// renders — label() calls during render for any uncached module see the pending entry
// and await it instead of firing their own per-module request.
if (shouldFetchSubpanelData()) {
    store.fetchLanguagesForSubpanels() // -> languages.fetchModulesLanguage([...])
}
```

A failed batch request leaves its modules out of the cache (so a later call retries them) rather than rejecting every pending caller in that batch.

### Pagination

Lists are paginated server-side (`modulesApi.getListData()` receives offset and limit); there is no virtual scrolling.

### Debounced Search and Logic

User input is debounced to reduce API calls, for example:

- the relate field search waits 500 ms after typing (`DEBOUNCE_TIME` in `Fields/relate/relate.edit.vue`) and starts at 3 characters;
- `useBean` re-evaluates logic for changed trigger fields through `useDebounceFn` / `useThrottleFn` from `@vueuse/core`.

## Security Considerations

### Authentication

All authenticated routes require valid session:

```typescript
// src/router/index.ts (excerpt)
router.beforeEach(async (to, from) => {
    // ...install check and backend.init()
    if (to.meta?.auth !== false && !auth.user?.id) {
        sessionStorage.setItem('auth_redirect', to.fullPath)
        return { name: 'auth-login', query: { redirect: to.path } }
    }
    if (to.meta?.adminOnly && !auth.user?.is_admin) {
        return { name: 'dashboard' }
    }
    // ...setup wizard, ACL check, legacy-view redirect
})
```

### ACL (Access Control List)

Component-level permission checks:

```typescript
import { useACL } from '@/composables/useACL'

const acl = useACL()

// hasAccess(module, view, is_owner = false, in_group = false)
if (!acl.hasAccess('Employees', 'edit')) {
    // Hide edit button
}
```

`view` is one of `delete`, `edit`, `export`, `import`, `list`, `massupdate`, `view`. UI checks are cosmetic; the API enforces ACL on its own.

### XSS Protection

- Vue automatically escapes `{{ }}` interpolations
- Use `v-html` only with sanitized content (DOMPurify is a dependency; the `richtext` detail view sanitizes with it)

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
vue/dist/
├── assets/
│   ├── index-[hash].js
│   ├── index-[hash].css
│   └── <Chunk>-[hash].js
└── index.html
```

## Next Steps

- Learn about [Directory Structure](./03-directory-structure.md)
- Understand [Working with Beans](./10-working-with-beans.md)
- Explore the [Field System](./09-fields.md)

---

**Understanding the architecture is key to effective development!** 🏗️

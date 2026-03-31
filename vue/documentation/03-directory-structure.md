# Directory Structure

Understanding the folder organization will help you navigate the codebase and know where to place your code.

## Root Structure

```
vue/
├── src/                    # Source code
├── public/                 # Static assets
├── documentation/          # This documentation
├── dist/                   # Production build output (generated)
├── node_modules/           # Dependencies (generated)
├── .env                    # Environment variables
├── .env.example            # Environment template
├── index.html              # HTML entry point
├── package.json            # Dependencies and scripts
├── tsconfig.json           # TypeScript configuration
├── vite.config.ts          # Vite configuration
├── .eslintrc.js            # ESLint rules
└── .gitignore              # Git ignore patterns
```

## `src/` Directory

The heart of the application. All source code lives here.

```
src/
├── main.ts                 # Application entry point
├── App.vue                 # Root component
├── main.scss               # Global styles
├── shims-vue.d.ts          # TypeScript declarations
│
├── api/                    # Backend communication
├── assets/                 # Images, icons, static files
├── business/               # Business logic layer
├── bundler/                # Dynamic loading utilities
├── components/             # Reusable UI components
├── composables/            # Composition API logic
├── custom/                 # Your customizations
├── drawers/                # Drawer/sidebar definitions
├── layouts/                # Page layout templates
├── plugins/                # Vue plugins
├── router/                 # Route configuration
├── store/                  # Pinia state management
├── utils/                  # Helper functions
└── views/                  # Page components (routes)
```

### Detailed Breakdown

#### `api/` - Backend Communication

Handles all HTTP communication with the MintHCM backend.

```
api/
├── api.ts                  # Main Axios instance, base config
├── interfaces.ts           # TypeScript interfaces for API responses
├── modules.api.ts          # Module-specific API calls
├── users.api.ts            # User-related API calls
├── files.api.ts            # File upload/download
├── candidates.api.ts       # Candidates module API
├── subpanels.api.ts        # Subpanel data fetching
├── error-manager/          # Error handling system
│   ├── error-handlers.ts   # Specific error handlers
│   └── error-registry.ts   # Error handler registry
└── interceptors/           # Axios interceptors
    ├── response-handler.ts         # Success response processing
    └── response-error-handler.ts   # Error response processing
```

**Key Files:**

- **`api.ts`** - Main Axios instance with base configuration
  ```typescript
  export const mintApi = axios.create({
      baseURL: '/api',
      headers: { 'Content-Type': 'application/json' }
  })
  ```

- **`interfaces.ts`** - Type definitions for API responses
  ```typescript
  export interface ApiResponse<T> {
      data: T
      success: boolean
      error?: string
  }
  ```

#### `components/` - Reusable UI Components

Organized by feature/domain:

```
components/
├── LoadingScreen.vue       # Full-screen loader
├── MintSearch.vue          # Global search component
├── MintOverlay.vue         # Modal overlay
│
├── Fields/                 # Dynamic field system ⭐
│   ├── Field.vue           # Main field router component
│   ├── Field.config.ts     # Field type configuration
│   ├── Field.model.ts      # Field interfaces
│   ├── useField.ts         # Field composable
│   ├── Pencil.vue          # Edit mode toggle
│   │
│   ├── string/             # String field type
│   │   ├── string.detail.vue
│   │   ├── string.edit.vue
│   │   └── string.list.vue
│   │
│   ├── enum/               # Dropdown/select field
│   ├── date/               # Date picker field
│   ├── email/              # Email field with validation
│   ├── bool/               # Checkbox/toggle field
│   └── ...                 # ~30 field types
│
├── MintButtons/            # Button components
│   ├── MintButton.vue
│   └── MintIconButton.vue
│
├── MintDataTable/          # Enhanced data tables
│   ├── MintDataTable.vue
│   └── MintDataTableHeader.vue
│
├── MintPanel/              # Panel/card components
│   ├── MintPanel.vue
│   ├── MintPanelHeader.vue
│   └── MintPanelContent.vue
│
├── MintPopups/             # Modal/dialog system
│   └── MintPopup.vue
│
├── MintChat/               # Chat/messaging components
├── MintComments/           # Comment system
├── MintKudos/              # Kudos/recognition
└── MintWall/               # Activity wall/feed
```

**Field System Architecture:**

The Field component dynamically loads the correct field type based on:
1. **Field type** (string, enum, date, etc.)
2. **View mode** (detail, edit, list)

```vue
<!-- Automatically loads bool.edit.vue for boolean field in edit mode -->
<Field 
    :defs="{ type: 'bool', name: 'is_active' }"
    :view="'edit'"
    v-model="formData.is_active"
/>
```

#### `views/` - Page Components

Each route corresponds to a view component:

```
views/
├── AuthView/               # Login, password reset
│   ├── AuthView.vue
│   ├── LoginForm.vue
│   └── ResetPassword.vue
│
├── DashboardView/          # User dashboard
│   └── DashboardView.vue
│
├── ListView/               # Module list view ⭐
│   ├── ListView.vue
│   ├── ListViewStore.ts    # List state management
│   ├── ListViewHeader.vue  # Action buttons, search
│   ├── ListViewTable.vue   # Data table
│   └── ListViewFilters.vue # Filter sidebar
│
├── DetailView/             # Record detail view ⭐
│   ├── DetailView.vue
│   ├── DetailViewStore.ts
│   └── DetailViewTabs.vue
│
├── EditView/               # Record create/edit ⭐
│   ├── EditView.vue
│   ├── EditViewStore.ts
│   └── EditViewForm.vue
│
├── RecordView/             # Unified record view wrapper
│   ├── RecordView.vue
│   └── RecordViewStore.ts
│
├── InstallView/            # Installation wizard
│   └── InstallView.vue
│
├── SetupWizard/            # First-time user setup
│   └── SetupWizard.vue
│
└── LegacyView/             # Legacy PHP view wrapper
    └── LegacyView.vue
```

**Generic Views:**

The ListView, DetailView, and EditView work for **any module**:

```vue
<!-- Same component, different modules -->
<ListView :module="'Users'" />
<ListView :module="'Candidates'" />
<ListView :module="'Meetings'" />
```

#### `store/` - State Management

Pinia stores for global state:

```
store/
├── index.ts                # Store initialization
│
├── auth.ts                 # Authentication state ⭐
├── backend.ts              # App initialization, config ⭐
├── modules.ts              # Module definitions ⭐
├── languages.ts            # Translations ⭐
├── preferences.ts          # User preferences
│
├── alerts.ts               # Notification system
├── popups.ts               # Modal/popup management
├── favorites.ts            # Favorite records
├── recents.ts              # Recently viewed records
├── statusBoxes.ts          # Status messages
├── url.ts                  # URL parsing utilities
└── ux.ts                   # UI state (sidebars, etc.)
```

**Core Stores:**

- **`auth.ts`** - User authentication, session
- **`backend.ts`** - Application initialization, global config
- **`modules.ts`** - Module metadata, field definitions
- **`languages.ts`** - Translations, labels
- **`preferences.ts`** - User and global preferences

#### `router/` - Routing

```
router/
├── index.ts                # Router configuration, guards
└── routes.ts               # Route definitions
```

**Route Structure:**

```typescript
const routes = [
    {
        path: '/modules/:module/list',
        name: 'list',
        component: ListView,
        meta: { auth: true }
    },
    {
        path: '/modules/:module/:view/:id?',
        name: 'record',
        component: RecordView,
        meta: { auth: true }
    },
]
```

#### `composables/` - Reusable Logic

Composition API functions for shared logic:

```
composables/
├── useACL.ts               # Access control checks
├── useBean.ts              # Bean data management
├── useField.ts             # Field rendering logic
├── useLink.ts              # Related records (relationships)
├── useLogic.ts             # Business logic execution
└── useDraggable.ts         # Drag & drop functionality
```

**Usage Example:**

```typescript
import { useACL } from '@/composables/useACL'

const acl = useACL()

if (acl.hasAccess('Users', 'edit')) {
    // Show edit button
}
```

#### `business/` - Business Logic

Action system for record operations:

```
business/
├── BeanActions/            # Single record actions
│   ├── BeanAction.ts       # Base action class
│   ├── index.ts            # Action registry
│   └── Actions/            # Individual actions
│       ├── EditAction.ts
│       ├── DeleteAction.ts
│       ├── DuplicateAction.ts
│       └── ...
│
├── MassActions/            # Bulk operations
│   ├── MassAction.ts       # Base mass action class
│   ├── index.ts            # Mass action registry
│   └── Actions/
│       ├── MassDeleteAction.ts
│       ├── MassExportAction.ts
│       └── ...
│
└── SubpanelActions/        # Subpanel operations
    ├── SubpanelAction.ts   # Base action class
    ├── index.ts            # Action registry (Actions + InlineActions)
    ├── Actions/            # Header-level buttons (per subpanel)
    │   ├── Create.ts
    │   ├── CreateCosts.ts
    │   ├── Select.ts
    │   └── SelectWorkScheduleForDelegation.ts
    └── InlineActions/      # Per-row action buttons
        ├── Edit.ts         # Redirect to EditView of related record
        ├── Delete.ts       # Delete related record (with confirmation)
        └── Remove.ts       # Unlink related record (with confirmation)
```

**Action Pattern:**

```typescript
export class DeleteAction extends BeanAction {
    async execute(module: string, id: string) {
        await mintApi.delete(`${module}/${id}`)
        // Refresh list, show notification, etc.
    }
}

// Action with options (e.g., Duplicate with skipFields)
export class Duplicate extends BeanAction {
    public async execute() {
        router.push({
            path: `/modules/${this.bean.module}/EditView`,
            query: {
                copy_id: this.bean.id,
                excludedFields: this.options.skipFields ?? {}
            },
        })
        return true
    }
}
```

**Configuring Actions in Backend:**

Actions are defined in module metadata (`recordviewdefs.php`):

```php
'actions' => [
    'Audit',          // Simple action
    'Delete',
    [
        'name' => 'Duplicate',
        'skipFields' => ['date_start', 'date_end'],  // Options
    ],
]
```

See [Working with Beans](./10-working-with-beans.md#bean-actions) for more details.

#### `custom/` - Your Customizations ⭐

**Most Important:** This is where you add custom code that won't be overwritten by updates.

```
custom/
├── drawers/                # Custom drawer definitions
├── router/                 # Custom routes
├── store/                  # Custom stores
└── views/                  # Custom page components
```

**Why `custom/`?**

- ✅ **Safe from updates** - Core updates won't overwrite your code
- ✅ **Organized** - Clear separation of core vs custom
- ✅ **Mergeable** - Easy to version control separately

See [Customization Guide](./11-customization.md) for details.

#### `layouts/` - Page Templates

Layout components that wrap page content:

```
layouts/
├── DefaultLayout/          # Main app layout
│   ├── DefaultLayout.vue
│   ├── DefaultLayoutTopbar.vue
│   ├── DefaultLayoutSidebar.vue
│   └── DefaultLayoutModulesPopup.vue
│
└── GuestLayout/            # Unauthenticated layout
    └── GuestLayout.vue
```

**Layout Structure:**

```vue
<template>
    <DefaultLayout>
        <template #topbar>
            <DefaultLayoutTopbar />
        </template>
        <template #sidebar>
            <DefaultLayoutSidebar />
        </template>
        <template #content>
            <router-view />
        </template>
    </DefaultLayout>
</template>
```

#### `plugins/` - Vue Plugins

```
plugins/
├── vuetify.ts              # Vuetify configuration
├── webfontloader.ts        # Font loading
└── reset-store.ts          # Store reset plugin
```

#### `utils/` - Helper Functions

```
utils/
├── dates.ts                # Date formatting, parsing
├── numbers.ts              # Number formatting
├── qsOperators.ts          # Query string operators
└── qsOperatorsTypes.ts     # Query operator types
```

#### `assets/` - Static Assets

```
assets/
├── images/                 # Images, logos
├── icons/                  # Custom icons
└── styles/                 # Global SCSS files
```

## `public/` Directory

Static files served as-is (not processed by Vite):

```
public/
├── favicon.ico             # Browser favicon
├── robots.txt              # SEO crawler instructions
└── ...                     # Other static assets
```

**Note:** Files in `public/` are copied to `dist/` root during build.

## File Naming Conventions

### Components

- **PascalCase** for component files: `MintDataTable.vue`
- **kebab-case** for field types: `string.detail.vue`

### TypeScript Files

- **camelCase** for utilities: `useField.ts`
- **PascalCase** for classes: `BeanAction.ts`
- **kebab-case** for stores: `backend.ts`

### Styles

- **kebab-case**: `main.scss`
- **Scoped styles** in components: `<style scoped>`

## Import Aliases

TypeScript path alias `@` points to `src/`:

```typescript
// Instead of:
import { useBackendStore } from '../../../store/backend'

// Use:
import { useBackendStore } from '@/store/backend'
```

**Configuration:** `tsconfig.json` and `vite.config.ts`

```typescript
// vite.config.ts
resolve: {
    alias: {
        '@': path.resolve(__dirname, 'src'),
    },
}
```

## Build Output (`dist/`)

Production build creates optimized files:

```
dist/
├── index.html              # Entry HTML
├── assets/                 # Bundled assets
│   ├── index.a1b2c3.js     # Main JS (hashed)
│   ├── index.d4e5f6.css    # Main CSS (hashed)
│   ├── chunk.g7h8i9.js     # Code-split chunks
│   └── ...
└── favicon.ico             # From public/
```

**Hash Naming:** Files have content hashes (e.g., `a1b2c3`) for cache busting.

## Navigation Tips

### Finding Components

1. **By feature**: Look in `components/{FeatureName}/`
2. **By field type**: Look in `components/Fields/{type}/`
3. **By view**: Look in `views/{ViewName}/`

### Finding State

1. **User auth**: `store/auth.ts`
2. **App config**: `store/backend.ts`
3. **Module data**: `store/modules.ts`
4. **Translations**: `store/languages.ts`

### Finding Routes

1. **Route definitions**: `router/routes.ts`
2. **Route guards**: `router/index.ts`
3. **Custom routes**: `custom/router/`

### Finding API Calls

1. **Module APIs**: `api/{module}.api.ts`
2. **Base config**: `api/api.ts`
3. **Error handling**: `api/error-manager/`

## Best Practices

### Where to Put New Code

| Type | Location | Example |
|------|----------|---------|
| **Reusable component** | `components/` | `components/MyWidget.vue` |
| **Page component** | `views/` | `views/MyPage/MyPage.vue` |
| **API call** | `api/` | `api/mymodule.api.ts` |
| **State store** | `store/` | `store/myfeature.ts` |
| **Composable** | `composables/` | `composables/useMyFeature.ts` |
| **Utility** | `utils/` | `utils/myHelper.ts` |
| **Custom code** | `custom/` | `custom/views/MyCustomView.vue` |

### Don't Put Code In

- ❌ Root `src/` directory (use subdirectories)
- ❌ `node_modules/` (managed by npm)
- ❌ `dist/` (auto-generated)
- ❌ Core files if customizing (use `custom/` instead)

## Next Steps

- Learn about [Building & Deployment](./04-building-deployment.md)
- Explore [Component System](./08-components.md)
- Understand [Customization](./11-customization.md)

---

**Know your structure, code with confidence!** 📁

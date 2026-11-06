# Directory Structure

Understanding the folder organization will help you navigate the codebase and know where to place your code.

## Root Structure

```
vue/
├── src/                    # Source code
├── public/                 # Static assets
├── documentation/          # This documentation
├── dist/                   # Build output (committed, served in place by the root .htaccess)
├── node_modules/           # Dependencies (generated)
├── .env                    # Environment variables (git-ignored, created from .env.example)
├── .env.example            # Environment template
├── index.html              # HTML entry point
├── package.json            # Dependencies, scripts, Node engines
├── tsconfig.json           # TypeScript configuration
├── vite.config.mts         # Vite configuration
├── babel.config.js         # Babel config (Vue CLI tooling)
├── .browserslistrc         # Target browsers
├── .eslintrc.js            # ESLint rules (incl. Prettier options)
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
├── bundler/                # Drawer bundle (core + custom drawers)
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
├── api.ts                  # Main Axios instance (mintApi), base config
├── interfaces.ts           # Error-handling types (HttpErrorData, ErrorHandler, ...)
├── modules.api.ts          # List view, list preferences, checklist
├── users.api.ts            # User-related API calls
├── files.api.ts            # File upload/download
├── candidatures.api.ts     # Candidatures module API
├── positions.api.ts        # Positions module API
├── subpanels.api.ts        # Subpanel data fetching
├── cyclicRecords.api.ts    # Cyclic (repeated) records
├── favorites.api.ts        # Favorites
├── unifiedSearch.api.ts    # Unified (global) search
├── adminApi.ts             # Admin views
├── adminElasticsearchApi.ts # Admin: Elasticsearch tools
├── adminLanguagesApi.ts    # Admin: languages
├── error-manager/          # Error handling system
│   ├── error-handlers.ts   # Specific error handlers
│   └── error-registry.ts   # Error handler registry
└── interceptors/           # Axios interceptors
    ├── response-handler.ts         # Success response processing
    └── response-error-handler.ts   # Error response processing
```

**Key Files:**

- **`api.ts`** - Main Axios instance with base configuration and response interceptors
  ```typescript
  function createMintApi() {
      const instance = axios.create({
          baseURL: 'api/', // relative: follows the instance path
          headers: { 'Content-Type': 'application/json' },
      })
      instance.interceptors.response.use(responseHandler, async (error) => {
          if (error.response?.status === 409) {
              return Promise.reject(error) // duplicates: handled by the caller
          }
          return await responseErrorHandler(error)
      })
      return instance
  }

  export const mintApi: AxiosInstance = createMintApi()
  ```
  Request config extensions: `rawError` (skip the error registry and rethrow) and `skipRefreshToken`.

- **`interfaces.ts`** - Types for the error-handling system (`HttpErrorData`, `HttpErrorType`, `ErrorHandler`, `ErrorHandlerObject`, `ErrorHandlerMany`)

#### `components/` - Reusable UI Components

Organized by feature/domain:

```
components/
├── LoadingScreen.vue       # Full-screen loader
├── MintSearch.vue          # Global search component
├── MintOverlay.vue         # Modal overlay
│
├── MintWysiwyg.vue         # TinyMCE editor wrapper
├── MintMenuList.vue        # Menu list (actions menus)
│
├── Fields/                 # Dynamic field system ⭐
│   ├── Field.vue           # Main field router component
│   ├── Field.config.ts     # Glob-built type registry + typeMap
│   ├── Field.model.ts      # FieldProps, FieldInterface
│   ├── useField.ts         # Per-field model/validation/formatting
│   ├── Pencil.vue          # Edit mode toggle
│   │
│   ├── varchar/            # Default text field type
│   │   ├── varchar.detail.vue
│   │   ├── varchar.edit.vue
│   │   └── varchar.list.vue
│   │
│   ├── enum/               # Dropdown/select field (+ enum.ts formatter)
│   ├── date/               # Date picker field (+ date.ts)
│   ├── email/              # Email field
│   ├── bool/               # Checkbox/toggle field
│   └── ...                 # 26 type folders in total, see 09-fields.md
│
├── MintButtons/            # MintButton.vue
├── MintDataTable/          # MintDataTable.vue (used by subpanels)
├── MintPanel/              # MintPanel.vue + RecordView panels:
│   ├── MintPanelRecordDetails/   # Record form + actions menu
│   ├── MintPanelRecordPanel/
│   ├── MintPanelSubpanels/
│   ├── MintPanelFiles/, MintPanelComments/, MintPanelChecklist/, MintPanelScheduler/, ...
│   └── MintPanelAdminConfig/, MintPanelLanguages/, MintPanelElasticsearchTools/  # admin views
├── MintPopups/             # MintPopups.vue host + MintPopupConfirm, MintPopupPrompt, MintPopupRelate, ...
├── MintSidepanel/          # Record-view side panel and widgets
├── ListViewPanels/         # Panels under the list view (e.g. Schedulers.vue)
├── MintStatusBoxes/        # Status messages
├── MintDropzone/, MintScheduler/, MintDuplicate/, ...
├── MintChat/               # Chat/messaging components
├── MintComments/           # Comment system
├── MintKudos/              # Kudos/recognition
└── MintWall/               # Activity wall/feed
```

**Field System Architecture:**

The Field component dynamically loads the correct field type based on:
1. **Field type** (`defs.type`, mapped through `typeMap`, falling back to `varchar`)
2. **View mode** (detail, edit, list)

```vue
<!-- Loads bool/bool.edit.vue; `field` is the MintField from bean.fields -->
<Field
    :defs="bean.fieldDefs.value.is_active"
    :view="'edit'"
    :label="languages.label('LBL_IS_ACTIVE', 'Employees')"
    :field="bean.fields.value.is_active"
    @update:modelValue="(v) => bean.updateFields({ is_active: v })"
/>
```

See [Field System](./09-fields.md) for details.

#### `views/` - Page Components

Each route corresponds to a view component:

```
views/
├── AuthView/               # Login, forgotten/reset password
│   ├── AuthView.vue
│   ├── AuthViewLogin.vue
│   ├── AuthViewForget.vue
│   ├── AuthViewReset.vue
│   └── AuthViewStore.ts
│
├── ListView/               # Module list view ⭐
│   ├── ListView.vue
│   ├── ListViewStore.ts    # List state management
│   ├── ListViewHeader.vue  # Action buttons, search
│   ├── ListViewTable.vue   # Data table (renders Field directly)
│   ├── ListViewFilters.vue # Filters
│   ├── ListViewMassUpdate.vue, ListViewColumnsPopup.vue, ...
│   └── fields/, inputs/, operators/   # Filter inputs and operators
│
├── RecordView/             # Record detail/edit view ⭐ (route 'record')
│   ├── RecordView.vue
│   └── RecordViewStore.ts
│
├── LegacyView/             # Legacy PHP view in an iframe
│   ├── LegacyView.vue
│   └── LegacyEventManager/ # Events posted by legacy pages (e.g. CaptureReturnLocation)
│
├── AdminView/              # Metadata-driven admin pages (see 15-admin-views.md)
├── UnifiedSearchView/      # Global search results
├── McpLoginView/           # MCP OAuth consent login
├── InstallView/            # Installation wizard (InstallView.vue, steps/)
├── SetupWizard/            # First-login user setup (SetupWizard.vue, SetupWizardSteps/)
│
├── DashboardView/          # Unused stub (dashboard route renders LegacyView)
├── DetailView/             # Unused stub
└── EditView/               # Unused stub
```

**Generic Views:**

`ListView` and `RecordView` work for **any module**. They take the module from the route, not from props:

```
#/modules/Employees                    → ListView
#/modules/Candidates/DetailView/<id>   → RecordView (detail)
#/modules/Meetings/EditView            → RecordView (new record)
```

Modules configured as legacy in `backend.initData.legacy_views` are redirected to `LegacyView`.

#### `store/` - State Management

Pinia stores for global state:

```
store/
├── index.ts                # Pinia instance + reset-store plugin
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
├── returnLocation.ts       # Where to go back to from a record (sessionStorage)
├── legacyIframe.ts         # Legacy iframe state
├── localStorage.ts         # localStorage helpers
├── theme.ts                # Light/dark/system theme (see 14-user-theme.md)
├── mcp.ts                  # MCP login state
├── url.ts                  # URL parsing utilities
└── ux.ts                   # UI state (layout, drawer, sidebars)
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
└── routes.ts               # Route definitions (core + custom/router)
```

**Route Structure (excerpt):**

```typescript
const coreRoutes: Array<RouteRecordRaw> = [
    { path: '/modules/:module', name: 'list', component: ListView, meta: { auth: true } },
    {
        path: '/modules/:module/DetailView/:id',
        alias: '/modules/:module/EditView/:id?',
        name: 'record',
        component: RecordView,
        meta: { auth: true },
    },
    { path: '/modules/:module/:action/:record?', name: 'module-view', component: LegacyView, meta: { isLegacy: true, auth: true } },
]

const routes: Array<RouteRecordRaw> = [...coreRoutes, ...customRoutes]
```

#### `composables/` - Reusable Logic

Composition API functions for shared logic:

```
composables/
├── useACL.ts               # Access control checks
├── useBean.ts              # Bean data management
├── useLink.ts              # Related records (relationships)
├── useLogic.ts             # Business logic execution
├── useMintDate.ts          # Date values (user/server format)
├── useDraggable.ts         # Drag & drop functionality
├── useMove.ts, useDataPosition.ts  # Positioning helpers
└── useEagerFetch.ts, useWorkscheduleColorStyle.ts
```

`useField` is not here: it lives in `components/Fields/useField.ts`.

**Usage Example:**

```typescript
import { useACL } from '@/composables/useACL'

const acl = useACL()

if (acl.hasAccess('Employees', 'edit')) {
    // Show edit button
}
```

#### `business/` - Business Logic

Action system for record operations:

```
business/
├── registerActionModules.ts # Registers glob-loaded actions by file name
│
├── BeanActions/            # Single record actions
│   ├── BeanAction.ts       # Base action class
│   ├── GenericApiAction.ts # Metadata-only actions (api_route)
│   ├── customVisibility.ts # Declarative visibility conditions
│   ├── index.ts            # Registry (core, then custom overrides)
│   └── Actions/            # Named actions (file name = action name)
│       ├── Audit.ts
│       ├── Delete.ts
│       ├── Duplicate.ts
│       ├── DuplicateMeetings.ts
│       ├── ConvertToEmployee.ts
│       └── ...             # AcceptWorkSchedule, CloseWorkSchedule, PublishNews, ...
│
├── MassActions/            # Bulk operations
│   ├── MassAction.ts       # Base mass action class
│   ├── index.ts            # Mass action registry
│   └── Actions/
│       ├── Delete.ts
│       ├── Export.ts
│       ├── Update.ts
│       ├── Merge.ts
│       └── ...             # MassAcceptance, MassConfirmation
│
└── SubpanelActions/        # Subpanel operations
    ├── SubpanelAction.ts   # Base action class
    ├── index.ts            # Action registry (Actions + InlineActions)
    ├── Actions/            # Header-level buttons (per subpanel)
    │   ├── Create.ts
    │   ├── CreateCosts.ts
    │   ├── CreateSpentTimeForWorkSchedule.ts
    │   ├── Select.ts
    │   └── SelectWorkScheduleForDelegation.ts
    └── InlineActions/      # Per-row action buttons
        ├── Edit.ts         # Redirect to EditView of related record
        ├── Delete.ts       # Delete related record (with confirmation)
        └── Remove.ts       # Unlink related record (with confirmation)
```

**Action Pattern:**

```typescript
// business/BeanActions/Actions/Delete.ts
export class Delete extends BeanAction {
    public static readonly TITLE = 'LBL_DELETE_BUTTON_LABEL'
    public static readonly ICON = 'mdi-delete'
    public static readonly ACL = ['delete']

    public async execute() {
        if (!(await usePopupsStore().confirm(useLanguagesStore().label('LBL_MINT4_BEAN_DELETE_CONFIRM')))) {
            return false
        }
        await this.bean.markDeleted()
        router.push({ name: 'list', params: { module: this.bean.module } })
        return true
    }
}

// Action with options (Duplicate with skipFields)
export class Duplicate extends BeanAction {
    public async execute() {
        const query: { copy_id: string; excludedFields?: string } = { copy_id: this.bean.id }
        if (Array.isArray(this.options.skipFields)) {
            query.excludedFields = JSON.stringify(this.options.skipFields) // parsed back in RecordView.vue
        }
        router.push({ path: `/modules/${this.bean.module}/EditView`, query })
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
├── business/
│   ├── BeanActions/Actions/          # Record actions (override core by file name)
│   ├── MassActions/Actions/          # Mass actions
│   └── SubpanelActions/{Actions,InlineActions}/
├── components/
│   └── ListViewPanels/               # Panels under the list view (override core by name)
├── drawers/                # Custom drawer definitions (*.ts)
├── router/index.ts         # Custom routes (default-exported array)
├── store/                  # Custom stores
└── views/                  # Custom page components (+ ListView/Actions/*.ts)
```

Only the paths above are picked up automatically; anything else in `custom/` must be imported explicitly (e.g. from a custom route). See [Customization Guide](./11-customization.md#extension-points) for the full list.

**Why `custom/`?**

- ✅ **Safe from updates** - Core updates won't overwrite your code
- ✅ **Organized** - Clear separation of core vs custom

See [Customization Guide](./11-customization.md) for details.

#### `layouts/` - Page Templates

Layout components that wrap page content:

```
layouts/
├── DefaultLayout/          # Main app layout
│   ├── DefaultLayout.vue
│   ├── DefaultLayoutTopbar.vue
│   ├── DefaultLayoutSidebar.vue
│   ├── DefaultLayoutDrawer.vue
│   ├── DefaultLayoutSearch.vue, DefaultLayoutUser.vue, DefaultLayoutAlerts.vue
│   └── DefaultLayoutModulesPopup.vue, DefaultLayoutOnOffBoardingPopup.vue
│
└── GuestLayout/            # Unauthenticated layout
    └── GuestLayout.vue
```

**Layout Structure:**

`App.vue` renders `<component :is="ux.layout">` (chosen from `route.meta.layout`, otherwise Default/Guest) and puts the `<router-view>` into its default slot:

```vue
<!-- layouts/DefaultLayout/DefaultLayout.vue -->
<template>
    <DefaultLayoutTopbar />
    <DefaultLayoutSidebar />
    <DefaultLayoutDrawer />
    <slot></slot>
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
├── componentLoader.ts     # Resolves a component path (custom/ first) for menu actions
├── dates.ts                # Date formatting, parsing
├── html.ts                 # HTML entity decoding
├── numbers.ts              # Number formatting
├── qsOperators.ts          # Query string operators
└── qsOperatorsTypes.ts     # Query operator types
```

#### `assets/` - Static Assets

Flat folder of images imported by components and bundled by Vite (`mint_logo.png`, `mint_logo_white.svg`, `thumb-*.png`). Global styles are `src/main.scss` (which imports `src/dark-mode.scss`).

## `public/` Directory

Static files served as-is (not processed by Vite):

```
public/
├── favicon.ico             # Browser favicon
└── bg.jpg                  # Background image (main.scss, LoadingScreen, InstallView)
```

**Note:** Files in `public/` are copied to `dist/` root during build.

## File Naming Conventions

### Components

- **PascalCase** for component files: `MintDataTable.vue`
- **`{type}.{view}.vue`** for field types, where `{type}` is the lowercase backend type: `varchar.detail.vue`

### TypeScript Files

- **camelCase** for utilities and composables: `useField.ts`, `componentLoader.ts`
- **PascalCase** for classes: `BeanAction.ts`
- **camelCase** for stores: `backend.ts`, `statusBoxes.ts`
- **`*.api.ts`** for API wrappers: `modules.api.ts`

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

**Configuration:** `tsconfig.json` and `vite.config.mts`

```typescript
// vite.config.mts
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
│   ├── index-C_TSTIPe.js   # Main JS (hashed)
│   ├── index-ftl8Sqjd.css  # Main CSS (hashed)
│   ├── <Chunk>-<hash>.js   # Code-split chunks
│   └── ...
├── favicon.ico             # From public/
└── bg.jpg                  # From public/
```

**Hash Naming:** Files have content hashes (e.g., `C_TSTIPe`) for cache busting.

`vue/dist/` is committed and served in place by the root `.htaccess` (see [Building & Deployment](./04-building-deployment.md)).

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
3. **Custom routes**: `custom/router/index.ts`

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
- ❌ `dist/` (generated by `npm run build`; it is committed, so commit the rebuilt output together with the source change)
- ❌ Core files if customizing (use the `custom/` extension points instead)

## Next Steps

- Learn about [Building & Deployment](./04-building-deployment.md)
- Explore the [Field System](./09-fields.md)
- Understand [Customization](./11-customization.md)

---

**Know your structure, code with confidence!** 📁

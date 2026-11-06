# Field System

The Field System is one of the most powerful features of MintHCM frontend. It automatically renders the correct input/display component based on field type and view mode.

## Overview

Fields in MintHCM are **dynamic** and **self-configuring**. A single `<Field>` component automatically:

1. Detects the field type (`varchar`, `enum`, `date`, etc.)
2. Loads the appropriate sub-component (`varchar.edit.vue`, `enum.detail.vue`, etc.)
3. Renders with proper styling and behavior
4. Shows the required/error state and the error message

In record views a field is always backed by a **`MintField`** - the per-field model created by `useField` and kept in `bean.fields` (see [Working with Beans](./10-working-with-beans.md)). Edit components write to `props.field.model`, so the `field` prop is required in edit view:

```vue
<template>
    <!-- Edit mode: text input bound to the bean's MintField -->
    <Field
        :defs="bean.fieldDefs.value.first_name"
        :view="'edit'"
        :label="languages.label('LBL_FIRST_NAME', 'Employees')"
        :field="bean.fields.value.first_name"
        :required="bean.logic.requiredFields.value.includes('first_name')"
        :errorMessage="bean.errorMessages.value.first_name"
        @update:modelValue="(value) => bean.updateFields({ first_name: value })"
    />

    <!-- Detail mode: plain text -->
    <Field
        :defs="bean.fieldDefs.value.first_name"
        :view="'detail'"
        :label="languages.label('LBL_FIRST_NAME', 'Employees')"
        :field="bean.fields.value.first_name"
        :modelValue="bean.syncAttributes.value.first_name"
    />

    <!-- List mode: each list row is a bean as well -->
    <Field
        view="list"
        :defs="store.defs.columns.first_name"
        :data="{ bean: item }"
        :label="languages.label(store.defs.columns.first_name.label, store.module)"
        :field="item.fields.first_name"
        :modelValue="item.attributes.first_name"
    />
</template>
```

The canonical usages are `MintPanelRecordPanel.vue` (`src/components/MintPanel/MintPanelRecordPanel/`) and `ListViewTable.vue` (`src/views/ListView/`).

## Architecture

### Field Component Structure

```
components/Fields/
├── Field.vue              # Main router component
├── Field.config.ts        # Glob-built type registry + typeMap
├── Field.model.ts         # FieldProps, FieldInterface
├── useField.ts            # MintField: model, dirty flag, validation, formatting
├── Pencil.vue             # Edit mode toggle button
│
└── {fieldType}/           # Each field type has a folder
    ├── {fieldType}.detail.vue    # Detail view
    ├── {fieldType}.edit.vue      # Edit view
    ├── {fieldType}.list.vue      # List view
    └── {fieldType}.ts            # FieldInterface: validator / formatters (optional)
```

A type does not need all three views; a missing view simply falls back to `varchar` for that view.

### Field Type Folder Structure

Example: `varchar` (the default text field)

```
Fields/varchar/
├── varchar.detail.vue      # Plain text display
├── varchar.edit.vue        # Text input (v-model on props.field.model)
└── varchar.list.vue        # Text for tables
```

### How Field Loading Works

```
1. <Field> component receives props
   - defs: { type: 'enum', name: 'status', ... }
   - view: 'edit'
   ↓
2. Field.vue resolves field type
   - date_entered / date_modified in detail view → 'datetimecombined'
   - defs.type has a component for this view? → use it
   - else typeMap[defs.type] has one? → use it
   - else → 'varchar' (console warning)
   ↓
3. Dynamically imports component
   - import(`@/components/Fields/enum/enum.edit.vue`)
   ↓
4. Renders loaded component
   - Passes defs, label, field, modelValue, options, data, view and the computed state
   ↓
5. Component displays/handles input and emits update:modelValue
```

## Field Types

### Available Field Types

`src/components/Fields/` contains 26 type folders:

| Type | Views | Description |
|------|-------|-------------|
| `varchar` | detail, edit, list | Text input (default/fallback type) |
| `text` | detail, edit, list | Multi-line text |
| `name` | list | Record name in lists (links to the record) |
| `id` | detail, edit, list | Record ID |
| `enum` | detail, edit, list | Dropdown select |
| `multienum` | detail, edit, list | Multi-select |
| `bool` | detail, edit, list | Checkbox/toggle |
| `int` | detail, edit, list | Integer input |
| `currency` | detail, edit, list | Money input |
| `date` | detail, edit, list | Date picker |
| `datetime` | detail, edit, list | Date + time picker |
| `datetimecombined` | detail | Date + time display (used for `date_entered` / `date_modified`) |
| `email` | detail, edit, list | Email address |
| `phone` | detail, edit, list | Phone number |
| `url` | detail, edit, list | Website |
| `file` | detail, edit, list | File / image upload |
| `relate` | detail, edit, list | Related record |
| `parent` | detail, edit, list | Polymorphic relation (parent type + record) |
| `richtext` | detail, edit, list | Rich text (HTML) |
| `markdown` | detail, edit | Markdown content |
| `fieldset` | detail, edit | Group of fields (e.g. address block) |
| `repeat` | detail, edit | Recurrence settings (meetings, work schedules) |
| `crontab` | detail, edit, list | Cron expression (see [List View Panels](./16-list-view-panels.md)) |
| `age` | detail, edit, list | Calculated age |
| `achievements` | detail | Achievement badges |
| `appraisaltoken` | list | Appraisal token |

Backend types mapped to another component through `typeMap`: `char` → `varchar`, `name` → `varchar` (detail/edit), `datetimecombo` → `datetime`, `ColoredEnum` / `ColoredActivityStatus` → `enum`, `image` → `file`, `html` → `richtext`. Any other unknown type (e.g. `float`, `decimal`) is rendered as `varchar`.

### Field Type Configuration

**File:** `Field.config.ts`

```typescript
const listFields = import.meta.glob('@/components/Fields/*/*.list.vue', { eager: true })
const editFields = import.meta.glob('@/components/Fields/*/*.edit.vue', { eager: true })
const detailFields = import.meta.glob('@/components/Fields/*/*.detail.vue', { eager: true })
const fields = import.meta.glob('@/components/Fields/*/*.ts', { eager: true })

export const fieldConfig = {
    // Types available per view - derived from the files that exist
    allowedTypes: {
        list: Object.keys(listFields).map(getFieldName),
        edit: Object.keys(editFields).map(getFieldName),
        detail: Object.keys(detailFields).map(getFieldName),
    },

    // FieldInterface objects from {type}/{type}.ts, keyed by type
    fields: { /* e.g. enum: { userFormatter }, date: { ... } */ },

    // Default type when resolution fails
    defaultType: 'varchar',

    // Map backend types to component types
    typeMap: {
        char: 'varchar',
        datetimecombo: 'datetime',
        ColoredActivityStatus: 'enum',
        ColoredEnum: 'enum',
        image: 'file',
        html: 'richtext',
        name: 'varchar',
    },
}
```

There is nothing to register by hand: adding a file is enough.

### Field Behavior File (`{type}.ts`)

A type folder may contain `{type}.ts` exporting a `FieldInterface` (`Field.model.ts`). `useField` uses it for every bean field of that type:

```typescript
export interface FieldInterface {
    validator?: (value: any) => string | void            // return an error label to fail validation
    toAppFormat?: (value: any) => any                     // server value → model value
    userFormatter?: (value: any, defs: FieldVardef) => string | number | boolean | string[]   // display
    serverFormatter?: (value: any, defs: FieldVardef) => string | number | boolean | string[] // value sent on save
}
```

Example - `enum/enum.ts`:

```typescript
const field: FieldInterface = {
    userFormatter: (value: string, defs: FieldVardef) => {
        if (!value) {
            return ''
        }
        const language = useLanguagesStore()
        return language.translateListValue(value, defs.options) || value
    },
}

export default field
```

Types with such a file today: `date`, `datetime`, `datetimecombined`, `enum`, `file`, `multienum`, `text`.

## Adding a New Field Type

> **Important:** field types are resolved only from `src/components/Fields/`. Files in `src/custom/components/Fields/` are **not** loaded by `Field.vue` or `Field.config.ts`, so a field type cannot currently be added through `custom/`.

To add a type, create a folder in core:

### Step 1: Create Field Components

**File:** `src/components/Fields/rating/rating.edit.vue`

```vue
<template>
    <div class="rating-edit">
        <label v-if="props.label">{{ props.label }}</label>
        <v-rating
            v-model="props.field.model"
            @update:modelValue="(v) => emit('update:modelValue', v)"
            hover
            :length="5"
        />
    </div>
</template>

<script setup lang="ts">
import { FieldProps } from '../Field.model'

const props = defineProps<FieldProps<number>>()
const emit = defineEmits(['update:modelValue'])
</script>
```

**File:** `src/components/Fields/rating/rating.detail.vue`

```vue
<template>
    <v-rating :model-value="Number(props.modelValue) || 0" readonly density="compact" />
</template>

<script setup lang="ts">
import { FieldProps } from '../Field.model'

const props = defineProps<FieldProps<number>>()
</script>
```

Add `rating.list.vue` the same way if the type should render differently in lists, and `rating.ts` for validation or formatting.

### Step 2: Use in Backend

Define the field in the module vardefs with the folder name as `type` (or map an existing backend type in `typeMap`):

```php
// legacy/custom/Extension/modules/Candidates/Ext/Vardefs/customer_rating.php
$dictionary['Candidates']['fields']['customer_rating'] = [
    'name' => 'customer_rating',
    'vname' => 'LBL_CUSTOMER_RATING',
    'type' => 'rating',
    'dbType' => 'int',
];
```

### Step 3: Rebuild

The new type is picked up by the globs at build time: run `npm run build` in `vue/` and commit `vue/dist/`.

## Field Props Interface

Every field component receives these props (`Field.model.ts`):

```typescript
interface FieldProps<T = any> {
    view: 'edit' | 'detail' | 'list'   // View mode
    defs: FieldVardef                   // Field definition from backend
    label: string                       // Display label (translated)
    field: MintField<T>                 // Per-field model from bean.fields (required for edit)
    modelValue?: any                    // Current value (detail/list)
    data?: any                          // Extra data, e.g. { bean } in record views
    options?: any                       // Options override (from logic: bean.logic.fieldsOptions)
    state?: 'normal' | 'error' | 'required'  // Set by Field.vue, not by the caller
    required?: boolean
    error?: boolean
    errorMessage?: string               // Label key of the error to show
    disabled?: boolean
    hidePencil?: boolean
    isDirty?: boolean                   // Show the "required" error only after editing / save attempt
    minuteStep?: number                 // Time pickers
}
```

### FieldVardef Interface

Field definitions from backend (`src/store/modules.ts`):

```typescript
interface FieldVardef {
    name: string
    type: string
    label: string
    inputType?: string                       // varchar: HTML input type
    config_key?: string
    id_name?: string                         // relate: ID field
    type_name?: string                       // parent: type field
    options?: string | Record<string, string> // enum: list name or { key: label }
    options_colors?: string                  // colored enums
    default?: string
    readonly?: boolean
    properties?: PropertiesObject            // fieldset: separator + fields
    filters?: { [moduleName: string]: filterDef[] } | filterDef[]  // relate: search filters
    comment?: string                         // help text (aria description)
    hide_detail?: boolean
    additional_info?: string
    upload_handler?: string
    static_value?: string
    visible_when?: Array<{ field: string; value: any }>
}
```

## Field States

`Field.vue` computes the state itself and passes it to the component; a `state` passed to `<Field>` is ignored:

| State | When | Visual |
|-------|------|--------|
| `normal` | Not edit view, or no error and not required | Default outline |
| `required` | Edit view and `required` is true | Label gets the ` (required)` suffix (`LBL_REQUIRED`) |
| `error` | Edit view and an error message exists | Error message shown below the field |

```vue
<Field
    :defs="defs"
    :view="'edit'"
    :label="label"
    :field="bean.fields.value.email"
    :required="true"
    :isDirty="bean.isDirty.value || bean.fields.value.email?.isDirty"
    :errorMessage="bean.errorMessages.value.email"
/>
```

## Field Validation

### Built-in Validation

The error message shown by `Field.vue` (edit view only):

```typescript
const errorMessage = computed(() => {
    if (props.view !== 'edit') {
        return ''
    }
    // 1. Explicit error (from bean.errorMessages / logic)
    if (props.errorMessage) {
        return languagesStore.label(props.errorMessage, modulesStore.currentModule?.name)
    }
    // 2. Required and empty, once the field is dirty (or a save was attempted)
    if (props.isDirty && props.required) {
        const fieldValue = props.field?.formatted?.server ?? props.modelValue
        if (fieldValue === null || fieldValue === undefined || fieldValue === '') {
            return languagesStore.label('ERR_FIELD_REQUIRED', modulesStore.currentModule?.name)
        }
    }
    return ''
})
```

### Type-Level Validation

Validation for a whole type goes into `validator` in `{type}/{type}.ts`. `useField().validate()` calls it, and `bean.errorMessages` collects the results for the fields of the record form (`MintPanelRecordDetails`). A non-empty `bean.errorMessages` makes `bean.isValid` false.

```typescript
// Fields/mytype/mytype.ts
const field: FieldInterface = {
    validator: (value: string) => {
        if (value && !/^[A-Z]{2}\d+$/.test(value)) {
            return 'LBL_INVALID_FORMAT' // label key
        }
    },
}
export default field
```

Record-specific rules (required/readonly/hidden fields, error messages) come from backend logic, see [Working with Beans](./10-working-with-beans.md#business-logic).

## Special Field Types

### Relate Field

Displays and selects related records. The vardef provides `module`, `id_name` and optionally `filters`.

**Features:**
- Autocomplete search with **multi-word support** — each word is matched independently using a wildcard (`word*`), so typing `"Jan Ko"` finds records matching both `Jan*` and `Ko*`
- Search triggers after **minimum 3 characters**; results are **debounced** (500 ms) to avoid excessive API calls
- Related record display
- Link to related record
- Advanced search popup (via `mdi-magnify` icon or "Advanced Search" button)
- Matching words highlighted in the dropdown results (words of any length are highlighted)

### Enum Field

Dropdown with options. Options come from `props.options` (logic override) or `defs.options`:

- a **string** - name of an app list (`languages.getList(name)`);
- an **object** `{ key: label }`;
- an **array** of `{ key, value }`.

**Features:**
- Translated labels (`enum.ts` `userFormatter`)
- Colored variants via the `ColoredEnum` / `ColoredActivityStatus` types and `options_colors`

### DateTime Field

Date and time picker (`datetime`; backend `datetimecombo` maps to it).

**Features:**
- Date picker calendar
- Time picker (`minuteStep`)
- Timezone aware (user timezone from preferences)
- Format localization (user date/time format)

### File Field

Single file upload (`file`; backend `image` maps to it).

**Features:**
- One file per field (`v-file-input`)
- Image preview with zoom popup for image fields
- On save, the file is read as a data URL and sent with the record (see [Working with Beans](./10-working-with-beans.md#file-uploads))

### Richtext Field

Rich text (HTML) content, backed by the `html` vardef type.

**Features:**
- Edit view uses `MintWysiwyg` (TinyMCE) for formatting
- Detail view sanitizes the stored HTML with DOMPurify before rendering (`v-html`) and shows the record's `image` field above the content, if one exists
- List view strips tags and truncates to 100 characters

## Field Options

The `options` prop overrides `defs.options` for option-based types (`enum`, `multienum`, `parent`). In record views it is filled from backend logic:

```vue
<Field
    :defs="defs"
    :view="'edit'"
    :label="label"
    :field="bean.fields.value.status"
    :options="bean.logic.fieldsOptions.value.status"
/>
```

Accepted shapes: an app list name, `{ key: label }`, or `[{ key, value }]`.

## View-Specific Behavior

### Detail View

- **Read-only** display of `modelValue` (in record views: `bean.syncAttributes`)
- Formatted output
- Links to related records (relate, parent, name)

### Edit View

- **Input controls** bound to `props.field.model`
- Emit `update:modelValue` so the parent can call `bean.updateFields()`
- Error state and message from `Field.vue`
- Help text from `defs.comment` (aria description)

```vue
<!-- varchar.edit.vue (excerpt) -->
<template>
    <v-text-field
        :label="props.label"
        variant="outlined"
        density="compact"
        hide-details
        :name="props.defs.name"
        v-model="props.field.model"
        :error="props.state === 'error'"
        :type="props.defs.inputType || 'text'"
        @update:modelValue="(v) => $emit('update:modelValue', v)"
    />
</template>
```

### List View

- **Compact** display of `modelValue`
- `richtext.list.vue` strips tags and truncates; `name.list.vue` links to the record

## `useField` (MintField)

**File:** `src/components/Fields/useField.ts`

`useBean` creates one `MintField` per attribute with `useField(defs, initialValue)`:

```typescript
export const useField = <T>(defs: FieldVardef, initialValue: T, opts: FieldOptions = {}) => {
    const resolvedFieldType = fieldConfig.typeMap[defs.type] || defs.type
    const fieldInterface: FieldInterface = fieldConfig.fields[resolvedFieldType] || {}

    const model = ref<T>(fieldInterface.toAppFormat ? fieldInterface.toAppFormat(initialValue) : initialValue)
    const isDirty = ref(false) // becomes true on the first change of model

    const formatted = computed(() => ({
        user: fieldInterface.userFormatter ? fieldInterface.userFormatter(model.value, defs) : model.value,
        server: fieldInterface.serverFormatter ? fieldInterface.serverFormatter(model.value, defs) : model.value,
    }))

    function validate() {
        if (typeof fieldInterface.validator === 'function') {
            return fieldInterface.validator(model.value)
        }
    }

    return { initialValue, model, isDirty, validate, formatted }
}
```

`formatted.server` is what `bean.save()` sends; the label suffix and error message logic live in `Field.vue`, not here.

## Best Practices

### ✅ DO

- **Use TypeScript** (`FieldProps<T>`) for type safety
- **Follow naming convention**: `{type}/{type}.{view}.vue`
- **Bind edit inputs to `props.field.model`** and emit `update:modelValue`
- **Put validation and formatting in `{type}.ts`**, not in the components
- **Use translation labels** for messages
- **Style with scoped CSS**

### ❌ DON'T

- **Put field types in `custom/`** - they are not loaded
- **Duplicate code** between views (share it through `{type}.ts`)
- **Ignore the props interface**
- **Hardcode labels** (use translations)

## Troubleshooting

### Field Not Rendering / Rendered as Plain Text

**Check:**
1. The folder and file names match `defs.type` (or a `typeMap` entry): `Fields/{type}/{type}.{view}.vue`
2. The file exists for this view (otherwise `varchar` is used and a `resolveFieldType` warning is logged)
3. The file is in `src/components/Fields/`, not in `src/custom/`
4. The frontend was rebuilt after adding the files

### Edit Field Throws `Cannot read properties of undefined (reading 'model')`

The `field` prop is missing. Pass the bean's `MintField`: `:field="bean.fields.value[name]"`.

### Value Not Saved

**Check:**
1. The edit component writes to `props.field.model` (that is what `save()` sends via `formatted.server`)
2. The parent handles `update:modelValue` with `bean.updateFields()`

## Next Steps

- See [Working with Beans](./10-working-with-beans.md) for `bean.fields` and saving
- Read [Record View Definitions](./12-recordviewdefs.md) for placing fields on the record view
- See [Customization Guide](./11-customization.md) for what can be extended via `custom/`

---

**Master the Field System, build powerful forms!** 🎯

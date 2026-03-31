# Field System

The Field System is one of the most powerful features of MintHCM frontend. It automatically renders the correct input/display component based on field type and view mode.

## Overview

Fields in MintHCM are **dynamic** and **self-configuring**. A single `<Field>` component automatically:

1. Detects the field type (string, enum, date, etc.)
2. Loads the appropriate sub-component (string.edit.vue, enum.detail.vue, etc.)
3. Renders with proper styling and behavior
4. Handles validation and state management

```vue
<template>
    <!-- Same component, different behavior based on props -->
    
    <!-- Edit mode: shows text input -->
    <Field 
        :defs="{ type: 'string', name: 'first_name' }"
        :view="'edit'"
        v-model="formData.first_name"
    />
    
    <!-- Detail mode: shows plain text -->
    <Field 
        :defs="{ type: 'string', name: 'first_name' }"
        :view="'detail'"
        :modelValue="record.first_name"
    />
    
    <!-- List mode: shows truncated text -->
    <Field 
        :defs="{ type: 'string', name: 'first_name' }"
        :view="'list'"
        :modelValue="record.first_name"
    />
</template>
```

## Architecture

### Field Component Structure

```
components/Fields/
├── Field.vue              # Main router component
├── Field.config.ts        # Field type configuration
├── Field.model.ts         # TypeScript interfaces
├── useField.ts            # Composable for field logic
├── Pencil.vue             # Edit mode toggle button
│
└── {fieldType}/           # Each field type has a folder
    ├── {fieldType}.detail.vue    # Detail view
    ├── {fieldType}.edit.vue      # Edit view
    ├── {fieldType}.list.vue      # List view
    └── {fieldType}.options.ts    # Configuration (optional)
```

### Field Type Folder Structure

Example: String field

```
Fields/string/
├── string.detail.vue      # Plain text display
├── string.edit.vue        # Text input
└── string.list.vue        # Truncated text for tables
```

### How Field Loading Works

```
1. <Field> component receives props
   - defs: { type: 'enum', name: 'status' }
   - view: 'edit'
   ↓
2. Field.vue resolves field type
   - Checks defs.type: 'enum'
   - Maps special types (Field.config.ts)
   - Falls back to default if not found
   ↓
3. Dynamically imports component
   - import(`@/components/Fields/enum/enum.edit.vue`)
   ↓
4. Renders loaded component
   - Passes through all props
   - Emits v-model updates
   ↓
5. Component displays/handles input
```

## Field Types

### Available Field Types

MintHCM includes ~30 field types:

| Type | Description | Example Use |
|------|-------------|-------------|
| `varchar` / `string` | Text input | Names, titles |
| `text` | Multi-line text | Descriptions |
| `enum` | Dropdown select | Status, category |
| `multienum` | Multi-select | Tags, skills |
| `bool` | Checkbox/toggle | Is Active, Published |
| `date` | Date picker | Birth date |
| `datetime` | Date + time picker | Created date |
| `datetimecombined` | Combined display | Modified date |
| `email` | Email input | Email address |
| `phone` | Phone input | Phone number |
| `url` | URL input | Website |
| `int` / `integer` | Number input | Age, count |
| `float` / `decimal` | Decimal input | Price, rating |
| `currency` | Money input | Salary, budget |
| `file` | File upload | Documents |
| `image` | Image upload | Photos |
| `relate` | Related record | Manager, Department |
| `parent` | Polymorphic relation | Parent record |
| `age` | Calculated age | Years since date |
| `achievements` | Achievement badges | Milestones |
| `appraisaltoken` | Appraisal token | Performance review |
| `fieldset` | Group of fields | Address block |
| `html` | Rich text editor | Content, notes |

### Field Type Configuration

**File:** `Field.config.ts`

```typescript
export const fieldConfig = {
    // Available field types per view
    allowedTypes: {
        list: ['string', 'enum', 'date', ...],
        edit: ['string', 'enum', 'date', ...],
        detail: ['string', 'enum', 'date', ...],
    },

    // Map backend types to component types
    typeMap: {
        'char': 'varchar',              // char → varchar component
        'datetimecombo': 'datetime',    // datetimecombo → datetime
        'ColoredEnum': 'enum',          // ColoredEnum → enum
        'image': 'file',                // image → file
    },

    // Default type when resolution fails
    defaultType: 'varchar',
    
    // Field-specific options
    options: {
        enum: { ... },
        date: { ... },
    }
}
```

## Creating Custom Field Types

### Step 1: Create Field Components

**File:** `custom/components/Fields/rating/rating.detail.vue`

```vue
<template>
    <div class="rating-detail">
        <v-rating
            :model-value="modelValue"
            readonly
            color="amber"
            density="compact"
        />
        <span class="rating-value">{{ modelValue }}/5</span>
    </div>
</template>

<script setup lang="ts">
import { FieldProps } from '@/components/Fields/Field.model'

const props = defineProps<Pick<FieldProps, 'modelValue' | 'defs'>>()
</script>

<style scoped>
.rating-detail {
    display: flex;
    align-items: center;
    gap: 8px;
}

.rating-value {
    font-size: 14px;
    color: #666;
}
</style>
```

**File:** `custom/components/Fields/rating/rating.edit.vue`

```vue
<template>
    <div class="rating-edit">
        <label v-if="label">{{ label }}</label>
        <v-rating
            :model-value="modelValue"
            @update:model-value="$emit('update:modelValue', $event)"
            color="amber"
            hover
            :length="5"
            :size="32"
        />
        <div v-if="state === 'error'" class="error-message">
            Rating is required
        </div>
    </div>
</template>

<script setup lang="ts">
import { FieldProps } from '@/components/Fields/Field.model'

const props = defineProps<FieldProps>()
const emit = defineEmits(['update:modelValue'])
</script>

<style scoped>
.rating-edit {
    padding: 8px 0;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
}

.error-message {
    color: red;
    font-size: 12px;
    margin-top: 4px;
}
</style>
```

**File:** `custom/components/Fields/rating/rating.list.vue`

```vue
<template>
    <v-rating
        :model-value="modelValue"
        readonly
        color="amber"
        density="compact"
        size="small"
    />
</template>

<script setup lang="ts">
import { FieldProps } from '@/components/Fields/Field.model'

const props = defineProps<Pick<FieldProps, 'modelValue'>>()
</script>
```

### Step 2: Register Field Type

Modify field configuration to include your custom type:

```typescript
// Field.config.ts or custom/components/Fields/config.ts
export const customFieldTypes = {
    allowedTypes: {
        detail: ['rating'],
        edit: ['rating'],
        list: ['rating'],
    },
    typeMap: {
        'stars': 'rating',  // Map backend 'stars' type to 'rating' component
    }
}
```

### Step 3: Use in Backend

Define field in backend vardefs:

```php
// modules/Products/vardefs.php
$dictionary['Product']['fields']['customer_rating'] = [
    'name' => 'customer_rating',
    'type' => 'rating',  // or 'stars' which maps to 'rating'
    'label' => 'Customer Rating',
];
```

### Step 4: Use in Frontend

The field now works automatically:

```vue
<template>
    <!-- Automatically uses rating.edit.vue in edit mode -->
    <Field 
        :defs="{ type: 'rating', name: 'customer_rating' }"
        :view="'edit'"
        v-model="product.customer_rating"
        label="Customer Rating"
    />
</template>
```

## Field Props Interface

Every field component receives these props:

```typescript
interface FieldProps {
    // View mode
    view: 'edit' | 'detail' | 'list'
    
    // Field definition from backend
    defs: FieldVardef
    
    // Display label
    label: string
    
    // Current value
    modelValue?: any
    
    // Full record data (for computed fields)
    data?: any
    
    // Custom options
    options?: any
    
    // Visual state
    state?: 'normal' | 'error' | 'required'
    
    // Validation
    required?: boolean
    error?: boolean
    errorMessage?: string
    
    // UI state
    disabled?: boolean
    hidePencil?: boolean
    isDirty?: boolean
}
```

### FieldVardef Interface

Field definitions from backend:

```typescript
interface FieldVardef {
    name: string                // Field name
    type: string                // Field type
    label?: string              // Display label
    required?: boolean          // Is required
    readonly?: boolean          // Is readonly
    len?: number                // Max length
    options?: string            // Options list name
    default?: any               // Default value
    validation?: any            // Validation rules
    // ... other metadata
}
```

## Field States

Fields have visual states to indicate status:

### Normal State

Default appearance:

```vue
<Field 
    :state="'normal'"
    v-model="data.field"
/>
```

### Required State

Indicates field is required:

```vue
<Field 
    :state="'required'"
    :required="true"
    v-model="data.field"
/>
```

**Styling:**
- Label has asterisk (*)
- Special border color (blue)

### Error State

Shows validation error:

```vue
<Field 
    :state="'error'"
    :error="true"
    :errorMessage="'ERR_FIELD_REQUIRED'"
    v-model="data.field"
/>
```

**Styling:**
- Red border
- Error message below field
- Error icon

## Field Validation

### Built-in Validation

Field component handles basic validation:

```vue
<script setup lang="ts">
const errorMessage = computed(() => {
    if (props.view !== 'edit') {
        return ''
    }
    
    // Check required
    if (props.required && !props.modelValue) {
        return languagesStore.label('ERR_FIELD_REQUIRED')
    }
    
    // Check custom error
    if (props.errorMessage) {
        return languagesStore.label(props.errorMessage)
    }
    
    return ''
})
</script>
```

### Custom Validation

Add field-specific validation:

```vue
<!-- email.edit.vue -->
<script setup lang="ts">
import { computed } from 'vue'

const isValidEmail = computed(() => {
    if (!props.modelValue) return true
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    return regex.test(props.modelValue)
})

const errorMessage = computed(() => {
    if (!isValidEmail.value) {
        return 'Invalid email address'
    }
    return ''
})
</script>

<template>
    <v-text-field
        :model-value="modelValue"
        :error="!isValidEmail"
        :error-messages="errorMessage"
        @update:model-value="$emit('update:modelValue', $event)"
    />
</template>
```

## Special Field Types

### Relate Field

Displays and selects related records:

```vue
<Field
    :defs="{
        type: 'relate',
        name: 'manager_id',
        module: 'Users',
        id_name: 'manager_id',
        rname: 'name'
    }"
    :view="'edit'"
    v-model="employee.manager_name"
/>
```

**Features:**
- Autocomplete search with **multi-word support** — each word is matched independently using a wildcard (`word*`), so typing `"Jan Ko"` finds records matching both `Jan*` and `Ko*`
- Search triggers after **minimum 3 characters**; results are **debounced** (500 ms) to avoid excessive API calls
- Related record display
- Link to related record
- Advanced search popup (via `mdi-magnify` icon or "Advanced Search" button)
- Matching words highlighted in the dropdown results (words of any length are highlighted)

### Enum Field

Dropdown with options:

```vue
<Field 
    :defs="{
        type: 'enum',
        name: 'status',
        options: 'employee_status_dom'
    }"
    :view="'edit'"
    v-model="employee.status"
/>
```

**Features:**
- Options loaded from backend
- Translated labels
- Custom colors (ColoredEnum)

### DateTime Field

Date and time picker:

```vue
<Field 
    :defs="{
        type: 'datetime',
        name: 'meeting_date'
    }"
    :view="'edit'"
    v-model="meeting.meeting_date"
/>
```

**Features:**
- Date picker calendar
- Time picker
- Timezone aware
- Format localization

### File Field

File upload:

```vue
<Field 
    :defs="{
        type: 'file',
        name: 'attachment'
    }"
    :view="'edit'"
    v-model="document.attachment"
/>
```

**Features:**
- Drag & drop upload
- Progress indication
- File preview
- Multiple files support

## Field Options

Some fields accept custom options:

```vue
<Field 
    :defs="{ type: 'enum', name: 'status' }"
    :options="{
        items: [
            { value: 'draft', label: 'Draft' },
            { value: 'published', label: 'Published' },
        ],
        color: true,  // Use colored badges
    }"
    v-model="record.status"
/>
```

**Field-specific options file:**

```typescript
// components/Fields/enum/enum.options.ts
export default {
    // Custom options for enum field
    colorMap: {
        'Active': 'green',
        'Inactive': 'red',
        'Draft': 'orange',
    },
    iconMap: {
        'Active': 'mdi-check-circle',
        'Inactive': 'mdi-close-circle',
    }
}
```

## View-Specific Behavior

### Detail View

- **Read-only** display
- Formatted output
- Links to related records
- Copy to clipboard

```vue
<!-- string.detail.vue -->
<template>
    <div class="string-detail">
        <span>{{ modelValue }}</span>
        <v-btn 
            icon 
            size="small"
            @click="copyToClipboard"
        >
            <v-icon>mdi-content-copy</v-icon>
        </v-btn>
    </div>
</template>
```

### Edit View

- **Input controls**
- Validation
- Error messages
- Help text

```vue
<!-- string.edit.vue -->
<template>
    <v-text-field
        :model-value="modelValue"
        :label="label"
        :error="state === 'error'"
        :required="required"
        @update:model-value="$emit('update:modelValue', $event)"
    />
</template>
```

### List View

- **Compact** display
- Truncated text
- Quick actions
- Sortable

```vue
<!-- string.list.vue -->
<template>
    <div class="string-list">
        {{ truncatedValue }}
    </div>
</template>

<script setup lang="ts">
const truncatedValue = computed(() => {
    const max = 50
    if (props.modelValue?.length > max) {
        return props.modelValue.substring(0, max) + '...'
    }
    return props.modelValue
})
</script>
```

## Composable: useField

Reusable field logic:

```typescript
// composables/useField.ts
import { computed } from 'vue'
import { useLanguagesStore } from '@/store/languages'

export function useField(props: FieldProps) {
    const languages = useLanguagesStore()
    
    const displayLabel = computed(() => {
        let label = props.label
        if (props.required && props.view === 'edit') {
            label += ` (${languages.label('LBL_REQUIRED').toLowerCase()})`
        }
        return label
    })
    
    const hasError = computed(() => {
        return props.state === 'error' || props.error
    })
    
    const errorText = computed(() => {
        if (props.errorMessage) {
            return languages.label(props.errorMessage)
        }
        if (props.required && !props.modelValue && props.isDirty) {
            return languages.label('ERR_FIELD_REQUIRED')
        }
        return ''
    })
    
    return {
        displayLabel,
        hasError,
        errorText,
    }
}
```

**Usage in field component:**

```vue
<script setup lang="ts">
import { useField } from '@/composables/useField'

const props = defineProps<FieldProps>()
const { displayLabel, hasError, errorText } = useField(props)
</script>

<template>
    <div>
        <label>{{ displayLabel }}</label>
        <input :class="{ error: hasError }" />
        <span v-if="hasError">{{ errorText }}</span>
    </div>
</template>
```

## Best Practices

### ✅ DO

- **Use TypeScript** for type safety
- **Follow naming convention**: `{type}.{view}.vue`
- **Handle all views**: detail, edit, list
- **Emit v-model** updates properly
- **Style with scoped CSS**
- **Support validation** and error states
- **Test with different data** types

### ❌ DON'T

- **Modify core** field components (use custom/)
- **Duplicate code** between views (use composables)
- **Ignore props** interface
- **Hardcode labels** (use translations)
- **Skip error handling**

## Troubleshooting

### Field Not Rendering

**Check:**
1. Field type is in `allowedTypes` for the view
2. Component file exists at correct path
3. No TypeScript errors in component
4. Props are passed correctly

### Field Shows Wrong Component

**Check:**
1. `typeMap` in Field.config.ts
2. `defs.type` value
3. Component naming matches type

### v-model Not Updating

**Check:**
1. Emitting `update:modelValue` correctly
2. Props interface includes `modelValue`
3. Parent component has `v-model`

## Next Steps

- See [Customization Guide](./11-customization.md) for custom fields
- Read [Component System](./08-components.md) for general components
- Check [Best Practices](./15-best-practices.md) for code quality

---

**Master the Field System, build powerful forms!** 🎯

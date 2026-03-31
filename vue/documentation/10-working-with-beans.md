# Working with Beans (Records)

This guide explains how to work with beans (records) in MintHCM frontend using the `useBean` and `useLink` composables.

## Overview

**Beans** are the core data objects in MintHCM representing database records (users, candidates, meetings, etc.).

The frontend provides two main composables for bean management:

- **`useBean`** - Manage single record data (CRUD operations)
- **`useLink`** - Manage relationships between records

## useBean Composable

`useBean` is the primary interface for working with individual records.

### Basic Usage

```typescript
import { useBean } from '@/composables/useBean'

// Load existing record
const bean = useBean('Users', '123-456-789')
await bean.init()

// Access data
console.log(bean.attributes.value.first_name)
console.log(bean.name.value)  // Computed display name

// Create new record
const newBean = useBean('Users', '')
await newBean.init()
```

### Creating a New Record

**Example:** Create a new user

```vue
<template>
    <div>
        <h1>Create New User</h1>
        
        <v-form>
            <Field 
                v-for="field in formFields"
                :key="field.name"
                :defs="field"
                :view="'edit'"
                v-model="bean.attributes.value[field.name]"
                :label="field.label"
                :required="field.required"
                :isDirty="bean.dirtyFields.value.has(field.name)"
                :errorMessage="bean.errorMessages.value[field.name]"
            />
            
            <v-btn 
                @click="handleSave"
                :disabled="!bean.isValid.value"
                :loading="bean.isSaving.value"
            >
                Save
            </v-btn>
        </v-form>
    </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useBean } from '@/composables/useBean'
import { useRouter } from 'vue-router'

const router = useRouter()

// Create new bean (no ID = new record)
const bean = useBean('Users', '')

const formFields = [
    { name: 'first_name', label: 'First Name', required: true },
    { name: 'last_name', label: 'Last Name', required: true },
    { name: 'email', label: 'Email', required: true },
    { name: 'phone_work', label: 'Phone' },
]

onMounted(async () => {
    // Initialize empty bean
    await bean.init()
    
    // Set default values
    bean.updateFields({
        status: 'Active',
        is_admin: false,
    })
})

async function handleSave() {
    const result = await bean.save()
    
    if (result) {
        // Redirect to detail view
        // (useBean automatically redirects after creating new record)
        console.log('User created:', bean.id)
    } else {
        console.error('Save failed:', bean.validationError.value)
    }
}
</script>
```

### Editing Existing Record

**Example:** Edit user details

```vue
<template>
    <div v-if="bean.isRetrieving.value">
        Loading...
    </div>
    
    <div v-else>
        <h1>Edit {{ bean.name.value }}</h1>
        
        <v-form>
            <Field 
                v-model="bean.attributes.value.first_name"
                :defs="{ type: 'string', name: 'first_name' }"
                :view="'edit'"
                label="First Name"
                :required="true"
                :isDirty="bean.dirtyFields.value.has('first_name')"
            />
            
            <Field 
                v-model="bean.attributes.value.last_name"
                :defs="{ type: 'string', name: 'last_name' }"
                :view="'edit'"
                label="Last Name"
                :required="true"
            />
            
            <Field 
                v-model="bean.attributes.value.email"
                :defs="{ type: 'email', name: 'email' }"
                :view="'edit'"
                label="Email"
            />
            
            <div class="actions">
                <v-btn 
                    @click="handleSave"
                    :disabled="!bean.isValid.value || !bean.isChanged.value"
                    :loading="bean.isSaving.value"
                    color="primary"
                >
                    Save Changes
                </v-btn>
                
                <v-btn 
                    @click="handleRestore"
                    :disabled="!bean.isChanged.value"
                    variant="text"
                >
                    Cancel
                </v-btn>
            </div>
        </v-form>
    </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useBean } from '@/composables/useBean'
import { useRoute } from 'vue-router'

const route = useRoute()

// Load existing bean
const bean = useBean('Users', route.params.id as string)

onMounted(async () => {
    await bean.init()
})

async function handleSave() {
    const result = await bean.save()
    
    if (result) {
        // Success notification
        console.log('User updated successfully')
    }
}

function handleRestore() {
    // Revert changes
    bean.restore()
}
</script>
```

### Duplicating a Record

**Example:** Create a copy of existing record

```vue
<script setup lang="ts">
import { onMounted } from 'vue'
import { useBean } from '@/composables/useBean'
import { useRoute } from 'vue-router'

const route = useRoute()

// Create new bean for duplicate
const bean = useBean('Candidates', '')

onMounted(async () => {
    await bean.init()
    
    // Copy data from existing record
    const originalId = route.query.copy_id as string
    if (originalId) {
        // Basic duplication - copies all non-system fields
        await bean.setAttributesFromBeanId(originalId)
        
        // Or with excluded fields (optional)
        // const excludedFields = route.query.excludedFields ?? []
        // await bean.setAttributesFromBeanId(originalId, excludedFields)
    }
})

async function handleSave() {
    await bean.save()
    // New record will be created with copied data
}
</script>
```

**What gets copied:**
- ✅ All field values (except excluded fields)
- ❌ `id`, `date_entered`, `date_modified`
- ❌ `created_by`, `modified_user_id`
- ❌ `repeat_*` fields (meeting recurrence)
- ❌ Files and images
- ❌ Any fields specified in `excludedFields` parameter

## useBean API Reference

### Properties

```typescript
const bean = useBean('ModuleName', 'record-id')

// State
bean.id                    // Record ID (string)
bean.module                // Module name (string)
bean.isNew                 // Is new record (computed boolean)
bean.isRetrieving          // Loading state (ref boolean)
bean.isSaving              // Saving state (ref boolean)
bean.isDirty               // Has unsaved changes (ref boolean)
bean.isChanged             // Has changed values (computed boolean)
bean.isValid               // Passes validation (computed boolean)

// Data
bean.attributes            // Current field values (ref object)
bean.syncAttributes        // Saved field values (ref object)
bean.name                  // Display name (computed string)
bean.dirtyFields           // Set of modified fields (ref Set<string>)
bean.aclAccess             // Access permissions (ref object)

// Validation
bean.validationError       // Server validation error (ref string)
bean.errorMessages         // Field-specific errors (computed object)

// Metadata
bean.fieldDefs             // Field definitions (computed object)
bean.logic                 // Business logic rules (object)
```

### Methods

#### `init()`

Initialize the bean - fetches data for existing records or sets up new record.

```typescript
await bean.init()
```

**Returns:** `Promise<Response>`

#### `retrieve()`

Fetch latest data from server.

```typescript
await bean.retrieve()
```

**Use case:** Refresh data after external changes

#### `updateFields(fields)`

Update multiple fields at once.

```typescript
bean.updateFields({
    first_name: 'John',
    last_name: 'Doe',
    email: 'john@example.com',
})
```

**Parameters:**
- `fields` - Object with field names as keys and new values

**Note:** Marks fields as dirty and triggers logic rules

#### `save()`

Save changes to the server.

```typescript
const result = await bean.save()

if (result) {
    console.log('Saved successfully')
} else {
    console.error('Save failed:', bean.validationError.value)
}
```

**Returns:** `Promise<Response | false>`

**Process:**
1. Validates required fields
2. Checks field-level validation
3. Sends PATCH request to backend
4. Handles file uploads
5. Saves relationship changes (links)
6. Redirects for new records (to detail view)
7. Refreshes data for updated records

#### `restore()`

Revert all unsaved changes.

```typescript
bean.restore()
```

Restores `attributes` to `syncAttributes` (last saved state).

#### `markDeleted()`

Delete the record.

```typescript
await bean.markDeleted()
```

**Returns:** `Promise<Response>`

**Note:** Soft delete (sets `deleted` flag)

#### `setData(data)`

Set bean data from API response.

```typescript
bean.setData({
    attributes: { ... },
    acl_access: { ... },
    logic: { rules: [ ... ] }
})
```

**Use case:** Manually set data without API call

#### `setAttributesFromQuery(query)`

Populate fields from URL query parameters.

```typescript
// URL: /Users/create?first_name=John&department_id=123

onMounted(async () => {
    await bean.init()
    bean.setAttributesFromQuery(route.query)
    // Sets first_name = 'John', department_id = '123'
})
```

**Use case:** Pre-fill form from URL parameters

#### `setAttributesFromBeanId(copyId, excludedFields?)`

Copy data from another record.

```typescript
// Copy all non-system fields
await bean.setAttributesFromBeanId('original-record-id')

// Copy with specific field exclusions
await bean.setAttributesFromBeanId('original-record-id', ['date_start', 'date_end', 'status'])
```

**Parameters:**
- `copyId` - ID of the record to copy from
- `excludedFields` - (Optional) Array of field names to skip during copy

**Use case:** Duplicate record functionality with configurable field exclusion

#### `loadRelationship(name)`

Load a relationship link.

```typescript
const link = bean.loadRelationship('contacts')
if (link) {
    await link.fetchRelatedRecords()
}
```

**Returns:** `useLink` instance or `null`

## useLink Composable

`useLink` manages relationships between records (one-to-many, many-to-many).

### Basic Usage

```typescript
import { useLink } from '@/composables/useLink'

// Load relationship
const link = bean.loadRelationship('contacts')

// Fetch related records
await link.fetchRelatedRecords()

// Access related beans
console.log(link.beans.value)

// Add relationship
link.add('contact-id-123')

// Remove relationship
link.remove('contact-id-456')

// Get changes to save
const changes = link.getChanges()
```

### Adding Related Records

**Example:** Add contacts to an account

```vue
<template>
    <div>
        <h2>Contacts</h2>
        
        <v-list>
            <v-list-item 
                v-for="[id, contact] in contactsLink.beans.value"
                :key="id"
            >
                <v-list-item-title>{{ contact.name }}</v-list-item-title>
                <template #append>
                    <v-btn 
                        icon 
                        size="small"
                        @click="contactsLink.remove(id)"
                    >
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </template>
            </v-list-item>
        </v-list>
        
        <v-btn @click="showAddDialog = true">
            Add Contact
        </v-btn>
        
        <v-dialog v-model="showAddDialog">
            <ContactPicker 
                @select="handleAddContact"
            />
        </v-dialog>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useBean } from '@/composables/useBean'

const bean = useBean('Accounts', 'account-id')
const showAddDialog = ref(false)

let contactsLink = null

onMounted(async () => {
    await bean.init()
    
    // Load contacts relationship
    contactsLink = bean.loadRelationship('contacts')
    
    if (contactsLink) {
        await contactsLink.fetchRelatedRecords()
    }
})

function handleAddContact(contactId: string) {
    contactsLink.add(contactId)
    showAddDialog.value = false
}

async function handleSave() {
    // Relationship changes are saved automatically with bean.save()
    await bean.save()
}
</script>
```

### Managing Relationships with Additional Data

Some relationships have additional fields (e.g., role in many-to-many):

```typescript
// Add contact with additional data
contactsLink.add('contact-id', {
    role: 'Decision Maker',
    primary_contact: true,
})

// Changes include additional values
const changes = contactsLink.getChanges()
// {
//   beansToAdd: {
//     'contact-id': {
//       id: 'contact-id',
//       additionalValues: { role: 'Decision Maker', primary_contact: true }
//     }
//   },
//   beansToRemove: []
// }
```

## useLink API Reference

### Properties

```typescript
const link = bean.loadRelationship('contacts')

link.link                  // Link name (string)
link.relationshipName      // Relationship name (string)
link.beans                 // Map of related records (ref Map)
link.relateFieldName       // Related field name (computed string | null)
link.idFieldName           // ID field name (computed string | null)
```

### Methods

#### `add(id, additionalValues?)`

Add a record to the relationship.

```typescript
link.add('contact-id')

// With additional data
link.add('contact-id', {
    role: 'Manager',
    is_primary: true,
})
```

#### `remove(id)`

Remove a record from the relationship.

```typescript
link.remove('contact-id')
```

#### `fetchRelatedRecords()`

Fetch all related records from server.

```typescript
await link.fetchRelatedRecords()

// Access fetched records
link.beans.value.forEach((record, id) => {
    console.log(id, record)
})
```

#### `getChanges()`

Get pending relationship changes (for saving).

```typescript
const changes = link.getChanges()
// {
//   beansToAdd: { 'id1': {...}, 'id2': {...} },
//   beansToRemove: ['id3', 'id4']
// }
```

**Note:** Called automatically by `bean.save()`

## Complete Example: Edit View

**Example:** Full edit view with relationships

```vue
<template>
    <div v-if="bean.isRetrieving.value">
        <LoadingScreen />
    </div>
    
    <div v-else class="edit-view">
        <h1>{{ bean.isNew.value ? 'Create' : 'Edit' }} {{ moduleName }}</h1>
        
        <!-- Main Form -->
        <v-card>
            <v-card-text>
                <Field 
                    v-for="field in formFields"
                    :key="field.name"
                    :defs="field"
                    :view="'edit'"
                    v-model="bean.attributes.value[field.name]"
                    :label="field.label"
                    :required="field.required"
                    :isDirty="bean.dirtyFields.value.has(field.name)"
                    :errorMessage="bean.errorMessages.value[field.name]"
                />
            </v-card-text>
        </v-card>
        
        <!-- Relationships (only for existing records) -->
        <v-card v-if="!bean.isNew.value" class="mt-4">
            <v-card-title>Related Contacts</v-card-title>
            <v-card-text>
                <v-list v-if="contactsLink">
                    <v-list-item 
                        v-for="[id, contact] in contactsLink.beans.value"
                        :key="id"
                    >
                        <v-list-item-title>{{ contact.name }}</v-list-item-title>
                        <template #append>
                            <v-btn 
                                icon 
                                @click="contactsLink.remove(id)"
                            >
                                <v-icon>mdi-close</v-icon>
                            </v-btn>
                        </template>
                    </v-list-item>
                </v-list>
                
                <v-btn @click="showAddContact = true">
                    Add Contact
                </v-btn>
            </v-card-text>
        </v-card>
        
        <!-- Actions -->
        <div class="actions mt-4">
            <v-btn 
                @click="handleSave"
                :disabled="!bean.isValid.value"
                :loading="bean.isSaving.value"
                color="primary"
            >
                Save
            </v-btn>
            
            <v-btn 
                @click="handleCancel"
                variant="text"
            >
                Cancel
            </v-btn>
            
            <v-btn 
                v-if="bean.isChanged.value"
                @click="handleRestore"
                variant="text"
            >
                Restore
            </v-btn>
        </div>
        
        <!-- Validation Errors -->
        <v-alert 
            v-if="bean.validationError.value"
            type="error"
            class="mt-4"
        >
            {{ bean.validationError.value }}
        </v-alert>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { useBean } from '@/composables/useBean'
import { useRouter, useRoute } from 'vue-router'
import { useModulesStore } from '@/store/modules'

const router = useRouter()
const route = useRoute()
const modulesStore = useModulesStore()

const module = route.params.module as string
const id = route.params.id as string

const bean = useBean(module, id)
const showAddContact = ref(false)
let contactsLink = null

const moduleName = computed(() => 
    modulesStore.modules[module]?.label || module
)

const formFields = computed(() => {
    // Get fields from module metadata
    return Object.values(bean.fieldDefs.value).filter(f => 
        !['id', 'date_entered', 'date_modified'].includes(f.name)
    )
})

onMounted(async () => {
    await bean.init()
    
    // Pre-fill from query params
    if (route.query) {
        bean.setAttributesFromQuery(route.query)
    }
    
    // Load relationships for existing records
    if (!bean.isNew.value) {
        contactsLink = bean.loadRelationship('contacts')
        if (contactsLink) {
            await contactsLink.fetchRelatedRecords()
        }
    }
})

async function handleSave() {
    const result = await bean.save()
    
    if (result) {
        // Success - bean.save() auto-redirects for new records
        if (!bean.isNew.value) {
            router.push({
                name: 'record',
                params: { module, id: bean.id }
            })
        }
    }
}

function handleCancel() {
    router.back()
}

function handleRestore() {
    bean.restore()
}
</script>

<style scoped>
.edit-view {
    padding: 20px;
}

.actions {
    display: flex;
    gap: 12px;
}
</style>
```

## Validation

### Field-Level Validation

Fields are validated based on:
- `required` flag
- Field type (email, phone, etc.)
- Custom validators

```typescript
// Check if valid
if (bean.isValid.value) {
    await bean.save()
}

// Get field errors
console.log(bean.errorMessages.value)
// {
//   first_name: 'ERR_FIELD_REQUIRED',
//   email: 'ERR_INVALID_EMAIL'
// }
```

### Required Fields

```vue
<Field 
    v-model="bean.attributes.value.email"
    :defs="{ type: 'email', name: 'email', required: true }"
    :view="'edit'"
    :required="true"
    :isDirty="bean.dirtyFields.value.has('email')"
    :errorMessage="bean.errorMessages.value.email"
/>
```

**Validation happens when:**
- Field is marked dirty
- Save is attempted (`bean.isDirty` = true)
- Field value changes

### Server-Side Validation

```typescript
const result = await bean.save()

if (!result) {
    // Server validation failed
    console.error(bean.validationError.value)
}
```

## Business Logic

### Logic Rules

MintHCM supports dynamic field behavior based on business rules:

```typescript
// Logic is automatically loaded with bean
await bean.init()

// Access logic rules
console.log(bean.logic.rules.value)

// Hidden fields (computed by logic)
console.log(bean.logic.hiddenFields.value)

// Readonly fields
console.log(bean.logic.readonlyFields.value)

// Required fields
console.log(bean.logic.requiredFields.value)

// Trigger fields (cause logic re-evaluation)
console.log(bean.logic.triggerFields.value)
```

### Trigger Fields

When certain fields change, business logic is re-evaluated:

```typescript
// Update a trigger field
bean.updateFields({ status: 'Closed' })

// Logic is automatically fetched and applied
// Hidden/readonly/required fields may change
```

## File Uploads

### Uploading Files

```vue
<template>
    <Field 
        v-model="bean.attributes.value.attachment"
        :defs="{ type: 'file', name: 'attachment' }"
        :view="'edit'"
        label="Attachment"
    />
</template>

<script setup lang="ts">
// File is automatically handled by Field component
// When user selects file, it's stored in bean.filesToSave

// On save, files are:
// 1. Read as base64
// 2. Sent with record_data
// 3. Processed by backend
</script>
```

**Supported:**
- Single file upload
- Multiple files (for file fields)
- Images with preview
- Drag & drop

## Best Practices

### ✅ DO

- **Always call `init()`** before using the bean
- **Use `updateFields()`** for multiple field updates
- **Check `isValid`** before saving
- **Handle save errors** with try/catch or result checking
- **Use `restore()`** for cancel functionality
- **Load relationships** only when needed
- **Watch `isRetrieving`/`isSaving`** for loading states

### ❌ DON'T

- **Modify `syncAttributes`** directly (read-only)
- **Skip validation** checking
- **Ignore error messages**
- **Update `attributes.value` directly** (use `updateFields`)
- **Call `save()` multiple times** in parallel

## Troubleshooting

### Bean Not Loading

**Check:**
1. Module name is correct
2. Record ID exists
3. User has access permission
4. Backend API is running

### Save Fails

**Check:**
1. `isValid` is true
2. Required fields are filled
3. No `errorMessages`
4. Backend validation passes
5. Network connection

### Relationships Not Working

**Check:**
1. Relationship name is correct
2. Link exists in field definitions
3. `loadRelationship()` returns non-null
4. Called `fetchRelatedRecords()`

### Changes Not Triggering Logic

**Check:**
1. Field is in `triggerFields`
2. Using `updateFields()` not direct assignment
3. Logic rules are defined in backend

## Bean Actions

Bean Actions are operations that can be performed on a record (edit, delete, duplicate, audit, etc.). These actions are defined in the backend metadata and rendered in the UI as menu items.

### Configuring Bean Actions

Actions are configured in the module's `recordviewdefs.php` file:

```php
// legacy/modules/Meetings/metadata/recordviewdefs.php
$viewdefs['Meetings'] = [
    'panels' => [
        'basicInfo' => [
            'component' => 'MintPanelRecordDetails',
            'data' => [
                'actions' => [
                    'Audit',          // Simple action (string)
                    'Delete',
                    [
                        'name' => 'Duplicate',           // Action with options (array)
                        'skipFields' => ['date_start', 'date_end'],  // Custom options
                    ],
                ],
                'sections' => [ /* ... */ ],
            ],
        ],
    ],
];
```

### Action Types

**String format** - Simple action:
```php
'actions' => [
    'Edit',
    'Delete',
    'Audit',
]
```

**Array format** - Action with options:
```php
'actions' => [
    [
        'name' => 'Duplicate',
        'skipFields' => ['date_start', 'date_end', 'status'],
    ],
]
```

### Duplicate Action with skipFields

The `Duplicate` action supports a special `skipFields` option to exclude specific fields when copying a record:

```php
'actions' => [
    [
        'name' => 'Duplicate',
        'skipFields' => ['date_start', 'date_end'],
    ],
]
```

When the user clicks "Duplicate", they'll be redirected to the EditView with:
- All field values copied from the original record
- Fields listed in `skipFields` will **not** be copied
- System fields (id, date_entered, etc.) are always excluded

**Example Use Cases:**

- **Meetings**: Skip `date_start`, `date_end` so user sets new meeting time
- **Projects**: Skip `status` to always start new projects as "Draft"
- **Contracts**: Skip `date_signed`, `signed_by` for new contract from template

### Available Bean Actions

Common built-in actions:

- `Edit` - Switch to edit mode
- `Delete` - Delete the record
- `Duplicate` - Create a copy (supports `skipFields` option)
- `Audit` - View audit trail
- `Export` - Export record data
- `ConvertToEmployee` - Convert candidate to employee (Candidates module)

### How Actions Work

1. **Backend defines actions** in `recordviewdefs.php`
2. **Frontend loads metadata** when viewing record
3. **MintPanelRecordDetails** renders action menu
4. **BeanAction classes** handle execution when clicked
5. **Options are passed** to action constructor

**Frontend Flow:**

```typescript
// In MintPanelRecordDetails.vue
const actions = computed<MenuListItem[]>(() => {
    const actions: MenuListItem[] = []

    props.data.actions?.forEach((action) => {
        const actionName = typeof action === 'string' ? action : action.name
        const actionClass = BeanActions[actionName]
        
        if (typeof actionClass !== 'function') {
            console.warn(`Action ${actionName} not defined in BeanActions`)
            return
        }
        
        // Pass options to action constructor
        const optionDefs = typeof action === 'string' ? {} : action
        const actionObject = new actionClass(store.bean, optionDefs)
        
        if (actionObject.isAvailable()) {
            actions.push(actionObject.toMenuListItem())
        }
    })
    return actions
})
```

### Creating Custom Bean Actions

See [Customization Guide](./11-customization.md) for how to create custom actions in the `custom/` directory.

## Next Steps

- Learn about [Field System](./09-fields.md) for form fields
- Explore [API Communication](./07-api-communication.md) for backend integration
- See [Customization Guide](./11-customization.md) for extending functionality

---

**Master beans, build powerful forms!** 📝

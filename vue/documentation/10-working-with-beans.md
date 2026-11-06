# Working with Beans (Records)

This guide explains how to work with beans (records) in MintHCM frontend using the `useBean` and `useLink` composables.

## Overview

**Beans** are the core data objects in MintHCM representing database records (employees, candidates, meetings, etc.).

The frontend provides two main composables for bean management:

- **`useBean`** - Manage single record data (CRUD operations)
- **`useLink`** - Manage relationships between records

## useBean Composable

`useBean(module, id, fetch_links = [])` (`src/composables/useBean.ts`) is the primary interface for working with individual records. `RecordView` uses it through `RecordViewStore`; every list row is a bean as well.

### How a Bean Holds Values

A bean keeps values in three places:

| Property | Content |
|----------|---------|
| `attributes` | Raw values from the last load (ref object) |
| `syncAttributes` | Copy of the last saved/loaded values, used by detail views (ref object) |
| `fields` | One **`MintField`** per attribute (`useField`, see [Field System](./09-fields.md#usefield-mintfield)): `model`, `isDirty`, `validate()`, `formatted.user` / `formatted.server` |

**Edits go through `fields`.** Edit components write to `field.model`, `updateFields()` sets `field.model`, and `save()` sends `fields[*].formatted.server`. Writing to `attributes.value` directly is **not** saved.

### Basic Usage

```typescript
import { useBean } from '@/composables/useBean'

// Load existing record (POST api/Employees/Get/<id>)
const bean = useBean('Employees', '123-456-789')
await bean.init()

// Access data
console.log(bean.attributes.value.first_name)
console.log(bean.fields.value.first_name.model)
console.log(bean.name.value)  // Computed display name

// Change a value (marks the field dirty, triggers logic for trigger fields)
bean.updateFields({ first_name: 'John' })

// Create new record (no ID; POST api/Employees/Get returns defaults)
const newBean = useBean('Employees', '')
await newBean.init()
```

`useBean` returns a plain object of refs, so use `.value` both in `<script setup>` and in templates (when the bean is stored in a Pinia store, as in `RecordViewStore`, the refs are unwrapped and `.value` is not used).

### Creating a New Record

**Example:** Create a new candidate

```vue
<template>
    <div>
        <h1>Create New Candidate</h1>

        <v-form v-if="!bean.isRetrieving.value">
            <template v-for="name in formFields" :key="name">
                <Field
                    v-if="bean.fields.value[name]"
                    :defs="bean.fieldDefs.value[name]"
                    :view="'edit'"
                    :label="languages.label(bean.fieldDefs.value[name].label, 'Candidates')"
                    :field="bean.fields.value[name]"
                    :required="bean.logic.requiredFields.value.includes(name)"
                    :isDirty="bean.isDirty.value || bean.fields.value[name].isDirty"
                    :errorMessage="bean.errorMessages.value[name]"
                    @update:modelValue="(value) => bean.updateFields({ [name]: value })"
                />
            </template>

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
import { useLanguagesStore } from '@/store/languages'
import Field from '@/components/Fields/Field.vue'

const languages = useLanguagesStore()

// Create new bean (no ID = new record)
const bean = useBean('Candidates', '')

const formFields = ['first_name', 'last_name', 'phone_mobile']

onMounted(async () => {
    // Load an empty record with default values
    await bean.init()

    // Set default values
    bean.updateFields({
        last_name: 'Doe',
    })
})

async function handleSave() {
    const result = await bean.save()

    if (result?.status === false) {
        // result.error: 'validation_failed' | 'duplicate_detected' | 'save_request_failed'
        console.error('Save failed:', result.error, bean.validationError.value)
        return
    }
    // Success: for a new record useBean already replaced the route with the saved record
    console.log('Candidate created:', result.data.id)
}
</script>
```

### Editing Existing Record

**Example:** Edit candidate details

```vue
<template>
    <div v-if="bean.isRetrieving.value">
        Loading...
    </div>

    <div v-else>
        <h1>Edit {{ bean.name.value }}</h1>

        <v-form>
            <template v-for="name in ['first_name', 'last_name', 'phone_mobile']" :key="name">
                <Field
                    v-if="bean.fields.value[name]"
                    :defs="bean.fieldDefs.value[name]"
                    :view="'edit'"
                    :label="languages.label(bean.fieldDefs.value[name].label, 'Candidates')"
                    :field="bean.fields.value[name]"
                    :required="bean.logic.requiredFields.value.includes(name)"
                    :isDirty="bean.isDirty.value || bean.fields.value[name].isDirty"
                    :errorMessage="bean.errorMessages.value[name]"
                    @update:modelValue="(value) => bean.updateFields({ [name]: value })"
                />
            </template>

            <div class="actions">
                <v-btn
                    @click="handleSave"
                    :disabled="!bean.isValid.value"
                    :loading="bean.isSaving.value"
                    color="primary"
                >
                    Save Changes
                </v-btn>

                <v-btn
                    @click="handleRestore"
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
import { useLanguagesStore } from '@/store/languages'
import Field from '@/components/Fields/Field.vue'

const route = useRoute()
const languages = useLanguagesStore()

// Load existing bean
const bean = useBean('Candidates', route.params.id as string)

onMounted(async () => {
    await bean.init()
})

async function handleSave() {
    const result = await bean.save()

    if (result?.status !== false) {
        // Success: useBean re-fetched the record (retrieve()) after the update
        console.log('Candidate updated successfully')
    }
}

function handleRestore() {
    // Revert changes
    bean.restore()
}
</script>
```

### Duplicating a Record

**Example:** Create a copy of existing record (this is what `RecordView` does for `?copy_id=`)

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

    const originalId = route.query.copy_id as string
    if (originalId) {
        // excludedFields arrives as a JSON string (set by the Duplicate action)
        let excludedFields: string[] = []
        if (typeof route.query.excludedFields === 'string') {
            try {
                const parsed = JSON.parse(route.query.excludedFields)
                excludedFields = Array.isArray(parsed) ? parsed : []
            } catch (e) {
                excludedFields = []
            }
        }
        await bean.setAttributesFromBeanId(originalId, excludedFields)
    }
})

async function handleSave() {
    await bean.save()
    // New record will be created with copied data
}
</script>
```

**What gets copied:**
- ✅ All non-empty field values (except excluded fields)
- ❌ `id`, `date_entered`, `date_modified`, `date_indexed`
- ❌ `created_by`, `created_by_name`, `modified_user_id`, `modified_by_name`
- ❌ `repeat*` fields (recurrence)
- ❌ `file` and `image` fields
- ❌ Any fields passed in `excludedFields`

## useBean API Reference

### Properties

```typescript
const bean = useBean('ModuleName', 'record-id')

// State
bean.id                    // Record ID (string)
bean.module                // Module name (string)
bean.isNew                 // No ID, or attributes.new_with_id (computed boolean)
bean.isRetrieving          // Loading state (ref boolean)
bean.isSaving              // Saving state (ref boolean)
bean.isDirty               // Set to true by save() (so required errors show for untouched fields); reset by restore()
bean.isChanged             // Some dirty field differs from syncAttributes (computed boolean)
bean.isValid               // Required fields (once dirty / after a save attempt) filled and no errorMessages (computed boolean)

// Data
bean.attributes            // Values from the last load (ref object)
bean.syncAttributes        // Saved field values (ref object)
bean.fields                // MintField per attribute (ref object) - edit through these
bean.name                  // Display name (computed string)
bean.dirtyFields           // Names of fields whose MintField is dirty (computed string[])
bean.aclAccess             // Access permissions, e.g. { edit: true, delete: false } (ref object)

// Validation
bean.validationError       // Server validation error (ref string)
bean.errorMessages         // Field-specific errors { field: labelKey } (computed object)

// Duplicates (HTTP 409 on save)
bean.duplicateDetected     // ref boolean
bean.duplicateCount        // ref number
bean.duplicatedRecords     // ref array
bean.clearDuplicateState() // reset the three above

// Metadata
bean.fieldDefs             // Module vardefs (computed object)
bean.logic                 // useLogic(): rules, triggerFields, requiredFields, readonlyFields, hiddenFields, errorMessages, fieldsOptions
bean.originalId            // Source record ID after setAttributesFromBeanId (ref string)
```

### Methods

#### `init()`

Initialize the bean - fetches the record (`POST api/{module}/Get/{id}`), or defaults for a new record.

```typescript
await bean.init()
```

**Returns:** `Promise` of the axios response (same as `retrieve()`)

#### `retrieve()`

Fetch latest data from server. Rejects with `{ response: { status: 408 } }` after a 30 s timeout.

```typescript
await bean.retrieve()
```

**Use case:** Refresh data after external changes

#### `abort()`

Cancels any in-flight `retrieve()`/`fetchLogic()` request for this bean instance and rearms the internal `AbortController` for subsequent calls.

```typescript
bean.abort()
```

**Use case:** Call before discarding a bean instance (e.g. navigating to a different record) so a late server response can't apply to a bean the UI has already replaced. `RecordViewStore.resetBean()` calls this on the outgoing bean before creating the new one.

#### `updateFields(fields)`

Update one or more fields at once.

```typescript
bean.updateFields({
    first_name: 'John',
    last_name: 'Doe',
})
```

**Parameters:**
- `fields` - Object with field names as keys and new values. A `File` value is queued for upload and the field gets the file name.

**Note:** Only fields that already exist in `bean.fields` are updated. Setting `field.model` marks the field dirty; changes to logic trigger fields re-run backend logic (debounced).

#### `save(force = false, options?)`

Save changes to the server.

```typescript
const result = await bean.save()

if (result?.status === false) {
    console.error('Save failed:', result.error, result.errorMessages, bean.validationError.value)
} else {
    console.log('Saved', result.data.id)
}
```

**Parameters:**
- `force` - sent as `force_save` (save despite detected duplicates)
- `options.editCycles` / `options.onCyclicComplete` - cyclic (repeated) records progress

**Returns:** the axios response on success, otherwise an object `{ status: false, error, errorMessages?, validationError? }` where `error` is `'validation_failed'`, `'duplicate_detected'` (HTTP 409; see `duplicate*` properties) or `'save_request_failed'`. It never returns `false`, so check `result.status === false`, not `!result`.

**Process:**
1. Sets `isDirty` and checks `isValid` (required fields, `errorMessages`)
2. Reads queued files as data URLs
3. Sends `PATCH api/{module}/Update/{id}` with `record_data` (`fields[*].formatted.server`), `files`, `links` (relationship changes) and `force_save`
4. New record: `router.replace()` to the saved record's DetailView
5. Existing record: `retrieve()` to refresh data
6. Starts cyclic-records progress when requested or reported by the backend

#### `restore()`

Revert all unsaved changes.

```typescript
bean.restore()
```

Restores `attributes` from `syncAttributes` (last saved state), rebuilds `fields`, clears queued files, `isDirty` and `validationError`.

#### `markDeleted()`

Delete the record.

```typescript
await bean.markDeleted()
```

Sends `DELETE api/{module}/{id}`. **Returns:** `Promise<Response>`

#### `setData(data)`

Set bean data from API response.

```typescript
bean.setData({
    attributes: { ... },
    acl_access: { ... },
    logic: { rules: [ ... ] }
})
```

**Use case:** Manually set data without API call (used for subpanel and list rows)

#### `setAttributesFromQuery(query)`

Populate fields from URL query parameters (only keys that exist in `fieldDefs`; date values are parsed).

```typescript
// URL: #/modules/Candidates/EditView?first_name=John&return_relationship=...&return_id=...

onMounted(async () => {
    await bean.init()
    bean.setAttributesFromQuery(route.query)
    // Sets first_name = 'John'; return_relationship + return_id link the new record
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

Get the `useLink` instance for a link field (or for a relationship name, resolved to its link field).

```typescript
const link = bean.loadRelationship('candidatures')
if (link) {
    await link.fetchRelatedRecords('candidatures') // subpanel key
}
```

**Returns:** `useLink` instance or `null` (not a `link` field)

#### `createFakeLink(name)`

Returns a placeholder link (`isFake = true`) whose `add`/`remove`/`unlink` do nothing. `RecordViewStore` uses it for subpanels whose `get_subpanel_data` is a `function:` (no link field), so they can still be fetched and paginated.

## useLink Composable

`useLink` manages relationships between records (one-to-many, many-to-many). Get instances through `bean.loadRelationship()`; changes are saved with `bean.save()`.

### Basic Usage

```typescript
// Load relationship
const link = bean.loadRelationship('candidatures')

// Fetch related records (as shown in the subpanel with that key)
await link.fetchRelatedRecords('candidatures')

// Access related beans (Map<id, bean>) or as an array
console.log(link.beans.value)
console.log(link.beansArray.value)

// Queue adding a relationship
link.add('related-id-123')

// Queue removing a relationship
link.remove('related-id-456')

// Get changes to save
const changes = link.getChanges()
```

### Adding Related Records

**Example:** Manage related records of a record

```vue
<template>
    <div>
        <h2>Candidatures</h2>

        <v-list v-if="relatedLink">
            <v-list-item
                v-for="related in relatedLink.beansArray.value"
                :key="related.id"
            >
                <v-list-item-title>{{ related.name.value }}</v-list-item-title>
                <template #append>
                    <v-btn
                        icon
                        size="small"
                        @click="relatedLink.remove(related.id)"
                    >
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </template>
            </v-list-item>
        </v-list>

        <v-btn @click="handleSave">Save</v-btn>
    </div>
</template>

<script setup lang="ts">
import { shallowRef, onMounted } from 'vue'
import { useBean } from '@/composables/useBean'

const bean = useBean('Candidates', 'candidate-id')
const relatedLink = shallowRef<ReturnType<typeof bean.loadRelationship>>(null)

onMounted(async () => {
    await bean.init()

    // Load relationship
    relatedLink.value = bean.loadRelationship('candidatures')
    await relatedLink.value?.fetchRelatedRecords('candidatures')
})

function handleAdd(relatedId: string) {
    relatedLink.value?.add(relatedId)
}

async function handleSave() {
    // Relationship changes are sent with bean.save() (links: getChanges())
    await bean.save()
}
</script>
```

### Managing Relationships with Additional Data

Some relationships have additional fields (e.g., role in many-to-many):

```typescript
// Add a related record with additional data
link.add('related-id', {
    role: 'Decision Maker',
})

// Changes include additional values
const changes = link.getChanges()
// {
//   beansToAdd: {
//     'related-id': {
//       id: 'related-id',
//       additionalValues: { role: 'Decision Maker' }
//     }
//   },
//   beansToRemove: []
// }
```

## useLink API Reference

### Properties

```typescript
const link = bean.loadRelationship('candidatures')

link.link                  // Link field name (string)
link.relationshipName      // Relationship name (string)
link.beans                 // Map of related beans (ref Map<string, bean>)
link.beansArray            // Same beans as an array (computed)
link.total                 // Total count from the last fetch (ref number)
link.currentPage           // Page from the last fetch (ref number)
link.isFake                // Placeholder link (ref boolean)
link.relateFieldName       // Related field name (computed string | null)
link.idFieldName           // ID field name (computed string | null)
link.parentFieldName       // Parent field name (computed string | null)
link.parentIdFieldName     // Parent ID field name (computed string | null)
link.parentTypeFieldName   // Parent type field name (computed string | null)
```

### Methods

#### `add(id, additionalValues?)`

Queue adding a record to the relationship (applied on `bean.save()`).

```typescript
link.add('related-id')

// With additional data
link.add('related-id', {
    role: 'Manager',
})
```

#### `remove(id)`

Queue removing a record from the relationship (applied on `bean.save()`).

```typescript
link.remove('related-id')
```

#### `unlink(parentBean, linkName)`

Immediately unlinks the queued removals: `POST api/{module}/Unlink/{id}` with `{ ids, link_name }`.

#### `fetchRelatedRecords(subpanelKey, paginateBy = -1, page = 0, sortBy = '', sortOrder = '', signal?)`

Fetch related records from server through the subpanel API (`subpanelKey` is the subpanel key from the module metadata).

```typescript
await link.fetchRelatedRecords('candidatures')

// Access fetched records
link.beans.value.forEach((related, id) => {
    console.log(id, related.attributes.value)
})
```

#### `hydrateFromBackend(payload)`

Builds `beans` from records already delivered by the backend (no request).

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
                    v-for="name in formFields"
                    :key="name"
                    :defs="bean.fieldDefs.value[name]"
                    :view="'edit'"
                    :label="languages.label(bean.fieldDefs.value[name].label, module)"
                    :field="bean.fields.value[name]"
                    :required="bean.logic.requiredFields.value.includes(name)"
                    :isDirty="bean.isDirty.value || bean.fields.value[name].isDirty"
                    :errorMessage="bean.errorMessages.value[name]"
                    @update:modelValue="(value) => bean.updateFields({ [name]: value })"
                />
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
import { onMounted, computed } from 'vue'
import { useBean } from '@/composables/useBean'
import { useRouter, useRoute } from 'vue-router'
import { useModulesStore } from '@/store/modules'
import { useLanguagesStore } from '@/store/languages'
import Field from '@/components/Fields/Field.vue'
import LoadingScreen from '@/components/LoadingScreen.vue'

const router = useRouter()
const route = useRoute()
const modulesStore = useModulesStore()
const languages = useLanguagesStore()

const module = route.params.module as string
const id = (route.params.id as string) ?? ''

const bean = useBean(module, id)

const moduleName = computed(() =>
    modulesStore.modules[module]?.label || module
)

const formFields = computed(() =>
    // Fields present on the bean, without system fields
    Object.keys(bean.fields.value).filter((name) =>
        bean.fieldDefs.value[name] && !['id', 'date_entered', 'date_modified'].includes(name)
    )
)

onMounted(async () => {
    await bean.init()

    // Pre-fill from query params
    bean.setAttributesFromQuery(route.query)
})

async function handleSave() {
    const result = await bean.save()
    if (result?.status === false) {
        return
    }
    // New records: useBean already navigated to the saved record.
    // Existing records: useBean re-fetched the data.
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

In the application this is done by `RecordView` + `RecordViewStore` driven by [Record View Definitions](./12-recordviewdefs.md); write your own view only for special cases.

## Validation

### Field-Level Validation

Fields are validated based on:
- required fields from backend logic (`bean.logic.requiredFields`)
- the type validator in `Fields/{type}/{type}.ts`
- error messages from backend logic (`bean.logic.errorMessages`)

```typescript
// Check if valid
if (bean.isValid.value) {
    await bean.save()
}

// Get field errors (label keys)
console.log(bean.errorMessages.value)
// {
//   last_name: 'LBL_SOME_ERROR_LABEL'
// }
```

`errorMessages` covers the fields placed in the record form (`MintPanelRecordDetails` sections) that are not hidden or readonly.

### Required Fields

```vue
<Field
    :defs="bean.fieldDefs.value.last_name"
    :view="'edit'"
    :label="label"
    :field="bean.fields.value.last_name"
    :required="bean.logic.requiredFields.value.includes('last_name')"
    :isDirty="bean.isDirty.value || bean.fields.value.last_name.isDirty"
    :errorMessage="bean.errorMessages.value.last_name"
    @update:modelValue="(value) => bean.updateFields({ last_name: value })"
/>
```

**The required error is shown when:**
- the field has been changed (`field.isDirty`), or
- a save was attempted (`bean.isDirty` = true)

### Server-Side Validation

```typescript
const result = await bean.save()

if (result?.status === false && result.error === 'save_request_failed') {
    // Server validation failed (response with isValid: false)
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

// Options overrides per field, error messages
console.log(bean.logic.fieldsOptions.value, bean.logic.errorMessages.value)
```

### Trigger Fields

When certain fields change, business logic is re-evaluated (`POST api/{module}/Logic/{id}`):

```typescript
// Update a trigger field
bean.updateFields({ status: 'Closed' })

// Logic is automatically fetched and applied (debounced)
// Hidden/readonly/required fields may change
```

## File Uploads

### Uploading Files

```vue
<template>
    <Field
        :defs="bean.fieldDefs.value.filename"
        :view="'edit'"
        label="Attachment"
        :field="bean.fields.value.filename"
        @update:modelValue="(file) => bean.updateFields({ filename: file })"
    />
</template>

<script setup lang="ts">
// When the user selects a file, updateFields() queues the File inside the bean
// and sets the field value to the file name.

// On save, files are:
// 1. Read as data URLs (base64)
// 2. Sent as `files` next to `record_data`
// 3. Processed by backend
</script>
```

**Supported:**
- One file per `file` / `image` field
- Images with preview

## Best Practices

### ✅ DO

- **Always call `init()`** before using the bean
- **Edit through `fields` / `updateFields()`**, never through `attributes`
- **Pass `:field="bean.fields.value[name]"`** to `<Field>` in edit view
- **Check `isValid`** before saving
- **Check `result.status === false`** after `save()`
- **Use `restore()`** for cancel functionality
- **Load relationships** only when needed
- **Watch `isRetrieving`/`isSaving`** for loading states

### ❌ DON'T

- **Modify `syncAttributes`** directly (read-only)
- **Update `attributes.value` directly** (not saved; use `updateFields`)
- **Skip validation** checking
- **Ignore error messages**
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
4. `result.error` (`validation_failed`, `duplicate_detected`, `save_request_failed`) and `validationError`
5. Network connection

### Changes Not Saved

**Check:**
1. Values were set through `updateFields()` or `field.model`, not `attributes`
2. The field exists in `bean.fields`

### Relationships Not Working

**Check:**
1. Name is a `link` field (or its relationship name) in the module vardefs
2. `loadRelationship()` returns non-null
3. `fetchRelatedRecords()` is called with the subpanel key

### Changes Not Triggering Logic

**Check:**
1. Field is in `triggerFields`
2. Using `updateFields()` not direct assignment to `attributes`
3. Logic rules are defined in backend

## Bean Actions

Bean Actions are operations that can be performed on a record (delete, duplicate, audit, etc.). They are declared in the backend metadata and rendered in the record view as menu items.

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
                        'name' => 'DuplicateMeetings',        // Action with options (array)
                        'skipFields' => ['repeat', 'status'], // Custom options
                    ],
                ],
                'sections' => [ /* ... */ ],
            ],
        ],
    ],
];
```

**Default actions:** the API merges the default actions `Audit`, `Delete` and `Duplicate` (`MetaController::DEFAULT_ACTIONS`) in front of the declared ones; a declaration with the same name replaces the default in place. Set `'includeDefaultActions' => false` in the panel `data` to list the menu explicitly. See [Record View Definitions](./12-recordviewdefs.md) and [Customization Guide](./11-customization.md#custom-business-logic).

### Action Types

**String format** - Named action:
```php
'actions' => [
    'Delete',
    'Audit',
]
```

**Array format** - Named action with options:
```php
'actions' => [
    [
        'name' => 'Duplicate',
        'skipFields' => ['date_start', 'date_end', 'status'],
    ],
]
```

**Generic API action** - an array with `api_route` (identified by `key`) is handled by `GenericApiAction` without any TypeScript; see [Record View Definitions](./12-recordviewdefs.md).

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
- Fields listed in `skipFields` will **not** be copied (passed as a JSON string in `excludedFields`)
- System fields (id, date_entered, etc.) are always excluded

**Example Use Cases:**

- **Meetings**: Skip `date_start`, `date_end` so user sets new meeting time
- **Work schedules**: Skip `repeat` so the copy is a single record

### Available Bean Actions

Named actions in `src/business/BeanActions/Actions/`:

- `Audit` - View audit trail
- `Delete` - Delete the record
- `Duplicate` - Create a copy (supports `skipFields` option)
- `DuplicateMeetings` - Duplicate for Meetings
- `ConvertToEmployee` - Convert candidate to employee (Candidatures module)
- `RejectAndMoveToAnotherRecruitment` - Candidatures
- `AcceptWorkSchedule`, `UndoAcceptWorkSchedule`, `CloseWorkSchedule` - work schedules
- `PublishNews`, `ArchiveNews` - News

Editing is not an action: the record view has its own edit mode.

### How Actions Work

1. **Backend defines actions** in `recordviewdefs.php` (merged with the defaults)
2. **Frontend loads metadata** when viewing record
3. **MintPanelRecordDetails** renders action menu
4. **BeanAction classes** (or `GenericApiAction`) handle execution when clicked
5. **Options are passed** to action constructor

**Frontend Flow:**

```typescript
// In MintPanelRecordDetails.vue
const actions = computed<MenuListItem[]>(() => {
    const actions: MenuListItem[] = []

    props.data.actions?.forEach((action) => {
        const isGeneric = typeof action === 'object' && action !== null && 'api_route' in action
        const actionName = typeof action === 'string' ? action : action.name
        const actionClass = isGeneric ? GenericApiAction : BeanActions[actionName]

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

See [Customization Guide](./11-customization.md#custom-business-logic) for how to create custom actions in the `custom/` directory.

## Next Steps

- Learn about [Field System](./09-fields.md) for form fields
- Read [Record View Definitions](./12-recordviewdefs.md) for record view metadata
- See [Customization Guide](./11-customization.md) for extending functionality

---

**Master beans, build powerful forms!** 📝

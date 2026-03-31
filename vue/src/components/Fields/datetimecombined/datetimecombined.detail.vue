<template>
    <div>
        <label>{{ props.label }}</label>
        <div class="detail-field-row">
            <div class="content-wrapper">
                {{ parsedDate + ' ' + (parsedDate != '' ? languages.label('LBL_BY') : '') }}
                <router-link v-if="hasViewAccess" :to="recordUrl" class="relate-field">
                    {{ nameField }}
                </router-link>
                <span v-else>
                    {{ nameField }}
                </span>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useLanguagesStore } from '@/store/languages';
import { useACL } from '@/composables/useACL';
import { FieldProps } from '../Field.model';

const props = defineProps<FieldProps>()
const languages = useLanguagesStore()

const module = computed(() => props.defs?.module || 'Employees')

const idField = computed(() => {
    let id_field = ''
    if (props.defs?.name == 'date_entered') {
        id_field = 'created_by'
    } else if (props.defs?.name == 'date_modified') {
        id_field = 'modified_user_id'
    }
    return id_field
})

const nameField = computed(() => {
    let name_field = ''
    if (props.defs?.name == 'date_entered') {
        name_field = 'created_by_name'
    } else if (props.defs?.name == 'date_modified') {
        name_field = 'modified_by_name'
    }

    let name_value = ''
    if (name_field !== '') {
        name_value = props.data?.bean?.syncAttributes?.[name_field]
    }
    return name_value
})

const recordUrl = computed(() => {
    const id = props.data?.bean?.attributes?.[idField.value]
    if (!module.value || !id) return ''
    return `/modules/${module.value}/DetailView/${id}`
})

const parsedDate = computed(() => {
    return props.field.model.isValid ? props.field.formatted.user : ''
})

const hasViewAccess = computed<boolean>(() => {
    return useACL().hasAccess(module.value, 'view', true, true)
})
</script>

<style scoped lang="scss">
label {
    font-size: 12px;
    color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
}

.content-wrapper {
    overflow-wrap: break-word;
    word-wrap: break-word;
    word-break: break-word;
    gap: 4px;
    flex-wrap: nowrap;
    display: flex;
}

.relate-field {
    text-decoration: none;
    color: rgb(var(--v-theme-secondary));
    cursor: pointer;
    display: block;
    width: fit-content;
    white-space: nowrap;
    flex-shrink: 0;
}
</style>

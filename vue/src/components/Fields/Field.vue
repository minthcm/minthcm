<template>
    <component
        v-bind="{
            ...$attrs,
            ...( !['fieldset', 'date', 'age', 'datetime', 'datetimecombo', 'relate', 'parent'].includes(props.defs?.type) ? { name: props.defs.name } : {} )
        }"
        :aria-description="comment"
        :aria-describedby="props.defs.name+'-help'"
        :aria-label="label"
        :is="FieldComponent"
        :class="classList"
        :data="data"
        :defs="defs"
        :label="label"
        :options="props.options"
        :state="fieldState"
        :hidePencil="true"
        :field="props.field"
        :modelValue="props.field?.model ?? modelValue"
        :view="view"
    >
    </component>
    <p :id="`${props.defs.name}-help`" hidden>
        {{comment}}
    </p>
    <div v-if="errorMessage" class="field-error-message">{{ errorMessage }}</div>
</template>

<script setup lang="ts">
import { defineAsyncComponent, computed, watch } from 'vue'
import { useModulesStore } from '@/store/modules'
import { fieldConfig } from '../Fields/Field.config'
import { FieldProps, FieldState } from './Field.model'
import { useLanguagesStore } from '@/store/languages'

const props = defineProps<FieldProps>()
const languagesStore = useLanguagesStore()
const modulesStore = useModulesStore()

const label = computed(() => {
    if (props.view !== 'edit' || !props.required) {
        return props.label
    }
    return `${props.label} (${languagesStore.label('LBL_REQUIRED').toLowerCase()})`
})

const comment = computed(() => {
    if(props.defs.type === 'fieldset'){
        return  languagesStore.label(modulesStore.currentModule?.vardefs[props.defs.properties?.fields[0].name]?.comment, modulesStore.currentModule?.name) ? 
        languagesStore.label(modulesStore.currentModule?.vardefs[props.defs.properties?.fields[0].name]?.comment, modulesStore.currentModule?.name)
        : modulesStore.currentModule?.vardefs[props.defs.properties?.fields[0].name]?.comment;
    }
    if(!props.defs?.comment || !modulesStore.currentModule?.name){
        return ''
    }
    return languagesStore.label(props.defs.comment, modulesStore.currentModule?.name)
})

const errorMessage = computed(() => {
    if (props.view !== 'edit') {
        return ''
    }
    if (props.errorMessage) {
        return languagesStore.label(props.errorMessage, modulesStore.currentModule?.name)
    }
    if (props.isDirty && props.required && !props.modelValue) {
        return languagesStore.label('ERR_FIELD_REQUIRED', modulesStore.currentModule?.name)
    }
    return ''
})

const fieldState = computed<FieldState>(() => {
    if (props.view !== 'edit') {
        return 'normal'
    }
    if (errorMessage.value) {
        return 'error'
    }
    if (props.required) {
        return 'required'
    }
    return 'normal'
})

const classList = computed(() => {
    const classList = [`${props.view}-field-container`, `field-state-${fieldState.value}`]
    return classList
})

const resolvedFieldType = computed(() => {
    const type = props.defs?.type?.trim() ?? ''
    if (['date_entered', 'date_modified'].includes(props.defs?.name) && props.view === 'detail') {
        return 'datetimecombined'
    } else if (fieldConfig.allowedTypes[props.view].includes(type)) {
        return type
    } else if (fieldConfig.allowedTypes[props.view].includes(fieldConfig.typeMap[type])) {
        return fieldConfig.typeMap[type]
    }
    console.warn(`Field.vue resolveFieldType: type "${type}" is not defined for view "${props.view}"`)
    return fieldConfig.defaultType
})

let FieldComponent = defineAsyncComponent(() => {
    return import(`@/components/Fields/${resolvedFieldType.value}/${resolvedFieldType.value}.${props.view}.vue`)
})

watch(
    () => props.view,
    () => {
        FieldComponent = defineAsyncComponent(() => {
            return import(`@/components/Fields/${resolvedFieldType.value}/${resolvedFieldType.value}.${props.view}.vue`)
        })
    },
    { immediate: true },
)
</script>

<style lang="scss">
.detail-field-row {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
}
.field-error-message {
    color: rgb(var(--v-theme-error));
    font-size: 12px;
    padding: 4px 8px;
}

.edit-field-container {
    .v-field__outline__start,
    .v-field__outline__notch::before,
    .v-field__outline__notch::after,
    .v-field__outline__end {
        opacity: 1;
    }

    &.field-state-normal {
        .v-field__outline__start,
        .v-field__outline__notch::before,
        .v-field__outline__notch::after,
        .v-field__outline__end {
            border-color: #dbdbdb;
        }

        .v-field-label.v-field-label--floating {
            background: rgb(var(--v-theme-surface));
            color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
            opacity: 1;
            padding: 0px 2px;
        }
    }

    &.field-state-normal,
    &.field-state-required {
        &:hover {
            .v-field__outline__start,
            .v-field__outline__notch::before,
            .v-field__outline__notch::after,
            .v-field__outline__end {
                border-color: rgb(var(--v-theme-primary));
            }
        }

        .v-field--focused .v-field__outline__start,
        .v-field--focused .v-field__outline__notch::before,
        .v-field--focused .v-field__outline__notch::after,
        .v-field--focused .v-field__outline__end {
            border-color: rgb(var(--v-theme-primary));
        }
    }
}
</style>

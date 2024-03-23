<template>
    <v-select
        :label="props.label"
        variant="outlined"
        density="compact"
        hide-details
        v-model="parsedValue"
        :items="languages.getList(props.defs?.options)"
        item-title="value"
        item-value="key"
    ></v-select>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { FieldVardef } from '@/store/modules'
import { useLanguagesStore } from '@/store/languages'

interface Props {
    defs: FieldVardef
    label: string
    modelValue?: any
    data?: any
}

const props = defineProps<Props>()
const languages = useLanguagesStore()
const emit = defineEmits(['update:modelValue'])
const model = ref('')

const parsedValue = computed({
    get() {
        return languages.translateListValue(props.modelValue ?? props.defs?.default ?? '', props.defs?.options)
    },
    set(newValue) {
        model.value = newValue
        emit('update:modelValue', model.value)
    },
})
</script>

<style scoped lang="scss">
.v-input {
    :deep(.v-field__outline__start),
    :deep(.v-field__outline__notch)::before,
    :deep(.v-field__outline__notch)::after,
    :deep(.v-field__outline__end) {
        opacity: 1;
    }

    :deep(.v-field__outline__start),
    :deep(.v-field__outline__notch)::before,
    :deep(.v-field__outline__notch)::after,
    :deep(.v-field__outline__end) {
        border-color: #dbdbdb;
    }

    &:hover {
        :deep(.v-field__outline__start),
        :deep(.v-field__outline__notch)::before,
        :deep(.v-field__outline__notch)::after,
        :deep(.v-field__outline__end) {
            border-color: rgb(var(--v-theme-primary));
        }
    }

    :deep(.v-field--focused .v-field__outline__start),
    :deep(.v-field--focused .v-field__outline__notch)::before,
    :deep(.v-field--focused .v-field__outline__notch)::after,
    :deep(.v-field--focused .v-field__outline__end) {
        border-color: rgb(var(--v-theme-primary));
    }

    :deep(.v-field-label.v-field-label--floating) {
        background: rgb(var(--v-theme-surface));
        color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
        opacity: 1;
        padding: 0px 2px;
    }
}
</style>

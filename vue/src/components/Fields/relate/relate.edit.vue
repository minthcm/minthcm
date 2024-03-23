<template>
    <v-autocomplete
        :items="items"
        :label="props.label"
        variant="outlined"
        density="compact"
        hide-details
        v-model="model"
        item-value="id"
        item-title="name"
    />
</template>

<script setup lang="ts">
import { computed, ref, onMounted } from 'vue'
import axios from 'axios'
import { FieldVardef } from '@/store/modules'

interface Props {
    defs: FieldVardef
    label: string
    modelValue?: any
    data?: any
}

const props = defineProps<Props>()
const emit = defineEmits(['update:modelValue'])

const items = ref(props.data.bean[props.defs.id_name] ? [
    {
        id: props.data.bean[props.defs.id_name],
        name: props.modelValue,
    },
] : [])

onMounted(() => {
    fetchItems()
})

const id = ref(props.data.bean[props.defs.id_name])
const model = computed({
    get() {
        return id.value
    },
    set(newVal) {
        props.data.bean[props.defs.id_name] = newVal
        id.value = newVal
        emit('update:modelValue', [props.defs.id_name])
    },
})

async function fetchItems() {
    const response = await axios.post(`api/${props.defs.module}`, {
        offset: 0,
    })
    if (response.data?.results?.length) {
        items.value = response.data.results.sort((a, b) => a.name.localeCompare(b.name, 'pl'))
    }
}
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

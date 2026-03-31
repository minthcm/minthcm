<template>
    <v-select
        v-if="props.defs?.name == 'currency_id'"
        :label="props.label"
        variant="outlined"
        density="compact"
        hide-details
        :error="props.state === 'error'"
        v-model="parsedValue"
        :items="items"
        item-title="value"
        item-value="key"
        @keyup.enter="$emit('inlineEditSave')"
        @keyup.esc="$emit('inlineEditCancel')"
    >
    </v-select>
    <v-text-field
        v-else
        :label="props.label"
        variant="outlined"
        density="compact"
        hide-details
        v-model="props.field.model"
        :error="props.state === 'error'"
        @keyup.enter="$emit('inlineEditSave')"
        @keyup.esc="$emit('inlineEditCancel')"
    />
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { useBackendStore } from '@/store/backend'
import { FieldProps } from '../Field.model'

const props = defineProps<FieldProps>()
const backend = useBackendStore()
const emit = defineEmits(['update:modelValue'])
const model = ref('')

const parsedValue = computed({
    get() {
        return items.value.find((item) => item.key === props.field.model)?.key || ''
    },
    set(newValue) {
        props.field.model = newValue
        model.value = newValue
        emit('update:modelValue', model.value)
    },
})

const items = computed(() => {
    if (props.defs?.name != 'currency_id') {
        return []
    }
    const currencies = backend?.initData?.global.currencies || []
    if (!Array.isArray(currencies) && typeof currencies === 'object') {
        return Object.entries(currencies).map(([key, value]) => ({
            key: key,
            value: value.name,
        }))
    }
    return currencies
})

watch(items, () => {
    if (!items.value.find((item) => item.key === model.value)) {
        const newItem = items.value.find((item) => !item.key) || items.value[0]
        model.value = newItem?.key || ''
        emit('update:modelValue', model.value)
    }
})
</script>

<style scoped lang="scss"></style>

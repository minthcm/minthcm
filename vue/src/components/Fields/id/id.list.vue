<template>
    <span>{{ parsedValue }}</span>
</template>

<script setup lang="ts">
import { useBackendStore } from '@/store/backend'
import { computed } from 'vue'
import { FieldProps } from '../Field.model'

const props = defineProps<FieldProps>()
const backend = useBackendStore()

const parsedValue = computed(() => {
    if (props.defs.name == 'currency_id') {
        return items.value.find((item) => item.key === props.modelValue)?.value || ''
    }
    return props.modelValue
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
</script>

<style scoped lang="scss"></style>

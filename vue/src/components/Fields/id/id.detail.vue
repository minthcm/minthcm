<template>
    <div>
        <label>{{ props.label }}</label>
        <div class="detail-field-row">
            {{ parsedValue }}
            <!-- <Pencil
                :defs="props.defs"
                @inlineEditBtnClicked="(fieldName: string) => $emit('inlineEditBtnClicked', fieldName)"
            /> -->
        </div>
    </div>
</template>

<script setup lang="ts">
// import Pencil from '../Pencil.vue'
import { useBackendStore } from '@/store/backend'
import { computed } from 'vue'
import { FieldProps } from '../Field.model'

const props = defineProps<FieldProps>()
const backend = useBackendStore()

const parsedValue = computed(() => {
    if (props.defs.name == 'currency_id') {
        return items.value.find((item) => item.key === props.field.model)?.value || ''
    }
    return props.field.model
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

<style scoped lang="scss">
label {
    font-size: 12px;
    color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
    display: block;
}
:deep(.v-chip.v-chip--size-default) {
    height: 28px;
    border-radius: 4px;
    letter-spacing: 0.09px;
}
</style>

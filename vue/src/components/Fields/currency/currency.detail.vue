<template>
    <div>
        <label>{{ props.label }}</label>
        <div class="detail-field-row">
            <div>{{value}}</div>
            <Pencil :defs="props.defs" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import Pencil from '../Pencil.vue'
import NumberUtils from '@/utils/numbers'
import { FieldProps } from '../Field.model'

const props = defineProps<FieldProps>()
const value = computed(() => { 
    const value = props.modelValue?.trim()
    if (!value) {
        return ''
    }
    const currency_id = props.data?.bean?.attributes?.currency_id ?? '-99'
    return NumberUtils.formatCurrency(value, currency_id)
})
</script>

<style scoped lang="scss">
label {
    font-size: 12px;
    color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
}
div {
    overflow-wrap: break-word;
    word-wrap: break-word;
    word-break: break-word;
}
</style>

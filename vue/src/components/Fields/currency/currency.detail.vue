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
import { defineProps, computed } from 'vue'
import { FieldVardef } from '@/store/modules'
import Pencil from '../Pencil.vue'
import { useAuthStore } from '@/store/auth'
import NumberUtils from '@/utils/numbers'


interface Props {
    defs: FieldVardef
    label: string
    modelValue?: any
    data?: any
}

const props = defineProps<Props>()
const value = computed(() => { 
    const value = props.modelValue?.trim()
    if (!value) {
        return ''
    }
    const auth = useAuthStore()
    const currency_id = props.data?.bean?.currency_id ?? '-99'
    
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

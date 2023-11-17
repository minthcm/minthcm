<template>
    <div>
        <label>{{ props.label }}</label>
        <div class="detail-field-row">
            <div>{{ parsedDate }}</div>
            <Pencil :defs="props.defs" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { DateTime } from 'luxon'
import { FieldVardef } from '@/store/modules'
import Pencil from '../Pencil.vue'

interface Props {
    defs: FieldVardef
    label: string
    modelValue?: any
    data?: any
}

const props = defineProps<Props>()
const parsedDate = computed(() => {
    const value = props.modelValue?.trim()
    if (!value) {
        return ''
    }
    const dt = DateTime.fromSQL(value)
    if (!dt.isValid) {
        return ''
    }
    return dt.toFormat('dd.MM.yyyy')
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

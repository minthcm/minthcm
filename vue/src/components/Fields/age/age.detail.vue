<template>
    <div>
        <label>{{ props.label }}</label>
        <div class="detail-field-row">
            <div>{{ parsedDate }} <span v-if="props.modelValue"> - ({{ age }} {{languages.label('LBL_YEARS')?.toLowerCase()}})</span></div>
            <Pencil :defs="props.defs" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { defineProps, computed } from 'vue'
import { DateTime } from 'luxon'
import { FieldVardef } from '@/store/modules'
import { useLanguagesStore } from '@/store/languages'
import Pencil from '../Pencil.vue'

interface Props {
    defs: FieldVardef
    label: string
    modelValue?: any
    data?: any
}

const props = defineProps<Props>()
const languages = useLanguagesStore()
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
const age = computed(() => { 
    const value = props.modelValue?.trim()
    if (!value) {
        return ''
    }
    const birthDate = DateTime.fromSQL(value)
    const age = birthDate.diffNow('years').years;
    return Math.floor(-age);
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

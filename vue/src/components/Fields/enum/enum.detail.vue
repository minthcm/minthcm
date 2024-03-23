<template>
    <div>
        <label>{{ props.label }}</label>
        <div class="detail-field-row">
            <v-chip
                v-if="props.defs?.options_colors"
                class="enum-chip"
                :color="languages.translateListValue(props.modelValue, props.defs?.options_colors)"
                >{{ languages.translateListValue(props.modelValue, props.defs?.options) }}</v-chip
            >
            <div v-else>{{ languages.translateListValue(props.modelValue, props.defs?.options) }}</div>
            <Pencil :defs="props.defs" />
        </div>
    </div>
</template>

<script setup lang="ts">
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

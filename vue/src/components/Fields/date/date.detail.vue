<template>
    <div>
        <label>{{ props.label }}</label>
        <div class="detail-field-row">
            <div :name="props.defs.name">{{ parsedDate }}</div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { FieldProps } from '../Field.model'
import { MintDate } from '@/composables/useMintDate'

const props = defineProps<FieldProps<MintDate>>()
const parsedDate = computed(() => {
    return props.field.model.isValid ? props.field.formatted.user : ''
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

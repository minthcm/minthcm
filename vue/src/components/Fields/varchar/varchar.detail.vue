<template>
    <div>
        <label>{{ props.label }}</label>
        <div class="detail-field-row">
            <div>{{ props.field.model }}</div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { FieldProps } from '../Field.model';

const props = defineProps<FieldProps>()
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

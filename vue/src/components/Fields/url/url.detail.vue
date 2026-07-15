<template>
    <div>
        <label>{{ props.label }}</label>
        <div class="detail-field-row">
            <a class="mint-url-detail-field" target="_blank" :href="props.field.model">
                <span>{{ props.field.model }}</span>
                <v-icon v-if="props.field.model" size="x-small">mdi-open-in-new</v-icon>
            </a>
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
.mint-url-detail-field {
    overflow-wrap: break-word;
    word-wrap: break-word;
    word-break: break-word;
    color: rgb(var(--v-theme-secondary));
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 4px;
    width: fit-content;
}
</style>

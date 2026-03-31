<template>
    <a class="mint-url-list-field" target="_blank" :href="props.modelValue">
        <span>{{ props.modelValue }}</span>
        <v-icon v-if="props.modelValue" size="x-small">mdi-open-in-new</v-icon>
    </a>
</template>

<script setup lang="ts">
import { FieldProps } from '../Field.model';

const props = defineProps<FieldProps>()
</script>

<style scoped lang="scss">
.mint-url-list-field {
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

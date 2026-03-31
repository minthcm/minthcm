<template>
    <a class="mint-email-list-field" :href="`mailto:${props.modelValue}`">
        <v-icon size="x-small" v-if="props.modelValue">mdi-email</v-icon>
        <span>{{ props.modelValue }}</span>
    </a>
</template>

<script setup lang="ts">
import { FieldProps } from '../Field.model';

const props = defineProps<FieldProps>()
</script>

<style scoped lang="scss">
.mint-email-list-field {
    overflow-wrap: break-word;
    word-wrap: break-word;
    word-break: break-word;
    color: rgb(var(--v-theme-secondary));
    text-decoration: none;
    display: flex;
    align-items: baseline;
    gap: 6px;
    width: fit-content;

    .v-icon {
        top: 1px;
    }
}
</style>

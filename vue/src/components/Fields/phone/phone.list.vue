<template>
    <a class="mint-phone-list-field" :href="`tel:${props.modelValue}`">
        <v-icon size="x-small" v-if="props.modelValue">mdi-phone</v-icon>
        {{ props.modelValue }}
    </a>
</template>

<script setup lang="ts">
import { FieldProps } from '../Field.model';

const props = defineProps<FieldProps>()
</script>

<style scoped lang="scss">
.mint-phone-list-field {
    overflow-wrap: break-word;
    word-wrap: break-word;
    word-break: break-word;
    color: rgb(var(--v-theme-secondary));
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 4px;
}
</style>

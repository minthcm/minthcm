<template>
    <v-switch
        :label="props.label"
        hide-details
        density="compact"
        color="secondary"
        :modelValue="!!Number(props.modelValue)"
        readonly
        disabled
    />
</template>

<script setup lang="ts">
import { FieldProps } from '../Field.model';

const props = defineProps<FieldProps>()
</script>

<style scoped lang="scss">
.v-input {
    :deep(.v-selection-control) {
        flex-direction: row-reverse;
        justify-content: space-between;
    }
    :deep(.v-selection-control--dirty) {
        padding-right: 8px;
    }
    :deep(.v-label) {
        padding-left: 0px;
    }
}
:deep(.v-selection-control--disabled) {
    opacity: 1;
}
:deep(.v-selection-control__wrapper) {
    opacity: var(--v-disabled-opacity);
}
:deep(.v-label) {
    font-size: 12px;
    cursor: default;
    color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
}
:deep(.v-switch__track) {
    cursor: default;
}
:deep(.v-selection-control__input input) {
    cursor: default;
}
:deep(.v-selection-control__input::before) {
    background-color: #fff;
}
</style>

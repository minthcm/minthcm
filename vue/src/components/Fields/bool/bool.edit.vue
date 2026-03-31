<template>
    <v-switch
        :label="props.label"
        hide-details
        density="compact"
        color="secondary"
        :modelValue="!!Number(props.modelValue)"
        @update:modelValue="(v) => $emit('update:modelValue', v ? '1' : '0')"
    />
</template>

<script setup lang="ts">
import { FieldProps } from '../Field.model'

const props = defineProps<FieldProps>()
</script>

<style scoped lang="scss">
.v-input {
    :deep(.v-selection-control) {
        justify-content: space-between;
        flex-direction: row-reverse;
    }
    :deep(.v-selection-control--dirty) {
        padding-right: 8px;
    }
    :deep(.v-label) {
        padding-left: 0px;
    }
}
</style>

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
import { defineProps } from 'vue'
import { FieldVardef } from '@/store/modules'

interface Props {
    defs: FieldVardef
    label: string
    modelValue?: any
    data?: any
}

const props = defineProps<Props>()
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

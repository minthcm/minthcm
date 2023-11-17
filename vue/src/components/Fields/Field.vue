<template>
    <keep-alive>
        <component
            :is="FieldComponent"
            :data="data"
            :defs="defs"
            :label="label"
            :modelValue="modelValue"
            :class="`${view}-field-container`"
            @update:modelValue="(v) => $emit('update:modelValue', v)"
        />
    </keep-alive>
</template>

<script setup lang="ts">
import { defineAsyncComponent, computed } from 'vue'
import { FieldVardef } from '@/store/modules'
import { fieldConfig } from '../Fields/Field.config'

interface Props {
    defs: FieldVardef
    view: 'edit' | 'detail' | 'list'
    data?: any
    modelValue?: any
    label?: string
}

const props = defineProps<Props>()

const resolvedFieldType = computed(() => {
    const type = props.defs?.type?.trim() ?? ''
    if (fieldConfig.allowedTypes[props.view].includes(type)) {
        return type
    } else if (fieldConfig.allowedTypes[props.view].includes(fieldConfig.typeMap[type])) {
        return fieldConfig.typeMap[type]
    }
    console.warn(`Field.vue resolveFieldType: type "${type}" is not defined for view "${props.view}"`)
    return fieldConfig.defaultType
})

const FieldComponent = defineAsyncComponent(() => {
    return import(`@/components/Fields/${resolvedFieldType.value}/${resolvedFieldType.value}.${props.view}.vue`)
})
</script>

<style lang="scss">
.detail-field-row {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
}
</style>

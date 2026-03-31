<template>
    <label>{{ props.label }}</label>
    <div :name="props.defs.name" class="d-flex detail-field-container">
        <p v-text="fieldContent"></p>
        <Pencil :defs="props.defs" />
    </div>
</template>

<script setup lang="ts">
import { defineProps, computed } from 'vue'
import Pencil from '../Pencil.vue'
import { FieldProps } from '../Field.model';

const props = defineProps<FieldProps>()
const fieldContent = computed(() => {
    let text = ''
    props.defs.properties?.fields.forEach((field, index) => {
        const fieldValue = props.data.bean.attributes[field.name]
        if (fieldValue && fieldValue.trim() !== '') {
            if (text !== '') {
                text += props.defs.properties?.separator
            }
            text += fieldValue
        }
    })
    return text
})
</script>

<style scoped lang="scss">
label {
    font-size: 12px;
    color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
}
p {
    overflow-wrap: break-word;
    word-wrap: break-word;
    word-break: break-word;
}
</style>

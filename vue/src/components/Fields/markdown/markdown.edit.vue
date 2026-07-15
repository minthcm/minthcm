<template>
    <div>
        <label>{{ props.label }}</label>
        <MdEditor
            language="en-US"
            :modelValue="props.modelValue"
            :preview="true"
            @update:modelValue="(v) => $emit('update:modelValue', v)" 
        />
    </div>
</template>

<script setup lang="ts">
import { FieldProps } from '../Field.model'
import { MdEditor } from 'md-editor-v3'
import { onMounted, defineEmits } from 'vue'
import 'md-editor-v3/lib/style.css';

const props = defineProps<FieldProps>()
const emit = defineEmits(['update:modelValue'])
onMounted(() => {
    var txt = document.createElement('textarea')
    txt.innerHTML = props.modelValue || ''
    emit('update:modelValue', txt.value)
})
</script>

<style scoped lang="scss">
label {
    font-size: 12px;
    color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
}
</style>

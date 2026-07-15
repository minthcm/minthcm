<template>
    <div>
        <label>{{ props.label }}</label>
        <div class="detail-field-row">
            <MdPreview language="en-US" :modelValue="decodeHTML(props.modelValue)"/>
        </div>
    </div>
</template>

<script setup lang="ts">
import { MdPreview } from 'md-editor-v3'
import { FieldProps } from '../Field.model';
import 'md-editor-v3/lib/style.css';

const props = defineProps<FieldProps>()

const decodeHTML = (text: string) => {
    var txt = document.createElement('textarea')
    txt.innerHTML = text
    return txt.value
}
</script>

<style scoped lang="scss">
label {
    font-size: 12px;
    color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
}
</style>

<template>
    <div>
        <label>{{ props.label }}</label>
        <MdEditor
            language="en-US"
            :modelValue="props.modelValue"
            :preview="true"
            :theme="mdTheme"
            @update:modelValue="(v) => $emit('update:modelValue', v)"
        />
    </div>
</template>

<script setup lang="ts">
import { FieldProps } from '../Field.model'
import { MdEditor } from 'md-editor-v3'
import { onMounted, defineEmits, computed } from 'vue'
import 'md-editor-v3/lib/style.css';
import { useThemeStore } from '@/store/theme'

const props = defineProps<FieldProps>()
const emit = defineEmits(['update:modelValue'])
const themeStore = useThemeStore()
const mdTheme = computed<'light' | 'dark'>(() => (themeStore.activeTheme === 'dark' ? 'dark' : 'light'))
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

// md-editor-v3 cascades chrome colors (toolbar, input, dropdowns, modals) from these two variables
// defined on the .md-editor root — overriding them here is enough for the whole editor (not just
// the preview) to use Mint colors instead of the built-in, hardcoded black dark theme background.
:deep(.md-editor) {
    --md-bk-color: rgb(var(--v-theme-surface));
    --md-color: rgb(var(--v-theme-on-surface));
}

// Overriding --md-bk-color/--md-color alone is not enough — the library derives blockquote/code/table
// backgrounds from a separate family of variables (--md-theme-*), hardcoded per theme
// (e.g. --md-theme-bg-color-inset: #ececec/#111). We override them here so the cascade
// inherits Mint colors instead of the library's built-in colors in both themes.
:deep(.md-editor-preview) {
    background-color: rgb(var(--v-theme-surface));
    color: rgb(var(--v-theme-on-surface));
    --md-theme-bg-color: rgb(var(--v-theme-surface));
    --md-theme-bg-color-inset: rgba(var(--v-theme-on-surface), 0.06);
    --md-theme-code-block-bg-color: rgba(var(--v-theme-on-surface), 0.06);
    --md-theme-code-block-color: rgb(var(--v-theme-on-surface));
    --md-theme-table-td-border-color: rgba(var(--v-theme-on-surface), 0.12);
    --md-theme-table-stripe-color: rgba(var(--v-theme-on-surface), 0.03);

    p,
    li {
        line-height: 1.6;
    }
}
</style>

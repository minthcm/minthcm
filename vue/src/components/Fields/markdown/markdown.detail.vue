<template>
    <div>
        <label>{{ props.label }}</label>
        <div class="detail-field-row">
            <MdPreview
                language="en-US"
                :modelValue="decodeHTML(props.modelValue)"
                :sanitize="sanitize"
                :theme="mdTheme"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { MdPreview } from 'md-editor-v3'
import { FieldProps } from '../Field.model';
import 'md-editor-v3/lib/style.css';
import DOMPurify from 'dompurify'
import { useThemeStore } from '@/store/theme'

const props = defineProps<FieldProps>()
const themeStore = useThemeStore()
const mdTheme = computed<'light' | 'dark'>(() => (themeStore.activeTheme === 'dark' ? 'dark' : 'light'))

const decodeHTML = (text: string) => {
    var txt = document.createElement('textarea')
    txt.innerHTML = text
    return txt.value
}

const sanitize = (html: string) => DOMPurify.sanitize(html)
</script>

<style scoped lang="scss">
label {
    font-size: 12px;
    color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
}

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

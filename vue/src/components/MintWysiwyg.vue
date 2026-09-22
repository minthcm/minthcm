<template>
    <div class="mint-wysiwyg">
        <textarea :id="uuid"></textarea>
        <div v-if="$slots.footer" class="mint-wysiwyg-footer">
            <slot name="footer"></slot>
        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted, onUnmounted, watch, ref } from 'vue'
import { v4 as uuidv4 } from 'uuid'
import tinymce, { Editor, RawEditorOptions } from 'tinymce'
import { useThemeStore } from '@/store/theme'

import 'tinymce/models/dom'
import 'tinymce/icons/default'
import 'tinymce/themes/silver'
import 'tinymce/models/dom'
import 'tinymce/plugins/table'
import 'tinymce/plugins/lists'
import 'tinymce/plugins/emoticons'
import 'tinymce/plugins/emoticons/js/emojis'
import 'tinymce/plugins/image'
import 'tinymce/plugins/link'
import 'tinymce/plugins/insertdatetime'
import 'tinymce/plugins/charmap'
import oxideSkinCss from 'tinymce/skins/ui/oxide/skin.css?inline'
import oxideDarkSkinCss from 'tinymce/skins/ui/oxide-dark/skin.css?inline'
import contentUiCss from 'tinymce/skins/ui/oxide/content.min.css?inline'
import contentUiDarkCss from 'tinymce/skins/ui/oxide-dark/content.min.css?inline'
import contentCss from 'tinymce/skins/content/default/content.css?inline'
import contentDarkCss from 'tinymce/skins/content/dark/content.min.css?inline'

interface Props {
    modelValue: string
    options?: RawEditorOptions
}

const props = withDefaults(defineProps<Props>(), {
    options: () => ({}),
})
const emit = defineEmits(['update:modelValue', 'cursorChange'])

const themeStore = useThemeStore()
const tinymceEditor = ref<null | Editor>(null)
const uuid = `id-${uuidv4()}`
const selector = `.mint-wysiwyg textarea#${uuid}`

defineExpose({
    tinymceEditor,
})

const SKIN_STYLE_ID = 'tinymce-skin-css'

function isDark() {
    return themeStore.activeTheme === 'dark'
}

function applySkinCss(dark: boolean) {
    let styleEl = document.getElementById(SKIN_STYLE_ID) as HTMLStyleElement | null
    if (!styleEl) {
        styleEl = document.createElement('style')
        styleEl.id = SKIN_STYLE_ID
        document.head.appendChild(styleEl)
    }
    styleEl.textContent = dark ? oxideDarkSkinCss : oxideSkinCss
}

function buildContentStyle(dark: boolean): string {
    const uiCss = dark ? contentUiDarkCss : contentUiCss
    const baseCss = dark ? contentDarkCss : contentCss
    const font = "@import url('https://fonts.googleapis.com/css2?family=Barlow:wght@300;400;600&display=swap'); body { font-family: Barlow; }"
    const extraStyle = props.options.content_style ?? ''
    return uiCss + '\n' + baseCss + '\n' + font + (extraStyle ? '\n' + extraStyle : '')
}

function initEditor(dark: boolean) {
    applySkinCss(dark)
    tinymce.init({
        selector,
        menubar: false,
        statusbar: false,
        promotion: false,
        skin: false,
        height: 250,
        plugins: 'table, lists, emoticons, image, link, insertdatetime, charmap',
        toolbar_mode: 'wrap',
        toolbar:
            'fontselect | fontsizeselect | bold italic underline | forecolor backcolor | styleselect | outdent indent | numlist bullist | table | emoticons',
        table_toolbar:
            'tabledelete | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol',
        setup: (editor) => {
            editor.on('init', () => {
                tinymceEditor.value = editor
                editor.setContent(props.modelValue)
            })
            editor.on('SetContent', () => {
                emit('update:modelValue', editor.getContent())
            })
            editor.on('input', () => {
                emit('update:modelValue', editor.getContent())
            })
            editor.on('change', () => {
                emit('update:modelValue', editor.getContent())
            })
            editor.on('SelectionChange', () => {
                emit('cursorChange')
            })
        },
        font_formats:
            'Andale Mono=andale mono,times; Arial=arial,helvetica,sans-serif; Arial Black=arial black,avant garde; Barlow=barlow; Book Antiqua=book antiqua,palatino; Comic Sans MS=comic sans ms,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; Symbol=symbol; Tahoma=tahoma,arial,helvetica,sans-serif; Terminal=terminal,monaco; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva; Webdings=webdings; Wingdings=wingdings,zapf dingbats',
        ...props.options,
        content_style: buildContentStyle(dark),
        license_key: 'gpl',
    })
}

onMounted(() => {
    initEditor(isDark())
})

watch(
    () => themeStore.activeTheme,
    (newTheme, oldTheme) => {
        if (newTheme === oldTheme) return
        const dark = newTheme === 'dark'
        const currentContent = tinymceEditor.value?.getContent() ?? props.modelValue
        tinymce.remove(selector)
        tinymceEditor.value = null
        initEditor(dark)
        watch(
            tinymceEditor,
            (editor) => {
                if (editor) editor.setContent(currentContent)
            },
            { once: true },
        )
    },
)

onUnmounted(() => {
    tinymce.remove(selector)
})
</script>

<style lang="scss">
.mint-wysiwyg {
    border: thin solid var(--mint-border);
    background-color: var(--mint-primary-lighter);
    position: relative;
    width: 100%;

    .tox.tox-tinymce {
        border: none;
    }

    .mint-wysiwyg-footer {
        background: rgb(var(--v-theme-primary-lighter));
        border-top: thin solid var(--mint-border);
        padding: 8px 16px;
    }
}
</style>

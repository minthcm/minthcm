<template>
    <div class="mint-wysiwyg">
        <textarea :id="uuid"></textarea>
        <div v-if="$slots.footer" class="mint-wysiwyg-footer">
            <slot name="footer"></slot>
        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { v4 as uuidv4 } from 'uuid'
import tinymce, { Editor, RawEditorSettings } from 'tinymce'

import 'tinymce/icons/default'
import 'tinymce/themes/silver'
import 'tinymce/plugins/table'
import 'tinymce/plugins/lists'
import 'tinymce/skins/ui/oxide/skin.css'
import 'tinymce/skins/ui/oxide/content.min.css'

interface Props {
    modelValue: string
    options?: RawEditorSettings
}

const props = withDefaults(defineProps<Props>(), {
    options: () => ({}),
})
const emit = defineEmits(['update:modelValue', 'cursorChange'])

const tinymceEditor = ref<null | Editor>(null)
const uuid = uuidv4()
const selector = `.mint-wysiwyg textarea#${uuid}`

defineExpose({
    tinymceEditor,
})

onMounted(() => {
    tinymce.init({
        selector,
        menubar: false,
        statusbar: false,
        promotion: false,
        height: 250,
        plugins: 'table, lists',
        toolbar_mode: 'wrap',
        toolbar:
            'fontselect | fontsizeselect | bold italic underline | forecolor backcolor | styleselect | outdent indent | numlist bullist | table',
        table_toolbar:
            'tabledelete | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol',
        setup: (editor) => {
            tinymceEditor.value = editor
            editor.on('init', () => {
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
        content_style:
            "@import url('https://fonts.googleapis.com/css2?family=Barlow:wght@300;400;600&display=swap'); body { font-family: Barlow; }",
        ...props.options,
    })
})
</script>

<style lang="scss">
.mint-wysiwyg {
    border: thin solid #0003;
    position: relative;
    width: 100%;

    .tox.tox-tinymce {
        border: none;
    }

    .mint-wysiwyg-footer {
        background: white;
        border-top: thin solid #0001;
        padding: 8px 16px;
    }
}
</style>

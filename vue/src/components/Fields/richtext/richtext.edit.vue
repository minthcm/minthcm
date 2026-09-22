<template>
    <MintWysiwyg
        :model-value="modelValue ?? ''"
        :options="editorOptions"
        @update:model-value="$emit('update:modelValue', $event)"
    />
</template>

<script setup lang="ts">
import type { RawEditorOptions } from 'tinymce'
import MintWysiwyg from '@/components/MintWysiwyg.vue'
import type { FieldProps } from '@/components/Fields/Field.model'

defineProps<FieldProps>()
defineEmits(['update:modelValue'])

const editorOptions: RawEditorOptions = {
    height: 400,
    plugins: 'table lists link emoticons insertdatetime charmap',
    toolbar_mode: 'wrap',
    toolbar:
        'blocks | fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | table | link emoticons | insertdatetime charmap',
    table_toolbar:
        'tabledelete | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol',
    font_size_formats: '8pt 10pt 12pt 14pt 16pt 18pt 24pt 36pt 48pt',
}
</script>

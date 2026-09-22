<template>
    <span>{{ plainText }}</span>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import type { FieldProps } from '@/components/Fields/Field.model'

const props = defineProps<Pick<FieldProps, 'modelValue' | 'defs'>>()

const plainText = computed(() => {
    const stripped = (props.modelValue ?? '').replace(/<[^>]*>/g, '')
    const decoded = stripped
        .replace(/&amp;/g, '&')
        .replace(/&lt;/g, '<')
        .replace(/&gt;/g, '>')
        .replace(/&nbsp;/g, ' ')
    return decoded.length > 100 ? decoded.slice(0, 100) + '...' : decoded
})
</script>

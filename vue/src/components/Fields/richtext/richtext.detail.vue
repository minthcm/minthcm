<template>
    <div>
        <div v-if="photoUrl" class="richtext-photo">
            <img :src="photoUrl" alt="" />
        </div>
        <div class="richtext-content" v-html="sanitized"></div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import DOMPurify from 'dompurify'
import type { FieldProps } from '@/components/Fields/Field.model'

const props = defineProps<FieldProps>()

const sanitized = computed(() => DOMPurify.sanitize(props.modelValue ?? ''))

const photoUrl = computed(() => {
    const fieldsDefs = props.data?.bean?.fieldDefs
    if (!fieldsDefs) {
        return ''
    }
    const entry = Object.entries(fieldsDefs).find(([, fieldDef]: any) => fieldDef?.type === 'image')
    if (!entry) {
        return ''
    }
    const fieldValue = (props.data?.bean.fields[entry[0]] as any)?.model
    if (!fieldValue) {
        return ''
    }
    const beanId = props.data?.bean?.attributes?.id
    const module = props.data?.bean?.module
    if (!beanId || !module) {
        return ''
    }
    return `legacy/index.php?entryPoint=download&type=${module}&id=${beanId}_${entry[0]}`
})
</script>

<style scoped>
.richtext-photo {
    display: flex;
    justify-content: center;
    margin-bottom: 16px;
}
.richtext-photo img {
    max-width: 100%;
    max-height: 400px;
    object-fit: contain;
    border-radius: 8px;
}
.richtext-content {
    line-height: 1.6;
}
</style>

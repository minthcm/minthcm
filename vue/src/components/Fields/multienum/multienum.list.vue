<template>
    <span>{{ value }}</span>
</template>

<script setup lang="ts">
import { useLanguagesStore } from '@/store/languages'
import { FieldProps } from '../Field.model'
import { computed } from 'vue'

const props = defineProps<FieldProps>()
const languages = useLanguagesStore()

const value = computed(() => {
    if (!props.modelValue) {
        return ''
    }
    let value: string[] = Array.isArray(props.modelValue)
        ? props.modelValue
        : props.modelValue.replaceAll('^', '').split(',')
    if (!props.defs?.options) {
        return value.join(', ')
    }
    const options =
        Object.fromEntries(languages.getList(props.defs.options)?.map((item) => [item.key, item.value])) || {}
    return value
        .filter((val) => val in options)
        .map((val) => languages.translateListValue(val, props.defs.options))
        .join(', ')
})
</script>

<style scoped lang="scss"></style>

<template>
    <v-select
        v-model="value"
        @update:model-value="emit('update:modelValue', value)"
        :items="items"
        item-value="value"
        item-title="text"
        :label="props.input.label"
        multiple
        chips
        closable-chips
        variant="outlined"
        hide-details
    />
</template>

<script setup lang="ts">
import { defineProps, defineEmits, ref, computed } from 'vue'
import { useLanguagesStore } from '@/store/languages'

export interface Props {
    input: any
    fieldDefs: any
}

const props = defineProps<Props>()
const emit = defineEmits(['update:modelValue'])
const languages = useLanguagesStore()
const value = ref(props.input?.value)

const items = computed(() => {
    return Object.entries(
        typeof props.fieldDefs.options === 'string'
            ? languages.languages.app_list_strings[props.fieldDefs.options] ?? {}
            : props.fieldDefs.options,
    ).map(([value, text]) => ({
        value,
        text,
    }))
})
</script>

<style scoped lang="scss"></style>

<template>
    <v-select
        :label="props.label"
        variant="outlined"
        density="compact"
        hide-details
        :error="props.state === 'error'"
        v-model="parsedValue"
        :items="items"
        item-title="value"
        item-value="key"
        @keyup.enter="$emit('inlineEditSave')"
        @keyup.esc="$emit('inlineEditCancel')"
    >
    </v-select>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { useLanguagesStore } from '@/store/languages'
import { FieldProps } from '../Field.model'

const props = defineProps<FieldProps>()
const languages = useLanguagesStore()
const emit = defineEmits(['update:modelValue'])
const model = ref('')

const parsedValue = computed({
    get() {
        return items.value.find((item) => item.key === props.field.model)?.key || ''
    },
    set(newValue) {
        model.value = newValue
        props.field.model = newValue
        emit('update:modelValue', model.value)
    },
})

const items = computed(() => {
    const options = props.options ?? props.defs?.options
    if (!options) {
        return []
    }
    if (typeof options === 'string') {
        return languages.getList(options)
    }
    if (!Array.isArray(options) && typeof options === 'object') {
        return Object.entries(options).map(([key, value]) => ({
            key,
            value,
        }))
    }
    return options
})

watch(items, () => {
    if (!items.value.find((item) => item.key === model.value)) {
        const newItem = items.value.find((item) => !item.key) || items.value[0]
        model.value = newItem?.key || ''
        props.field.model = newItem
        emit('update:modelValue', model.value)
    }
})
</script>

<style scoped lang="scss"></style>

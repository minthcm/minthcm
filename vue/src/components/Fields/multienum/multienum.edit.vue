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
        multiple
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
const model = ref<Array<string>>(Array.isArray(props.modelValue) ? props.modelValue : [])

const parsedValue = computed({
    get() {
        return props.field.model
    },
    set(newValue: string[]) {
        model.value = newValue
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
    // Remove values not present in items
    const validKeys = items.value.map(item => item.key)
    const filtered = model.value.filter(val => validKeys.includes(val))
    if (filtered.length !== model.value.length) {
        model.value = filtered
        emit('update:modelValue', model.value)
    }
})
</script>

<style scoped lang="scss"></style>

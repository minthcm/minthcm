<template>
    <v-text-field
        :label="props.label"
        variant="outlined"
        density="compact"
        hide-details
        :error="props.state === 'error'"
        :modelValue="props.modelValue"
        type="text"
        inputmode="decimal"
        :pattern="format"
        @update:modelValue="onInput"
    />
</template>

<script setup lang="ts">
import { FieldProps } from '../Field.model'
import { usePreferencesStore } from '@/store/preferences'
import { computed, onMounted } from 'vue'

const props = defineProps<FieldProps>()
const emit = defineEmits(['update:modelValue'])
const preferences = usePreferencesStore()

const format = computed(() => {
    const dec_sep = preferences.user?.dec_sep || '.'
    const currency_significant_digits = preferences.user?.default_currency_significant_digits || 2
    return `^\\d*(${dec_sep}\\d{0,${currency_significant_digits}})?$`
})
onMounted(() => {
    const dec_sep = preferences.user?.dec_sep || '.'
    if (props.modelValue) {
        props.modelValue = onInput(String(props.modelValue).replace(/[.]/g, dec_sep))
    }
})

function onInput(value: string) {
    const dec_sep = preferences.user?.dec_sep || '.'
    const currency_significant_digits = preferences.user?.default_currency_significant_digits || 2
    let val = value.replace(new RegExp(`[^0-9${dec_sep}]`, 'g'), '')
    const parts = val.split(dec_sep)
    if (parts.length > 2) {
        val = parts[0] + dec_sep + parts.slice(1).join('')
    }
    if (parts[1]?.length > currency_significant_digits) {
        val = parts[0] + dec_sep + parts[1].substring(0, currency_significant_digits)
    }
    if (val === '' && value !== '') {
        emit('update:modelValue', value)
    } else {
        emit('update:modelValue', val)
    }
}
</script>

<style scoped lang="scss"></style>

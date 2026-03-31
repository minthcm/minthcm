<template>
    <v-text-field
        :label="props.label"
        variant="outlined"
        density="compact"
        hide-details
        :name="props.defs.name"
        v-model="props.field.model"
        :error="props.state === 'error'"
        @keyup.enter="$emit('inlineEditSave')"
        @keyup.esc="$emit('inlineEditCancel')"
        :autocomplete="autocompleteValue"
    />
</template>

<script setup lang="ts">
import { FieldProps } from '../Field.model'
import { computed } from 'vue'

const props = defineProps<FieldProps<string>>()

const autocompleteValue = computed(() => {
    const name = props.defs?.name?.toLowerCase() || ''
    if (name.includes('email')) return 'email'
    if (name.includes('country')) return 'country'
    if (name.includes('first')) return 'given-name'
    if (name.includes('last')) return 'family-name'
    if (name.includes('phone')) return 'tel'
    return 'on'
})
</script>

<style scoped lang="scss"></style>

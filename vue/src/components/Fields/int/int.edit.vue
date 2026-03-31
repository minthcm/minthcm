<template>
    <v-text-field
        :label="props.label"
        variant="outlined"
        density="compact"
        hide-details
        v-model="props.field.model"
        :error="props.state === 'error'"
        @keyup.enter="$emit('inlineEditSave')"
        @keyup.esc="$emit('inlineEditCancel')"
    />
</template>

<script setup lang="ts">
import { FieldProps } from '../Field.model'

const props = defineProps<FieldProps<string | number>>()
</script>

<style scoped lang="scss"></style>

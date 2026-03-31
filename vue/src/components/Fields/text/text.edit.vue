<template>
    <v-textarea
        :label="props.label"
        variant="outlined"
        density="compact"
        rows="4"
        hide-details
        :error="props.state === 'error'"
        v-model="props.field.model"
        @keyup.enter.prevent="$emit('inlineEditSave')"
        @keyup.esc="$emit('inlineEditCancel')"
    />
</template>

<script setup lang="ts">
import { FieldProps } from '../Field.model'

const props = defineProps<FieldProps<string>>()
</script>

<style scoped lang="scss"></style>

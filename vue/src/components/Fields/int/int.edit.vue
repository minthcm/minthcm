<template>
    <v-text-field
        :label="props.label"
        variant="outlined"
        density="compact"
        hide-details
        v-model="props.field.model"
        :error="props.state === 'error'"
        @update:modelValue="(v) => $emit('update:modelValue', v)"
    />
</template>

<script setup lang="ts">
import { FieldProps } from '../Field.model'

const props = defineProps<FieldProps<string | number>>()
</script>

<style scoped lang="scss"></style>

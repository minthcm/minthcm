<template>
    <v-textarea
        :label="props.label"
        variant="outlined"
        density="compact"
        rows="4"
        hide-details
        :error="props.state === 'error'"
        v-model="props.field.model"
        @update:modelValue="(v) => $emit('update:modelValue', v)"
    />
</template>

<script setup lang="ts">
import { FieldProps } from '../Field.model'

const props = defineProps<FieldProps<string>>()
</script>

<style scoped lang="scss"></style>

<template>
    <span>{{ props.modelValue }}</span>
</template>

<script setup lang="ts">
import { FieldProps } from '../Field.model'

const props = defineProps<FieldProps>()
</script>

<style scoped lang="scss"></style>

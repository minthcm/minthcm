<template>
    <span :name="props.defs.name">{{ parsedDate }}</span>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { FieldProps } from '../Field.model'
import { MintDate, useMintDate } from '@/composables/useMintDate'

const props = defineProps<FieldProps<MintDate>>()

const parsedDate = computed(() => {
    if (props.field) {
        return props.field.model.isValid ? props.field.formatted.user : ''
    }
    const date = useMintDate(props.modelValue as string)
    return date.isValid.value ? date.formatted.value.user_datetime : ''
})
</script>

<style scoped lang="scss"></style>

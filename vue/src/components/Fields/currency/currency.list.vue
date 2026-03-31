<template>
    <span>{{ value }}</span>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { FieldProps } from '../Field.model';
import NumberUtils from '@/utils/numbers';

const props = defineProps<FieldProps>()

const value = computed(() => { 
    const value = props.modelValue?.trim()
    if (!value) {
        return ''
    }
    const currency_id = props.data?.bean?.attributes?.currency_id ?? '-99'
    return NumberUtils.formatCurrency(value, currency_id)
})
</script>

<style scoped lang="scss"></style>

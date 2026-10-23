<template>
    <span class="crontab-list-value">{{ displayValue }}</span>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { FieldProps } from '../Field.model'

const props = defineProps<FieldProps>()

const displayValue = computed(() => {
    const val = props.modelValue
    if (!val || typeof val !== 'string') return ''
    return val.replace(/::/g, ' ')
})
</script>

<style scoped>
.crontab-list-value {
    color: rgb(var(--v-theme-secondary));
    font-weight: 500;
}
</style>

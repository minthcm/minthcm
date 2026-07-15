<template>
    <div>
        <label>{{ props.label }}</label>
        <div class="detail-field-row">
            <div :name="props.defs.name">{{ parsedDate }}</div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { DateTime } from 'luxon'
import { usePreferencesStore } from '@/store/preferences';
import { FieldProps } from '../Field.model';

const props = defineProps<FieldProps>()
const parsedDate = computed(() => {
    return props.field.formatted.user
})
</script>

<style scoped lang="scss">
label {
    font-size: 12px;
    color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
}
div {
    overflow-wrap: break-word;
    word-wrap: break-word;
    word-break: break-word;
}
</style>

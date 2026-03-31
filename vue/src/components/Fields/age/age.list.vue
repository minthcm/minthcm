<template>
    <span :name="props.defs.name">
        {{ parsedDate }}
        <span v-if="props.modelValue"> - ({{ age }} {{ languages.label('LBL_YEARS')?.toLowerCase() }})</span>
    </span>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { FieldProps } from '../Field.model'
import { useLanguagesStore } from '@/store/languages';
import { MintDate } from '@/composables/useMintDate';

const props = defineProps<FieldProps<MintDate>>()
const languages = useLanguagesStore()

const parsedDate = computed(() => {
    return props.field.model.isValid ? props.field.formatted.user : ''
})
const age = computed(() => {
    if (!props.field.model.isValid) {
        return ''
    }
    const age = props.field.model.instance.diffNow('years').years
    return Math.floor(-age)
})
</script>

<style scoped lang="scss"></style>

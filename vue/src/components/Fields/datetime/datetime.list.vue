<template>
    <div class="mint-datetime-field">{{ parsedDateTime }}</div>
</template>

<script setup lang="ts">
import { defineProps, computed } from 'vue'
import { FieldVardef } from '@/store/modules'
import { DateTime } from 'luxon'
import { usePreferencesStore } from '@/store/preferences';

interface Props {
    defs: FieldVardef
    data?: any
}

const props = defineProps<Props>()
const preferences = usePreferencesStore()

const parsedDateTime = computed(() => {
    const dateString = props.data.bean[props.defs.name].trim()
    if (!dateString) {
        return ''
    }
    let dateTime = DateTime.fromFormat(dateString, `${preferences.user?.date_format} ${preferences.user?.time_format}`)
    if (!dateTime.isValid) {
        dateTime = DateTime.fromSQL(dateString, { zone: 'UTC' })
    }
    return dateTime.isValid
        ? dateTime.toLocal().toFormat(`${preferences.user?.date_format} ${preferences.user?.time_format}`)
        : dateString
})
</script>

<style scoped lang="scss">
.mint-datetime-field {
    width: min-content;
    text-wrap: wrap;
}
</style>

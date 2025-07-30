<template>
    <span>{{ parsedDate }}</span>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { FieldVardef } from '@/store/modules'
import { DateTime } from 'luxon'
import { usePreferencesStore } from '@/store/preferences';

interface Props {
    defs: FieldVardef
    data?: any
}

const props = defineProps<Props>()
const preferences = usePreferencesStore()
const parsedDate = computed(() => {
    const dateString = props.data.bean[props.defs.name].trim()
    if (!dateString) {
        return ''
    }
    let dateTime = DateTime.fromFormat(dateString, preferences.user?.date_format || 'dd.MM.yyyy')
    if (!dateTime.isValid) {
        dateTime = DateTime.fromSQL(dateString)
    }
    return dateTime.isValid ? dateTime.toFormat(preferences.user?.date_format || 'dd.MM.yyyy') : dateString
})
</script>

<style scoped lang="scss"></style>

<template>
    <span>{{ parsedDate }}</span>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { FieldVardef } from '@/store/modules'
import { DateTime } from 'luxon'

interface Props {
    defs: FieldVardef
    data?: any
}

const props = defineProps<Props>()

const parsedDate = computed(() => {
    const dateString = props.data.bean[props.defs.name].trim()
    if (!dateString) {
        return ''
    }
    //TODO: luxon date/time format
    let dateTime = DateTime.fromFormat(dateString, 'dd.MM.yyyy')
    if (!dateTime.isValid) {
        dateTime = DateTime.fromSQL(dateString)
    }
    return dateTime.isValid ? dateTime.toFormat('dd.MM.yyyy') : dateString
})
</script>

<style scoped lang="scss"></style>

<template>
    <div class="mint-datetime-field">{{ parsedDateTime }}</div>
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

const parsedDateTime = computed(() => {
    const dateString = props.data.bean[props.defs.name].trim()
    if (!dateString) {
        return ''
    }
    //TODO: luxon date/time format
    let dateTime = DateTime.fromFormat(dateString, 'dd.MM.yyyy HH:mm:ss')
    if (!dateTime.isValid) {
        dateTime = DateTime.fromSQL(dateString, { zone: 'UTC' })
    }
    return dateTime.isValid ? dateTime.toLocal().toFormat('dd.MM.yyyy HH:mm:ss') : dateString
})
</script>

<style scoped lang="scss">
.mint-datetime-field {
    width: min-content;
    text-wrap: wrap;
}
</style>

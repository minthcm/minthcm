<template>
    <div>
        <label>{{ props.label }}</label>
        <div class="detail-field-row">
            <div>{{ fieldValue }}</div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { FieldProps } from '../Field.model'
import { computed } from 'vue'
import { useLanguagesStore } from '@/store/languages'

const languages = useLanguagesStore()

const props = defineProps<FieldProps>()

const fieldValue = computed(() => {
    const repeatTypeDom = languages.getList('repeat_type_dom')
    const repeatIntervals = languages.getList('repeat_intervals')
    const calendarDays = languages.getList('dom_cal_day_long').filter((item) => item.value !== '')

    const repeatType = repeatTypeDom.find((item) => item.key === props.data.bean.fields.repeat_type?.model)?.value || ''
    const repeatInterval =
        repeatIntervals.find((item) => item.key == props.data.bean.fields.repeat_type?.model)?.value || ''

    if (!props.data.bean.fields.repeat_type?.model) {
        return repeatType
    }

    let value = `${repeatType}, ${languages.label('LBL_EVERY')} ${
        props.data.bean.fields.repeat_interval?.model
    } ${repeatInterval}, `

    if (props.data.bean.fields.repeat_count?.model && props.data.bean.fields.repeat_count?.model !== '') {
        value += `${props.data.bean.fields.repeat_count?.model} ${languages.label('LBL_TIMES').toLowerCase()}`
    } else if (props.data.bean.fields.repeat_until?.model) {
        value += `${languages.label('LBL_UNTIL').toLowerCase()} ${
            props.data.bean.fields.repeat_until?.model.formatted.user_date ?? ''
        }`
    }

    if (props.data.bean.fields.repeat_type?.model === 'Weekly' && props.data.bean.fields.repeat_dow?.model) {
        const days = props.data.bean.fields.repeat_dow?.model.split('').map((dayKey) => {
            return calendarDays.find((day) => day.key == dayKey)?.value || ''
        })
        value += `, ${languages.label('LBL_CRON_ON_THE_WEEKDAY')} ${days.join(', ')}`
    }

    return value
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

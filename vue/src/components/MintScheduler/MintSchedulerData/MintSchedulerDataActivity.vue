<template>
    <v-tooltip location="top center">
        <template v-slot:activator="{ props: tooltipProps }">
            <component
                :is="hasAccess ? 'router-link' : 'div'"
                v-bind="tooltipProps"
                :to="activityUrl"
                target="_blank"
                class="data-activity"
                :style="style"
            >
                <span>{{ activityTimeText }}</span>
                <span class="data-activity-name">{{ props.activity.name }}</span>
            </component>
        </template>
        <div class="activity-tooltip">
            <div class="activity-tooltip-row">
                <b>{{ language.label('LBL_SUBJECT') }}:</b>
                <span>{{ props.activity.name }}</span>
            </div>
            <div class="activity-tooltip-row">
                <b>{{ language.label('LBL_SCHEDULER_DATE_START') }}:</b>
                <span>{{ dateFromText }}</span>
            </div>
            <div class="activity-tooltip-row">
                <b>{{ language.label('LBL_SCHEDULER_DATE_END') }}:</b>
                <span>{{ dateToText }}</span>
            </div>
        </div>
    </v-tooltip>
</template>

<script setup lang="ts">
import { DateTime } from 'luxon'
import { useDataPosition } from '@/composables/useDataPosition'
import { computed } from 'vue'
import { useMintScheduler } from '../useMintScheduler'
import { DataActivity } from '../MintScheduler.model'
import { useLanguagesStore } from '@/store/languages'
import { usePreferencesStore } from '@/store/preferences'
import { useACL } from '@/composables/useACL'

interface Props {
    activity: DataActivity
    scheduler: ReturnType<typeof useMintScheduler>
}
const props = defineProps<Props>()

const language = useLanguagesStore()
const preferences = usePreferencesStore()

const hasAccess = computed(() => {
    return useACL().hasAccess(props.activity.module, 'view', true, true)
})

const dateFrom = computed(() => props.activity.date_start)
const dateTo = computed(() => props.activity.date_end)

const dateFromText = computed(() => {
    if (!dateFrom.value) {
        return ''
    }
    const dt = DateTime.fromSQL(dateFrom.value, { zone: 'UTC' })
    if (!dt.isValid) {
        return ''
    }
    return dt.setZone(preferences.user?.timezone).toFormat('dd.MM.yyyy HH:mm')
})

const dateToText = computed(() => {
    if (!dateTo.value) {
        return ''
    }
    const dt = DateTime.fromSQL(dateTo.value, { zone: 'UTC' })
    if (!dt.isValid) {
        return ''
    }
    return dt.setZone(preferences.user?.timezone).toFormat('dd.MM.yyyy HH:mm')
})

const position = useDataPosition(
    { dateFrom, dateTo },
    { minDate: props.scheduler.dateBegin, maxDate: props.scheduler.dateEnd },
)

const style = computed(() => {
    return {
        ...position.style.value,
        'bottom': `${6 + (props.activity.overflowIndex * 30)}px`,
    }
})

const activityTimeText = computed(() => {
    const text = []
    if (dateFrom.value) {
        text.push(
            DateTime.fromSQL(dateFrom.value, { zone: 'UTC' }).setZone(preferences.user?.timezone).toFormat('HH:mm'),
        )
    }
    if (dateTo.value) {
        text.push(DateTime.fromSQL(dateTo.value, { zone: 'UTC' }).setZone(preferences.user?.timezone).toFormat('HH:mm'))
    }
    return text.join(' - ')
})

const activityUrl = computed(() => {
    return `/modules/${props.activity.module}/DetailView/${props.activity.id}`
})
</script>

<style scoped lang="scss">
.data-activity {
    position: absolute;
    display: flex;
    flex-direction: column;
    height: min-content;
    padding: 1px 2px;
    overflow: hidden;
    background: rgb(var(--v-theme-activity));
    color: rgb(var(--v-theme-on-activity));
    box-shadow: 0px 3px 6px #00000029;
    font-size: 10px;
    border-radius: 4px;
    letter-spacing: 0.33px;
    font-size: 10px;
    bottom: 6px;
    text-decoration: none;
    transition: all 150ms ease-in-out;
    margin: 0 4px;
    line-height: 12px;
    white-space: nowrap;
    &:hover {
        z-index: 5;
        box-shadow: 0px 6px 12px #00000040;
    }

    .data-activity-name {
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
}

.activity-tooltip {
    font-size: 12px;
    display: flex;
    flex-direction: column;
    .activity-tooltip-row {
        display: flex;
        gap: 4px;
    }
}
</style>

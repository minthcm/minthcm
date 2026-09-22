<template>
    <MintWidgetFrame
        :is-loading="isLoading"
        :has-error="hasError"
        icon="mdi-calendar-today-outline"
        :title="languages.label('LBL_WIDGET_CALENDAR_TITLE', 'Employees')"
        :error-label="languages.label('LBL_ERROR_LOADING_DATA', 'Employees')"
        :refresh-label="languages.label('LBL_REFRESH', 'Employees')"
        @refresh="fetchData(store.bean.id)"
    >
        <div class="mint-widget-calendar">
            <div v-if="!hasEvents" class="mint-widget-calendar__empty">
                {{ languages.label('LBL_WIDGET_CALENDAR_EMPTY', 'Employees') }}
            </div>

            <template v-else>
                <div v-if="allDayWorkschedules.length" class="mint-widget-calendar__allday">
                    <div
                        v-for="ws in allDayWorkschedules"
                        :key="ws.id"
                        class="mint-widget-calendar__allday-chip"
                        :style="workscheduleColorStyle(ws.type)"
                    >
                        {{ languages.translateListValue(ws.type, 'workschedule_type_list') }}
                    </div>
                </div>

                <div ref="gridBody" class="mint-widget-calendar__day">
                    <MintCalendarDayGrid
                        :hour-labels="hourLabels"
                        :timed-workschedules="timedWorkschedules"
                        :positioned-activities="positionedActivities"
                        :now-offset="nowOffset"
                    />
                </div>
            </template>
        </div>
    </MintWidgetFrame>
</template>

<script setup lang="ts">
import { watch, computed, ref, nextTick, onMounted, onUnmounted } from 'vue'
import { useRecordViewStore } from '@/views/RecordView/RecordViewStore'
import { usePreferencesStore } from '@/store/preferences'
import { useLanguagesStore } from '@/store/languages'
import { useACL } from '@/composables/useACL'
import { useWorkscheduleColorStyle } from '@/composables/useWorkscheduleColorStyle'
import { mintApi } from '@/api/api'
import { DateTime } from 'luxon'
import { useWidgetFetch } from './useWidgetFetch'
import { layoutActivities } from './calendarLayout'
import MintWidgetFrame from './MintWidgetFrame.vue'
import MintCalendarDayGrid from './MintCalendarDayGrid.vue'
import type { Participant, DataWorkschedule, DataActivity } from '../MintScheduler/MintScheduler.model'

const HOUR_HEIGHT = 44
const DAY_MINUTES = 1440
const MIN_BLOCK_HEIGHT = 20
const ALLOWED_ACTIVITY_MODULES = ['Calls', 'Meetings']

const store = useRecordViewStore()
const preferences = usePreferencesStore()
const languages = useLanguagesStore()
const { workscheduleColorStyle } = useWorkscheduleColorStyle()

const clamp = (value: number, min: number, max: number) => Math.min(max, Math.max(min, value))

const todayKey = () => DateTime.now().setZone(preferences.user?.timezone).toISODate()

const {
    data: participant,
    isLoading,
    hasError,
    fetchData,
} = useWidgetFetch<Participant | null>(
    async (id: string) => {
        const todayLocal = DateTime.now().setZone(preferences.user?.timezone)
        const dateFrom = todayLocal.startOf('day').toUTC().toFormat('yyyy-MM-dd HH:mm:ss')
        const dateTo = todayLocal.endOf('day').toUTC().toFormat('yyyy-MM-dd HH:mm:ss')
        const response = await mintApi.post<Participant[]>('/scheduler', {
            date_from: dateFrom,
            date_to: dateTo,
            participants: [{ module: 'Employees', id }],
        })
        return response.data?.find((row) => row.id === id) ?? response.data?.[0] ?? null
    },
    null,
    (id: string) => `mint-calendar-${id}-${todayKey()}`,
)

watch(
    () => store.bean.id,
    (id) => {
        if (id) fetchData(id)
    },
    { immediate: true },
)

const dayStart = computed(() => DateTime.now().setZone(preferences.user?.timezone).startOf('day'))

const toLocalDt = (raw: string) => {
    const dt = DateTime.fromSQL(raw, { zone: 'UTC' }).setZone(preferences.user?.timezone)
    return dt.isValid ? dt : null
}

const minutesFromDayStart = (raw: string) => {
    const dt = toLocalDt(raw)
    return dt ? dt.diff(dayStart.value, 'minutes').minutes : null
}

const hourLabel = (hour: number) => dayStart.value.plus({ hours: hour }).toFormat('H:00')

const hourLabels = computed(() => Array.from({ length: 24 }, (_, hour) => hourLabel(hour)))

const workscheduleRaw = computed(() => {
    return (participant.value?.workschedules ?? []).map((ws: DataWorkschedule) => {
        const startMin = minutesFromDayStart(ws.date_start)
        const endMin = minutesFromDayStart(ws.date_end)
        return {
            ...ws,
            startMin: clamp(startMin ?? 0, 0, DAY_MINUTES),
            endMin: clamp(endMin ?? DAY_MINUTES, 0, DAY_MINUTES),
        }
    })
})

const allDayWorkschedules = computed(() =>
    workscheduleRaw.value.filter((ws) => ws.endMin - ws.startMin >= DAY_MINUTES - 5),
)

const timedWorkschedules = computed(() =>
    workscheduleRaw.value
        .filter((ws) => ws.endMin - ws.startMin < DAY_MINUTES - 5)
        .map((ws) => ({
            ...ws,
            label: languages.translateListValue(ws.type, 'workschedule_type_list'),
            style: {
                top: `${(ws.startMin / 60) * HOUR_HEIGHT}px`,
                height: `${Math.max(((ws.endMin - ws.startMin) / 60) * HOUR_HEIGHT, 6)}px`,
                ...workscheduleColorStyle(ws.type),
            },
        })),
)

const activityIntervals = computed(() => {
    return (participant.value?.activities ?? [])
        .map((activity: DataActivity) => {
            const startMin = clamp(minutesFromDayStart(activity.date_start) ?? 0, 0, DAY_MINUTES)
            const endMin = clamp(minutesFromDayStart(activity.date_end) ?? startMin, 0, DAY_MINUTES)
            return { ...activity, startMin, endMin: Math.max(endMin, startMin) }
        })
        .sort((a, b) => a.startMin - b.startMin || a.endMin - b.endMin)
})

const activityIcon = (activity: DataActivity) =>
    activity.module === 'Calls' ? 'mdi-phone-outline' : 'mdi-account-multiple-outline'

const activityUrl = (activity: DataActivity) => `/modules/${activity.module}/DetailView/${activity.id}`

const hasAccess = (activity: DataActivity) =>
    ALLOWED_ACTIVITY_MODULES.includes(activity.module) && useACL().hasAccess(activity.module, 'view', true, true)

const formatActivityTime = (activity: DataActivity) => {
    const start = toLocalDt(activity.date_start)
    const end = toLocalDt(activity.date_end)
    const startStr = start?.toFormat('HH:mm') ?? ''
    const endStr = end?.toFormat('HH:mm') ?? ''
    return startStr && endStr ? `${startStr} – ${endStr}` : startStr
}

const activityTooltip = (activity: DataActivity) => {
    const time = formatActivityTime(activity)
    return time ? `${time} — ${activity.name}` : activity.name
}

const positionedActivities = computed(() => {
    return layoutActivities(activityIntervals.value).map((activity) => ({
        ...activity,
        hasAccess: hasAccess(activity),
        url: activityUrl(activity),
        icon: activityIcon(activity),
        timeLabel: formatActivityTime(activity),
        tooltip: activityTooltip(activity),
        style: {
            top: `${(activity.startMin / 60) * HOUR_HEIGHT}px`,
            height: `${Math.max(((activity.endMin - activity.startMin) / 60) * HOUR_HEIGHT, MIN_BLOCK_HEIGHT)}px`,
            left: `calc(${(activity.column / activity.columnCount) * 100}% + 2px)`,
            width: `calc(${100 / activity.columnCount}% - 4px)`,
        },
    }))
})

const hasEvents = computed(
    () =>
        allDayWorkschedules.value.length > 0 ||
        timedWorkschedules.value.length > 0 ||
        positionedActivities.value.length > 0,
)

const nowRef = ref(DateTime.now())
let nowTimer: ReturnType<typeof setInterval> | undefined
onMounted(() => {
    nowTimer = setInterval(() => {
        nowRef.value = DateTime.now()
    }, 60000)
})
onUnmounted(() => {
    if (nowTimer) clearInterval(nowTimer)
})

const nowOffset = computed(() => {
    const nowLocal = nowRef.value.setZone(preferences.user?.timezone)
    if (!nowLocal.hasSame(dayStart.value, 'day')) return null
    const minutes = clamp(nowLocal.diff(dayStart.value, 'minutes').minutes, 0, DAY_MINUTES)
    return (minutes / 60) * HOUR_HEIGHT
})

const focusMinutes = computed(() => {
    const starts = [
        ...timedWorkschedules.value.map((ws) => ws.startMin),
        ...activityIntervals.value.map((a) => a.startMin),
    ]
    if (starts.length) return Math.min(...starts)
    return clamp(
        nowRef.value.setZone(preferences.user?.timezone).diff(dayStart.value, 'minutes').minutes,
        0,
        DAY_MINUTES,
    )
})

const gridBody = ref<HTMLElement | null>(null)

function scrollToFocus() {
    nextTick(() => {
        if (!gridBody.value) return
        gridBody.value.scrollTop = Math.max(0, ((focusMinutes.value - 60) / 60) * HOUR_HEIGHT)
    })
}

watch(
    () => participant.value,
    () => {
        if (hasEvents.value) scrollToFocus()
    },
    { immediate: true },
)
</script>

<style scoped lang="scss">
.mint-widget-calendar {
    &__allday {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 8px;
    }

    &__allday-chip {
        padding: 5px 10px;
        border-radius: 8px;
        border: 1px solid transparent;
        font-size: 11px;
        font-weight: 600;
    }

    &__day {
        position: relative;
        max-height: 320px;
        overflow-y: auto;
        overflow-x: hidden;
    }

    &__empty {
        font-size: 13px;
        color: rgba(var(--v-theme-on-surface), 0.45);
        text-align: center;
        padding: 8px 0 12px;
    }
}
</style>

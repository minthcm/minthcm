<template>
    <MintWidgetFrame
        :is-loading="isLoading"
        :has-error="hasError"
        icon="mdi-umbrella"
        :title="languages.label('LBL_WIDGET_LEAVE_TITLE', 'Employees')"
        :error-label="languages.label('LBL_ERROR_LOADING_DATA', 'Employees')"
        :refresh-label="languages.label('LBL_REFRESH', 'Employees')"
        @refresh="fetchData(store.bean.id)"
    >
        <div class="mint-widget-leave">
            <div v-if="entries.length" class="mint-widget-leave__list">
                <div v-for="entry in sortedEntries" :key="entry.id" class="mint-widget-leave__chip" :style="chipStyle(entry)">
                    <span class="mint-widget-leave__chip-label">{{ chipLabel(entry) }}</span>
                    <span class="mint-widget-leave__chip-dates">{{ formatRange(entry) }}</span>
                </div>
            </div>
            <div v-else class="mint-widget-leave__empty">
                {{ languages.label('LBL_WIDGET_LEAVE_EMPTY', 'Employees') }}
            </div>
        </div>
    </MintWidgetFrame>
</template>

<script setup lang="ts">
import { watch, computed } from 'vue'
import { useRecordViewStore } from '@/views/RecordView/RecordViewStore'
import { usePreferencesStore } from '@/store/preferences'
import { useLanguagesStore } from '@/store/languages'
import { useWorkscheduleColorStyle } from '@/composables/useWorkscheduleColorStyle'
import { mintApi } from '@/api/api'
import { DateTime } from 'luxon'
import { useWidgetFetch } from './useWidgetFetch'
import MintWidgetFrame from './MintWidgetFrame.vue'

interface LeaveEntry {
    id: string
    type: string
    date_start: string | null
    date_end: string | null
    supervisor_acceptance: string
}

const store = useRecordViewStore()
const preferences = usePreferencesStore()
const languages = useLanguagesStore()
const { workscheduleColorStyle, acceptedLeaveColorStyle } = useWorkscheduleColorStyle()

const todayKey = () => DateTime.utc().toISODate()

const { data: entries, isLoading, hasError, fetchData } = useWidgetFetch<LeaveEntry[]>(
    async (id: string) => {
        const response = await mintApi.get(`Employees/${id}/sidepanel/leave`, { rawError: true })
        return Array.isArray(response.data) ? response.data : []
    },
    [],
    (id: string) => `mint-leave-${id}-${todayKey()}`,
)

watch(() => store.bean.id, (id) => { if (id) fetchData(id) }, { immediate: true })

const sortedEntries = computed(() => {
    return [...entries.value].sort((a, b) => {
        const aTime = a.date_start ? DateTime.fromISO(a.date_start).toMillis() : 0
        const bTime = b.date_start ? DateTime.fromISO(b.date_start).toMillis() : 0
        return aTime - bTime
    })
})

const isAccepted = (entry: LeaveEntry) => entry.supervisor_acceptance === 'accepted'

const chipLabel = (entry: LeaveEntry) => {
    const typeLabel = languages.translateListValue(entry.type, 'workschedule_type_list')
    if (!isAccepted(entry)) return typeLabel
    const acceptedLabel = languages.translateListValue('accepted', 'supervisor_acceptance_dom')
    return `${typeLabel} (${acceptedLabel})`
}

const chipStyle = (entry: LeaveEntry) => {
    if (isAccepted(entry)) {
        return acceptedLeaveColorStyle()
    }
    return workscheduleColorStyle('holiday', 0.1)
}

const formatRange = (entry: LeaveEntry) => {
    const start = entry.date_start ? DateTime.fromISO(entry.date_start, { zone: 'utc' }) : null
    const end = entry.date_end ? DateTime.fromISO(entry.date_end, { zone: 'utc' }) : null
    const startStr = start?.isValid ? start.toFormat(preferences.userDateFormat) : ''
    const endStr = end?.isValid ? end.toFormat(preferences.userDateFormat) : ''
    if (startStr && endStr && startStr !== endStr) return `${startStr} – ${endStr}`
    return startStr
}
</script>

<style scoped lang="scss">
.mint-widget-leave {
    &__list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    &__chip {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        padding: 6px 10px;
        border-radius: 8px;
        border: 1px solid transparent;
        font-size: 12px;
    }

    &__chip-label {
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    &__chip-dates {
        font-size: 11px;
        opacity: 0.8;
        white-space: nowrap;
    }

    &__empty {
        font-size: 13px;
        color: rgba(var(--v-theme-on-surface), 0.45);
        text-align: center;
        padding: 8px 0 12px;
    }
}
</style>

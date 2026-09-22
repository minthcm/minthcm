<template>
    <MintWidgetFrame
        :is-loading="isLoading"
        :has-error="hasError"
        icon="mdi-briefcase-clock-outline"
        :title="languages.label('LBL_WIDGET_TENURE_TITLE', 'Employees')"
        :error-label="languages.label('LBL_ERROR_LOADING_DATA', 'Employees')"
        :refresh-label="languages.label('LBL_REFRESH', 'Employees')"
        @refresh="fetchData(store.bean.id)"
    >
        <div class="mint-widget-tenure">
            <div v-if="isValid" class="mint-widget-tenure__body">
                <div class="mint-widget-tenure__stat">
                    <span class="mint-widget-tenure__value">{{ diff.years }}</span>
                    <span class="mint-widget-tenure__label">{{ yearsLabel }}</span>
                </div>
                <div class="mint-widget-tenure__divider" />
                <div class="mint-widget-tenure__stat">
                    <span class="mint-widget-tenure__value">{{ diff.months }}</span>
                    <span class="mint-widget-tenure__label">{{ monthsLabel }}</span>
                </div>
                <div class="mint-widget-tenure__divider" />
                <div class="mint-widget-tenure__stat">
                    <span class="mint-widget-tenure__value">{{ diff.days }}</span>
                    <span class="mint-widget-tenure__label">{{ daysLabel }}</span>
                </div>
            </div>
            <div v-else class="mint-widget-tenure__empty">
                {{ languages.label('LBL_WIDGET_TENURE_NO_DATE', 'Employees') }}
            </div>
            <div v-if="startDate" class="mint-widget-tenure__dates">
                {{ languages.label('LBL_WIDGET_TENURE_FROM', 'Employees') }}: {{ startDate }}
                &middot;
                <template v-if="endDate">{{ endDate }}</template>
                <template v-else>{{ languages.label('LBL_WIDGET_TENURE_TO_TODAY', 'Employees') }}</template>
            </div>
            <div v-if="isOngoing && nextAnniversary" class="mint-widget-tenure__anniversary">
                {{ languages.label('LBL_WIDGET_TENURE_NEXT_ANNIVERSARY', 'Employees') }}: {{ nextAnniversary }}
            </div>
        </div>
    </MintWidgetFrame>
</template>

<script setup lang="ts">
import { watch, computed } from 'vue'
import { useRecordViewStore } from '@/views/RecordView/RecordViewStore'
import { usePreferencesStore } from '@/store/preferences'
import { useLanguagesStore } from '@/store/languages'
import { mintApi } from '@/api/api'
import { DateTime } from 'luxon'
import { useWidgetFetch } from './useWidgetFetch'
import MintWidgetFrame from './MintWidgetFrame.vue'

const store = useRecordViewStore()
const preferences = usePreferencesStore()
const languages = useLanguagesStore()

const { data, isLoading, hasError, fetchData } = useWidgetFetch(
    async (id: string) => {
        const response = await mintApi.get(`Employees/${id}/sidepanel/tenure`, { rawError: true })
        return {
            period_starting_date: (response.data?.period_starting_date ?? null) as string | null,
            period_ending_date: (response.data?.period_ending_date ?? null) as string | null,
        }
    },
    { period_starting_date: null as string | null, period_ending_date: null as string | null },
    (id: string) => `mint-tenure-${id}`,
)

watch(() => store.bean.id, (id) => { if (id) fetchData(id) }, { immediate: true })

const parseDate = (val: string | null): DateTime => {
    if (!val) return DateTime.invalid('empty')
    const dt = DateTime.fromISO(val, { zone: 'utc' })
    return dt.isValid ? dt : DateTime.invalid('parse')
}

const startDt = computed(() => parseDate(data.value.period_starting_date))
const endDt = computed(() => parseDate(data.value.period_ending_date))
const nowDt = computed(() => DateTime.utc())
const effectiveEndDt = computed(() => (endDt.value.isValid && endDt.value < nowDt.value) ? endDt.value : nowDt.value)
const isOngoing = computed(() => !endDt.value.isValid || endDt.value >= nowDt.value)

const isValid = computed(() => startDt.value.isValid)

const diff = computed(() => {
    if (!isValid.value) return { years: 0, months: 0, days: 0 }
    const d = effectiveEndDt.value.diff(startDt.value, ['years', 'months', 'days']).toObject()
    return {
        years: Math.floor(d.years ?? 0),
        months: Math.floor(d.months ?? 0),
        days: Math.floor(d.days ?? 0),
    }
})

const yearsLabel = computed(() => {
    const y = diff.value.years
    if (y === 1) return languages.label('LBL_YEAR_ONE', 'Employees')
    if (y >= 2 && y <= 4) return languages.label('LBL_YEAR_FEW', 'Employees')
    return languages.label('LBL_YEAR_MANY', 'Employees')
})

const monthsLabel = computed(() => {
    const m = diff.value.months
    if (m === 1) return languages.label('LBL_MONTH_ONE', 'Employees')
    if (m >= 2 && m <= 4) return languages.label('LBL_MONTH_FEW', 'Employees')
    return languages.label('LBL_MONTH_MANY', 'Employees')
})

const daysLabel = computed(() => {
    const d = diff.value.days
    if (d === 1) return languages.label('LBL_DAY_ONE', 'Employees')
    if (d >= 2 && d <= 4) return languages.label('LBL_DAY_FEW', 'Employees')
    return languages.label('LBL_DAY_MANY', 'Employees')
})

const startDate = computed(() => {
    if (!isValid.value) return null
    return startDt.value.toFormat(preferences.userDateFormat)
})

const endDate = computed(() => {
    if (!endDt.value.isValid) return null
    return endDt.value.toFormat(preferences.userDateFormat)
})

const nextAnniversary = computed(() => {
    if (!isValid.value) return null
    let next = startDt.value.set({ year: nowDt.value.year })
    if (next < nowDt.value.startOf('day')) next = startDt.value.set({ year: nowDt.value.year + 1 })
    return next.toFormat(preferences.userDateFormat)
})
</script>

<style scoped lang="scss">
.mint-widget-tenure {
    &__body {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 24px;
        margin-bottom: 12px;
    }

    &__stat {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
    }

    &__value {
        font-size: 32px;
        font-weight: 700;
        color: rgb(var(--v-theme-primary));
        line-height: 1;
    }

    &__label {
        font-size: 12px;
        color: rgb(var(--v-theme-secondary));
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    &__divider {
        width: 1px;
        height: 40px;
        background: #dbdbdb;
    }

    &__dates {
        font-size: 12px;
        color: rgba(var(--v-theme-on-surface), 0.6);
        text-align: center;
    }

    &__anniversary {
        font-size: 12px;
        color: rgba(var(--v-theme-on-surface), 0.6);
        text-align: center;
        margin-top: 2px;
    }

    &__empty {
        font-size: 13px;
        color: rgba(var(--v-theme-on-surface), 0.45);
        text-align: center;
        padding: 8px 0 12px;
    }
}
</style>

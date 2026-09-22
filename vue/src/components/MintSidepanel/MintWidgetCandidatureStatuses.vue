<template>
    <div class="mint-widget-candidature-statuses">
        <div v-if="isLoading" class="mint-widget-candidature-statuses__loading">
            <v-progress-circular indeterminate color="primary" size="32" width="3" />
        </div>

        <div v-else-if="hasError" class="mint-widget-candidature-statuses__error">
            <v-icon icon="mdi-alert-circle-outline" size="18" color="error" />
            <span>{{ languages.label('LBL_ERROR_LOADING_DATA', 'Recruitments') }}</span>
            <button class="mint-widget-candidature-statuses__refresh-btn" @click="fetchData(store.bean.id)" :title="languages.label('LBL_REFRESH', 'Recruitments')">
                <v-icon icon="mdi-refresh" size="16" />
            </button>
        </div>

        <template v-else>
            <div class="mint-widget-candidature-statuses__title">
                <v-icon icon="mdi-chart-pie" size="18" />
                <span>{{ languages.label('LBL_WIDGET_CANDIDATURE_STATUSES_TITLE', 'Recruitments') }}</span>
            </div>

            <div v-if="total === 0" class="mint-widget-candidature-statuses__empty">
                {{ languages.label('LBL_WIDGET_CANDIDATURE_STATUSES_EMPTY', 'Recruitments') }}
            </div>

            <div v-else class="mint-widget-candidature-statuses__content">
                <div class="mint-widget-candidature-statuses__chart-wrap">
                    <svg viewBox="0 0 130 130" width="124" height="124" class="mint-widget-candidature-statuses__svg">
                        <circle
                            cx="65" cy="65" r="50"
                            fill="none"
                            class="mint-widget-candidature-statuses__track"
                            stroke-width="12"
                        />
                        <circle
                            v-for="segment in segments"
                            :key="segment.key"
                            cx="65" cy="65" r="50"
                            fill="none"
                            :stroke="segment.color"
                            stroke-width="12"
                            stroke-linecap="butt"
                            :stroke-dasharray="segment.dasharray"
                            :stroke-dashoffset="segment.dashoffset"
                            transform="rotate(-90 65 65)"
                        >
                            <title>{{ segment.label }}: {{ segment.count }}</title>
                        </circle>
                        <text x="65" y="61" text-anchor="middle" class="mint-widget-candidature-statuses__num">{{ total }}</text>
                        <text x="65" y="78" text-anchor="middle" class="mint-widget-candidature-statuses__denom">{{ languages.label('LBL_MODULE_NAME', 'Candidatures') }}</text>
                    </svg>
                </div>

                <div class="mint-widget-candidature-statuses__legend">
                    <div v-for="segment in segments" :key="segment.key" class="mint-widget-candidature-statuses__legend-row">
                        <span class="mint-widget-candidature-statuses__dot" :style="{ background: segment.color }" />
                        <span class="mint-widget-candidature-statuses__legend-label">{{ segment.label }}</span>
                        <span class="mint-widget-candidature-statuses__legend-count">{{ segment.count }}</span>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script setup lang="ts">
import { computed, watch } from 'vue'
import { useRecordViewStore } from '@/views/RecordView/RecordViewStore'
import { useLanguagesStore } from '@/store/languages'
import { mintApi } from '@/api/api'
import { useWidgetFetch } from './useWidgetFetch'
import { RECRUITMENT_FLOW_ORDER } from './recruitmentStages'

interface StatusCount {
    status: string
    count: number
}

const store = useRecordViewStore()
const languages = useLanguagesStore()

const radius = 50
const circumference = 2 * Math.PI * radius
// Must stay paired with stroke-linecap="butt" below: a round cap's radius (strokeWidth / 2 = 6)
// is bigger than this gap, so adjacent segments' rounded ends would overlap each other's gap and
// whichever segment paints later (SVG z-order = DOM order) would visually "win" the overlap,
// making equal-count segments look unequal depending on paint order.
const SEGMENT_GAP = 3
const DEFAULT_COLOR = '#898781'

// Fixed status -> color identity map, so a status always renders in the same color regardless
// of sort order or which other statuses happen to be present (color must follow the entity, not
// its rank in the current recruitment). Derived from Candidatures.status options_colors
// (legacy/modules/Candidatures/vardefs.php), where most of the 16 statuses collide on "yellow" —
// each collision here gets its own distinct hue instead, while New/Hired/Rejected keep their
// original blue/green/red hints.
const STATUS_COLORS: Record<string, string> = {
    New: '#2a78d6',
    Hired: '#008300',
    Rejected: '#e34948',
    CandidateResignation: '#eb6834',
    Acceptance: '#eda100',
    AfterEntryInterview: '#1baf7a',
    EntryInterview: '#4a3aa7',
    InProgress: '#e87ba4',
    MeetingAdditional: '#0f9b8e',
    MeetingPrimary: '#b8860b',
    Negotation: '#b03a8c',
    Offer: '#8a9a2b',
    PracticalTask: '#8d5b3f',
    Preselection: '#17a2b8',
    Scored: '#c2185b',
    Scored2: '#5c4b9e',
}

// Same recruitment-flow order as MintWidgetRecruitmentStage.vue (New -> Screening -> Interviews ->
// Offer -> Result), so a status always renders at the same position in the pie/legend regardless
// of how many candidatures are in each status — position, like color, must follow the entity.
function flowIndex(status: string): number {
    const idx = RECRUITMENT_FLOW_ORDER.indexOf(status)
    return idx === -1 ? RECRUITMENT_FLOW_ORDER.length : idx
}

const { data: statusCounts, isLoading, hasError, fetchData } = useWidgetFetch(
    async (id: string) => {
        const response = await mintApi.get(`Recruitments/${id}/sidepanel/candidature-statuses`, { rawError: true })
        return (response.data ?? []) as StatusCount[]
    },
    [] as StatusCount[],
    (id: string) => `mint-candidature-statuses-${id}`,
)

const total = computed(() => statusCounts.value.reduce((sum, s) => sum + s.count, 0))

const segments = computed(() => {
    const t = total.value
    if (t === 0) return []

    // Sorted by recruitment-flow position (not by count) so the pie/legend order always matches
    // the pipeline sequence, regardless of how many candidatures are in each status.
    const nonEmpty = statusCounts.value
        .filter(s => s.count > 0)
        .sort((a, b) => flowIndex(a.status) - flowIndex(b.status))
        .map(s => ({
            key: s.status,
            label: languages.translateListValue(s.status, 'status_list'),
            count: s.count,
            color: STATUS_COLORS[s.status] ?? DEFAULT_COLOR,
        }))

    let cumulativeFraction = 0
    return nonEmpty.map(entry => {
        const fraction = entry.count / t
        const rawLen = fraction * circumference
        const visibleLen = nonEmpty.length > 1 ? Math.max(rawLen - SEGMENT_GAP, 1) : rawLen
        const dashoffset = -(cumulativeFraction * circumference)
        cumulativeFraction += fraction
        return {
            ...entry,
            dasharray: `${visibleLen} ${circumference - visibleLen}`,
            dashoffset,
        }
    })
})

watch(() => store.bean.id, (id) => { if (id) fetchData(id) }, { immediate: true })
</script>

<style scoped lang="scss">
.mint-widget-candidature-statuses {
    border-radius: 16px;
    background: rgb(var(--v-theme-surface));
    box-shadow: 0px 1px 12px #00997619;
    padding: 16px;

    &__title {
        display: flex;
        align-items: center;
        gap: 8px;
        color: rgb(var(--v-theme-secondary));
        font-size: 15px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.47px;
        margin-bottom: 16px;
    }

    &__loading {
        display: flex;
        justify-content: center;
        padding: 16px 0 12px;
    }

    &__empty {
        font-size: 12px;
        color: rgba(var(--v-theme-on-surface), 0.45);
        text-align: center;
        padding: 12px 0;
    }

    &__content {
        display: flex;
        flex-direction: column;
        align-items: center;
        animation: mint-widget-fadein 0.18s ease-out;
    }

    &__chart-wrap {
        display: flex;
        justify-content: center;
    }

    &__svg {
        overflow: visible;
    }

    &__track {
        stroke: rgba(var(--v-theme-on-surface), 0.1);
    }

    &__num {
        fill: rgb(var(--v-theme-primary));
        font-size: 22px;
        font-weight: 700;
    }

    &__denom {
        fill: rgba(var(--v-theme-on-surface), 0.45);
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    &__legend {
        display: flex;
        flex-direction: column;
        gap: 6px;
        width: 100%;
        margin-top: 12px;
    }

    &__legend-row {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    &__dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    &__legend-label {
        font-size: 12px;
        color: rgb(var(--v-theme-secondary));
        flex: 1;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    &__legend-count {
        font-size: 13px;
        font-weight: 600;
        color: rgb(var(--v-theme-primary));
    }

    &__error {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: rgb(var(--v-theme-error));
        padding: 8px 0 12px;
    }

    &__refresh-btn {
        margin-left: auto;
        background: none;
        border: none;
        cursor: pointer;
        color: rgb(var(--v-theme-secondary));
        display: flex;
        align-items: center;
        padding: 2px;
        border-radius: 4px;
        transition: color 0.15s;

        &:hover {
            color: rgb(var(--v-theme-primary));
        }
    }
}
</style>

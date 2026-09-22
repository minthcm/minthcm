<template>
    <div class="mint-widget-candidate-score">
        <div v-if="isLoading" class="mint-widget-candidate-score__loading">
            <v-progress-circular indeterminate color="primary" size="32" width="3" />
        </div>

        <div v-else-if="hasError" class="mint-widget-candidate-score__error">
            <v-icon icon="mdi-alert-circle-outline" size="18" color="error" />
            <span>{{ languages.label('LBL_ERROR_LOADING_DATA', 'Candidatures') }}</span>
            <button class="mint-widget-candidate-score__refresh-btn" @click="fetchData(store.bean.id)" :title="languages.label('LBL_REFRESH', 'Candidatures')">
                <v-icon icon="mdi-refresh" size="16" />
            </button>
        </div>

        <template v-else>
            <div class="mint-widget-candidate-score__title">
                <v-icon icon="mdi-chart-arc" size="18" />
                <span>{{ languages.label('LBL_WIDGET_CANDIDATE_SCORE_TITLE', 'Candidatures') }}</span>
            </div>

            <div class="mint-widget-candidate-score__content">
                <div class="mint-widget-candidate-score__gauge-wrap">
                    <svg viewBox="0 0 120 70" width="140" height="82" class="mint-widget-candidate-score__svg">
                        <path
                            :d="arcPath"
                            fill="none"
                            class="mint-widget-candidate-score__gauge-track"
                            stroke-width="13"
                            stroke-linecap="round"
                        />
                        <path
                            :d="arcPath"
                            fill="none"
                            class="mint-widget-candidate-score__gauge-arc"
                            stroke-width="13"
                            stroke-linecap="round"
                            :stroke-dasharray="`${progressLength} 999`"
                        />
                        <text x="60" y="58" text-anchor="middle" class="mint-widget-candidate-score__score-text">
                            {{ scoreDisplay }}
                        </text>
                        <text x="60" y="68" text-anchor="middle" class="mint-widget-candidate-score__max-text">
                            / {{ scoreData.max }}
                        </text>
                    </svg>
                </div>

                <div v-if="scoreData.count > 0" class="mint-widget-candidate-score__meta">
                    <span>{{ languages.label('LBL_WIDGET_CANDIDATE_SCORE_EVALUATIONS', 'Candidatures') }}: {{ scoreData.count }}</span>
                </div>
                <div v-else class="mint-widget-candidate-score__empty">
                    {{ languages.label('LBL_WIDGET_CANDIDATE_SCORE_EMPTY', 'Candidatures') }}
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

const store = useRecordViewStore()
const languages = useLanguagesStore()

const arcPath = 'M 10,62 A 50,50 0 0,1 110,62'
const arcLength = Math.PI * 50

const { data: scoreData, isLoading, hasError, fetchData } = useWidgetFetch(
    async (id: string) => {
        const response = await mintApi.get(`Candidatures/${id}/sidepanel/score`, { rawError: true })
        return {
            score: response.data?.score ?? 0,
            max: response.data?.max ?? 5,
            count: response.data?.count ?? 0,
        }
    },
    { score: 0, max: 5, count: 0 },
    (id: string) => `mint-candidate-score-${id}`,
)

const progressLength = computed(() => {
    if (scoreData.value.max === 0 || scoreData.value.count === 0) return 0
    return (scoreData.value.score / scoreData.value.max) * arcLength
})

const scoreDisplay = computed(() => {
    if (scoreData.value.count === 0) return '–'
    return scoreData.value.score.toFixed(1)
})

watch(() => store.bean.id, (id) => { if (id) fetchData(id) }, { immediate: true })
</script>

<style scoped lang="scss">
.mint-widget-candidate-score {
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

    &__content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        animation: mint-widget-fadein 0.18s ease-out;
    }

    &__gauge-wrap {
        display: flex;
        justify-content: center;
    }

    &__svg {
        overflow: visible;
    }

    &__gauge-track {
        stroke: rgba(var(--v-theme-on-surface), 0.1);
    }

    &__gauge-arc {
        stroke: rgb(var(--v-theme-primary));
        transition: stroke-dasharray 0.5s ease;
    }

    &__score-text {
        fill: rgb(var(--v-theme-primary));
        font-size: 22px;
        font-weight: 700;
    }

    &__max-text {
        fill: rgba(var(--v-theme-on-surface), 0.45);
        font-size: 11px;
    }

    &__meta {
        font-size: 12px;
        color: rgba(var(--v-theme-on-surface), 0.55);
        text-align: center;
    }

    &__empty {
        font-size: 12px;
        color: rgba(var(--v-theme-on-surface), 0.45);
        text-align: center;
        padding: 4px 0;
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

<template>
    <div class="mint-widget-trainings">
        <div v-if="isLoading" class="mint-widget-trainings__loading">
            <v-progress-circular indeterminate color="primary" size="32" width="3" />
        </div>

        <div v-else-if="hasError" class="mint-widget-trainings__error">
            <v-icon icon="mdi-alert-circle-outline" size="18" color="error" />
            <span>{{ languages.label('LBL_ERROR_LOADING_DATA', 'Employees') }}</span>
            <button class="mint-widget-trainings__refresh-btn" @click="fetchData(store.bean.id)" :title="languages.label('LBL_REFRESH', 'Employees')">
                <v-icon icon="mdi-refresh" size="16" />
            </button>
        </div>

        <template v-else>
            <div class="mint-widget-trainings__title">
                <v-icon icon="mdi-school-outline" size="18" />
                <span>{{ languages.label('LBL_WIDGET_TRAININGS_TITLE', 'Employees') }}</span>
            </div>

            <div class="mint-widget-trainings__content">
                <div class="mint-widget-trainings__chart-wrap">
                    <svg viewBox="0 0 120 120" width="110" height="110" class="mint-widget-trainings__svg">
                        <circle
                            cx="60" cy="60" r="46"
                            fill="none"
                            class="mint-widget-trainings__track"
                            stroke-width="12"
                        />
                        <circle
                            v-if="summary.total > 0"
                            cx="60" cy="60" r="46"
                            fill="none"
                            class="mint-widget-trainings__arc"
                            stroke-width="12"
                            stroke-linecap="round"
                            :stroke-dasharray="`${circumference}`"
                            :stroke-dashoffset="dashOffset"
                            transform="rotate(-90 60 60)"
                        />
                        <text x="60" y="56" text-anchor="middle" class="mint-widget-trainings__num">{{ summary.held }}</text>
                        <text x="60" y="73" text-anchor="middle" class="mint-widget-trainings__denom">/ {{ summary.total }}</text>
                    </svg>
                </div>

                <div class="mint-widget-trainings__legend">
                    <div class="mint-widget-trainings__legend-row">
                        <span class="mint-widget-trainings__dot mint-widget-trainings__dot--primary" />
                        <span class="mint-widget-trainings__legend-label">{{ languages.label('LBL_WIDGET_TRAININGS_HELD', 'Employees') }}</span>
                        <span class="mint-widget-trainings__legend-count">{{ summary.held }}</span>
                    </div>
                    <div class="mint-widget-trainings__legend-row">
                        <span class="mint-widget-trainings__dot mint-widget-trainings__dot--muted" />
                        <span class="mint-widget-trainings__legend-label">{{ languages.label('LBL_WIDGET_TRAININGS_PLANNED', 'Employees') }}</span>
                        <span class="mint-widget-trainings__legend-count">{{ summary.planned }}</span>
                    </div>
                </div>

                <div class="mint-widget-trainings__year">{{ currentYear }}</div>
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
const currentYear = new Date().getFullYear()

const radius = 46
const circumference = 2 * Math.PI * radius

const { data: summary, isLoading, hasError, fetchData } = useWidgetFetch(
    async (id: string) => {
        const response = await mintApi.get(`Employees/${id}/sidepanel/trainings`, { rawError: true })
        return {
            held: response.data?.held ?? 0,
            planned: response.data?.planned ?? 0,
            total: response.data?.total ?? 0,
        }
    },
    { held: 0, planned: 0, total: 0 },
    (id: string) => `mint-trainings-${id}-${new Date().getFullYear()}`,
)

const dashOffset = computed(() => {
    if (summary.value.total === 0) return circumference
    return circumference * (1 - summary.value.held / summary.value.total)
})

watch(() => store.bean.id, (id) => { if (id) fetchData(id) }, { immediate: true })
</script>

<style scoped lang="scss">
.mint-widget-trainings {
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

    &__arc {
        stroke: rgb(var(--v-theme-primary));
        transition: stroke-dashoffset 0.5s ease;
    }

    &__num {
        fill: rgb(var(--v-theme-primary));
        font-size: 24px;
        font-weight: 700;
    }

    &__denom {
        fill: rgba(var(--v-theme-on-surface), 0.45);
        font-size: 11px;
    }

    &__legend {
        display: flex;
        flex-direction: column;
        gap: 6px;
        width: 100%;
        margin-top: 8px;
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

        &--primary {
            background: rgb(var(--v-theme-primary));
        }

        &--muted {
            background: rgba(var(--v-theme-on-surface), 0.15);
        }
    }

    &__legend-label {
        font-size: 12px;
        color: rgb(var(--v-theme-secondary));
        text-transform: uppercase;
        letter-spacing: 0.4px;
        flex: 1;
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

    &__year {
        font-size: 12px;
        color: rgba(var(--v-theme-on-surface), 0.45);
        text-align: center;
        margin-top: 10px;
    }
}

</style>
